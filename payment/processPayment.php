<?php

require('vendor/autoload.php');
use Razorpay\Api\Api;

// Set your Razorpay API key and secret key
$keyId = 'rzp_test_m5XZYW8t6K0K99';
$keySecret = 'I3TNUf0B7l5duuujZk17hF6z';

// Initialize the Razorpay API
$api = new Api($keyId, $keySecret);

if (!empty($_POST['razorpay_payment_id'])) {
    try {
        // Fetch the payment object
        $payment = $api->payment->fetch($_POST['razorpay_payment_id']);

        if ($payment->status == 'captured') {
            // Payment was successful
            echo "Payment successful!";
            // You can store payment details in your database here
        } else {
            // Payment failed
            echo "Payment failed. Please try again.";
        }
    } catch (Exception $e) {
        echo 'Razorpay Error: ' . $e->getMessage();
    }
} else {
    echo "Payment ID not found!";
}
?>
