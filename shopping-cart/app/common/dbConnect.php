<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "shopping_cart";

try {
    // 1. Khởi tạo kết nối PDO
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);

    // 2. Thiết lập chế độ báo lỗi exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 3. Thiết lập kiểu fetch mặc định là mảng kết hợp (Associative Array)
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Xử lý khi kết nối thất bại
    die("Connection failed: " . $e->getMessage());
}
?>