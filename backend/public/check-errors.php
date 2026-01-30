<?php
/**
 * Error Check Script
 * 
 * Access: https://swimming-academy.wuaze.com/check-errors.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Error Diagnostics</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

// Check error logs
echo "=== Checking Error Logs ===\n";
$logPath = $basePath . '/storage/logs/laravel.log';
if (file_exists($logPath)) {
    echo "✅ Log file exists: $logPath\n";
    $logSize = filesize($logPath);
    echo "Log file size: " . number_format($logSize) . " bytes\n";
    
    if ($logSize > 0) {
        echo "\n=== Last 50 lines of log ===\n";
        $lines = file($logPath);
        $lastLines = array_slice($lines, -50);
        echo implode('', $lastLines);
    } else {
        echo "Log file is empty\n";
    }
} else {
    echo "❌ Log file not found: $logPath\n";
    echo "Checking if storage/logs exists...\n";
    if (is_dir($basePath . '/storage/logs')) {
        echo "✅ storage/logs directory exists\n";
    } else {
        echo "❌ storage/logs directory NOT FOUND\n";
    }
}

// Check PHP error log location
echo "\n=== PHP Error Log Location ===\n";
$phpErrorLog = ini_get('error_log');
if ($phpErrorLog) {
    echo "PHP error_log setting: $phpErrorLog\n";
    if (file_exists($phpErrorLog)) {
        echo "✅ PHP error log exists\n";
        $phpLogSize = filesize($phpErrorLog);
        echo "PHP log size: " . number_format($phpLogSize) . " bytes\n";
        if ($phpLogSize > 0) {
            echo "\n=== Last 20 lines of PHP error log ===\n";
            $phpLines = file($phpErrorLog);
            $lastPhpLines = array_slice($phpLines, -20);
            echo implode('', $lastPhpLines);
        }
    } else {
        echo "⚠️  PHP error log file not found at that location\n";
    }
} else {
    echo "⚠️  PHP error_log not configured\n";
}

// Try to see what error happens when accessing API
echo "\n=== Testing API Endpoint ===\n";
try {
    if (file_exists($basePath . '/vendor/autoload.php')) {
        require_once $basePath . '/vendor/autoload.php';
        
        // Set up environment
        if (file_exists($basePath . '/.env')) {
            $dotenv = Dotenv\Dotenv::createImmutable($basePath);
            $dotenv->load();
            echo "✅ .env loaded\n";
        }
        
        // Try to create app
        if (file_exists($basePath . '/bootstrap/app.php')) {
            $app = require_once $basePath . '/bootstrap/app.php';
            echo "✅ Laravel app bootstrapped\n";
            
            // Try to make a request
            $request = Illuminate\Http\Request::create('/api/register', 'POST', [
                'name' => 'Test',
                'email' => 'test@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'player'
            ], [], [], [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json'
            ]);
            
            $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
            $response = $kernel->handle($request);
            
            echo "Response status: " . $response->getStatusCode() . "\n";
            echo "Response content type: " . $response->headers->get('Content-Type') . "\n";
            echo "Response preview: " . substr($response->getContent(), 0, 200) . "...\n";
        }
    }
} catch (Throwable $e) {
    echo "❌ Error occurred:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";

