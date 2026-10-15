<?php
session_start();
require_once __DIR__ . '/common/dbConnect.php';

// Nếu đã đăng nhập thì chuyển thẳng đến trang danh sách sản phẩm
if (isset($_SESSION['user'])) {
    header("Location: product_list.php");
    exit();
}

$error = '';
$success = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 1. Kiểm tra không được để trống
    if ($username === '') {
        $error = 'Vui lòng nhập tên đăng nhập.';
    } elseif ($password === '') {
        $error = 'Vui lòng nhập mật khẩu.';
    } elseif ($password !== $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải chứa ít nhất 6 ký tự.';
    } else {
        try {
            global $conn;

            // 2. Kiểm tra tên đăng nhập đã tồn tại trong Database chưa
            $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
            $checkStmt->execute([':username' => $username]);

            if ($checkStmt->fetch()) {
                $error = "Tên đăng nhập '{$username}' đã tồn tại. Vui lòng chọn tên khác.";
            } else {
                // 3. Sử dụng password_hash() để mã hóa mật khẩu
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Thêm người dùng mới vào CSDL
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                $result = $stmt->execute([
                    ':username' => $username,
                    ':password' => $hashedPassword
                ]);

                if ($result) {
                    // 4. Thông báo và chuyển hướng sang trang đăng nhập
                    $_SESSION['register_success'] = 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.';
                    header("Location: login.php");
                    exit();
                } else {
                    $error = 'Đã có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại.';
                }
            }
        } catch (PDOException $e) {
            $error = 'Lỗi hệ thống: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - Shopping Cart Admin</title>
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

        .register-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body>

    <div class="card register-card p-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary"><i class="fa-solid fa-user-plus me-2"></i>Đăng Ký</h3>
            <p class="text-muted">Tạo tài khoản quản trị hệ thống</p>
        </div>

        <!-- Thông báo lỗi (PHP hoặc JS) -->
        <div id="js-error" class="alert alert-danger alert-dismissible fade show" role="alert"
            style="display: <?php echo $error ? 'block' : 'none'; ?>;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <span id="js-error-text"><?php echo htmlspecialchars($error); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Form Đăng Ký -->
        <form action="register.php" method="POST" id="registerForm" class="needs-validation" novalidate>

            <!-- Tên đăng nhập -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Tên đăng nhập"
                    value="<?php echo htmlspecialchars($username); ?>" required>
                <label for="username"><i class="fa-solid fa-user me-1"></i> Tên đăng nhập</label>
                <div class="invalid-feedback">Vui lòng nhập tên đăng nhập.</div>
            </div>

            <!-- Mật khẩu -->
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu"
                    required>
                <label for="password"><i class="fa-solid fa-lock me-1"></i> Mật khẩu</label>
                <div class="invalid-feedback">Vui lòng nhập mật khẩu (ít nhất 6 ký tự).</div>
            </div>

            <!-- Xác nhận mật khẩu -->
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                    placeholder="Xác nhận mật khẩu" required>
                <label for="confirm_password"><i class="fa-solid fa-key me-1"></i> Xác nhận mật khẩu</label>
                <div class="invalid-feedback">Vui lòng nhập lại mật khẩu.</div>
            </div>

            <!-- Nút submit -->
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">
                <i class="fa-solid fa-user-check me-1"></i> Đăng Ký Tài Khoản
            </button>

            <div class="text-center">
                <span class="text-muted">Đã có tài khoản?</span>
                <a href="login.php" class="text-decoration-none fw-bold ms-1">Đăng nhập ngay</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Client-side validation với Bootstrap
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    const username = document.getElementById('username').value.trim();
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm_password').value;

                    let customError = '';

                    if (username === '') {
                        customError = 'Vui lòng nhập tên đăng nhập!';
                    } else if (password === '') {
                        customError = 'Vui lòng nhập mật khẩu!';
                    } else if (password.length < 6) {
                        customError = 'Mật khẩu phải dài ít nhất 6 ký tự!';
                    } else if (password !== confirmPassword) {
                        customError = 'Mật khẩu xác nhận không khớp!';
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