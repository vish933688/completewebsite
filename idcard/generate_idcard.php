<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Student ID Card</title>
</head>
<body>
    <h2>Enter Student Details</h2>
    <form action="idcard_pdf.php" method="POST">
        <label for="studentID">Student ID:</label>
        <input type="text" id="studentID" name="studentID" required><br><br>
        
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br><br>
        
        <button type="submit">Generate ID Card</button>
    </form>
</body>
</html>
