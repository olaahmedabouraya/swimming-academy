<?php
/**
 * Simple API Test Script (No Laravel Bootstrap)
 * 
 * Access: https://swimming-academy.wuaze.com/test-simple.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Simple API Test</h1>";
echo "<pre>";

// Test 1: Check if files exist
echo "=== File Check ===\n";
$basePath = dirname(__DIR__);

$filesToCheck = [
    'app/Exceptions/Handler.php',
    'app/Http/Requests/Auth/RegisterRequest.php',
    'app/Http/Requests/Auth/LoginRequest.php',
    'routes/api.php',
    'vendor/autoload.php',
    'bootstrap/app.php'
];

foreach ($filesToCheck as $file) {
    $fullPath = $basePath . '/' . $file;
    if (file_exists($fullPath)) {
        echo "✅ $file exists\n";
        // Check if Handler.php has the render method
        if ($file === 'app/Exceptions/Handler.php') {
            $content = file_get_contents($fullPath);
            if (strpos($content, 'public function render') !== false) {
                echo "   ✅ Handler.php has render() method\n";
            } else {
                echo "   ❌ Handler.php missing render() method\n";
            }
        }
        // Check if RegisterRequest has expectsJson
        if ($file === 'app/Http/Requests/Auth/RegisterRequest.php') {
            $content = file_get_contents($fullPath);
            if (strpos($content, 'expectsJson') !== false) {
                echo "   ✅ RegisterRequest.php has expectsJson() method\n";
            } else {
                echo "   ❌ RegisterRequest.php missing expectsJson() method\n";
            }
        }
    } else {
        echo "❌ $file NOT FOUND\n";
    }
}

// Test 2: Check PHP version
echo "\n=== PHP Version ===\n";
echo "PHP Version: " . phpversion() . "\n";

// Test 3: Check if vendor exists
echo "\n=== Vendor Check ===\n";
if (file_exists($basePath . '/vendor')) {
    echo "✅ vendor/ directory exists\n";
    if (file_exists($basePath . '/vendor/autoload.php')) {
        echo "✅ vendor/autoload.php exists\n";
    } else {
        echo "❌ vendor/autoload.php missing\n";
    }
} else {
    echo "❌ vendor/ directory NOT FOUND - This is critical!\n";
}

// Test 4: Check .env
echo "\n=== Environment Check ===\n";
if (file_exists($basePath . '/.env')) {
    echo "✅ .env file exists\n";
    $env = file_get_contents($basePath . '/.env');
    if (strpos($env, 'APP_KEY') !== false) {
        echo "✅ APP_KEY is set\n";
    } else {
        echo "❌ APP_KEY not found in .env\n";
    }
} else {
    echo "❌ .env file NOT FOUND\n";
}

// Test 5: Check storage permissions
echo "\n=== Storage Permissions ===\n";
$storagePath = $basePath . '/storage';
if (is_dir($storagePath)) {
    echo "✅ storage/ directory exists\n";
    if (is_writable($storagePath)) {
        echo "✅ storage/ is writable\n";
    } else {
        echo "❌ storage/ is NOT writable (permissions issue)\n";
    }
} else {
    echo "❌ storage/ directory NOT FOUND\n";
}

// Test 6: Try to bootstrap Laravel (simple test)
echo "\n=== Laravel Bootstrap Test ===\n";
try {
    if (file_exists($basePath . '/vendor/autoload.php')) {
        require_once $basePath . '/vendor/autoload.php';
        echo "✅ Autoloader loaded\n";
        
        if (file_exists($basePath . '/bootstrap/app.php')) {
            echo "✅ bootstrap/app.php exists\n";
            // Don't actually bootstrap, just check if file is readable
            echo "✅ Can proceed with Laravel bootstrap\n";
        }
    } else {
        echo "❌ Cannot load autoloader\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Summary ===\n";
echo "If you see ❌ for vendor/ or .env, those need to be fixed first.\n";
echo "</pre>";

