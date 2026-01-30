<?php
/**
 * Test MySQL Connection - Find the correct host/socket
 * 
 * Access: https://swimming-academy.wuaze.com/test-mysql-connection.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>MySQL Connection Tester</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

// Load .env
if (file_exists($basePath . '/.env')) {
    $envContent = file_get_contents($basePath . '/.env');
    preg_match('/^DB_HOST\s*=\s*(.+)$/m', $envContent, $matches);
    $dbHost = isset($matches[1]) ? trim($matches[1], " \t\n\r\0\x0B\"'") : 'localhost';
    
    preg_match('/^DB_PORT\s*=\s*(.+)$/m', $envContent, $matches);
    $dbPort = isset($matches[1]) ? trim($matches[1], " \t\n\r\0\x0B\"'") : '3306';
    
    preg_match('/^DB_DATABASE\s*=\s*(.+)$/m', $envContent, $matches);
    $dbDatabase = isset($matches[1]) ? trim($matches[1], " \t\n\r\0\x0B\"'") : '';
    
    preg_match('/^DB_USERNAME\s*=\s*(.+)$/m', $envContent, $matches);
    $dbUsername = isset($matches[1]) ? trim($matches[1], " \t\n\r\0\x0B\"'") : '';
    
    preg_match('/^DB_PASSWORD\s*=\s*(.+)$/m', $envContent, $matches);
    $dbPassword = isset($matches[1]) ? trim($matches[1], " \t\n\r\0\x0B\"'") : '';
    
    echo "Current .env settings:\n";
    echo "DB_HOST: $dbHost\n";
    echo "DB_PORT: $dbPort\n";
    echo "DB_DATABASE: $dbDatabase\n";
    echo "DB_USERNAME: $dbUsername\n";
    echo "\n";
} else {
    die("❌ .env file not found!\n");
}

// Test different connection methods
$testConfigs = [
    ['host' => 'localhost', 'port' => 3306, 'socket' => null],
    ['host' => '127.0.0.1', 'port' => 3306, 'socket' => null],
    ['host' => 'localhost', 'port' => null, 'socket' => '/tmp/mysql.sock'],
    ['host' => 'localhost', 'port' => null, 'socket' => '/var/run/mysqld/mysqld.sock'],
    ['host' => 'localhost', 'port' => null, 'socket' => '/var/lib/mysql/mysql.sock'],
];

// Extract account number from username (if0_41026765 -> 41026765)
$accountNumber = preg_replace('/^if0_/', '', $dbUsername);

// Common InfinityFree MySQL host patterns
$infinityFreeHosts = [
    'sql' . $accountNumber . '.infinityfree.com',
    'mysql' . $accountNumber . '.infinityfree.com',
    'sql' . $accountNumber . '.epizy.com',
    'sql' . $accountNumber . '.ifastnet.com',
];

echo "=== Testing MySQL Connections ===\n\n";

// First, try connecting without a database to test credentials
echo "=== Testing Connection Without Database (to verify credentials) ===\n";
$testHosts = array_merge(['localhost', '127.0.0.1'], $infinityFreeHosts);

foreach ($testHosts as $testHost) {
    echo "Testing host: $testHost (no database)\n";
    try {
        $dsn = "mysql:host=$testHost;port=3306";
        $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);
        
        echo "✅ Connection successful to $testHost!\n";
        echo "   Credentials are correct!\n";
        
        // List available databases
        $stmt = $pdo->query("SHOW DATABASES");
        $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "   Available databases: " . implode(', ', $databases) . "\n";
        
        if (in_array($dbDatabase, $databases)) {
            echo "   ✅ Database '$dbDatabase' exists!\n";
        } else {
            echo "   ⚠️  Database '$dbDatabase' does NOT exist!\n";
            echo "   You may need to create it in InfinityFree Control Panel.\n";
        }
        
        break;
    } catch (PDOException $e) {
        echo "   ❌ Failed: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

echo "\n=== Testing MySQL Connections with Database ===\n\n";

$successful = false;

// Add InfinityFree hosts to test configs
foreach ($infinityFreeHosts as $host) {
    $testConfigs[] = ['host' => $host, 'port' => 3306, 'socket' => null];
}

foreach ($testConfigs as $config) {
    echo "Testing: ";
    if ($config['socket']) {
        echo "Socket: " . $config['socket'];
    } else {
        echo "Host: " . $config['host'] . ":" . ($config['port'] ?? 'default');
    }
    echo "\n";
    
    try {
        $dsn = 'mysql:';
        if ($config['socket']) {
            $dsn .= 'unix_socket=' . $config['socket'];
        } else {
            $dsn .= 'host=' . $config['host'];
            if ($config['port']) {
                $dsn .= ';port=' . $config['port'];
            }
        }
        $dsn .= ';dbname=' . $dbDatabase;
        
        $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);
        
        echo "✅ SUCCESS! Connection works!\n";
        echo "   Use this configuration in your .env:\n";
        if ($config['socket']) {
            echo "   DB_HOST=localhost\n";
            echo "   DB_SOCKET=" . $config['socket'] . "\n";
            echo "   (Remove DB_PORT or set it to empty)\n";
        } else {
            echo "   DB_HOST=" . $config['host'] . "\n";
            if ($config['port']) {
                echo "   DB_PORT=" . $config['port'] . "\n";
            }
        }
        
        // Test query
        $stmt = $pdo->query("SELECT VERSION() as version");
        $version = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "   MySQL Version: " . $version['version'] . "\n";
        
        // Check if database exists
        $stmt = $pdo->query("SELECT DATABASE() as db");
        $currentDb = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "   Current Database: " . $currentDb['db'] . "\n";
        
        $successful = true;
        break;
        
    } catch (PDOException $e) {
        echo "   ❌ Failed: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

if (!$successful) {
    echo "\n❌ None of the standard configurations worked.\n";
    echo "\n=== Next Steps ===\n";
    echo "1. Check InfinityFree Control Panel for MySQL connection details\n";
    echo "2. Look for:\n";
    echo "   - MySQL Host (might be sqlXXX.infinityfree.com)\n";
    echo "   - MySQL Socket path\n";
    echo "   - Connection method (TCP/IP vs Socket)\n";
    echo "3. Common InfinityFree MySQL hosts:\n";
    echo "   - sqlXXX.infinityfree.com (where XXX is your account number)\n";
    echo "   - Check your InfinityFree control panel\n";
}

echo "</pre>";

