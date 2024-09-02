<?php
session_start();
include('config.php'); // Include your database connection

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $teacherID = $_POST['teacherID'];
    $class = $_POST['class']; // Corrected to use 'class'

    // Check teacher credentials based on teacherID and class
    $query = "SELECT * FROM teacher WHERE teacherID = ? AND class = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $teacherID, $class);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher = $result->fetch_assoc();
    

    echo "Teacher ID: " . $teacherID . "<br>";
    echo "Class: " . $class . "<br>";

    if ($teacher) {
        $_SESSION['teacherID'] = $teacher['teacherID'];
        $_SESSION['class'] = $teacher['class'];
        header('Location: attendance.php');
        exit();
    } else {
        echo "Invalid login details!";
    }
}

// Handle Attendance Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submitAttendance'])) {
    $class = $_SESSION['class'];
    $teacherID = $_SESSION['teacherID'];
    $attendanceDate = date('Y-m-d');

    foreach ($_POST['attendance'] as $studentName => $status) {
        $query = "INSERT INTO attendance (name, class, attendanceDate, status, teacherID) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssi', $studentName, $class, $attendanceDate, $status, $teacherID);
        $stmt->execute();
    }
    echo "Attendance submitted successfully!";
}

// Check if logged in
if (!isset($_SESSION['teacherID'])) {
    // Display login form
    echo '
    <form method="POST" action="">
        <label for="teacherID">Teacher ID:</label>
        <input type="text" name="teacherID" required><br>
        <label for="class">Class:</label>
        <input type="text" name="class" required><br>
        <button type="submit" name="login">Login</button>
    </form>';
} else {
    // Display attendance form
    $class = $_SESSION['class'];
    echo '<h2>Attendance for Class: ' . $class . '</h2>';
    echo '<form method="POST" action="">';

    // Fetch student names from a hypothetical `students` table
    $query = "SELECT name FROM student WHERE class = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $class);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '
        <div>
            <label>' . $row['name'] . '</label>
            <input type="radio" name="attendance[' . $row['name'] . ']" value="Present" required> Present
            <input type="radio" name="attendance[' . $row['name'] . ']" value="Absent" required> Absent
        </div>';
    }

    echo '<button type="submit" name="submitAttendance">Submit Attendance</button>';
    echo '</form>';
}
?>
