<?php

/**
 * COMPREHENSIVE SYSTEM CHECK
 * ==========================
 * 
 * This script checks all aspects of your Laravel system:
 * - Directory structure and permissions
 * - Configuration files
 * - Database connectivity
 * - File permissions
 * - Laravel requirements
 * 
 * Usage: php system-check.php
 */

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                                                              ║\n";
echo "║                   COMPREHENSIVE SYSTEM CHECK                 ║\n";
echo "║                                                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

$errors = [];
$warnings = [];
$fixes = [];

// 1. PHP Environment Check
echo "🔍 Checking PHP Environment...\n";
echo "─────────────────────────────────\n";

echo "PHP Version: " . PHP_VERSION . "\n";
if (version_compare(PHP_VERSION, '8.2.0', '<')) {
    $errors[] = "PHP 8.2+ required. Current: " . PHP_VERSION;
} else {
    echo "✅ PHP version is compatible\n";
}

// Check required PHP extensions
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
$missingExtensions = [];

foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}

if (!empty($missingExtensions)) {
    $errors[] = "Missing PHP extensions: " . implode(', ', $missingExtensions);
} else {
    echo "✅ All required PHP extensions are loaded\n";
}

echo "\n";

// 2. Directory Structure Check
echo "📁 Checking Directory Structure...\n";
echo "──────────────────────────────────\n";

$requiredDirs = [
    'storage' => 'Main storage directory',
    'storage/app' => 'Application storage',
    'storage/app/public' => 'Public file storage',
    'storage/framework' => 'Framework storage',
    'storage/framework/cache' => 'Cache storage',
    'storage/framework/cache/data' => 'Cache data storage',
    'storage/framework/sessions' => 'Session storage',
    'storage/framework/views' => 'Compiled views storage',
    'storage/logs' => 'Log files storage',
    'bootstrap/cache' => 'Bootstrap cache',
    'public/storage' => 'Storage symlink'
];

foreach ($requiredDirs as $dir => $description) {
    $path = __DIR__ . '/' . $dir;
    
    if (!is_dir($path)) {
        $warnings[] = "Missing directory: $dir ($description)";
        $fixes[] = "Create directory: $dir";
        echo "⚠️  Missing: $dir\n";
        
        // Try to create the directory
        if (mkdir($path, 0755, true)) {
            echo "   → Created successfully\n";
        } else {
            echo "   → Failed to create\n";
        }
    } else {
        echo "✅ $dir exists\n";
        
        // Check if writable
        if (!is_writable($path)) {
            $warnings[] = "Directory not writable: $dir";
            $fixes[] = "Set permissions for: $dir (chmod 755)";
            echo "   ⚠️  Not writable\n";
        }
    }
}

echo "\n";

// 3. File Permissions Check
echo "🔐 Checking File Permissions...\n";
echo "───────────────────────────────\n";

$writableDirs = ['storage', 'bootstrap/cache'];
foreach ($writableDirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (is_dir($path)) {
        if (is_writable($path)) {
            echo "✅ $dir is writable\n";
        } else {
            $errors[] = "Directory not writable: $dir";
            $fixes[] = "Set write permissions: chmod -R 755 $dir";
            echo "❌ $dir is not writable\n";
        }
    }
}

echo "\n";

// 4. Configuration Files Check
echo "⚙️  Checking Configuration Files...\n";
echo "───────────────────────────────────\n";

$configFiles = [
    '.env' => 'Environment configuration',
    'hosting-config.php' => 'Hosting configuration',
    'composer.json' => 'Composer dependencies',
    'artisan' => 'Laravel Artisan CLI'
];

foreach ($configFiles as $file => $description) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ $file exists ($description)\n";
    } else {
        if ($file === '.env') {
            $warnings[] = "Missing .env file - run apply-hosting-config.php";
        } else {
            $errors[] = "Missing critical file: $file";
        }
        echo "❌ $file missing\n";
    }
}

echo "\n";

// 5. Laravel Configuration Check
echo "🚀 Checking Laravel Configuration...\n";
echo "────────────────────────────────────\n";

if (file_exists(__DIR__ . '/.env')) {
    $envContent = file_get_contents(__DIR__ . '/.env');
    
    // Check APP_KEY
    if (strpos($envContent, 'APP_KEY=') !== false) {
        preg_match('/APP_KEY=(.*)/', $envContent, $matches);
        $appKey = trim($matches[1] ?? '');
        
        if (empty($appKey) || $appKey === 'base64:GENERATE_NEW_KEY_FOR_HOSTING') {
            $warnings[] = "APP_KEY not set or needs regeneration";
            $fixes[] = "Generate APP_KEY: php artisan key:generate";
            echo "⚠️  APP_KEY needs to be generated\n";
        } else {
            echo "✅ APP_KEY is set\n";
        }
    }
    
    // Check environment settings
    if (strpos($envContent, 'APP_ENV=local') !== false) {
        $warnings[] = "APP_ENV is still set to 'local' - should be 'production' for hosting";
        $fixes[] = "Set APP_ENV=production in hosting-config.php";
        echo "⚠️  APP_ENV is 'local' (should be 'production' for hosting)\n";
    }
    
    if (strpos($envContent, 'APP_DEBUG=true') !== false) {
        $warnings[] = "APP_DEBUG is enabled - should be false for production";
        $fixes[] = "Set APP_DEBUG=false in hosting-config.php";
        echo "⚠️  APP_DEBUG is enabled (should be false for production)\n";
    }
} else {
    echo "❌ .env file not found\n";
}

echo "\n";

// 6. Database Connection Check
echo "🗄️  Checking Database Configuration...\n";
echo "─────────────────────────────────────\n";

if (file_exists(__DIR__ . '/hosting-config.php')) {
    $hostingConfig = require __DIR__ . '/hosting-config.php';
    
    $dbRequired = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
    $dbMissing = [];
    
    foreach ($dbRequired as $key) {
        if (empty($hostingConfig[$key])) {
            $dbMissing[] = $key;
        }
    }
    
    if (!empty($dbMissing)) {
        $errors[] = "Missing database configuration: " . implode(', ', $dbMissing);
        echo "❌ Missing database configuration\n";
    } else {
        echo "✅ Database configuration found\n";
        
        // Try to connect
        try {
            $pdo = new PDO(
                "mysql:host={$hostingConfig['DB_HOST']};port={$hostingConfig['DB_PORT']};dbname={$hostingConfig['DB_DATABASE']}",
                $hostingConfig['DB_USERNAME'],
                $hostingConfig['DB_PASSWORD'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo "✅ Database connection successful\n";
            
            // Check if tables exist
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (count($tables) === 0) {
                $warnings[] = "Database is empty - you may need to import your SQL file or run migrations";
                echo "⚠️  Database is empty\n";
            } else {
                echo "✅ Found " . count($tables) . " database tables\n";
            }
            
        } catch (PDOException $e) {
            $errors[] = "Database connection failed: " . $e->getMessage();
            echo "❌ Database connection failed\n";
            echo "   Error: " . $e->getMessage() . "\n";
        }
    }
} else {
    $errors[] = "hosting-config.php not found";
    echo "❌ hosting-config.php not found\n";
}

echo "\n";

// 7. Storage Symlink Check
echo "🔗 Checking Storage Symlink...\n";
echo "──────────────────────────────\n";

$storagePath = __DIR__ . '/storage/app/public';
$linkPath = __DIR__ . '/public/storage';

if (is_link($linkPath)) {
    echo "✅ Storage symlink exists\n";
} elseif (is_dir($linkPath)) {
    echo "✅ Storage directory exists (not symlinked)\n";
} else {
    $warnings[] = "Storage symlink missing";
    $fixes[] = "Create storage symlink: php artisan storage:link";
    echo "⚠️  Storage symlink missing\n";
}

echo "\n";

// 8. Web Server Configuration Check
echo "🌐 Checking Web Server Configuration...\n";
echo "──────────────────────────────────────\n";

if (file_exists(__DIR__ . '/public/.htaccess')) {
    echo "✅ .htaccess file exists\n";
} else {
    $warnings[] = "Missing .htaccess file for Apache";
    $fixes[] = "Create .htaccess file in public directory";
    echo "⚠️  .htaccess file missing\n";
}

if (file_exists(__DIR__ . '/public/index.php')) {
    echo "✅ Laravel entry point exists\n";
} else {
    $errors[] = "Missing Laravel entry point (public/index.php)";
    echo "❌ Laravel entry point missing\n";
}

echo "\n";

// Summary
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                           SUMMARY                            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

if (empty($errors) && empty($warnings)) {
    echo "🎉 ALL CHECKS PASSED!\n";
    echo "Your system is ready for hosting!\n";
} else {
    if (!empty($errors)) {
        echo "❌ CRITICAL ERRORS (must fix before hosting):\n";
        foreach ($errors as $i => $error) {
            echo "   " . ($i + 1) . ". $error\n";
        }
        echo "\n";
    }
    
    if (!empty($warnings)) {
        echo "⚠️  WARNINGS (recommended to fix):\n";
        foreach ($warnings as $i => $warning) {
            echo "   " . ($i + 1) . ". $warning\n";
        }
        echo "\n";
    }
    
    if (!empty($fixes)) {
        echo "🔧 SUGGESTED FIXES:\n";
        foreach ($fixes as $i => $fix) {
            echo "   " . ($i + 1) . ". $fix\n";
        }
        echo "\n";
    }
}

echo "RECOMMENDED NEXT STEPS:\n";
echo "1. Fix any critical errors listed above\n";
echo "2. Update hosting-config.php with your hosting details\n";
echo "3. Run: php make-hosting-ready.php\n";
echo "4. Test your application thoroughly\n";
echo "\n";

echo "For detailed hosting instructions, see: DEPLOY-INSTRUCTIONS.txt\n";
echo "\n";