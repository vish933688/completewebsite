<?php
session_start();
include('config.php');

if(isset($_POST['login'])) {
    $studentID = $_POST['studentID'];
    $dob = $_POST['dob'];
    
    $query = "SELECT * FROM student WHERE studentID = '$studentID' AND dob = '$dob'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $_SESSION['studentID'] = $studentID;
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit;
}

if(!isset($_SESSION['studentID'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$studentID = $_SESSION['studentID'];
$student_query = "SELECT * FROM student WHERE studentID = '$studentID'";
$student_result = mysqli_query($conn, $student_query);
$student_data = mysqli_fetch_assoc($student_result);

$subject_query = "SELECT * FROM subject WHERE class = '{$student_data['class']}'";
$subject_result = mysqli_query($conn, $subject_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .header, .footer {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .navbar {
            display: flex;
            justify-content: center;
            background-color: #444;
            padding: 10px;
        }
        .navbar a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .content {
            padding: 20px;
        }
        .dashboard-section {
            margin-bottom: 20px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .footer a {
            color: white;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Vishal Public School</h1>
</div>

<div class="navbar">
    <a href="index.html">Home</a>
    <a href="html_pages/aboutus.html">About Us</a>
    
    <a href="html_pages/admission.html">Admission</a>
    <a href="../result/result.php">Result</a>
    <a href="#">Notice Board</a>
    <a href="#">Contact Us</a>
</div>

<div class="content">
    <div class="dashboard-section">
        <h2>Welcome, <?php echo $student_data['name']; ?></h2>
        <p>Class: <?php echo $student_data['class']; ?></p>
    </div>

    <div class="dashboard-section">
        <h3>Your Subjects</h3>
        <ul>
            <?php while($subject = mysqli_fetch_assoc($subject_result)) { ?>
                <li><?php echo $subject['subjectName']; ?> (<?php echo $subject['subjectCode']; ?>)</li>
            <?php } ?>
        </ul>
    </div>

    <div class="dashboard-section">
        <h3>Assignments</h3>
        <!-- Assignment List (to be fetched from database) -->
    </div>

    <div class="dashboard-section">
        <h3>Fee Details</h3>
        <!-- Fee details and payment gateway integration -->
        <a href="payment/index.html">Pay Fees</a>
    </div>
</div>

<div class="footer">
    <p>&copy; 2024 Vishal Public School. All rights reserved.</p>
    <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
</div>

<script>
$(document).ready(function(){
    $('#loginForm').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'student_dashboard.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response){
                if(response.status == 'success'){
                    window.location.href = 'student_dashboard.php';
                } else {
                    alert('Invalid login credentials.');
                }
            }
        });
    });
});
</script>

</body>
</html>
