<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_POST['studentID'];
    $dob = $_POST['dob'];

    // Query to verify student credentials
    $query = "SELECT * FROM student WHERE studentID = '$studentID' AND dob = '$dob'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['studentID'] = $studentID;
        echo "<script>window.location.href='../fee/fee.php';</script>";
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
    <!-- Add your CSS here -->
</head>
<body>
    <form method="post" action="">
        <label for="studentID">Student ID</label>
        <input type="text" id="studentID" name="studentID" required>

        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" name="dob" required>

        <button type="submit">Login</button>
    </form>
</body>
</html>
