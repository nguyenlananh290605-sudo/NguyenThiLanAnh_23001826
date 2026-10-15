<?php
session_start();

// 1. Xóa tất cả các biến lưu trong session
$_SESSION = array();

// 2. Nếu sử dụng cookie session, xóa luôn cookie session ở browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Hủy bỏ session trên server
session_destroy();

// 4. Chuyển hướng người dùng về trang login.php
header("Location: login.php");
exit();
?>