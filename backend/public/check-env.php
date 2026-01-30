<?php
/**
 * Check Environment Variables
 * 
 * Access: https://swimming-academy.wuaze.com/check-env.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Environment Check</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

// Check .env file directly
echo "=== Reading .env file directly ===\n";
$envFile = $basePath . '/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    // Extract SESSION_DRIVER
    if (preg_match('/^SESSION_DRIVER\s*=\s*(.+)$/m', $envContent, $matches)) {
        $sessionDriver = trim($matches[1]);
        echo "SESSION_DRIVER from .env: " . var_export($sessionDriver, true) . "\n";
        if ($sessionDriver === 'file' || $sessionDriver === '"file"') {
            echo "✅ SESSION_DRIVER is set to 'file'\n";
        } else {
            echo "❌ SESSION_DRIVER is NOT 'file' - it's: " . var_export($sessionDriver, true) . "\n";
        }
    } else {
        echo "⚠️  SESSION_DRIVER not found in .env file\n";
    }
    
    // Extract DB_CONNECTION
    if (preg_match('/^DB_CONNECTION\s*=\s*(.+)$/m', $envContent, $matches)) {
        $dbConnection = trim($matches[1]);
        echo "DB_CONNECTION from .env: " . var_export($dbConnection, true) . "\n";
    } else {
        echo "⚠️  DB_CONNECTION not found in .env file\n";
    }
} else {
    echo "❌ .env file not found!\n";
}

// Check config cache
echo "\n=== Checking config cache ===\n";
$configCache = $basePath . '/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    echo "⚠️  Config cache file exists: bootstrap/cache/config.php\n";
    echo "   This might have old values! DELETE THIS FILE.\n";
    
    // Try to read it
    $cached = include $configCache;
    if (isset($cached['session']['driver'])) {
        echo "   Cached SESSION_DRIVER: " . var_export($cached['session']['driver'], true) . "\n";
    }
    if (isset($cached['database']['default'])) {
        echo "   Cached DB_CONNECTION: " . var_export($cached['database']['default'], true) . "\n";
    }
} else {
    echo "✅ No config cache file found\n";
}

// Try loading Laravel and check actual config
echo "\n=== Loading Laravel and checking actual config ===\n";
try {
    require_once $basePath . '/vendor/autoload.php';
    
    if (file_exists($envFile)) {
        $dotenv = Dotenv\Dotenv::createImmutable($basePath);
        $dotenv->load();
    }
    
    $app = require_once $basePath . '/bootstrap/app.php';
    
    // Check actual config values
    $sessionDriver = config('session.driver');
    $dbConnection = config('database.default');
    
    echo "Actual SESSION_DRIVER from config(): " . var_export($sessionDriver, true) . "\n";
    echo "Actual DB_CONNECTION from config(): " . var_export($dbConnection, true) . "\n";
    
    if ($sessionDriver === 'file') {
        echo "✅ Config shows SESSION_DRIVER is 'file'\n";
    } else {
        echo "❌ Config shows SESSION_DRIVER is NOT 'file'!\n";
    }
    
    // Check if pgsql connection exists
    echo "\n=== Checking database connections ===\n";
    $connections = config('database.connections');
    if (isset($connections['pgsql'])) {
        echo "⚠️  PostgreSQL connection is configured\n";
        $pgsqlConfig = $connections['pgsql'];
        if (empty($pgsqlConfig['host']) || empty($pgsqlConfig['database'])) {
            echo "❌ But PostgreSQL config is incomplete!\n";
            echo "   Host: " . ($pgsqlConfig['host'] ?? 'NOT SET') . "\n";
            echo "   Database: " . ($pgsqlConfig['database'] ?? 'NOT SET') . "\n";
        }
    } else {
        echo "✅ PostgreSQL connection is NOT configured (this is OK if using MySQL)\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error loading Laravel: " . $e->getMessage() . "\n";
}

echo "\n=== Recommendations ===\n";
if (file_exists($configCache)) {
    echo "1. DELETE bootstrap/cache/config.php to clear cached config\n";
}
echo "2. Make sure SESSION_DRIVER=file in .env (no quotes, no spaces)\n";
echo "3. If using PostgreSQL, make sure it's properly configured in database.php\n";
echo "4. If NOT using PostgreSQL, set DB_CONNECTION=mysql in .env\n";

echo "</pre>";

