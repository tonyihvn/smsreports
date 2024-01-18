<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$info_bipapiKey = $_ENV['info_bipapiKey'];
$infobipauth = $_ENV['infobipauth'];

$apiEndpoint = "https://dddd8.api.infobip.com/mi/verification/1/verify";

// Your Infobip API Key
$apiKey =$info_bipapiKey;
$auth =$infobipauth;
// Phone number to validate
$phoneNumber = "2347067973091";

// Construct the request parameters
$requestData = [
    'phoneNumber' => $phoneNumber,
    'consentGranted'=>true,
    'callbackUrl'=>'https://api.infobip.com/my-site/my-shop.html'
];

// Initialize cURL session
$curl = curl_init();

// Set cURL options
curl_setopt_array($curl, [
    CURLOPT_URL => $apiEndpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($requestData),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Basic '.base64_encode($auth)
    ]
]);

// Execute the request
$response = curl_exec($curl);

// Check response and handle accordingly
if ($response) {
    $responseData = json_decode($response, true);
    // Process $responseData to check the validity of the phone number
    // Handle validation status or other information provided by the API
    var_dump($responseData); // Display the API response
} else {
    // Handle API request failure
    echo 'Error: ' . curl_error($curl);
}

// Close cURL session
curl_close($curl);
?>