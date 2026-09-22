<?php
require_once "ex2.php";
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
/*
 findBestStudent($students): Tìm và trả về sinh viên có điểm cao nhất.
 findWorstStudent($students): Tìm và trả về sinh viên có điểm thấp nhất.
 countPassedStudents($students): Đếm số sinh viên đạt. Sinh viên đạt khi điểm >= 5.
 findStudentByName($students, $name): Tìm sinh viên theo tên và trả về sinh viên tìm được.
*/
function findBestStudent($students)
{
    if (empty($students))
        return null;
    $best = $students[0];

    foreach ($students as $student) {
        if ($student["score"] > $best["score"]) {
            $best = $student;
        }
    }

    return $best;
}

function findWorstStudent($students)
{
    if (empty($students))
        return null;
    $worst = $students[0];

    foreach ($students as $student) {
        if ($student["score"] < $worst["score"]) {
            $worst = $student;
        }
    }

    return $worst;
}

function countPassedStudents($students)
{
    $count = 0;

    foreach ($students as $student) {
        if ($student["score"] >= 5) {
            $count++;
        }
    }

    return $count;
}

function findStudentByName($students, $name)
{
    if (empty($students))
        return null;
    foreach ($students as $student) {
        if (strtolower($student["name"]) == strtolower($name)) {
            return $student;
        }
    }

    return null;
}

$bestStudent = findBestStudent($students);
echo "<b>Sinh viên có điểm cao nhất:</b><br>";
displayStudent($bestStudent);


$worstStudent = findWorstStudent($students);
echo "<br><b>Sinh viên có điểm thấp nhất:</b><br>";
displayStudent($worstStudent);


$passedCount = countPassedStudents($students);
echo "<br><b>Số sinh viên đạt (Điểm >= 5):</b> " . $passedCount . " sinh viên<br>";


$searchName = "Tran Thi An";
$foundStudent = findStudentByName($students, $searchName);
echo "<br><b>Sinh viên tìm được với tên " . $searchName . ":</b><br>";
if ($foundStudent != null) {
    displayStudent($foundStudent);
} else {
    echo "Không tìm thấy sinh viên này trong danh sách.


";
}
?>