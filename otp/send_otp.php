<?php
session_start();

// Include the Twilio SDK
require __DIR__ . '../../vendor/autoload.php';
use Twilio\Rest\Client;

// Twilio credentials
$account_sid = 'ACbed2bb98db1a39fc89e1f2efa04fddf0';
$auth_token = '501c8f634a42190ceecff97a043a92e4';
$twilio_number = '9336882409'; // Your Twilio phone number

// Initialize variables
$mobileNo = $emailId = $otp = $message = "";

// Initialize or reset the failed attempts counter
if (!isset($_SESSION['failedAttempts'])) {
    $_SESSION['failedAttempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['sendOtp'])) {
        // Step 1: Send OTP

        // Get form data
        $mobileNo = $_POST['mobileNo'];
        $emailId = $_POST['emailId'];

        // Generate OTP
        $otp = rand(100000, 999999); // Generate a 6-digit OTP

        // Store OTPs in session
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $emailId;
        $_SESSION['mobile'] = $mobileNo;

        // Send OTP via SMS using Twilio
        try {
            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                $mobileNo,
                [
                    'from' => $twilio_number,
                    'body' => "Your OTP is $otp"
                ]
            );

            // Send OTP via Email
            $subject = "Your OTP Code";
            $messageBody = "Your OTP for verification is $otp";
            $headers = "From: vish9336@gmail.com";

            mail($emailId, $subject, $messageBody, $headers);

            // Feedback message
            $message = "OTP sent to your mobile number and email.";
            // Reset failed attempts counter
            $_SESSION['failedAttempts'] = 0;
        } catch (Exception $e) {
            $message = "Error sending OTP: " . $e->getMessage();
        }
    } elseif (isset($_POST['verifyOtp'])) {
        // Step 2: Verify OTP

        $userOtp = $_POST['otp'];

        // Validate OTP
        if ($userOtp == $_SESSION['otp']) {
            $message = "OTP Verified Successfully!";
            // Clear OTP and failed attempts after successful verification
            unset($_SESSION['otp']);
            unset($_SESSION['failedAttempts']);
        } else {
            // Increment the failed attempts counter
            $_SESSION['failedAttempts'] += 1;

            // Check if the user has exceeded the maximum number of attempts
            if ($_SESSION['failedAttempts'] >= 3) {
                // Reset session and redirect to main page
                session_unset();
                session_destroy();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $message = "Invalid OTP! You have " . (3 - $_SESSION['failedAttempts']) . " attempts left.";
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
    <title>OTP Verification</title>
</head>
<body>
    <h2>Verify Your Mobile Number and Email</h2>

    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (!isset($_SESSION['otp'])): ?>
        <!-- Step 1: Collect Mobile Number and Email -->
        <form method="POST" action="">
            <label for="mobileNo">Mobile Number:</label>
            <input type="text" id="mobileNo" name="mobileNo" value="<?php echo $mobileNo; ?>" required><br><br>
            
            <label for="emailId">Email ID:</label>
            <input type="email" id="emailId" name="emailId" value="<?php echo $emailId; ?>" required><br><br>
            
            <input type="submit" name="sendOtp" value="Send OTP">
        </form>
    <?php else: ?>
        <!-- Step 2: Verify OTP -->
        <form method="POST" action="">
            <label for="otp">Enter OTP:</label>
            <input type="text" id="otp" name="otp" required><br><br>
            
            <input type="submit" name="verifyOtp" value="Verify OTP">
        </form>
    <?php endif; ?>
</body>
</html>
