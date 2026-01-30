<?php
/**
 * Direct API Test - Tests the actual API endpoint
 * 
 * Access: https://swimming-academy.wuaze.com/test-api-direct.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Direct API Test</h1>";
echo "<pre>";

// Test the actual API endpoint
$url = 'https://swimming-academy.wuaze.com/api/register';

$data = [
    'name' => 'Test User',
    'email' => 'test' . time() . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'role' => 'player'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$error = curl_error($ch);
curl_close($ch);

echo "URL: $url\n";
echo "HTTP Code: $httpCode\n";
echo "Content-Type: $contentType\n\n";

if ($error) {
    echo "CURL Error: $error\n\n";
}

echo "Response:\n";
echo $response . "\n\n";

// Check if response is JSON
$json = json_decode($response, true);
if ($json !== null) {
    echo "✅ Response is valid JSON!\n";
    echo "Response structure:\n";
    print_r($json);
} else {
    echo "❌ Response is NOT JSON\n";
    if (strpos($response, '<!doctype') !== false || strpos($response, '<html') !== false) {
        echo "⚠️  Response is HTML (This site requires Javascript message likely)\n";
        echo "This means the backend is returning HTML instead of JSON.\n";
    }
}

echo "</pre>";

