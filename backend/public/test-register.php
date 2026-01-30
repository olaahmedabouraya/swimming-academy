<?php
/**
 * Test Registration Endpoint
 * 
 * Access: https://swimming-academy.wuaze.com/test-register.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Registration Test</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

try {
    require_once $basePath . '/vendor/autoload.php';
    
    if (file_exists($basePath . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable($basePath);
        $dotenv->load();
    }
    
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    // Test data
    $testData = [
        'name' => 'Test User ' . time(),
        'email' => 'test' . time() . '@example.com',
        'password' => '12345678',
        'password_confirmation' => '12345678',
        'role' => 'manager',
        'phone' => ''
    ];
    
    echo "=== Testing Registration ===\n";
    echo "Test Data:\n";
    print_r($testData);
    echo "\n";
    
    // Create request
    $request = Illuminate\Http\Request::create('/api/register', 'POST', $testData, [], [], [
        'HTTP_ACCEPT' => 'application/json',
        'CONTENT_TYPE' => 'application/json'
    ]);
    
    $request->headers->set('Accept', 'application/json');
    $request->headers->set('Content-Type', 'application/json');
    
    echo "Making request...\n";
    $response = $kernel->handle($request);
    
    echo "\n=== Response ===\n";
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Content-Type: " . $response->headers->get('Content-Type') . "\n";
    echo "Response:\n";
    $content = $response->getContent();
    echo $content . "\n";
    
    $json = json_decode($content, true);
    if ($json) {
        echo "\n=== Parsed JSON ===\n";
        print_r($json);
        
        if (isset($json['errors'])) {
            echo "\n❌ Validation Errors:\n";
            print_r($json['errors']);
        } elseif (isset($json['user'])) {
            echo "\n✅ Registration Successful!\n";
            echo "User ID: " . ($json['user']['id'] ?? 'N/A') . "\n";
            echo "Email: " . ($json['user']['email'] ?? 'N/A') . "\n";
        } elseif (isset($json['message'])) {
            echo "\n⚠️  Message: " . $json['message'] . "\n";
        }
    }
    
    // Check database connection
    echo "\n=== Database Check ===\n";
    try {
        $db = \Illuminate\Support\Facades\DB::connection();
        $db->getPdo();
        echo "✅ Database connection successful\n";
        
        // Check if users table exists
        $tables = \Illuminate\Support\Facades\DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = current_schema()");
        $tableNames = array_map(function($table) {
            return $table->table_name;
        }, $tables);
        
        if (in_array('users', $tableNames)) {
            echo "✅ 'users' table exists\n";
            
            // Check table structure
            $columns = \Illuminate\Support\Facades\DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'users'");
            echo "Users table columns:\n";
            foreach ($columns as $column) {
                echo "  - " . $column->column_name . " (" . $column->data_type . ")\n";
            }
        } else {
            echo "❌ 'users' table NOT FOUND!\n";
            echo "Available tables: " . implode(', ', $tableNames) . "\n";
            echo "⚠️  You need to run migrations!\n";
        }
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";

