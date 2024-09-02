<?php
session_start();
include('../student/config.php');

if (!isset($_SESSION['studentID'])) {
    echo "<script>window.location.href='../student/login.php';</script>";
    exit;
}

$feeID = $_GET['feeID'];

// Update the fee status to "paid"
$updateQuery = "UPDATE fees SET status='paid' WHERE feeID='$feeID'";

if (mysqli_query($conn, $updateQuery)) {
    echo "<script>alert('Payment successful!'); window.location.href='fee.php';</script>";
} else {
    echo "<script>alert('Payment failed. Please try again.'); window.location.href='fee.php';</script>";
}

mysqli_close($conn);
?>
