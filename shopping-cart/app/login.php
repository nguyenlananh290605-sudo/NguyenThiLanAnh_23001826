<?php
session_start();
require_once __DIR__ . '/common/dbConnect.php';

// Nếu đã đăng nhập trước đó thì chuyển hướng thẳng đến danh sách sản phẩm
if (isset($_SESSION['user'])) {
    header("Location: product_list.php");
    exit();
}

$error = '';
$username = '';

// Kiểm tra thông báo thành công từ trang Đăng ký gửi sang
$success_msg = $_SESSION['register_success'] ?? '';
unset($_SESSION['register_success']); // Xóa session thông báo sau khi đã lấy

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // 1. Kiểm tra các trường không được để trống
    if ($username === '') {
        $error = 'Vui lòng nhập tên đăng nhập.';
    } elseif ($password === '') {
        $error = 'Vui lòng nhập mật khẩu.';
    } else {
        try {
            global $conn;

            // 2. Truy vấn người dùng theo username từ bảng users
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3. Sử dụng password_verify() để kiểm tra mật khẩu
            if ($user && password_verify($password, $user['password'])) {
                // 4. Đăng nhập thành công: Lưu thông tin người dùng vào $_SESSION
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username']
                ];

                // Chuyển hướng đến product_list.php
                header("Location: product_list.php");
                exit();
            } else {
                // 5. Nếu sai tài khoản hoặc mật khẩu -> Báo lỗi
                $error = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
            }
        } catch (PDOException $e) {
            $error = 'Lỗi kết nối cơ sở dữ liệu: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Shopping Cart Admin</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body>

    <div class="card login-card p-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary"><i class="fa-solid fa-right-to-bracket me-2"></i>Đăng Nhập</h3>
            <p class="text-muted">Hệ thống quản lý sản phẩm</p>
        </div>

        <!-- Hiển thị thông báo đăng ký thành công (nếu có) -->
        <?php if ($success_msg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?php echo htmlspecialchars($success_msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Hiển thị thông báo lỗi đăng nhập -->
        <div id="js-error" class="alert alert-danger alert-dismissible fade show" role="alert"
            style="display: <?php echo $error ? 'block' : 'none'; ?>;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <span id="js-error-text">
                <?php echo htmlspecialchars($error); ?>
            </span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Form Đăng Nhập -->
        <form action="login.php" method="POST" id="loginForm" class="needs-validation" novalidate>

            <!-- Tên đăng nhập -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Tên đăng nhập"
                    value="<?php echo htmlspecialchars($username); ?>" required>
                <label for="username"><i class="fa-solid fa-user me-1"></i> Tên đăng nhập</label>
                <div class="invalid-feedback">Vui lòng nhập tên đăng nhập.</div>
            </div>

            <!-- Mật khẩu -->
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu"
                    required>
                <label for="password"><i class="fa-solid fa-lock me-1"></i> Mật khẩu</label>
                <div class="invalid-feedback">Vui lòng nhập mật khẩu.</div>
            </div>

            <!-- Nút Submit -->
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Đăng Nhập
            </button>

            <div class="text-center">
                <span class="text-muted">Chưa có tài khoản?</span>
                <a href="register.php" class="text-decoration-none fw-bold ms-1">Đăng ký ngay</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Client-side Validation sử dụng Bootstrap
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    const username = document.getElementById('username').value.trim();
                    const password = document.getElementById('password').value;

                    let customError = '';

                    if (username === '') {
                        customError = 'Vui lòng nhập tên đăng nhập!';
                    } else if (password === '') {
                        customError = 'Vui lòng nhập mật khẩu!';
                    }

                    if (customError !== '' || !form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();

                        if (customError !== '') {
                            const errorBox = document.getElementById('js-error');
                            document.getElementById('js-error-text').innerText = customError;
                            errorBox.style.display = 'block';
                        }
                    }

                    form.classList.add('was-validated');
                }, false)
            })
        })()
    </script>

</body>

</html>