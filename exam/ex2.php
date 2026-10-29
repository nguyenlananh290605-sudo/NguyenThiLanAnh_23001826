<?php
$prices = [150000, 320000, 95000, 450000, 210000];
 // $prices = [];
function calculateTotal($arr) {
    if (empty($arr) || !is_array($arr)) {
        return 0; 
    }
    
    $total = 0;
    foreach ($arr as $price) {
        if (is_numeric($price)) {
            $total += $price;
        }
    }
    return $total;
}

function findMaxPrice($arr) {
    if (empty($arr) || !is_array($arr)) {
        return null;
    }
    return max($arr);
}

echo "<h3>Quản lý danh sách giá sản phẩm:</h3>";

if (empty($prices)) {
    echo "<p style='color:red;'>Lỗi: Danh sách sản phẩm đang bị rỗng, không thể tính toán.</p>";
} else {
    $totalPrice = calculateTotal($prices);
    $maxPrice = findMaxPrice($prices);

    echo "Danh sách giá sản phẩm: " . implode(", ", $prices) . " VNĐ<br>";
    echo "Tổng giá trị tất cả sản phẩm: " . number_format($totalPrice) . " VNĐ<br>";
    echo "Giá sản phẩm cao nhất: " . number_format($maxPrice) . " VNĐ";
}
?>