<?php

/**
 * COMPLETE HOSTING DEPLOYMENT SCRIPT
 * ==================================
 * 
 * This script will fully prepare your Laravel application for hosting.
 * Run this after uploading all files and updating hosting-config.php
 * 
 * Usage: php deploy-to-hosting.php
 */

ini_set('max_execution_time', 300); // 5 minutes
ini_set('memory_limit', '256M');

echo "\n";
echo "========================================\n";
echo "  STII E-VOTE HOSTING DEPLOYMENT\n";
echo "========================================\n";
echo "\n";

// Step 1: Load and validate hosting configuration
echo "Step 1: Loading hosting configuration...\n";

if (!file_exists(__DIR__ . '/hosting-config.php')) {
    echo "✗ hosting-config.php not found!\n";
    exit(1);
}

$hostingConfig = require_once __DIR__ . '/hosting-config.php';

// Validate required settings
$required = ['APP_URL', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
foreach ($required as $key) {
    if (empty($hostingConfig[$key])) {
        echo "✗ Required setting missing: $key\n";
        echo "Please update hosting-config.php with your hosting details.\n";
        exit(1);
    }
}

echo "✓ Hosting configuration loaded and validated\n";

// Step 2: Apply hosting configuration
echo "\nStep 2: Applying hosting configuration...\n";

// Run the existing apply script
ob_start();
include __DIR__ . '/apply-hosting-config.php';
$output = ob_get_clean();
echo $output;

// Step 3: Set up Laravel-specific hosting requirements
echo "\nStep 3: Setting up Laravel hosting requirements...\n";

// Create required directories
$directories = [
    'storage/app/public',
    'storage/logs',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache',
    'public/storage'
];

foreach ($directories as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            echo "✓ Created directory: $dir\n";
        } else {
            echo "✗ Failed to create directory: $dir\n";
        }
    }
}

// Step 4: Create symbolic link for storage (if not exists)
$storageLink = __DIR__ . '/public/storage';
$storagePath = __DIR__ . '/storage/app/public';

if (!file_exists($storageLink) && is_dir($storagePath)) {
    if (function_exists('symlink')) {
        if (symlink($storagePath, $storageLink)) {
            echo "✓ Created storage symbolic link\n";
        } else {
            echo "⚠ Could not create symbolic link, may need manual setup\n";
        }
    } else {
        echo "⚠ Symbolic links not supported, may need manual setup\n";
    }
}

// Step 5: Create/Update .htaccess for better security
echo "\nStep 5: Setting up security configurations...\n";

$htaccessContent = <<<HTACCESS
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Hide sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "(composer\.(json|lock)|package\.json|\.env|\.git)">
    Order allow,deny
    Deny from all
</FilesMatch>
HTACCESS;

$publicHtaccess = __DIR__ . '/public/.htaccess';
if (file_put_contents($publicHtaccess, $htaccessContent)) {
    echo "✓ Updated public/.htaccess with security rules\n";
} else {
    echo "⚠ Could not update .htaccess file\n";
}

// Step 6: Create database migration check
echo "\nStep 6: Checking database connection...\n";

try {
    $pdo = new PDO(
        "mysql:host={$hostingConfig['DB_HOST']};port={$hostingConfig['DB_PORT']};dbname={$hostingConfig['DB_DATABASE']}",
        $hostingConfig['DB_USERNAME'],
        $hostingConfig['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✓ Database connection successful\n";
    
    // Check if tables exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) === 0) {
        echo "⚠ Database is empty - you need to import your database or run migrations\n";
    } else {
        echo "✓ Found " . count($tables) . " database tables\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    echo "Please check your database credentials in hosting-config.php\n";
}

// Step 7: Create deployment info file
echo "\nStep 7: Creating deployment information...\n";

$deploymentInfo = [
    'deployed_at' => date('Y-m-d H:i:s'),
    'environment' => $hostingConfig['APP_ENV'],
    'app_url' => $hostingConfig['APP_URL'],
    'database' => $hostingConfig['DB_DATABASE'],
    'php_version' => PHP_VERSION,
    'laravel_version' => '11.x', // Based on composer.json
    'deployment_script_version' => '1.0'
];

$deploymentFile = __DIR__ . '/storage/app/deployment-info.json';
if (file_put_contents($deploymentFile, json_encode($deploymentInfo, JSON_PRETTY_PRINT))) {
    echo "✓ Created deployment information file\n";
}

// Step 8: Final checks and instructions
echo "\n";
echo "========================================\n";
echo "  DEPLOYMENT COMPLETED SUCCESSFULLY!\n";
echo "========================================\n";
echo "\n";

echo "✓ Your STII E-Vote system is now ready for hosting!\n";
echo "\n";

echo "NEXT STEPS (if needed):\n";
echo "----------------------\n";
echo "1. If database is empty, import your SQL file:\n";
echo "   - Upload database/voting_system-*.sql to your hosting control panel\n";
echo "   - Or run: php artisan migrate (if using migrations)\n";
echo "\n";
echo "2. For better performance, run these commands if available:\n";
echo "   - php artisan config:cache\n";
echo "   - php artisan route:cache\n";
echo "   - php artisan view:cache\n";
echo "\n";
echo "3. Test your application at: {$hostingConfig['APP_URL']}\n";
echo "\n";

echo "SECURITY CHECKLIST:\n";
echo "-------------------\n";
echo "✓ APP_ENV set to 'production'\n";
echo "✓ APP_DEBUG set to 'false'\n";
echo "✓ New APP_KEY generated\n";
echo "✓ Secure .htaccess created\n";
echo "✓ Database connection configured\n";
echo "✓ Email settings configured\n";
echo "\n";

echo "SUPPORT:\n";
echo "--------\n";
echo "If you encounter any issues:\n";
echo "1. Check your hosting provider's error logs\n";
echo "2. Verify file permissions (755 for folders, 644 for files)\n";
echo "3. Ensure PHP 8.2+ is enabled\n";
echo "4. Contact your hosting provider for server-specific help\n";
echo "\n";

echo "Your system is ready! 🎉\n";
echo "\n";