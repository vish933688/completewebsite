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

// Fetch images from the database
$sql = "SELECT imageName FROM gallery";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
    <style>
        .gallery img {
            width: 200px;
            height: 200px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <h2>Gallery</h2>
    <div class="gallery">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<img src="uploads/' . $row["imageName"] . '" alt="Image">';
            }
        } else {
            echo "No images found.";
        }
        ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
