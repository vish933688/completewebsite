<?php
require __DIR__ . '/vendor/autoload.php'; // Load the Twilio SDK

use Twilio\Rest\Client;

// Twilio credentials
$account_sid = 'ACbed2bb98db1a39fc89e1f2efa04fddf0';
$auth_token = '501c8f634a42190ceecff97a043a92e4';
$twilio_number = '+919336882409'; // Your Twilio phone number

// User's mobile number and OTP
$recipient_number = '+0987654321'; // Recipient's phone number
$otp = rand(100000, 999999); // Generate a 6-digit OTP

// Create Twilio client
$client = new Client($account_sid, $auth_token);

// Send OTP via SMS
$message = $client->messages->create(
    $recipient_number, // Send SMS to this number
    [
        'from' => $twilio_number, // Twilio number
        'body' => "Your OTP is $otp"
    ]
);

echo "Message sent with SID: " . $message->sid;
