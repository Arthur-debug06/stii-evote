<?php

/**
 * HOSTING CONFIGURATION CHECKER
 * =============================
 * 
 * Run this script to verify your hosting configuration is correct
 * Usage: php check-hosting-config.php
 */

echo "\n";
echo "=================================\n";
echo "  HOSTING CONFIGURATION CHECK\n";
echo "=================================\n";
echo "\n";

$errors = [];
$warnings = [];

// Check if hosting config file exists
if (!file_exists(__DIR__ . '/hosting-config.php')) {
    $errors[] = "hosting-config.php file not found";
} else {
    $config = require __DIR__ . '/hosting-config.php';
    
    // Check required configurations
    $required = [
        'APP_URL' => 'Application URL',
        'DB_HOST' => 'Database host',
        'DB_DATABASE' => 'Database name',
        'DB_USERNAME' => 'Database username',
        'DB_PASSWORD' => 'Database password',
        'MAIL_USERNAME' => 'Email username',
        'MAIL_PASSWORD' => 'Email password'
    ];
    
    foreach ($required as $key => $desc) {
        if (empty($config[$key])) {
            $errors[] = "$desc ($key) is not configured";
        }
    }
    
    // Check environment settings
    if ($config['APP_ENV'] !== 'production') {
        $warnings[] = "APP_ENV should be 'production' for hosting";
    }
    
    if ($config['APP_DEBUG'] !== false) {
        $warnings[] = "APP_DEBUG should be 'false' for security";
    }
    
    if (strpos($config['APP_URL'], 'localhost') !== false) {
        $warnings[] = "APP_URL still contains 'localhost' - update with your domain";
    }
    
    // Check database connection
    try {
        $pdo = new PDO(
            "mysql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_DATABASE']}",
            $config['DB_USERNAME'],
            $config['DB_PASSWORD'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo "✓ Database connection: OK\n";
    } catch (PDOException $e) {
        $errors[] = "Database connection failed: " . $e->getMessage();
    }
    
    // Check email configuration
    if ($config['MAIL_HOST'] === 'smtp.gmail.com' && empty($config['MAIL_PASSWORD'])) {
        $warnings[] = "Gmail SMTP requires an App Password, not your regular password";
    }
}

// Check file permissions
$criticalDirs = [
    'storage',
    'bootstrap/cache'
];

foreach ($criticalDirs as $dir) {
    if (!is_writable(__DIR__ . '/' . $dir)) {
        $errors[] = "Directory '$dir' is not writable - set permissions to 755";
    }
}

// Check .env file
if (!file_exists(__DIR__ . '/.env')) {
    $warnings[] = ".env file not found - run apply-hosting-config.php first";
}

// Display results
echo "\n";
if (empty($errors) && empty($warnings)) {
    echo "🎉 All checks passed! Your configuration looks good.\n";
    echo "You can proceed with deployment.\n";
} else {
    if (!empty($errors)) {
        echo "❌ ERRORS (must fix before deployment):\n";
        foreach ($errors as $error) {
            echo "   • $error\n";
        }
        echo "\n";
    }
    
    if (!empty($warnings)) {
        echo "⚠️  WARNINGS (recommended to fix):\n";
        foreach ($warnings as $warning) {
            echo "   • $warning\n";
        }
        echo "\n";
    }
}

echo "Next steps:\n";
echo "1. Fix any errors listed above\n";
echo "2. Update hosting-config.php with your hosting details\n";
echo "3. Run: php deploy-to-hosting.php\n";
echo "\n";