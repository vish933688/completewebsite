
<?php
session_start();
include('config.php');

// Session timeout duration (e.g., 30 minutes)
$timeout_duration = 1800; // 30 minutes

// Check if the session has expired
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // If last activity is longer than timeout, destroy session and redirect to login
    session_unset();
    session_destroy();
    echo "<script>window.location.href='admin_login.php';</script>";
    exit;
}

// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();

// Check if admin is logged in
if (!isset($_SESSION['adminID'])) {
    echo "<script>window.location.href='admin_login.php';</script>";
    exit;
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_POST['studentID'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $monthlyFee = $_POST['monthlyFee'];
    $examFee = $_POST['examFee'];
    $extraCurricularCharge = $_POST['extraCurricularCharge'];
    $transportCharge = $_POST['transportCharge'];
    $totalAmount = $monthlyFee + $examFee + $extraCurricularCharge + $transportCharge;

    $insert_query = "INSERT INTO fees (studentID, month, year, monthlyFee, examFee, extraCurricularCharge, transportCharge, totalAmount, status)
                     VALUES ('$studentID', '$month', '$year', '$monthlyFee', '$examFee', '$extraCurricularCharge', '$transportCharge', '$totalAmount', 'due')";

    if (mysqli_query($conn, $insert_query)) {
        echo "<script>alert('Fee details added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding fee details: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Student Fees</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin - Manage Student Fees</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="studentID">Student ID</label>
            <input type="text" id="studentID" name="studentID" required>
        </div>
        <div class="form-group">
            <label for="month">Month</label>
            <select id="month" name="month" required>
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>
        </div>
        <div class="form-group">
            <label for="year">Year</label>
            <input type="text" id="year" name="year" required value="2024">
        </div>
        <div class="form-group">
            <label for="monthlyFee">Monthly Fee</label>
            <input type="number" id="monthlyFee" name="monthlyFee" required>
        </div>
        <div class="form-group">
            <label for="examFee">Exam Fee</label>
            <input type="number" id="examFee" name="examFee" required>
        </div>
        <div class="form-group">
            <label for="extraCurricularCharge">Extracurricular Activity Charge</label>
            <input type="number" id="extraCurricularCharge" name="extraCurricularCharge" required>
        </div>
        <div class="form-group">
            <label for="transportCharge">Transport Charge</label>
            <input type="number" id="transportCharge" name="transportCharge" required>
        </div>
        <div class="form-group">
            <button type="submit">Add Fee Details</button>
        </div>
    </form>
</div>

</body>
</html>
