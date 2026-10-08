<?php
require_once __DIR__ . '/model/product.php';

$error = '';
$name = '';
$price = '';
$quantity = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';

    if (empty($name)) {
        $error = 'Tên sản phẩm không được rỗng.';
    } elseif (!is_numeric($price) || (float) $price <= 0) {
        $error = 'Giá sản phẩm phải là số hợp lệ và lớn hơn 0.';
    } elseif (!is_numeric($quantity) || (int) $quantity < 0) {
        $error = 'Số lượng sản phẩm phải là số nguyên không âm (>= 0).';
    } else {
        try {
            if (addProduct($name, (float) $price, (int) $quantity)) {
                header("Location: product_list.php?message=" . urlencode("Thêm sản phẩm thành công!"));
                exit();
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

include __DIR__ . '/view/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h3 class="mb-0 text-primary"><i class="fa-solid fa-box-open me-2"></i>Thêm Sản Phẩm Mới</h3>
    <a href="product_list.php" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="product_add.php" method="POST" class="needs-validation" novalidate style="max-width: 600px;">
    <!-- Form Floating Label cho Tên sản phẩm -->
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên sản phẩm"
            value="<?php echo htmlspecialchars($name); ?>" required>
        <label for="name">Tên sản phẩm</label>
        <div class="invalid-feedback">Vui lòng nhập tên sản phẩm.</div>
    </div>

    <!-- Form Floating Label cho Giá -->
    <div class="form-floating mb-3">
        <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Giá sản phẩm"
            value="<?php echo htmlspecialchars($price); ?>" required>
        <label for="price">Giá sản phẩm (VNĐ)</label>
        <div class="invalid-feedback">Vui lòng nhập giá hợp lệ (> 0).</div>
    </div>

    <!-- Form Floating Label cho Số lượng -->
    <div class="form-floating mb-4">
        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Số lượng"
            value="<?php echo htmlspecialchars($quantity); ?>" required>
        <label for="quantity">Số lượng tồn kho</label>
        <div class="invalid-feedback">Vui lòng nhập số lượng (>= 0).</div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success px-4">
            <i class="fa-solid fa-check me-1"></i> Lưu sản phẩm
        </button>
        <a href="product_list.php" class="btn btn-light px-4 border">Hủy</a>
    </div>
</form>

<script>
    // Bootstrap Client-side Validation script
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<?php include __DIR__ . '/view/footer.php'; ?>