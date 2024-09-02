<?php
require_once('tcpdf/tcpdf.php');
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentID = $_POST['studentID'];
    $dob = $_POST['dob'];

    // Fetch student details
    $query = "SELECT name, fatherName, class, address FROM student WHERE studentID = ? AND dob = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $studentID, $dob);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('School Management System');
        $pdf->SetTitle('Student ID Card');
        $pdf->SetSubject('ID Card');
        $pdf->SetKeywords('TCPDF, PDF, ID Card, student');

        // Set default header data
        $pdf->SetHeaderData('', 0, 'School Name', 'Student ID Card');  // Ensure the width (second parameter) is numeric

        // Set margins
        $pdf->SetMargins(10, 10, 10); // Ensure these are numeric
        $pdf->SetHeaderMargin(5);     // Ensure these are numeric
        $pdf->SetFooterMargin(10);    // Ensure these are numeric

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 10); // Ensure these are numeric

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Add a page
        $pdf->AddPage();

        // Create the ID card content
        $html = '
        <h2 style="text-align:center;">Student ID Card</h2>
        <p><strong>Student ID:</strong> ' . htmlspecialchars($studentID, ENT_QUOTES) . '</p>
        <p><strong>Name:</strong> ' . htmlspecialchars($student['name'], ENT_QUOTES) . '</p>
        <p><strong>Father\'s Name:</strong> ' . htmlspecialchars($student['fatherName'], ENT_QUOTES) . '</p>
        <p><strong>Class:</strong> ' . htmlspecialchars($student['class'], ENT_QUOTES) . '</p>
        <p><strong>Address:</strong> ' . htmlspecialchars($student['address'], ENT_QUOTES) . '</p>';

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // Output the PDF
        $pdf->Output('student_idcard.pdf', 'I');
    } else {
        echo "No student found with the provided ID and DOB.";
    }
}
?>
