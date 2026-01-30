<?php
/**
 * Check Database Configuration and Drivers
 * 
 * Access: https://swimming-academy.wuaze.com/check-database.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Configuration Check</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

// Check PHP PDO drivers
echo "=== PHP PDO Drivers Available ===\n";
$pdoDrivers = PDO::getAvailableDrivers();
echo "Available drivers: " . implode(', ', $pdoDrivers) . "\n";

if (in_array('pgsql', $pdoDrivers)) {
    echo "✅ PostgreSQL (pgsql) driver is available\n";
} else {
    echo "❌ PostgreSQL (pgsql) driver is NOT available\n";
    echo "   InfinityFree doesn't support PostgreSQL!\n";
    echo "   You need to use MySQL instead.\n";
}

if (in_array('mysql', $pdoDrivers)) {
    echo "✅ MySQL driver is available\n";
} else {
    echo "❌ MySQL driver is NOT available\n";
}

// Check .env file
echo "\n=== Database Configuration in .env ===\n";
$envFile = $basePath . '/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    if (preg_match('/^DB_CONNECTION\s*=\s*(.+)$/m', $envContent, $matches)) {
        $dbConnection = trim($matches[1], " \t\n\r\0\x0B\"'");
        echo "DB_CONNECTION: " . var_export($dbConnection, true) . "\n";
        
        if ($dbConnection === 'pgsql' && !in_array('pgsql', $pdoDrivers)) {
            echo "❌ PROBLEM: You're trying to use PostgreSQL but it's not available!\n";
            echo "   Solution: Change DB_CONNECTION=mysql in .env\n";
        }
    }
    
    if (preg_match('/^DB_HOST\s*=\s*(.+)$/m', $envContent, $matches)) {
        $dbHost = trim($matches[1], " \t\n\r\0\x0B\"'");
        echo "DB_HOST: " . var_export($dbHost, true) . "\n";
    }
    
    if (preg_match('/^DB_DATABASE\s*=\s*(.+)$/m', $envContent, $matches)) {
        $dbDatabase = trim($matches[1], " \t\n\r\0\x0B\"'");
        echo "DB_DATABASE: " . var_export($dbDatabase, true) . "\n";
    }
}

// Try to connect
echo "\n=== Testing Database Connection ===\n";
try {
    require_once $basePath . '/vendor/autoload.php';
    
    if (file_exists($envFile)) {
        $dotenv = Dotenv\Dotenv::createImmutable($basePath);
        $dotenv->load();
    }
    
    $app = require_once $basePath . '/bootstrap/app.php';
    
    $dbConnection = config('database.default');
    echo "Default connection: " . $dbConnection . "\n";
    
    if ($dbConnection === 'pgsql' && !in_array('pgsql', $pdoDrivers)) {
        echo "❌ Cannot use PostgreSQL - driver not available!\n";
        echo "   Change DB_CONNECTION=mysql in .env\n";
    } else {
        try {
            $db = \Illuminate\Support\Facades\DB::connection();
            $db->getPdo();
            echo "✅ Database connection successful!\n";
        } catch (Exception $e) {
            echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Recommendations ===\n";
if (!in_array('pgsql', $pdoDrivers)) {
    echo "1. Change DB_CONNECTION=mysql in your .env file\n";
    echo "2. Use InfinityFree's MySQL database (or external MySQL)\n";
    echo "3. Update DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD for MySQL\n";
}

echo "</pre>";

