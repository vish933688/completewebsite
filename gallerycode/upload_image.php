<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Aa1@monu";
$dbname = "schooldb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if(isset($_POST['submit'])) {
    $imageName = $_FILES['image']['name'];
    $imageTempName = $_FILES['image']['tmp_name'];
    $uploadDir = "uploads/";

    // Move uploaded image to server directory
    if(move_uploaded_file($imageTempName, $uploadDir.$imageName)) {
        // Store image details in database
        $sql = "INSERT INTO gallery (imageName) VALUES ('$imageName')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Image uploaded and stored successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}

$conn->close();
?>
