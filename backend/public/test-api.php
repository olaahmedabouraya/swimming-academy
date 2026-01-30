<?php
/**
 * API Test Script
 * 
 * Access this file via browser: https://swimming-academy.wuaze.com/test-api.php
 * This will test if the API routes are working
 */

// Get Laravel base path
$basePath = dirname(__DIR__);

// Bootstrap Laravel
require_once $basePath . '/vendor/autoload.php';
$app = require_once $basePath . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a test request
$request = Illuminate\Http\Request::create('/api/register', 'POST', [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'role' => 'player'
], [], [], [
    'HTTP_ACCEPT' => 'application/json',
    'CONTENT_TYPE' => 'application/json'
]);

$request->headers->set('Accept', 'application/json');
$request->headers->set('Content-Type', 'application/json');

try {
    $response = $kernel->handle($request);
    $content = $response->getContent();
    
    echo "<h1>API Test Results</h1>";
    echo "<pre>";
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Content Type: " . $response->headers->get('Content-Type') . "\n\n";
    echo "Response:\n";
    echo $content . "\n";
    echo "</pre>";
    
    // Check if response is JSON
    $json = json_decode($content, true);
    if ($json !== null) {
        echo "<p style='color: green;'>✅ Response is valid JSON!</p>";
    } else {
        echo "<p style='color: red;'>❌ Response is NOT JSON (likely HTML)</p>";
        if (strpos($content, '<!doctype') !== false) {
            echo "<p style='color: red;'>⚠️  Response contains HTML (This site requires Javascript message)</p>";
        }
    }
    
    $kernel->terminate($request, $response);
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<pre>";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "</pre>";
}

