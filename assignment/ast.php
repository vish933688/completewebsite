<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $stmt = $conn->prepare("INSERT INTO Assignment (studentID, teacherID, subjectID, assignmentTitle, assignmentDescription, dueDate) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $_POST['studentID'], $_POST['teacherID'], $_POST['subjectID'], $_POST['assignmentTitle'], $_POST['assignmentDescription'], $_POST['dueDate']);
    $stmt->execute();
}
?>
