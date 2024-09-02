<?php
require 'config.php';

if (isset($_POST['search'])) {
    $studentName = $_POST['studentName'];
    $studentClass = $_POST['studentClass'];

    // Fetch student details
    $query = "SELECT Student.*, Fees.*, Class.className, Teacher.firstName as teacherFirstName, Teacher.lastName as teacherLastName
              FROM Student
              JOIN Fees ON Student.studentID = Fees.studentID
              JOIN Class ON Student.classID = Class.classID
              JOIN Teacher ON Class.teacherID = Teacher.teacherID
              WHERE Student.firstName LIKE ? AND Class.className = ?";
    $stmt = $conn->prepare($query);
    $searchName = "%$studentName%";
    $stmt->bind_param('ss', $searchName, $studentClass);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="details-container">
        <?php if (isset($student)) : ?>
            <h2>Student Details</h2>
            <p>Name: <?php echo $student['firstName'] . ' ' . $student['lastName']; ?></p>
            <p>Class: <?php echo $student['className']; ?></p>
            <p>Class Teacher: <?php echo $student['teacherFirstName'] . ' ' . $student['teacherLastName']; ?></p>
            <p>Tuition Fees: <?php echo $student['tuitionFees']; ?> - Status: <?php echo $student['tuitionFeesPaid'] ? 'Paid' : 'Due'; ?></p>
            <p>Lab Fees: <?php echo $student['labFees']; ?> - Status: <?php echo $student['labFeesPaid'] ? 'Paid' : 'Due'; ?></p>
            <p>Other Fees: <?php echo $student['otherFees']; ?> - Status: <?php echo $student['otherFeesPaid'] ? 'Paid' : 'Due'; ?></p>

            <!-- Add more student details as needed -->
        <?php else: ?>
            <p>No student found matching the criteria.</p>
        <?php endif; ?>
    </div>

    <!-- Add your CSS and JavaScript links here -->
</body>
</html>
