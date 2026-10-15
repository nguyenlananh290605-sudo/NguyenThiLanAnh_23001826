<?php
require_once __DIR__ . '/common/dbConnect.php';

// Thông tin tài khoản mẫu
$username = 'admin';
$rawPassword = 'admin123'; // Mật khẩu chưa mã hóa

// Mã hóa mật khẩu theo chuẩn BCRYPT
$hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

try {
    global $conn;

    // Kiểm tra xem user admin đã tồn tại chưa
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
    $checkStmt->execute([':username' => $username]);

    if ($checkStmt->rowCount() > 0) {
        echo "<h3 style='color: orange;'>Tài khoản '{$username}' đã tồn tại trước đó!</h3>";
    } else {
        // Chèn tài khoản mẫu vào CSDL
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword
        ]);

        echo "<h3 style='color: green;'>Tạo tài khoản mẫu thành công!</h3>";
        echo "<ul>";
        echo "<li><strong>Username:</strong> {$username}</li>";
        echo "<li><strong>Password:</strong> {$rawPassword}</li>";
        echo "<li><strong>Password Hash (Lưu trong CSDL):</strong> <code>{$hashedPassword}</code></li>";
        echo "</ul>";
    }
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>Lỗi: " . htmlspecialchars($e->getMessage()) . "</h3>";
}
?>