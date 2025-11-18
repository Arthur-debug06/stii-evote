<?php

/**
 * CONFIGURATION SWITCHER
 * ======================
 * 
 * This script helps you switch between local development and hosting configurations
 * Usage: php switch-config.php [local|hosting]
 */

$mode = $argv[1] ?? '';

if (empty($mode) || !in_array($mode, ['local', 'hosting'])) {
    echo "\n";
    echo "Configuration Switcher\n";
    echo "=====================\n";
    echo "\n";
    echo "Usage: php switch-config.php [local|hosting]\n";
    echo "\n";
    echo "Options:\n";
    echo "  local   - Use local development configuration (XAMPP/WAMP)\n";
    echo "  hosting - Use hosting production configuration\n";
    echo "\n";
    echo "Current status:\n";
    
    if (file_exists(__DIR__ . '/.env')) {
        $envContent = file_get_contents(__DIR__ . '/.env');
        if (strpos($envContent, 'APP_ENV=local') !== false) {
            echo "  ✅ Currently set to: LOCAL DEVELOPMENT\n";
        } elseif (strpos($envContent, 'APP_ENV=production') !== false) {
            echo "  ✅ Currently set to: HOSTING PRODUCTION\n";
        } else {
            echo "  ⚠️  Environment not clearly set\n";
        }
        
        if (strpos($envContent, 'DB_HOST=127.0.0.1') !== false || strpos($envContent, 'DB_HOST=localhost') !== false) {
            echo "  📡 Database: LOCAL (127.0.0.1/localhost)\n";
        } else {
            echo "  📡 Database: REMOTE HOSTING\n";
        }
    } else {
        echo "  ❌ No .env file found\n";
    }
    
    echo "\n";
    exit;
}

echo "\n";
echo "🔄 Switching to " . strtoupper($mode) . " configuration...\n";
echo "══════════════════════════════════════════════\n";

if ($mode === 'local') {
    // Switch to local development
    echo "Setting up LOCAL DEVELOPMENT configuration...\n";
    
    if (!file_exists(__DIR__ . '/local-config.php')) {
        echo "❌ local-config.php not found!\n";
        echo "Please create this file first.\n";
        exit(1);
    }
    
    // Copy local config to hosting config temporarily
    $backupConfig = __DIR__ . '/hosting-config.backup.php';
    if (file_exists(__DIR__ . '/hosting-config.php')) {
        copy(__DIR__ . '/hosting-config.php', $backupConfig);
        echo "✅ Backed up hosting-config.php\n";
    }
    
    copy(__DIR__ . '/local-config.php', __DIR__ . '/hosting-config.php');
    echo "✅ Applied local configuration\n";
    
    // Apply the configuration
    ob_start();
    include __DIR__ . '/apply-hosting-config.php';
    ob_end_clean();
    
    echo "✅ Generated .env file for local development\n";
    echo "\n";
    echo "🎉 LOCAL DEVELOPMENT READY!\n";
    echo "──────────────────────────\n";
    echo "• Environment: local\n";
    echo "• Debug: enabled\n";
    echo "• Database: localhost/127.0.0.1\n";
    echo "• URL: http://localhost/stii-evote/public\n";
    echo "\n";
    echo "Make sure XAMPP/WAMP is running and database 'stii_evote' exists.\n";
    
} else {
    // Switch to hosting
    echo "Setting up HOSTING PRODUCTION configuration...\n";
    
    // Restore hosting config if backup exists
    $backupConfig = __DIR__ . '/hosting-config.backup.php';
    if (file_exists($backupConfig)) {
        copy($backupConfig, __DIR__ . '/hosting-config.php');
        unlink($backupConfig);
        echo "✅ Restored hosting configuration\n";
    }
    
    if (!file_exists(__DIR__ . '/hosting-config.php')) {
        echo "❌ hosting-config.php not found!\n";
        echo "Please update hosting-config.php with your hosting details first.\n";
        exit(1);
    }
    
    // Check if hosting config has been updated
    $config = require __DIR__ . '/hosting-config.php';
    
    if ($config['DB_HOST'] === 'localhost' && $config['DB_DATABASE'] === 'your_database_name') {
        echo "⚠️  hosting-config.php still has default values!\n";
        echo "Please update it with your hosting provider's details:\n";
        echo "• DB_HOST\n";
        echo "• DB_DATABASE\n";
        echo "• DB_USERNAME\n";
        echo "• DB_PASSWORD\n";
        echo "• APP_URL\n";
        echo "\n";
        echo "Then run this command again.\n";
        exit(1);
    }
    
    // Apply the configuration
    ob_start();
    include __DIR__ . '/apply-hosting-config.php';
    ob_end_clean();
    
    echo "✅ Generated .env file for hosting\n";
    echo "\n";
    echo "🚀 HOSTING CONFIGURATION READY!\n";
    echo "──────────────────────────────\n";
    echo "• Environment: production\n";
    echo "• Debug: disabled\n";
    echo "• Database: " . $config['DB_HOST'] . "\n";
    echo "• URL: " . $config['APP_URL'] . "\n";
    echo "\n";
    echo "Next steps:\n";
    echo "1. Upload files to hosting server\n";
    echo "2. Run: php make-hosting-ready.php\n";
}

echo "\n";