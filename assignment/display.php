<?php
require 'config.php';

$studentID = 1; // Replace with the actual studentID
$sql = "SELECT a.assignmentTitle, a.assignmentDescription, a.dueDate, a.submittedDate, a.status, a.grade, t.firstName AS teacherName, su.subjectName
        FROM Assignment a
        JOIN Teacher t ON a.teacherID = t.teacherID
        JOIN Subject su ON a.subjectID = su.subjectID
        WHERE a.studentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$results = $stmt->get_result();

while ($row = $results->fetch_assoc()) {
    echo "<tr>
            <td>{$row['assignmentTitle']}</td>
            <td>{$row['assignmentDescription']}</td>
            <td>{$row['dueDate']}</td>
            <td>{$row['submittedDate']}</td>
            <td>{$row['status']}</td>
            <td>{$row['grade']}</td>
            <td>{$row['teacherName']}</td>
            <td>{$row['subjectName']}</td>
          </tr>";
}
?>
