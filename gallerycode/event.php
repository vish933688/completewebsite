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

// Fetch images grouped by event type
$sql = "SELECT imageName, eventType FROM gallery ORDER BY eventType";
$result = $conn->query($sql);

// Group images by event type
$images = [];
while($row = $result->fetch_assoc()) {
    $images[$row['eventType']][] = $row['imageName'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>School Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        header, footer {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .event-section {
            margin: 20px;
        }
        .event-heading {
            font-size: 24px;
            margin: 20px 0;
        }
        .event-images {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .event-images img {
            width: 200px;
            height: 200px;
            margin: 10px;
            object-fit: cover;
            border: 2px solid #ccc;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>School Name</h1>
</header>

<main>
    <?php foreach ($images as $eventType => $eventImages) : ?>
        <div class="event-section">
            <h2 class="event-heading"><?php echo htmlspecialchars($eventType); ?></h2>
            <div class="event-images">
                <?php foreach ($eventImages as $imageName) : ?>
                    <img src="uploads/<?php echo htmlspecialchars($imageName); ?>" alt="<?php echo htmlspecialchars($eventType); ?>">
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</main>

<footer>
    <p>Important Links: <a href="link1.html">Link 1</a> | <a href="link2.html">Link 2</a> | <a href="link3.html">Link 3</a></p>
</footer>

</body>
</html>
