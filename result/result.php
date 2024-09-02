<?php
// Include database connection
require 'config.php';
require 'fpdf186/fpdf.php'; // Make sure you have the FPDF library

session_start();

// Handle form submission
if (isset($_POST['showResult'])) {
    $studentID = $_POST['studentID'];
    $dob = $_POST['dob'];

    // Validate student
    $sql = "SELECT * FROM student WHERE studentID = ? AND dob = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $studentID, $dob);
    $stmt->execute();
    $studentResult = $stmt->get_result();

    if ($studentResult->num_rows > 0) {
        $student = $studentResult->fetch_assoc();
        // Fetch results
        $sql = "SELECT r.*, s.subjectName FROM result r JOIN subject s ON r.subjectID = s.subjectID WHERE r.studentID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $studentID);
        $stmt->execute();
        $results = $stmt->get_result();
        
        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Vishal Public School', 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Student Result Report', 0, 1, 'C');
        $pdf->Ln(10);

        // Student Details
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Student Name: ' . $student['name'], 0, 1);
        $pdf->Cell(0, 10, 'Date of Birth: ' . $student['dob'], 0, 1);
        $pdf->Cell(0, 10, 'Father\'s Name: ' . $student['fatherName'], 0, 1);
        $pdf->Cell(0, 10, 'Mother\'s Name: ' . $student['motherName'], 0, 1);
        $pdf->Cell(0, 10, 'Address: ' . $student['address'], 0, 1);
        $pdf->Ln(10);

        // Results Table
        $pdf->Cell(0, 10, 'Results:', 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, 'Subject', 1);
        $pdf->Cell(30, 10, 'Grade', 1);
        $pdf->Cell(30, 10, 'Exam Date', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        while ($row = $results->fetch_assoc()) {
            $pdf->Cell(90, 10, $row['subjectName'], 1);
            $pdf->Cell(30, 10, $row['grade'], 1);
            $pdf->Cell(30, 10, $row['examDate'], 1);
            $pdf->Ln();
        }

        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Date of Result: ' . date('Y-m-d'), 0, 1, 'L');
        $pdf->Ln(20);

        // Signature and Stamp
        $pdf->Cell(0, 10, 'Principal', 0, 1, 'L');
        $pdf->Cell(0, 10, 'Signature: ______________', 0, 1, 'L');
        $pdf->Cell(0, 10, 'Stamp: ______________', 0, 1, 'L');

        // Output PDF
        $pdf->Output();
        exit();
    } else {
        $error = "No results found for the provided student ID and DOB.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
        }
        .form-section {
            margin: 20px 0;
        }
        .form-section form input,
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
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2>Check Your Result</h2>
            <form method="POST" action="">
                <input type="text" name="studentID" placeholder="Student ID" required>
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <button type="submit" name="showResult">Show Result</button>
            </form>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
