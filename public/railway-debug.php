<?php
/**
 * Railway Debug & Fix Script
 * Access via: https://your-app.up.railway.app/railway-debug.php
 */

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

echo "=== RAILWAY DIAGNOSTIC REPORT ===\n\n";

// 1. PHP Version
echo "PHP Version: " . phpversion() . "\n\n";

// 2. Environment
echo "APP_ENV: " . env('APP_ENV', 'NOT SET') . "\n";
echo "APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "\n";
echo "APP_URL: " . env('APP_URL', 'NOT SET') . "\n\n";

// 3. Storage Permissions
echo "=== STORAGE PERMISSIONS ===\n";
$storagePath = base_path('storage');
if (file_exists($storagePath)) {
    echo "storage/ exists: YES\n";
    echo "storage/ writable: " . (is_writable($storagePath) ? 'YES' : 'NO') . "\n";
    echo "storage/ permissions: " . substr(sprintf('%o', fileperms($storagePath)), -4) . "\n";
} else {
    echo "storage/ exists: NO (CRITICAL ERROR)\n";
}

$publicStorage = storage_path('app/public');
if (file_exists($publicStorage)) {
    echo "storage/app/public exists: YES\n";
    echo "storage/app/public writable: " . (is_writable($publicStorage) ? 'YES' : 'NO') . "\n";
    echo "storage/app/public permissions: " . substr(sprintf('%o', fileperms($publicStorage)), -4) . "\n";
} else {
    echo "storage/app/public exists: NO\n";
}

// Check symlink
$publicLink = public_path('storage');
if (file_exists($publicLink)) {
    echo "public/storage link exists: YES\n";
    echo "public/storage is link: " . (is_link($publicLink) ? 'YES' : 'NO') . "\n";
} else {
    echo "public/storage link exists: NO (Run: php artisan storage:link)\n";
}

echo "\n";

// 4. Database Connection
echo "=== DATABASE CONNECTION ===\n";
try {
    $pdo = DB::connection()->getPdo();
    echo "Database: CONNECTED\n";
    echo "Driver: " . DB::connection()->getDriverName() . "\n";
} catch (\Exception $e) {
    echo "Database: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 5. Mail Configuration
echo "=== MAIL CONFIGURATION ===\n";
echo "MAIL_MAILER: " . env('MAIL_MAILER', 'NOT SET') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST', 'NOT SET') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT', 'NOT SET') . "\n";
echo "MAIL_USERNAME: " . (env('MAIL_USERNAME') ? 'SET' : 'NOT SET') . "\n";
echo "MAIL_PASSWORD: " . (env('MAIL_PASSWORD') ? 'SET (hidden)' : 'NOT SET') . "\n";
echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION', 'NOT SET') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS', 'NOT SET') . "\n";
echo "\n";

// 6. Filesystem Configuration
echo "=== FILESYSTEM CONFIGURATION ===\n";
echo "FILESYSTEM_DISK: " . env('FILESYSTEM_DISK', 'NOT SET') . "\n";
echo "Default Disk: " . config('filesystems.default') . "\n";
echo "\n";

// 7. Cache Status
echo "=== CACHE STATUS ===\n";
$configCached = file_exists(base_path('bootstrap/cache/config.php'));
echo "Config Cached: " . ($configCached ? 'YES' : 'NO') . "\n";
$routesCached = file_exists(base_path('bootstrap/cache/routes-v7.php'));
echo "Routes Cached: " . ($routesCached ? 'YES' : 'NO') . "\n";
echo "\n";

// 8. Required Directories
echo "=== REQUIRED DIRECTORIES ===\n";
$dirs = [
    'storage/app',
    'storage/app/public',
    'storage/app/private',
    'storage/app/livewire-tmp',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
    'public/storage',
];

foreach ($dirs as $dir) {
    $fullPath = base_path($dir);
    $exists = file_exists($fullPath);
    $writable = $exists ? is_writable($fullPath) : false;
    echo "$dir: " . ($exists ? '✓' : '✗') . " | Writable: " . ($writable ? '✓' : '✗') . "\n";
}

echo "\n=== END REPORT ===\n";
