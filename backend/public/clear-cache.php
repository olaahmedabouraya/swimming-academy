<?php
/**
 * Cache Clearing Script for InfinityFree
 * 
 * Access this file via browser: https://swimming-academy.wuaze.com/clear-cache.php
 * DELETE THIS FILE AFTER USE FOR SECURITY!
 */

// Security: Only allow from specific IP or remove this check if needed
// Uncomment and set your IP for security:
// $allowedIP = 'YOUR_IP_HERE';
// if (isset($allowedIP) && $_SERVER['REMOTE_ADDR'] !== $allowedIP) {
//     die('Access denied');
// }

// Get Laravel base path
$basePath = dirname(__DIR__);

// Change to Laravel root
chdir($basePath);

echo "<h1>Clearing Laravel Cache</h1>";
echo "<pre>";

// Clear config cache
if (file_exists($basePath . '/bootstrap/cache/config.php')) {
    unlink($basePath . '/bootstrap/cache/config.php');
    echo "✅ Config cache cleared\n";
} else {
    echo "ℹ️  Config cache file not found (may already be cleared)\n";
}

// Clear route cache
if (file_exists($basePath . '/bootstrap/cache/routes-v7.php')) {
    unlink($basePath . '/bootstrap/cache/routes-v7.php');
    echo "✅ Route cache cleared\n";
} else {
    echo "ℹ️  Route cache file not found (may already be cleared)\n";
}

// Clear view cache
$viewCachePath = $basePath . '/storage/framework/views';
if (is_dir($viewCachePath)) {
    $files = glob($viewCachePath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✅ View cache cleared\n";
} else {
    echo "ℹ️  View cache directory not found\n";
}

// Clear application cache
$appCachePath = $basePath . '/storage/framework/cache/data';
if (is_dir($appCachePath)) {
    $files = glob($appCachePath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✅ Application cache cleared\n";
} else {
    echo "ℹ️  Application cache directory not found\n";
}

echo "\n✅ Cache clearing complete!\n";
echo "\n⚠️  IMPORTANT: Delete this file (clear-cache.php) for security!\n";
echo "</pre>";

