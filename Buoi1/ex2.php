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
function calculateAverageScore($students)
{
    $totalScore = 0;
    $totalStudent = count($students);
    if ($totalStudent == 0) {
        return 0;
    }
    foreach ($students as $student) {
        $totalScore += $student['score'];
    }
    return $totalScore / $totalStudent;
}

function getRank($score)
{
    if ($score >= 8) {
        return "Giỏi";
    } elseif ($score >= 6.5) {
        return "Khá";
    } elseif ($score >= 5) {
        return "Trung bình";
    } else {
        return "Yếu";
    }
}
function displayStudent($student)
{
    $rank = getRank($student["score"]);
    echo "- Họ tên: " . $student["name"]
        . " | Tuổi: " . $student["age"]
        . " | Điểm: " . $student["score"]
        . " | Xếp loại: " . $rank . "<br>";
}

echo "Danh sách sinh viên: <br>";
echo "---------------------<br>";
foreach ($students as $student) {
    displayStudent($student);
}
echo "---------------------<br>";

$avgScore = calculateAverageScore($students);
echo "Điểm trung bình : " . $avgScore . "<br>";

?>