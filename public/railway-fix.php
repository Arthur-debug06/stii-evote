<?php
/**
 * Railway Emergency Fix Script
 * Run this via: https://your-app.up.railway.app/railway-fix.php
 * This will attempt to fix common deployment issues
 */

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');
echo "=== RAILWAY EMERGENCY FIX ===\n\n";

// Change to Laravel root
chdir(__DIR__ . '/..');

// 1. Clear all caches
echo "1. Clearing caches...\n";
try {
    exec('php artisan config:clear 2>&1', $output1);
    echo implode("\n", $output1) . "\n";

    exec('php artisan cache:clear 2>&1', $output2);
    echo implode("\n", $output2) . "\n";

    exec('php artisan view:clear 2>&1', $output3);
    echo implode("\n", $output3) . "\n";

    exec('php artisan route:clear 2>&1', $output4);
    echo implode("\n", $output4) . "\n";

    echo "✓ Caches cleared\n\n";
} catch (Exception $e) {
    echo "✗ Error clearing caches: " . $e->getMessage() . "\n\n";
}

// 2. Create storage directories
echo "2. Creating storage directories...\n";
$dirs = [
    'storage/app/public',
    'storage/app/private',
    'storage/app/livewire-tmp',
    'storage/framework/cache',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
];

$basePath = dirname(__DIR__);
foreach ($dirs as $dir) {
    $fullPath = $basePath . '/' . $dir;
    if (!file_exists($fullPath)) {
        mkdir($fullPath, 0775, true);
        echo "✓ Created: $dir\n";
    } else {
        echo "- Exists: $dir\n";
    }
    @chmod($fullPath, 0775);
}
echo "\n";

// 3. Remove old symlink and create new one
echo "3. Creating storage symlink...\n";
try {
    $publicStorage = $basePath . '/public/storage';

    // Remove existing symlink or directory
    if (file_exists($publicStorage)) {
        if (is_link($publicStorage)) {
            unlink($publicStorage);
            echo "- Removed old symlink\n";
        } elseif (is_dir($publicStorage)) {
            rmdir($publicStorage);
            echo "- Removed old directory\n";
        }
    }

    // Create symlink using PHP
    $target = $basePath . '/storage/app/public';
    if (symlink($target, $publicStorage)) {
        echo "✓ Storage symlink created (PHP method)\n";
    } else {
        // Fallback to artisan command
        exec('php artisan storage:link --force 2>&1', $output5);
        echo implode("\n", $output5) . "\n";
    }
    echo "✓ Storage link configured\n\n";
} catch (Exception $e) {
    echo "✗ Error creating storage link: " . $e->getMessage() . "\n\n";
}

// 4. Set proper permissions
echo "4. Setting permissions...\n";
try {
    $basePath = dirname(__DIR__);
    @chmod($basePath . '/storage', 0775);
    @chmod($basePath . '/bootstrap/cache', 0775);

    // Recursively set permissions
    exec('chmod -R 775 ' . escapeshellarg($basePath . '/storage') . ' 2>&1', $output6);
    exec('chmod -R 775 ' . escapeshellarg($basePath . '/bootstrap/cache') . ' 2>&1', $output7);
    exec('chmod -R 777 ' . escapeshellarg($basePath . '/storage/app/public') . ' 2>&1', $output8);
    echo "✗ Error setting permissions: " . $e->getMessage() . "\n\n";
}

// 5. Cache configs again
echo "5. Rebuilding caches...\n";
try {
    exec('php artisan config:cache 2>&1', $output9);
    echo implode("\n", $output9) . "\n";

    exec('php artisan route:cache 2>&1', $output10);
    echo implode("\n", $output10) . "\n";

    exec('php artisan view:cache 2>&1', $output11);
    echo implode("\n", $output11) . "\n";

    echo "✓ Caches rebuilt\n\n";
} catch (Exception $e) {
    echo "✗ Error rebuilding caches: " . $e->getMessage() . "\n\n";
}

echo "=== FIX COMPLETED ===\n";
echo "Please test your application now.\n\n";
echo "⚠️  IMPORTANT: Set this environment variable on Railway:\n";
echo "FILESYSTEM_DISK=public\n\n";
echo "Then redeploy or restart your Railway app.\n\n";
echo "Check status: https://stii-evote-production.up.railway.app/railway-debug.php\n";
