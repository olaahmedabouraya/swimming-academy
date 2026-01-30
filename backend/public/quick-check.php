<?php
/**
 * Quick Check - Simple diagnostic without Laravel bootstrap
 * 
 * Access: https://swimming-academy.wuaze.com/quick-check.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Quick Diagnostic Check</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

// Check 1: Routes file
echo "=== 1. Checking routes/api.php ===\n";
$routesFile = $basePath . '/routes/api.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    if (strpos($content, 'SettingController') !== false) {
        if (strpos($content, 'use App\Http\Controllers\SettingController;') !== false) {
            echo "✅ SettingController is properly imported\n";
        } else {
            echo "❌ SettingController is used but NOT imported!\n";
            echo "   You need to add: use App\Http\Controllers\SettingController;\n";
        }
    } else {
        echo "⚠️  SettingController not found in routes (might be OK if routes changed)\n";
    }
} else {
    echo "❌ routes/api.php NOT FOUND!\n";
}

// Check 2: Vendor folder
echo "\n=== 2. Checking vendor folder ===\n";
if (file_exists($basePath . '/vendor')) {
    echo "✅ vendor/ folder exists\n";
    if (file_exists($basePath . '/vendor/autoload.php')) {
        echo "✅ vendor/autoload.php exists\n";
    } else {
        echo "❌ vendor/autoload.php MISSING - Critical!\n";
    }
} else {
    echo "❌ vendor/ folder NOT FOUND - This will cause 500 errors!\n";
    echo "   You MUST upload the vendor/ folder from your local backend directory.\n";
}

// Check 3: .env file
echo "\n=== 3. Checking .env file ===\n";
if (file_exists($basePath . '/.env')) {
    echo "✅ .env file exists\n";
    $env = file_get_contents($basePath . '/.env');
    if (strpos($env, 'APP_KEY=') !== false && strpos($env, 'base64:') !== false) {
        echo "✅ APP_KEY is set\n";
    } else {
        echo "⚠️  APP_KEY might not be set properly\n";
    }
} else {
    echo "❌ .env file NOT FOUND - This will cause errors!\n";
}

// Check 4: Storage permissions
echo "\n=== 4. Checking storage permissions ===\n";
$storagePath = $basePath . '/storage';
if (is_dir($storagePath)) {
    echo "✅ storage/ directory exists\n";
    if (is_writable($storagePath)) {
        echo "✅ storage/ is writable\n";
    } else {
        echo "⚠️  storage/ is NOT writable (set permissions to 755)\n";
    }
} else {
    echo "❌ storage/ directory NOT FOUND\n";
}

// Check 5: Route cache
echo "\n=== 5. Checking route cache ===\n";
$cachePath = $basePath . '/bootstrap/cache';
if (is_dir($cachePath)) {
    $cacheFiles = glob($cachePath . '/routes-*.php');
    if (count($cacheFiles) > 0) {
        echo "⚠️  Route cache files found:\n";
        foreach ($cacheFiles as $file) {
            echo "   - " . basename($file) . "\n";
            echo "     DELETE THIS FILE to clear cache!\n";
        }
    } else {
        echo "✅ No route cache files found\n";
    }
} else {
    echo "⚠️  bootstrap/cache directory not found\n";
}

// Check 6: Check if SettingController exists
echo "\n=== 6. Checking SettingController file ===\n";
$controllerFile = $basePath . '/app/Http/Controllers/SettingController.php';
if (file_exists($controllerFile)) {
    echo "✅ SettingController.php exists\n";
} else {
    echo "❌ SettingController.php NOT FOUND!\n";
    echo "   This will cause a 500 error if routes try to use it.\n";
}

// Check 7: Check Handler.php
echo "\n=== 7. Checking Exception Handler ===\n";
$handlerFile = $basePath . '/app/Exceptions/Handler.php';
if (file_exists($handlerFile)) {
    $handlerContent = file_get_contents($handlerFile);
    if (strpos($handlerContent, 'public function render') !== false) {
        echo "✅ Handler.php has render() method\n";
    } else {
        echo "⚠️  Handler.php might be missing render() method\n";
    }
} else {
    echo "❌ Handler.php NOT FOUND!\n";
}

// Summary
echo "\n=== SUMMARY ===\n";
$issues = [];
if (!file_exists($basePath . '/vendor/autoload.php')) {
    $issues[] = "Missing vendor/autoload.php - Upload vendor folder";
}
if (!file_exists($basePath . '/.env')) {
    $issues[] = "Missing .env file";
}
if (file_exists($routesFile)) {
    $routesContent = file_get_contents($routesFile);
    if (strpos($routesContent, 'SettingController') !== false && 
        strpos($routesContent, 'use App\Http\Controllers\SettingController;') === false) {
        $issues[] = "routes/api.php missing SettingController import";
    }
}
if (!file_exists($controllerFile)) {
    $issues[] = "Missing SettingController.php file";
}

if (count($issues) > 0) {
    echo "❌ Issues found:\n";
    foreach ($issues as $issue) {
        echo "   - $issue\n";
    }
} else {
    echo "✅ No obvious issues found\n";
    echo "   Run show-error.php to see the actual error\n";
}

echo "</pre>";

