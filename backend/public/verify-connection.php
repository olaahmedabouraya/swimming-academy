<?php
/**
 * Verify Database Connection
 * 
 * Access: https://swimming-academy.wuaze.com/verify-connection.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Connection Verification</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

try {
    require_once $basePath . '/vendor/autoload.php';
    
    if (file_exists($basePath . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable($basePath);
        $dotenv->load();
    }
    
    $app = require_once $basePath . '/bootstrap/app.php';
    
    // Bootstrap the application
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "=== Testing Database Connection ===\n";
    try {
        $db = \Illuminate\Support\Facades\DB::connection();
        $pdo = $db->getPdo();
        echo "✅ Database connection successful!\n\n";
        
        // Get database info
        $stmt = $pdo->query("SELECT DATABASE() as db, VERSION() as version");
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Connected to database: " . $info['db'] . "\n";
        echo "MySQL Version: " . $info['version'] . "\n\n";
        
        // Check existing tables
        echo "=== Existing Tables ===\n";
        $tables = \Illuminate\Support\Facades\DB::select("SHOW TABLES");
        if (count($tables) > 0) {
            $tableKey = 'Tables_in_' . str_replace('-', '_', $info['db']);
            foreach ($tables as $table) {
                echo "  ✅ " . $table->$tableKey . "\n";
            }
        } else {
            echo "  ⚠️  No tables found. You need to run migrations.\n";
        }
        
        echo "\n✅ Database is ready! You can now run migrations.\n";
        
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        echo "\nPlease check:\n";
        echo "1. DB_HOST is correct: sql200.infinityfree.com\n";
        echo "2. DB_DATABASE is correct: if0_41026765_swimming_academy\n";
        echo "3. DB_USERNAME is correct: if0_41026765\n";
        echo "4. DB_PASSWORD is correct\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "</pre>";

