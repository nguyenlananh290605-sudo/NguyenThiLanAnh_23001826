<?php
require_once __DIR__ . '/model/product.php';

// 1. Lấy ID sản phẩm cần xóa từ URL
$id = $_GET['id'] ?? null;

// Kiểm tra tính hợp lệ của ID truyền vào
if (!$id || !is_numeric($id) || (int) $id <= 0) {
    header("Location: product_list.php?error=" . urlencode("Mã sản phẩm cần xóa không hợp lệ."));
    exit();
}

$id = (int) $id;

try {
    // 2. Thực hiện xóa sản phẩm (Hàm deleteProduct() sẽ kiểm tra sự tồn tại trước khi xóa)
    if (deleteProduct($id)) {
        header("Location: product_list.php?message=" . urlencode("Xóa sản phẩm ID {$id} thành công!"));
    } else {
        header("Location: product_list.php?error=" . urlencode("Không thể xóa sản phẩm ID {$id}."));
    }
} catch (Exception $e) {
    // 3. Nếu sản phẩm không tồn tại hoặc gặp lỗi CSDL -> Thông báo lỗi tương ứng
    header("Location: product_list.php?error=" . urlencode($e->getMessage()));
}
exit();
?>