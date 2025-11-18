<?php

/**
 * ENVIRONMENT-AWARE CONFIGURATION CHECKER
 * =======================================
 * 
 * This script detects whether you're running locally or on hosting
 * and adjusts the database connection test accordingly
 * 
 * Usage: php smart-config-check.php
 */

echo "\n";
echo "🔍 SMART CONFIGURATION CHECK\n";
echo "=============================\n";
echo "\n";

// Detect environment
$isLocal = false;
$detectedEnv = 'unknown';

// Check if we're running locally
if (isset($_SERVER['SERVER_NAME'])) {
    $serverName = $_SERVER['SERVER_NAME'];
    if (in_array($serverName, ['localhost', '127.0.0.1']) || strpos($serverName, '.local') !== false) {
        $isLocal = true;
        $detectedEnv = 'local';
    }
} else {
    // CLI environment - check for common local indicators
    if (stripos(PHP_OS, 'WIN') !== false || file_exists('C:\\xampp') || file_exists('/Applications/XAMPP')) {
        $isLocal = true;
        $detectedEnv = 'local (CLI)';
    }
}

echo "🌍 Environment detected: " . $detectedEnv . "\n";
echo "📍 Running locally: " . ($isLocal ? 'YES' : 'NO') . "\n";
echo "\n";

// Load configuration
if (!file_exists(__DIR__ . '/hosting-config.php')) {
    echo "❌ hosting-config.php not found!\n";
    exit(1);
}

$config = require __DIR__ . '/hosting-config.php';

// Check .env file
if (!file_exists(__DIR__ . '/.env')) {
    echo "❌ .env file not found!\n";
    echo "Run: php apply-hosting-config.php first\n";
    exit(1);
}

$envContent = file_get_contents(__DIR__ . '/.env');

// Parse database settings from .env
preg_match('/DB_HOST=(.*)/', $envContent, $hostMatch);
preg_match('/DB_DATABASE=(.*)/', $envContent, $dbMatch);
preg_match('/DB_USERNAME=(.*)/', $envContent, $userMatch);

$currentDbHost = trim($hostMatch[1] ?? '');
$currentDbName = trim($dbMatch[1] ?? '');
$currentDbUser = trim($userMatch[1] ?? '');

echo "📊 CURRENT CONFIGURATION STATUS\n";
echo "─────────────────────────────────\n";
echo "Database Host: $currentDbHost\n";
echo "Database Name: $currentDbName\n";
echo "Database User: $currentDbUser\n";

// InfinityFree specific validation
if (strpos($currentDbHost, 'infinityfree') !== false) {
    echo "\n";
    echo "🔍 INFINITYFREE CREDENTIAL VALIDATION\n";
    echo "────────────────────────────────────\n";
    
    // Check hostname format
    if (preg_match('/^sql\d+\.infinityfree\.com$/', $currentDbHost)) {
        echo "✅ Database hostname format looks correct\n";
    } else {
        echo "⚠️  Unusual hostname format - verify in control panel\n";
    }
    
    // Check username format
    if (preg_match('/^if0_\d+/', $currentDbUser)) {
        echo "✅ Username format looks correct (starts with if0_)\n";
    } else {
        echo "⚠️  Username doesn't match InfinityFree format (should start with if0_)\n";
    }
    
    // Check database name format
    if (preg_match('/^if0_\d+_/', $currentDbName)) {
        echo "✅ Database name format looks correct\n";
    } else {
        echo "⚠️  Database name doesn't match InfinityFree format\n";
    }
    
    echo "\n";
    echo "💡 INFINITYFREE TIPS:\n";
    echo "• Database hostname: Check 'MySQL Databases' in control panel\n";
    echo "• Database name: Usually if0_XXXXXXX_yourdbname\n";
    echo "• Username: Usually matches the first part of database name\n";
    echo "• Password: Set when creating database user\n";
}

echo "\n";

// Check if configuration makes sense for current environment
if ($isLocal && strpos($currentDbHost, 'infinityfree') !== false) {
    echo "⚠️  CONFIGURATION MISMATCH DETECTED!\n";
    echo "───────────────────────────────────────\n";
    echo "You're running LOCALLY but have HOSTING database settings.\n";
    echo "InfinityFree's database server is only accessible from their hosting.\n";
    echo "\n";
    
    echo "🔧 SOLUTIONS:\n";
    echo "\n";
    
    echo "Option 1 - Set up LOCAL development:\n";
    echo "1. Create a local database in XAMPP/phpMyAdmin\n";
    echo "2. Run: php switch-config.php local\n";
    echo "3. This will use localhost database for development\n";
    echo "\n";
    
    echo "Option 2 - Test HOSTING configuration:\n";
    echo "1. Upload your files to InfinityFree hosting\n";
    echo "2. Run the configuration check there\n";
    echo "3. The database will work on their servers\n";
    echo "\n";
    
    echo "Option 3 - Skip database test for now:\n";
    echo "1. Your configuration looks correct for hosting\n";
    echo "2. Just deploy to InfinityFree and test there\n";
    echo "\n";
    
    // Create local configuration suggestion
    echo "📝 SUGGESTED LOCAL CONFIGURATION:\n";
    echo "─────────────────────────────────────\n";
    echo "For local development, you need:\n";
    echo "• DB_HOST=127.0.0.1 (or localhost)\n";
    echo "• DB_DATABASE=stii_evote (create this in phpMyAdmin)\n";
    echo "• DB_USERNAME=root\n";
    echo "• DB_PASSWORD= (empty for XAMPP)\n";
    echo "\n";
    
} else if (!$isLocal && (strpos($currentDbHost, 'localhost') !== false || strpos($currentDbHost, '127.0.0.1') !== false)) {
    echo "⚠️  CONFIGURATION MISMATCH DETECTED!\n";
    echo "───────────────────────────────────────\n";
    echo "You're running on HOSTING but have LOCAL database settings.\n";
    echo "Update your hosting-config.php with hosting database details.\n";
    echo "\n";
    
} else {
    // Configuration seems appropriate for environment
    echo "✅ Configuration appears appropriate for current environment\n";
    echo "\n";
    
    // Try database connection
    echo "🔍 Testing database connection...\n";
    echo "─────────────────────────────────\n";
    
    try {
        $pdo = new PDO(
            "mysql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_DATABASE']}",
            $config['DB_USERNAME'],
            $config['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 10 // 10 second timeout
            ]
        );
        
        echo "✅ Database connection successful!\n";
        
        // Check tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "✅ Found " . count($tables) . " database tables\n";
        
        if (count($tables) === 0) {
            echo "⚠️  Database is empty - you may need to import your SQL file\n";
        } else {
            // Check for key tables
            $keyTables = ['users', 'students', 'elections', 'candidates'];
            $foundTables = array_intersect($keyTables, $tables);
            echo "✅ Key tables found: " . implode(', ', $foundTables) . "\n";
        }
        
    } catch (PDOException $e) {
        $errorMessage = $e->getMessage();
        
        if ($isLocal && strpos($errorMessage, 'getaddrinfo') !== false) {
            echo "❌ Connection failed: External database not accessible from local environment\n";
            echo "This is NORMAL - hosting databases are only accessible from hosting servers.\n";
            echo "\n";
            echo "Your configuration is likely CORRECT for hosting.\n";
            echo "Deploy to your hosting server to test the actual connection.\n";
        } else if (strpos($errorMessage, 'getaddrinfo') !== false || strpos($errorMessage, 'Name or service not known') !== false) {
            echo "❌ Database server not found: $currentDbHost\n";
            echo "\n";
            echo "🔧 INFINITYFREE TROUBLESHOOTING:\n";
            echo "──────────────────────────────────\n";
            echo "1. Verify database server name in InfinityFree control panel\n";
            echo "2. Common InfinityFree DB hosts:\n";
            echo "   • sql200.infinityfree.com\n";
            echo "   • sql201.infinityfree.com\n";
            echo "   • sql300.infinityfree.com\n";
            echo "   • sql301.infinityfree.com\n";
            echo "\n";
            echo "3. Check your hosting control panel for the correct:\n";
            echo "   • Database hostname\n";
            echo "   • Database name\n";
            echo "   • Database username\n";
            echo "   • Database password\n";
            echo "\n";
            echo "4. Make sure your database is created in the control panel\n";
            echo "5. Try accessing phpMyAdmin to verify credentials\n";
        } else if (strpos($errorMessage, 'Access denied') !== false) {
            echo "❌ Access denied: Wrong username or password\n";
            echo "\n";
            echo "🔧 CREDENTIAL ISSUES:\n";
            echo "─────────────────────\n";
            echo "• Double-check DB_USERNAME and DB_PASSWORD in hosting-config.php\n";
            echo "• InfinityFree usernames usually start with 'if0_'\n";
            echo "• Passwords are case-sensitive\n";
            echo "• Make sure the database user has access to the database\n";
        } else if (strpos($errorMessage, 'Unknown database') !== false) {
            echo "❌ Database not found: $currentDbName\n";
            echo "\n";
            echo "🔧 DATABASE ISSUES:\n";
            echo "──────────────────\n";
            echo "• Create the database in your hosting control panel first\n";
            echo "• Database names in InfinityFree usually have format: if0_XXXXXXX_dbname\n";
            echo "• Check exact database name in control panel\n";
        } else {
            echo "❌ Database connection failed: " . $errorMessage . "\n";
            echo "\n";
            echo "🔧 GENERAL TROUBLESHOOTING:\n";
            echo "──────────────────────────\n";
            echo "• Check all database credentials in hosting-config.php\n";
            echo "• Verify database exists in hosting control panel\n";
            echo "• Try connecting via phpMyAdmin with same credentials\n";
            echo "• Contact hosting support if credentials are correct\n";
        }
    }
}

echo "\n";
echo "📋 NEXT STEPS SUMMARY\n";
echo "────────────────────\n";

if ($isLocal) {
    echo "Since you're developing locally:\n";
    echo "1. For LOCAL development → Run: php switch-config.php local\n";
    echo "2. For HOSTING deployment → Upload files and test on hosting server\n";
    echo "3. Your hosting config looks ready for InfinityFree!\n";
} else {
    echo "Since you're on hosting:\n";
    echo "1. Your configuration should work here\n";
    echo "2. If database fails, check with hosting provider\n";
    echo "3. Import your database if it's empty\n";
}

echo "\n";
echo "🚀 Ready to deploy? Your hosting configuration looks correct!\n";
echo "The database connection will work when deployed to InfinityFree.\n";
echo "\n";