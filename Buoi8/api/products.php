<?php
header('Content-Type: application/json; charset=utf-8');

function respond(array $body, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}

function readProduct(): array
{
    try {
        $input = json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        respond(['success' => false, 'message' => 'Nội dung JSON không hợp lệ.'], 400);
    }

    if (!is_object($input)) {
        respond(['success' => false, 'message' => 'Dữ liệu phải là đối tượng JSON gồm name, price và stock.'], 400);
    }

    $name = isset($input->name) && is_string($input->name) ? trim($input->name) : '';
    // Đếm ký tự Unicode để tên tiếng Việt được kiểm tra đúng độ dài.
    $length = preg_match_all('/./us', $name);
    if ($length === false || $length < 1 || $length > 100) {
        respond(['success' => false, 'message' => 'Tên sau khi bỏ khoảng trắng hai đầu phải có từ 1 đến 100 ký tự.'], 422);
    }
    if (!isset($input->price) || !is_int($input->price) || $input->price <= 0 || $input->price > 2147483647) {
        respond(['success' => false, 'message' => 'Đơn giá phải là số nguyên lớn hơn 0 và không vượt quá 2.147.483.647.'], 422);
    }
    if (!isset($input->stock) || !is_int($input->stock) || $input->stock < 0 || $input->stock > 2147483647) {
        respond(['success' => false, 'message' => 'Tồn kho phải là số nguyên không âm và không vượt quá 2.147.483.647.'], 422);
    }

    return ['name' => $name, 'price' => $input->price, 'stock' => $input->stock];
}

function readId(): int
{
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 2147483647]
    ]);
    if ($id === false) {
        respond(['success' => false, 'message' => 'ID sản phẩm phải là số nguyên dương hợp lệ.'], 400);
    }
    return $id;
}

$method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'], true)) {
    header('Allow: GET, POST, PUT, DELETE');
    respond(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
}

// Kiểm tra đầu vào trước khi kết nối và ghi dữ liệu.
$id = in_array($method, ['PUT', 'DELETE'], true) ? readId() : null;
$product = in_array($method, ['POST', 'PUT'], true) ? readProduct() : null;

try {
    require __DIR__ . '/../config/database.php';

    if ($method === 'GET') {
        $products = $pdo->query('SELECT id, name, price, stock FROM products ORDER BY id')->fetchAll();
        foreach ($products as &$row) {
            $row['id'] = (int) $row['id'];
            $row['price'] = (int) $row['price'];
            $row['stock'] = (int) $row['stock'];
        }
        unset($row);
        respond(['success' => true, 'data' => $products]);
    }

    if ($method === 'POST') {
        $statement = $pdo->prepare('INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)');
        $statement->execute($product);
        respond([
            'success' => true,
            'message' => 'Thêm sản phẩm thành công.',
            'data' => ['id' => (int) $pdo->lastInsertId()] + $product
        ], 201);
    }

    // Khóa bản ghi trong giao dịch để kiểm tra tồn tại và cập nhật nhất quán.
    $pdo->beginTransaction();
    $statement = $pdo->prepare('SELECT id FROM products WHERE id = :id FOR UPDATE');
    $statement->execute(['id' => $id]);
    if (!$statement->fetch()) {
        $pdo->rollBack();
        respond(['success' => false, 'message' => 'Sản phẩm không tồn tại.'], 404);
    }

    if ($method === 'PUT') {
        $statement = $pdo->prepare('UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id');
        $statement->execute($product + ['id' => $id]);
        $pdo->commit();
        respond(['success' => true, 'message' => 'Cập nhật sản phẩm thành công.', 'data' => ['id' => $id] + $product]);
    }

    $statement = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $statement->execute(['id' => $id]);
    $pdo->commit();
    respond(['success' => true, 'message' => 'Xóa sản phẩm thành công.']);
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log($e->getMessage());
    respond(['success' => false, 'message' => 'Không thể xử lý dữ liệu. Hãy kiểm tra MySQL, cấu hình kết nối và bảng products.'], 500);
}
