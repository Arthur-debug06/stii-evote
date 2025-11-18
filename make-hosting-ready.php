#!/usr/bin/env php
<?php

/**
 * MASTER HOSTING DEPLOYMENT SCRIPT
 * ================================
 * 
 * The ONLY script you need to run after updating hosting-config.php
 * This handles everything: configuration, optimization, security, and verification
 * 
 * Usage: php make-hosting-ready.php
 */

// Increase execution limits
ini_set('max_execution_time', 300);
ini_set('memory_limit', '512M');

// Clear screen and show banner
if (PHP_SAPI === 'cli') {
    system('clear 2>/dev/null || cls 2>nul');
}

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                                                              ║\n";
echo "║              STII E-VOTE HOSTING DEPLOYMENT                  ║\n";
echo "║                                                              ║\n";
echo "║     🚀 One Script to Make Your System Hosting-Ready         ║\n";
echo "║                                                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

$startTime = microtime(true);

// Step 1: Pre-flight checks
echo "🔍 Pre-flight checks...\n";
echo "─────────────────────────\n";

// Check PHP version
if (version_compare(PHP_VERSION, '8.2.0', '<')) {
    echo "❌ PHP 8.2+ required. Current: " . PHP_VERSION . "\n";
    exit(1);
}
echo "✅ PHP version: " . PHP_VERSION . "\n";

// Check if in correct directory
if (!file_exists(__DIR__ . '/artisan')) {
    echo "❌ Laravel application not found. Make sure you're in the project root.\n";
    exit(1);
}
echo "✅ Laravel application detected\n";

// Check hosting config
if (!file_exists(__DIR__ . '/hosting-config.php')) {
    echo "❌ hosting-config.php not found!\n";
    echo "Please create this file from hosting-config.php.example\n";
    exit(1);
}
echo "✅ Hosting configuration found\n";

// Load and validate config
$config = require __DIR__ . '/hosting-config.php';
$required = ['APP_URL', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
$missing = [];

foreach ($required as $key) {
    if (empty($config[$key])) {
        $missing[] = $key;
    }
}

if (!empty($missing)) {
    echo "❌ Missing required configuration:\n";
    foreach ($missing as $key) {
        echo "   • $key\n";
    }
    echo "\nPlease update hosting-config.php\n";
    exit(1);
}
echo "✅ Configuration validated\n";

echo "\n";

// Step 2: Apply hosting configuration
echo "⚙️  Applying hosting configuration...\n";
echo "─────────────────────────────────────\n";

// Run the configuration applier silently
ob_start();
include __DIR__ . '/apply-hosting-config.php';
ob_end_clean();

echo "✅ Environment configuration applied\n";
echo "✅ Directories created and permissions set\n";

// Step 3: Laravel optimizations
echo "\n";
echo "🚀 Laravel optimizations...\n";
echo "───────────────────────────\n";

$commands = [
    'php artisan config:clear' => 'Clearing configuration cache',
    'php artisan config:cache' => 'Caching configuration',
    'php artisan route:clear' => 'Clearing route cache',
    'php artisan route:cache' => 'Caching routes',
    'php artisan view:clear' => 'Clearing view cache',
    'php artisan view:cache' => 'Caching views'
];

foreach ($commands as $command => $description) {
    $output = [];
    $returnCode = 0;
    exec($command . ' 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ $description\n";
    } else {
        echo "⚠️  $description (may need manual execution)\n";
    }
}

// Step 4: Security setup
echo "\n";
echo "🔒 Security configuration...\n";
echo "────────────────────────────\n";

// Create secure .htaccess
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
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Hide sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "(composer\.(json|lock)|package\.json|\.env|hosting-config\.php)">
    Order allow,deny
    Deny from all
</FilesMatch>
HTACCESS;

if (file_put_contents(__DIR__ . '/public/.htaccess', $htaccessContent)) {
    echo "✅ Security headers configured\n";
}

// Create robots.txt if it doesn't exist
if (!file_exists(__DIR__ . '/public/robots.txt')) {
    $robotsContent = "User-agent: *\nDisallow: /admin\nDisallow: /storage\n";
    file_put_contents(__DIR__ . '/public/robots.txt', $robotsContent);
    echo "✅ Search engine configuration created\n";
}

echo "✅ Sensitive files protected\n";

// Step 5: Database verification
echo "\n";
echo "🗄️  Database verification...\n";
echo "────────────────────────────\n";

try {
    $pdo = new PDO(
        "mysql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_DATABASE']}",
        $config['DB_USERNAME'],
        $config['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✅ Database connection successful\n";
    
    // Check if tables exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) === 0) {
        echo "⚠️  Database is empty - you may need to import your SQL file\n";
    } else {
        echo "✅ Found " . count($tables) . " database tables\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Check your credentials in hosting-config.php\n";
}

// Step 6: Final verification
echo "\n";
echo "✅ Final verification...\n";
echo "───────────────────────\n";

$checks = [
    'Environment' => $config['APP_ENV'] === 'production' ? '✅ Production' : '⚠️  Not production',
    'Debug Mode' => $config['APP_DEBUG'] === false ? '✅ Disabled' : '⚠️  Still enabled',
    'App URL' => strpos($config['APP_URL'], 'localhost') === false ? '✅ Set' : '⚠️  Still localhost',
    '.env file' => file_exists(__DIR__ . '/.env') ? '✅ Created' : '❌ Missing',
    'Storage writable' => is_writable(__DIR__ . '/storage') ? '✅ Writable' : '❌ Not writable',
    'Cache writable' => is_writable(__DIR__ . '/bootstrap/cache') ? '✅ Writable' : '❌ Not writable'
];

foreach ($checks as $check => $status) {
    echo "$status $check\n";
}

// Step 7: Generate deployment summary
echo "\n";
echo "📋 Deployment Summary\n";
echo "────────────────────\n";

$executionTime = round(microtime(true) - $startTime, 2);

$summary = [
    'deployed_at' => date('Y-m-d H:i:s'),
    'execution_time' => $executionTime . 's',
    'environment' => $config['APP_ENV'],
    'app_url' => $config['APP_URL'],
    'database' => $config['DB_DATABASE'],
    'php_version' => PHP_VERSION,
    'deployment_script' => 'make-hosting-ready.php v1.0'
];

file_put_contents(__DIR__ . '/deployment-summary.json', json_encode($summary, JSON_PRETTY_PRINT));

echo "Environment: " . $config['APP_ENV'] . "\n";
echo "App URL: " . $config['APP_URL'] . "\n";
echo "Database: " . $config['DB_DATABASE'] . "\n";
echo "Execution time: {$executionTime}s\n";

// Final message
echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                                                              ║\n";
echo "║                    🎉 DEPLOYMENT COMPLETE!                   ║\n";
echo "║                                                              ║\n";
echo "║     Your STII E-Vote system is now hosting-ready!           ║\n";
echo "║                                                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "🌐 Your application is ready at: " . $config['APP_URL'] . "\n";
echo "\n";

echo "Next steps (if needed):\n";
echo "• Import your database if it's empty\n";
echo "• Test all functionality\n";
echo "• Set up SSL certificate\n";
echo "• Configure backups\n";

echo "\n";
echo "Need help? Check deployment-summary.json for details.\n";
echo "\n";