<?php
session_start();

// Get the OTPs entered by the user
$emailOtpInput = $_POST['emailOtp'];
$mobileOtpInput = $_POST['mobileOtp'];

// Retrieve the OTPs stored in session
$emailOtp = $_SESSION['emailOtp'];
$mobileOtp = $_SESSION['mobileOtp'];

// Verify the OTPs
if ($emailOtpInput == $emailOtp && $mobileOtpInput == $mobileOtp) {
    echo 'OTP Verification Successful!';
} else {
    echo 'OTP Verification Failed! Please try again.';
}
?>

<?php
session_start(); // Start the session

// Store OTP in session or database
$_SESSION['otp'] = $otp;

// Later, validate the OTP
$user_input_otp = $_POST['otp']; // OTP input by the user

if ($user_input_otp == $_SESSION['otp']) {
    echo "OTP Verified";
} else {
    echo "Invalid OTP";
}

