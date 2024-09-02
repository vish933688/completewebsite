<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminID = $_POST['adminID'];
    $adminPassword = $_POST['adminPassword'];

    // Check credentials against the database
    $query = "SELECT * FROM admins WHERE adminID = '$adminID' AND adminPassword = '$adminPassword'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Set session variables
        $_SESSION['adminID'] = $adminID;
        $_SESSION['LAST_ACTIVITY'] = time(); // Initialize the session timeout

        // Redirect to admin_fee.php
        echo "<script>window.location.href='admin_fee.php';</script>";
        exit;
    } else {
        echo "<script>alert('Invalid Admin ID or Password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-container h2 {
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
        .form-group input {
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

<div class="login-container">
    <h2>Admin Login</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="adminID">Admin ID</label>
            <input type="text" id="adminID" name="adminID" required>
        </div>
        <div class="form-group">
            <label for="adminPassword">Password</label>
            <input type="password" id="adminPassword" name="adminPassword" required>
        </div>
        <div class="form-group">
            <button type="submit">Login</button>
        </div>
    </form>
</div>

</body>
</html>
