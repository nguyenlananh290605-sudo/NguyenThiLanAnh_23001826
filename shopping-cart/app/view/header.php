<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['SCRIPT_NAME']);
$isLoggedIn = isset($_SESSION['user']);
$currentUser = $isLoggedIn ? $_SESSION['user']['username'] : '';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart Admin</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="product_list.php">
                <i class="fa-solid fa-cart-shopping me-2 text-primary"></i>Shopping Cart Admin
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage === 'product_list.php') ? 'active' : ''; ?>"
                            href="product_list.php">
                            <i class="fa-solid fa-list me-1"></i> Danh sách sản phẩm
                        </a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage === 'product_add.php') ? 'active' : ''; ?>"
                                href="product_add.php">
                                <i class="fa-solid fa-plus me-1"></i> Thêm mới
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Khu vực tài khoản góc phải -->
                <div class="d-flex align-items-center gap-2">
                    <?php if ($isLoggedIn): ?>
                        <span class="text-light me-2">
                            <i class="fa-solid fa-user-circle me-1 text-info"></i>
                            <?php echo htmlspecialchars($currentUser); ?>
                        </span>
                        <a href="logout.php" class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Đăng xuất
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-light btn-sm">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Đăng nhập
                        </a>
                        <a href="register.php" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-user-plus me-1"></i> Đăng ký
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="card p-4">