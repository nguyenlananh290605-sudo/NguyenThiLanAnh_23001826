<?php

$Sname = "Nguyễn Thị Lan Anh";
$Sscore = 8.5;  // 5 3 7
if ($Sscore >= 8.0) {
    $ranking = "Giỏi";
} elseif ($Sscore >= 6.5) {
    $ranking = "Khá";
} elseif ($Sscore >= 5.0) {
    $ranking = "Trung bình";
} else {
    $ranking = "Yếu";
}

echo "<h3>Kết quả xếp loại:</h3>";
echo "Họ và tên: " . $Sname . "<br>";
echo "Điểm số: " . $Sscore . "<br>";
echo "Xếp loại: " . $ranking;
?>