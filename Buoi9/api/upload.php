<?php
header('Content-Type: application/json; charset=utf-8');
function reply(array $body, int $status = 200): never {
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    reply(['success' => false, 'message' => 'Chỉ hỗ trợ POST.'], 405);
}
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$file = $_FILES['image'] ?? null;
if (!$id || !is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    reply(['success' => false, 'message' => 'Mã sản phẩm hoặc file không hợp lệ; ảnh không được vượt quá 2 MB.'], 400);
}
if ($file['size'] > 2 * 1024 * 1024 || $file['size'] <= 0 || !is_uploaded_file($file['tmp_name'])) {
    reply(['success' => false, 'message' => 'Ảnh phải có dung lượng tối đa 2 MB.'], 422);
}
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$info = @getimagesize($file['tmp_name']);
$types = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'];
if (!isset($types[$extension]) || !$info || $info['mime'] !== $types[$extension]) {
    reply(['success' => false, 'message' => 'Chỉ chấp nhận ảnh JPG, JPEG hoặc PNG hợp lệ.'], 422);
}
$path = null;
try {
    require __DIR__ . '/../config/database.php';
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('SELECT id FROM products WHERE id = ? FOR UPDATE');
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        $pdo->rollBack();
        reply(['success' => false, 'message' => 'Sản phẩm không tồn tại.'], 404);
    }
    $dir = __DIR__ . '/../uploads';
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('Không tạo được thư mục ảnh.');
    }
    $relative = 'uploads/' . bin2hex(random_bytes(16)) . '.' . $extension;
    $path = __DIR__ . '/../' . $relative;
    if (!move_uploaded_file($file['tmp_name'], $path)) throw new RuntimeException('Không lưu được ảnh.');
    $stmt = $pdo->prepare('UPDATE products SET image = ? WHERE id = ?');
    $stmt->execute([$relative, $id]);
    $pdo->commit();
    reply(['success' => true, 'message' => 'Upload ảnh thành công.', 'image' => $relative]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    if ($path && is_file($path)) unlink($path);
    error_log($e->getMessage());
    reply(['success' => false, 'message' => 'Không thể upload ảnh. Vui lòng thử lại.'], 500);
}
