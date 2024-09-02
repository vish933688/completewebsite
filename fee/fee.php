<?php
session_start();
include('../student/config.php');

// Check if the user is logged in
if (!isset($_SESSION['studentID'])) {
    echo "<script>window.location.href='../student/login.php';</script>";
    exit;
}

// Get the logged-in student's ID
$studentID = $_SESSION['studentID'];

// Fetch fee details from the database
$query = "SELECT * FROM fees WHERE studentID = '$studentID'";
$result = mysqli_query($conn, $query);

// Logout functionality
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    echo "<script>window.location.href='../student/login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Details</title>
    <!-- Add your CSS here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .logout-link {
            text-align: right;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .paid {
            color: green;
        }
        .unpaid, .due {
            color: red;
        }
        .total-amount {
            font-size: 1.2em;
            font-weight: bold;
            text-align: right;
        }
        .pay-now {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .pay-now:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="logout-link">
        <strong>Logged in as: <?php echo $studentID; ?></strong> |
        <a href="?action=logout">Logout</a>
    </div>

    <h2>Fee Details for Student ID: <?php echo $studentID; ?></h2>

    <table border="1">
        <tr>
            <th>Month</th>
            <th>Year</th>
            <th>Monthly Fee</th>
            <th>Exam Fee</th>
            <th>Extracurricular Charge</th>
            <th>Transport Charge</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['month']; ?></td>
            <td><?php echo $row['year']; ?></td>
            <td><?php echo $row['monthlyFee']; ?></td>
            <td><?php echo $row['examFee']; ?></td>
            <td><?php echo $row['extraCurricularCharge']; ?></td>
            <td><?php echo $row['transportCharge']; ?></td>
            <td><?php echo $row['totalAmount']; ?></td>
            <td><?php echo $row['status'] == 'paid' ? '✔️' : '❌'; ?></td>
            <td>
                <?php if ($row['status'] != 'paid') { ?>
                    <a href="../payment/index.html?php echo $row['feeID']; ?>" class="pay-now">Pay Now</a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
