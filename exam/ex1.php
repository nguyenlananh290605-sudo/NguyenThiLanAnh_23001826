<?php

$Sname = "Nguyễn Thị Lan Anh";
$Sscore ="a";  // 5 3 7

echo "<h3>Kết quả xếp loại:</h3>";
if(!is_numeric($Sscore)){
    echo "Điểm không hợp lệ";
    exit;
}
if ($Sscore >= 8.0) {
    $ranking = "Giỏi";
} elseif ($Sscore >= 6.5) {
    $ranking = "Khá";
} elseif ($Sscore >= 5.0) {
    $ranking = "Trung bình";
} else {
    $ranking = "Yếu";
}
echo "Họ và tên: " . $Sname . "<br>";
echo "Điểm số: " . $Sscore . "<br>";
echo "Xếp loại: " . $ranking;
?>