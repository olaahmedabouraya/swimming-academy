<?php
/**
 * Run Database Migrations
 * 
 * Access: https://swimming-academy.wuaze.com/run-migrations.php
 * 
 * WARNING: Only run this once! Delete this file after migrations are complete.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Run Database Migrations</h1>";
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
    
    echo "=== Checking Database Connection ===\n";
    try {
        $db = \Illuminate\Support\Facades\DB::connection();
        $db->getPdo();
        echo "✅ Database connection successful\n";
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        echo "   Please fix your database configuration in .env first!\n";
        echo "\n   Common issues:\n";
        echo "   - DB_PORT should be 3306 for MySQL (not 5432)\n";
        echo "   - DB_HOST should be 'localhost' for InfinityFree\n";
        echo "   - Check database credentials are correct\n";
        exit;
    }
    
    echo "\n=== Running Migrations ===\n";
    $migrator = $app->make('migrator');
    $migrator->run([$basePath . '/database/migrations']);
    
    echo "✅ Migrations completed successfully!\n";
    
    echo "\n=== Migration Status ===\n";
    $migrations = $migrator->getRepository()->getRan();
    echo "Migrations run: " . count($migrations) . "\n";
    foreach ($migrations as $migration) {
        echo "  ✅ " . $migration . "\n";
    }
    
    echo "\n⚠️  IMPORTANT: Delete this file (run-migrations.php) for security!\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";

