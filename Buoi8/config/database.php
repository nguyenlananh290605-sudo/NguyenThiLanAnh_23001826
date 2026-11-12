<?php
// Cấu hình MySQL mặc định của XAMPP. Đổi mật khẩu nếu máy bạn đã đặt.
$host = 'localhost';
$database = 'buoi8_products';
$username = 'root';
$password = '';

// Lỗi kết nối được file API bắt và chuyển thành phản hồi JSON.
$pdo = new PDO(
    "mysql:host=$host;dbname=$database;charset=utf8mb4",
    $username,
    $password,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
);
