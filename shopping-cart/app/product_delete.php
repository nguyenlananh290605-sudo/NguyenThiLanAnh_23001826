<?php
session_start();

// Kiểm soát truy cập: Chưa đăng nhập thì đá về trang login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/model/product.php';

// 1. Lấy ID sản phẩm cần xóa từ URL
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id) || (int) $id <= 0) {
    header("Location: product_list.php?error=" . urlencode("Mã sản phẩm cần xóa không hợp lệ."));
    exit();
}

$id = (int) $id;

try {
    // 2. Thực hiện xóa sản phẩm
    if (deleteProduct($id)) {
        header("Location: product_list.php?message=" . urlencode("Xóa sản phẩm ID {$id} thành công!"));
    } else {
        header("Location: product_list.php?error=" . urlencode("Không thể xóa sản phẩm ID {$id}."));
    }
} catch (Exception $e) {
    header("Location: product_list.php?error=" . urlencode($e->getMessage()));
}
exit();
?>