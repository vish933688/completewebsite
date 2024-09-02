<?php
session_start();
require 'config.php'; // Adjust the path as needed

if (!isset($_SESSION['studentID'])) {
    header("Location: ../student/student_login.php");
    exit;
}

$studentID = $_SESSION['studentID'];

// Fetch student details
$query = "SELECT * FROM Student WHERE studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $studentID);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// Fetch results, subjects, and teacher details
$query = "SELECT Subject.subjectName, Subject.subjectCode, Result.grade, 
                 Result.percentage, Teacher.firstName AS teacherFirstName, 
                 Teacher.lastName AS teacherLastName 
          FROM Result 
          JOIN Subject ON Result.subjectID = Subject.subjectID 
          JOIN Teacher ON Subject.teacherID = Teacher.teacherID 
          WHERE Result.studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $studentID);
$stmt->execute();
$results = $stmt->get_result();

// Fetch class rank and other students' comparisons
$query = "SELECT studentID, percentage 
          FROM Result 
          WHERE subjectID IN (SELECT subjectID FROM Result WHERE studentID = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $studentID);
$stmt->execute();
$rankings = $stmt->get_result();

$studentRank = 1;
$studentPercentage = 0;

while ($rank = $rankings->fetch_assoc()) {
    if ($rank['studentID'] == $studentID) {
        $studentPercentage = $rank['percentage'];
    } else if ($rank['percentage'] > $studentPercentage) {
        $studentRank++;
    }
}
?>
