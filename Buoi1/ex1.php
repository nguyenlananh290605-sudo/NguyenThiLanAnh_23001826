<?php
$students = [
    [
        "name" => "Nguyen Van An",
        "age" => 20,
        "score" => 8.5
    ],
    [
        "name" => "Tran Thi Binh",
        "age" => 21,
        "score" => 6.5
    ],
    [
        "name" => "Le Van Cuong",
        "age" => 19,
        "score" => 4.5
    ],
    [
        "name" => "Pham Thi Dung",
        "age" => 20,
        "score" => 7.5
    ]
];
$totalScore = 0;
$totalStudent = count($students);
echo "Danh sách sinh viên: <br>";
echo "------------------- <br>";
foreach ($students as $student) {
    echo "Họ và tên: " . $student['name'] . " | Tuổi: " . $student['age'] . " | Điểm: " . $student['score'] . "<br>";
    $totalScore += $student['score'];
}
echo "------------------- <br>";
if ($totalStudent > 0) {
    $avgScore = $totalScore / $totalStudent;
    echo "Điểm trung bình: " . $avgScore . "<br>";
}

?>