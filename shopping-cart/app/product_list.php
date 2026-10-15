<?php
session_start();
require_once __DIR__ . '/model/product.php';

// Kiểm tra xem đã đăng nhập hay chưa
$isLoggedIn = isset($_SESSION['user']);

$products = [];
if ($isLoggedIn) {
    try {
        $products = getAllProducts();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include __DIR__ . '/view/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h3 class="mb-0 text-primary"><i class="fa-solid fa-list me-2"></i>Danh Sách Sản Phẩm</h3>
    <?php if ($isLoggedIn): ?>
        <a href="product_add.php" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Thêm sản phẩm mới
        </a>
    <?php endif; ?>
</div>

<!-- Hiển thị thông báo thành công/lỗi -->
<?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($_GET['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error']) || isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo htmlspecialchars($_GET['error'] ?? $error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- NẾU CHƯA ĐĂNG NHẬP: Hiển thị cảnh báo và KHÔNG cho xem danh sách -->
<?php if (!$isLoggedIn): ?>
    <div class="alert alert-warning text-center py-4" role="alert">
        <i class="fa-solid fa-lock fa-3x mb-3 text-warning"></i>
        <h4 class="alert-heading fw-bold">Yêu cầu đăng nhập</h4>
        <p class="mb-3">Bạn cần phải đăng nhập vào hệ thống để có thể xem danh sách sản phẩm và quản lý.</p>
        <hr>
        <div class="d-flex justify-content-center gap-2">
            <a href="login.php" class="btn btn-primary px-4">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Đăng nhập ngay
            </a>
            <a href="register.php" class="btn btn-outline-secondary px-4">
                <i class="fa-solid fa-user-plus me-1"></i> Đăng ký
            </a>
        </div>
    </div>
<?php else: ?>
    <!-- NẾU ĐÃ ĐĂNG NHẬP: Hiển thị bảng danh sách bình thường -->
    <table class="table table-hover align-middle shadow-sm">
        <thead class="table-dark">
            <tr>
                <th class="text-center" style="width: 80px;">ID</th>
                <th>Tên sản phẩm</th>
                <th class="text-end">Giá</th>
                <th class="text-center">Số lượng</th>
                <th class="text-center" style="width: 180px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products) && is_array($products)): ?>
                <?php foreach ($products as $prod): ?>
                    <tr>
                        <td class="text-center fw-bold">#<?php echo $prod['id']; ?></td>
                        <td><?php echo htmlspecialchars($prod['name']); ?></td>
                        <td class="text-end text-success fw-bold"><?php echo number_format($prod['price'], 2); ?> đ</td>
                        <td class="text-center"><span class="badge bg-info text-dark"><?php echo $prod['quantity']; ?></span></td>
                        <td class="text-center">
                            <a href="product_edit.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                <i class="fa-solid fa-pen-to-square"></i> Sửa
                            </a>
                            <a href="product_delete.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fa-solid fa-box-open fa-2x mb-2 d-block"></i> Chưa có sản phẩm nào trong danh sách.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/view/footer.php'; ?>