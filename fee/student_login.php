<?php
session_start(); // Start the session at the beginning

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_POST['studentID'];
    $dob = $_POST['dob']; // Assuming date of birth is being passed as plain text (YYYY-MM-DD)

    // Validate user credentials
    $query = "SELECT * FROM students WHERE studentID = '$studentID' AND dob = '$dob'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $student = mysqli_fetch_assoc($result);

        // Store student information in session
        $_SESSION['studentID'] = $student['studentID'];
        $_SESSION['studentName'] = $student['name'];
        
        // Redirect to fee.php
        echo "<script>window.location.href='fee.php';</script>";
        exit();
    } else {
        echo "<script>alert('Invalid Student ID or Date of Birth');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
</head>
<body>
    <h2>Student Login</h2>
    <form method="post" action="student_login.php">
        <label for="studentID">Student ID:</label>
        <input type="text" name="studentID" id="studentID" required><br><br>

        <label for="dob">Date of Birth (YYYY-MM-DD):</label>
        <input type="date" name="dob" id="dob" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
