<?php
require_once __DIR__ . '/model/product.php';

// 1. Lấy ID sản phẩm từ URL qua $_GET
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id) || (int) $id <= 0) {
    header("Location: product_list.php?error=" . urlencode("Mã sản phẩm không hợp lệ."));
    exit();
}

$id = (int) $id;

// 2. Lấy thông tin hiện tại của sản phẩm từ CSDL
try {
    $product = getProductById($id);
    if (!$product) {
        header("Location: product_list.php?error=" . urlencode("Sản phẩm không tồn tại."));
        exit();
    }
} catch (Exception $e) {
    header("Location: product_list.php?error=" . urlencode($e->getMessage()));
    exit();
}

$error = '';
$name = $product['name'];
$price = $product['price'];
$quantity = $product['quantity'];

// 3. Xử lý Server-side Validation & Update khi Submit Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';

    if ($name === '') {
        $error = 'Tên sản phẩm không được để rỗng.';
    } elseif (!is_numeric($price) || (float) $price <= 0) {
        $error = 'Giá sản phẩm phải là số hợp lệ và lớn hơn 0.';
    } elseif (!is_numeric($quantity) || (int) $quantity < 0) {
        $error = 'Số lượng sản phẩm phải là số nguyên không âm (>= 0).';
    } else {
        try {
            if (updateProduct($id, $name, (float) $price, (int) $quantity)) {
                header("Location: product_list.php?message=" . urlencode("Cập nhật sản phẩm #{$id} thành công!"));
                exit();
            } else {
                $error = 'Cập nhật thất bại, vui lòng thử lại.';
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

include __DIR__ . '/view/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h3 class="mb-0 text-primary">
        <i class="fa-solid fa-pen-to-square me-2"></i>Cập Nhật Sản Phẩm <span
            class="text-secondary">#<?php echo $id; ?></span>
    </h3>
    <a href="product_list.php" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
</div>

<!-- Alert thông báo lỗi (JS hoặc PHP) -->
<div id="js-error" class="alert alert-danger alert-dismissible fade show" role="alert"
    style="display: <?php echo $error ? 'block' : 'none'; ?>;">
    <i class="fa-solid fa-triangle-exclamation me-2"></i>
    <span id="js-error-text"><?php echo htmlspecialchars($error); ?></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- Form với Bootstrap Floating Labels -->
<form action="product_edit.php?id=<?php echo $id; ?>" method="POST" id="editForm" class="needs-validation" novalidate
    style="max-width: 600px;">

    <!-- Tên sản phẩm -->
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên sản phẩm"
            value="<?php echo htmlspecialchars($name); ?>" required>
        <label for="name">Tên sản phẩm</label>
        <div class="invalid-feedback">Vui lòng nhập tên sản phẩm.</div>
    </div>

    <!-- Giá sản phẩm -->
    <div class="form-floating mb-3">
        <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Giá sản phẩm"
            value="<?php echo htmlspecialchars($price); ?>" required>
        <label for="price">Giá sản phẩm (VNĐ)</label>
        <div class="invalid-feedback">Vui lòng nhập giá hợp lệ (> 0).</div>
    </div>

    <!-- Số lượng tồn kho -->
    <div class="form-floating mb-4">
        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Số lượng"
            value="<?php echo htmlspecialchars($quantity); ?>" required>
        <label for="quantity">Số lượng tồn kho</label>
        <div class="invalid-feedback">Vui lòng nhập số lượng không âm (>= 0).</div>
    </div>

    <!-- Thao tác nút bấm -->
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật
        </button>
        <a href="product_list.php" class="btn btn-light px-4 border">Hủy</a>
    </div>
</form>

<script>
    // Client-side Validation sử dụng Bootstrap 5
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                const name = document.getElementById('name').value.trim();
                const price = parseFloat(document.getElementById('price').value);
                const quantity = Number(document.getElementById('quantity').value);

                let customError = '';

                if (name === '') {
                    customError = 'Tên sản phẩm không được để rỗng!';
                } else if (isNaN(price) || price <= 0) {
                    customError = 'Giá sản phẩm phải lớn hơn 0!';
                } else if (isNaN(quantity) || !Number.isInteger(quantity) || quantity < 0) {
                    customError = 'Số lượng phải là số nguyên lớn hơn hoặc bằng 0!';
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

<?php include __DIR__ . '/view/footer.php'; ?>