<?php
/**
 * Show Actual Error - This will display the real error causing 500
 * 
 * Access: https://swimming-academy.wuaze.com/show-error.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

echo "<h1>Error Display</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

// Enable error display
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "ERROR: [$errno] $errstr in $errfile on line $errline\n";
    return true;
});

set_exception_handler(function($exception) {
    echo "EXCEPTION: " . $exception->getMessage() . "\n";
    echo "File: " . $exception->getFile() . "\n";
    echo "Line: " . $exception->getLine() . "\n";
    echo "Trace:\n" . $exception->getTraceAsString() . "\n";
});

try {
    // Check if routes file has the fix
    echo "=== Checking routes/api.php ===\n";
    $routesFile = $basePath . '/routes/api.php';
    if (file_exists($routesFile)) {
        $routesContent = file_get_contents($routesFile);
        if (strpos($routesContent, 'use App\Http\Controllers\SettingController;') !== false) {
            echo "✅ SettingController import found\n";
        } else {
            echo "❌ SettingController import MISSING - This is the problem!\n";
            echo "The routes/api.php file needs to be updated.\n";
        }
    } else {
        echo "❌ routes/api.php file not found!\n";
    }
    
    echo "\n=== Attempting to load Laravel ===\n";
    
    if (!file_exists($basePath . '/vendor/autoload.php')) {
        die("❌ vendor/autoload.php not found. You must upload the vendor/ folder!\n");
    }
    
    require_once $basePath . '/vendor/autoload.php';
    echo "✅ Autoloader loaded\n";
    
    // Load environment
    if (file_exists($basePath . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable($basePath);
        $dotenv->load();
        echo "✅ .env loaded\n";
    } else {
        echo "⚠️  .env file not found\n";
    }
    
    // Try to bootstrap
    echo "Attempting to bootstrap Laravel...\n";
    $app = require_once $basePath . '/bootstrap/app.php';
    echo "✅ Laravel app created\n";
    
    // Try to make kernel
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Kernel created\n";
    
    // Create request
    $request = Illuminate\Http\Request::create('/api/register', 'POST', [
        'name' => 'Test User',
        'email' => 'test' . time() . '@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'player'
    ], [], [], [
        'HTTP_ACCEPT' => 'application/json',
        'CONTENT_TYPE' => 'application/json'
    ]);
    
    $request->headers->set('Accept', 'application/json');
    $request->headers->set('Content-Type', 'application/json');
    
    echo "✅ Request created\n";
    echo "Attempting to handle request...\n";
    
    // This is where the error will show
    $response = $kernel->handle($request);
    
    echo "✅ Request handled successfully!\n";
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Content-Type: " . $response->headers->get('Content-Type') . "\n";
    echo "Response preview: " . substr($response->getContent(), 0, 500) . "\n";
    
} catch (Error $e) {
    echo "\n❌ FATAL ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
} catch (Exception $e) {
    echo "\n❌ EXCEPTION:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
} catch (Throwable $e) {
    echo "\n❌ THROWABLE:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "</pre>";

