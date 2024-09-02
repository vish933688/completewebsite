<?php
// Database configuration
$host = 'localhost'; // Database host
$dbname = 'schooldb'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create a PDO instance (connect to the database)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare an SQL statement to insert form data into the database
    $sql = "INSERT INTO submitform (full_name, dob, gender, address, email, phone, current_school, grade, previous_achievements, guardian_name, guardian_phone, guardian_email) 
            VALUES (:full_name, :dob, :gender, :address, :email, :phone, :current_school, :grade, :previous_achievements, :guardian_name, :guardian_phone, :guardian_email)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind parameters to the statement
    $stmt->bindParam(':full_name', $_POST['full_name']);
    $stmt->bindParam(':dob', $_POST['dob']);
    $stmt->bindParam(':gender', $_POST['gender']);
    $stmt->bindParam(':address', $_POST['address']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':phone', $_POST['phone']);
    $stmt->bindParam(':current_school', $_POST['current_school']);
    $stmt->bindParam(':grade', $_POST['grade']);
    $stmt->bindParam(':previous_achievements', $_POST['previous_achievements']);
    $stmt->bindParam(':guardian_name', $_POST['guardian_name']);
    $stmt->bindParam(':guardian_phone', $_POST['guardian_phone']);
    $stmt->bindParam(':guardian_email', $_POST['guardian_email']);

    // Execute the statement
    $stmt->execute();

    // Redirect to a success page or display a success message
    echo "Student admission form submitted successfully!";
} catch (PDOException $e) {
    // Handle any errors
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$pdo = null;
?>
