<?php
/**
 * Run Database Migrations
 * 
 * Access: https://swimming-academy.wuaze.com/run-migrations.php
 * 
 * ⚠️  SECURITY: Delete this file after migrations are complete!
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
        exit;
    }
    
    echo "\n=== Setting up migrations table ===\n";
    $repository = $app->make('migration.repository');
    if (!$repository->repositoryExists()) {
        echo "Creating migrations table...\n";
        $repository->createRepository();
        echo "✅ Migrations table created\n";
    } else {
        echo "✅ Migrations table exists\n";
    }
    
    echo "\n=== Running Migrations ===\n";
    $migrator = $app->make('migrator');
    $migrator->run([$basePath . '/database/migrations']);
    
    echo "✅ Migrations completed successfully!\n";
    
    echo "\n=== Migration Status ===\n";
    $migrations = $migrator->getRepository()->getRan();
    echo "Total migrations run: " . count($migrations) . "\n";
    foreach ($migrations as $migration) {
        echo "  ✅ " . $migration . "\n";
    }
    
    // Check if personal_access_tokens table exists
    echo "\n=== Verifying Critical Tables ===\n";
    $criticalTables = ['users', 'personal_access_tokens', 'sessions'];
    foreach ($criticalTables as $table) {
        if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
            echo "  ✅ Table '$table' exists\n";
        } else {
            echo "  ❌ Table '$table' is missing!\n";
        }
    }
    
    echo "\n⚠️  IMPORTANT: Delete this file (run-migrations.php) for security!\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    if (strpos($e->getMessage(), 'key was too long') !== false) {
        echo "\n💡 Tip: This error means a migration needs string length fix.\n";
        echo "   Check AppServiceProvider has Schema::defaultStringLength(191);\n";
    }
}

echo "</pre>";

