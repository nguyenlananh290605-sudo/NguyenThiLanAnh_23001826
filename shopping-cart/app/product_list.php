<?php
require_once __DIR__ . '/model/product.php';

// 1. Lấy danh sách sản phẩm và gán vào biến $products (có 's')
try {
    $products = getAllProducts();
} catch (Exception $e) {
    $error = $e->getMessage();
    $products = []; // Khởi tạo mảng rỗng nếu xảy ra lỗi
}

include __DIR__ . '/view/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h3 class="mb-0 text-primary"><i class="fa-solid fa-list me-2"></i>Danh Sách Sản Phẩm</h3>
    <a href="product_add.php" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Thêm sản phẩm mới
    </a>
</div>

<!-- Hiển thị thông báo -->
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

<!-- Bảng hiển thị -->
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
        <!-- Kiểm tra $products không rỗng và duyệt vòng lặp -->
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

<?php include __DIR__ . '/view/footer.php'; ?>