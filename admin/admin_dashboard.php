<?php
// Include database connection
require 'config.php';
session_start();

// Initialize variables
$adminID = '';
$loginError = '';

// Check if the admin is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    $adminID = $_SESSION['admin_id']; // Assuming you set this session variable upon login

    // Handle form submissions for different sections
    if (isset($_POST['addStudent'])) {
        $studentName = isset($_POST['name']) ? $_POST['name'] : '';
        $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';
        $address = isset($_POST['address']) ? $_POST['address'] : '';
        $enrollmentDate = isset($_POST['enrollmentDate']) ? $_POST['enrollmentDate'] : '';
        $fatherName = isset($_POST['fatherName']) ? $_POST['fatherName'] : '';
        $motherName = isset($_POST['motherName']) ? $_POST['motherName'] : '';
        $studentclass = isset($_POST['class']) ? $_POST['class'] : '';
    
        // Correct SQL query
        $sql = "INSERT INTO student (name, class, dob, gender, email, phoneNumber, address, enrollmentDate, fatherName, motherName)
                VALUES ('$studentName', '$studentclass', '$dob', '$gender', '$email', '$phoneNumber', '$address', '$enrollmentDate', '$fatherName', '$motherName')";
    
        if ($conn->query($sql) === TRUE) {
            echo "New student added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    

    if (isset($_POST['addTeacher'])) {
        $teacherName = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';
        $hireDate = isset($_POST['hireDate']) ? $_POST['hireDate'] : '';
        $salary = isset($_POST['salary']) ? $_POST['salary'] : '';
        $address = isset($_POST['address']) ? $_POST['address'] : '';
        $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
        
        // Ensure these variables are not empty or handle them accordingly
        // Generate a unique teacher ID
        $prefix = 'TCHR';
        $query = "SELECT MAX(CAST(SUBSTRING(teacherUniqid, 5) AS UNSIGNED)) AS maxID FROM teacher";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $maxID = $row['maxID'] ? $row['maxID'] : 0;
        $newID = $prefix . str_pad($maxID + 1, 4, '0', STR_PAD_LEFT);
    
        // Insert the new teacher record with the generated ID
        $sql = "INSERT INTO teacher (teacherUniqid, name, subject, email, phoneNumber, hireDate, salary, address)
                VALUES ('$newID', '$teacherName', '$subject', '$email', '$phoneNumber', '$hireDate', '$salary', '$address')";
    
        if ($conn->query($sql) === TRUE) {
            echo "New teacher added successfully with ID: $newID";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    

    if (isset($_POST['addSubject'])) {
        $subjectName = $_POST['subjectName'];
        $subjectCode = $_POST['subjectCode'];

        $sql = "INSERT INTO subject (subjectName, subjectCode) VALUES ('$subjectName', '$subjectCode')";
        if ($conn->query($sql) === TRUE) {
            echo "New subject added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['addSalary'])) {
        $teacherID = $_POST['teacherID'];
        $amount = $_POST['amount'];
        $date = $_POST['date'];

        $sql = "INSERT INTO salary (teacherID, amount, date) VALUES ('$teacherID', '$amount', '$date')";
        if ($conn->query($sql) === TRUE) {
            echo "Salary added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['addResult'])) {
        $studentID = $_POST['studentID'];
        $subjectID = $_POST['subjectID'];
        $grade = $_POST['grade'];
        $examDate = $_POST['examDate'];

        $sql = "INSERT INTO result (studentID, subjectID, grade, examDate) VALUES ('$studentID', '$subjectID', '$grade', '$examDate')";
        if ($conn->query($sql) === TRUE) {
            echo "Result added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['addAttendance'])) {
        $studentID = $_POST['studentID'];
        $teacherID = $_POST['teacherID'];
        $date = $_POST['date'];
        $status = $_POST['status'];

        $sql = "INSERT INTO attendance (studentID, teacherID, date, status) VALUES ('$studentID', '$teacherID', '$date', '$status')";
        if ($conn->query($sql) === TRUE) {
            echo "Attendance added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Logout functionality
    if (isset($_POST['logout'])) {
        session_destroy();
        setcookie('adminID', '', time() - 3600, '/'); // Remove cookie
        header("Location: admin_dashboard.php");
        exit();
    }
} else {
    // Handle admin login
    if (isset($_POST['login'])) {
        $adminID = $_POST['adminID'];
        $adminPassword = $_POST['adminPassword'];

        // Check if cookies are set and match the login details
        if (isset($_COOKIE['adminID']) && $_COOKIE['adminID'] == $adminID) {
            // Validate cookie (could include hashed value check if applicable)
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $adminID;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $sql = "SELECT * FROM admins WHERE adminID = ? AND adminPassword = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $adminID, $adminPassword);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $adminID; // Store adminID in session
                setcookie('adminID', $adminID, time() + 3600, '/'); // Set cookie
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $loginError = "Invalid Admin ID or Password";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: relative;
        }
        .admin-info {
            position: absolute;
            top: 10px;
            right: 20px;
            text-align: right;
            color: #fff;
        }
        .admin-info span {
            display: block;
        }
        .admin-info a {
            color: #fff;
            text-decoration: none;
            padding: 5px 0;
            display: inline-block;
            border: 1px solid transparent;
            border-radius: 5px;
        }
        .admin-info a:hover {
            border: 1px solid #fff;
        }
        .container {
            padding: 20px;
        }
        .tab {
            display: flex;
            margin-bottom: 20px;
        }
        .tab button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-right: 5px;
            border-radius: 5px;
        }
        .tab button.active {
            background: #0056b3;
        }
        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
        }
        .form-section form {
            margin-bottom: 20px;
        }
        .form-section form input,
        .form-section form select,
        .form-section form button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
        }
        .form-section form button {
            background: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .form-section form button:hover {
            background: #0056b3;
        }
        .login-form {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
        .login-form input {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Vishal Public School Admin Dashboard</h1>
        <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']): ?>
            <div class="admin-info">
                <span>Admin ID: <?php echo htmlspecialchars($adminID ?? ''); ?></span>
                <form method="POST" action="">
                    <button type="submit" name="logout">Logout</button>
                </form>
            </div>
        <?php endif; ?>
    </header>
    
    <?php if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']): ?>
        <!-- Admin Login Form -->
        <div class="login-form">
            <form method="POST" action="">
                <h2>Admin Login</h2>
                <input type="text" name="adminID" placeholder="Admin ID" required>
                <input type="password" name="adminPassword" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <?php if ($loginError): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($loginError); ?></p>
                <?php endif; ?>
            </form>
        </div>
    <?php else: ?>
        <!-- Admin Dashboard -->
        <div class="container">
            <div class="tab">
                <button class="tablinks active" data-target="student-management">Student Management</button>
                <button class="tablinks" data-target="teacher-management">Teacher Management</button>
                <button class="tablinks" data-target="subject-management">Subject Management</button>
                <button class="tablinks" data-target="salary-management">Salary Management</button>
                <button class="tablinks" data-target="result-management">Result Management</button>
                <button class="tablinks" data-target="attendance-management">Attendance Management</button>
                <button class="tablinks" data-target="logout">Logout</button>
            </div>

            <!-- Student Management Section -->
            <div id="student-management" class="form-section active">
                <h2>Student Management</h2>
                <form method="POST" action="">
                    <input type="text" name="name" placeholder="Student Name" required>
                    <input type="text" name="fatherName" placeholder="Father Name" required>
                    <input type="text" name="motherName" placeholder="Mother Name" required>
                    <input type="text" name="class" placeholder="Class" required>
                    <input type="date" name="dob" placeholder="Date Of Birth " required>
                    <input type="text" name="gender" placeholder="Gender" required>
                    <input type="text" name="email" placeholder="Email Id" required>
                    <input type="text" name="phoneNumber" placeholder="Phone Number" required>
                    <input type="text" name="address" placeholder="Address" required>
                    <input type="date" name="enrollmentDate" placeholder="Enrollment Date" required>
                    <button type="submit" name="addStudent">Add Student</button>
                </form>
            </div>

            <!-- Teacher Management Section -->
            <div id="teacher-management" class="form-section">
                <h2>Teacher Management</h2>
                <form method="POST" action="">
                    <input type="text" name="name" placeholder="Teacher Name" required>
                    <input type="text" name="subject" placeholder="Subject" required>
                    <input type="text" name="email" placeholder="EmailID" required>
                    <input type="text" name="phoneNumber" placeholder="PhoneNumber" required>
                    <input type="date" name="hireDate" placeholder="HireDate " required>
                    <input type="text" name="salary" placeholder="Salary" required>
                    <input type="text" name="address" placeholder="Address " required>
                    



                    <button type="submit" name="addTeacher">Add Teacher</button>
                </form>
            </div>

            <!-- Subject Management Section -->
            <div id="subject-management" class="form-section">
                <h2>Subject Management</h2>
                <form method="POST" action="">
                    <input type="text" name="subjectName" placeholder="Subject Name" required>
                    <input type="text" name="subjectCode" placeholder="Subject Code" required>
                    <button type="submit" name="addSubject">Add Subject</button>
                </form>
            </div>

            <!-- Salary Management Section -->
            <div id="salary-management" class="form-section">
                <h2>Salary Management</h2>
                <form method="POST" action="">
                    <input type="text" name="teacherID" placeholder="Teacher ID" required>
                    <input type="number" name="amount" placeholder="Amount" required>
                    <input type="date" name="date" required>
                    <button type="submit" name="addSalary">Add Salary</button>
                </form>
            </div>

            <!-- Result Management Section -->
            <div id="result-management" class="form-section">
                <h2>Result Management</h2>
                <form method="POST" action="">
                    <input type="text" name="studentID" placeholder="Student ID" required>
                    <input type="text" name="subjectID" placeholder="Subject ID" required>
                    <input type="text" name="grade" placeholder="Grade" required>
                    <input type="date" name="examDate" required>
                    <button type="submit" name="addResult">Add Result</button>
                </form>
            </div>

            <!-- Attendance Management Section -->
            <div id="attendance-management" class="form-section">
                <h2>Attendance Management</h2>
                <form method="POST" action="">
                    <input type="text" name="studentID" placeholder="Student ID" required>
                    <input type="text" name="teacherID" placeholder="Teacher ID" required>
                    <input type="date" name="date" required>
                    <select name="status" required>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                    </select>
                    <button type="submit" name="addAttendance">Add Attendance</button>
                </form>
            </div>

            <!-- Logout Section -->
            <div id="logout" class="form-section">
                <form method="POST" action="">
                    <button type="submit" name="logout">Logout</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show the first tab by default
            $('.tablinks:first').addClass('active');
            $('.form-section:first').addClass('active');

            $('.tablinks').click(function() {
                // Remove active class from all tabs and sections
                $('.tablinks').removeClass('active');
                $('.form-section').removeClass('active');

                // Add active class to clicked tab and corresponding section
                $(this).addClass('active');
                var target = $(this).data('target');
                $('#' + target).addClass('active');
            });
        });
    </script>
</body>
</html>
