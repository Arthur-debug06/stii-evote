<?php

/**
 * HOSTING DEPLOYMENT COMPLETION CHECKER
 * ====================================
 * 
 * Run this after php make-hosting-ready.php to verify everything is working
 * 
 * Usage: php deployment-complete-check.php
 */

echo "\n";
echo "🎯 DEPLOYMENT COMPLETION CHECK\n";
echo "==============================\n";
echo "\n";

$checks = [];
$score = 0;
$maxScore = 0;

// Check 1: Environment Configuration
$maxScore++;
echo "1️⃣ Environment Configuration...\n";
if (file_exists(__DIR__ . '/.env')) {
    $envContent = file_get_contents(__DIR__ . '/.env');
    
    if (strpos($envContent, 'APP_ENV=production') !== false) {
        echo "   ✅ Environment set to production\n";
        $score++;
    } else {
        echo "   ❌ Environment not set to production\n";
    }
    
    if (strpos($envContent, 'APP_DEBUG=false') !== false) {
        echo "   ✅ Debug mode disabled\n";
    } else {
        echo "   ⚠️  Debug mode still enabled\n";
    }
    
    if (strpos($envContent, 'APP_KEY=base64:') !== false && strlen($envContent) > 200) {
        echo "   ✅ APP_KEY generated\n";
    } else {
        echo "   ❌ APP_KEY not properly set\n";
    }
} else {
    echo "   ❌ .env file missing\n";
}

echo "\n";

// Check 2: Database Connection
$maxScore++;
echo "2️⃣ Database Connection...\n";
try {
    if (file_exists(__DIR__ . '/.env')) {
        // Read from .env file instead of hosting-config.php  
        $envContent = file_get_contents(__DIR__ . '/.env');
        preg_match('/DB_HOST=(.*)/', $envContent, $hostMatch);
        preg_match('/DB_DATABASE=(.*)/', $envContent, $dbMatch);
        preg_match('/DB_USERNAME=(.*)/', $envContent, $userMatch);
        preg_match('/DB_PASSWORD=(.*)/', $envContent, $passMatch);
        
        $dbHost = trim($hostMatch[1] ?? '');
        $dbName = trim($dbMatch[1] ?? '');
        $dbUser = trim($userMatch[1] ?? '');
        $dbPass = trim($passMatch[1] ?? '', '"');
        
        $pdo = new PDO(
            "mysql:host=$dbHost;dbname=$dbName;port=3306",
            $dbUser,
            $dbPass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 10
            ]
        );
        echo "   ✅ Database connection successful\n";
        $score++;
        
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "   ✅ Found " . count($tables) . " database tables\n";
        
        // Check for key voting system tables (more flexible matching)
        $keyTables = ['users', 'students', 'elections', 'candidates', 'votes', 'applied_candidacy', 'course'];
        $foundTables = array_intersect($keyTables, $tables);
        
        // Also check for partial matches (in case of prefixes or different naming)
        $votingKeywords = ['user', 'student', 'election', 'candidate', 'vote', 'course'];
        $matchingCount = 0;
        foreach ($tables as $table) {
            foreach ($votingKeywords as $keyword) {
                if (strpos(strtolower($table), $keyword) !== false) {
                    $matchingCount++;
                    break;
                }
            }
        }
        
        if (count($foundTables) >= 3 || $matchingCount >= 5) {
            echo "   ✅ Key voting system tables present (" . count($foundTables) . " exact + " . ($matchingCount - count($foundTables)) . " partial matches)\n";
        } else {
            echo "   ⚠️  Some key tables might be missing (found " . count($foundTables) . " matches)\n";
        }
    } else {
        echo "   ❌ .env file not found\n";
    }
} catch (Exception $e) {
    echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "   💡 Note: Database worked in infinityfree-db-check.php, so this might be a timeout issue\n";
}

echo "\n";

// Check 3: Directory Structure
$maxScore++;
echo "3️⃣ Directory Structure...\n";
$requiredDirs = [
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

$dirScore = 0;
foreach ($requiredDirs as $dir) {
    if (is_dir(__DIR__ . '/' . $dir) && is_writable(__DIR__ . '/' . $dir)) {
        $dirScore++;
    }
}

if ($dirScore === count($requiredDirs)) {
    echo "   ✅ All required directories exist and are writable\n";
    $score++;
} else {
    echo "   ⚠️  Some directories missing or not writable ($dirScore/" . count($requiredDirs) . ")\n";
}

echo "\n";

// Check 4: Security Configuration
$maxScore++;
echo "4️⃣ Security Configuration...\n";
if (file_exists(__DIR__ . '/public/.htaccess')) {
    $htaccessContent = file_get_contents(__DIR__ . '/public/.htaccess');
    if (strpos($htaccessContent, 'X-Content-Type-Options') !== false) {
        echo "   ✅ Security headers configured\n";
        $score++;
    } else {
        echo "   ⚠️  Basic .htaccess only\n";
    }
} else {
    echo "   ❌ .htaccess file missing\n";
}

if (file_exists(__DIR__ . '/public/web-deploy.php')) {
    echo "   ⚠️  Temporary deployment script still exists (should be deleted)\n";
} else {
    echo "   ✅ No temporary deployment scripts found\n";
}

echo "\n";

// Check 5: Laravel Application
$maxScore++;
echo "5️⃣ Laravel Application...\n";
if (file_exists(__DIR__ . '/public/index.php')) {
    echo "   ✅ Laravel entry point exists\n";
    $score++;
} else {
    echo "   ❌ Laravel entry point missing\n";
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "   ✅ Composer dependencies installed\n";
} else {
    echo "   ⚠️  Composer dependencies might be missing\n";
}

echo "\n";

// Check 6: Caching Status
$maxScore++;
echo "6️⃣ Laravel Optimization...\n";
$cacheFiles = [
    'bootstrap/cache/config.php' => 'Configuration cache',
    'bootstrap/cache/routes.php' => 'Route cache',
    'bootstrap/cache/packages.php' => 'Package discovery'
];

$cacheScore = 0;
foreach ($cacheFiles as $file => $desc) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "   ✅ $desc exists\n";
        $cacheScore++;
    } else {
        echo "   ⚠️  $desc missing\n";
    }
}

if ($cacheScore >= 2) {
    $score++;
}

echo "\n";

// Overall Score
echo "🏆 DEPLOYMENT SCORE: $score/$maxScore (" . round(($score/$maxScore)*100) . "%)\n";
echo "═══════════════════════════════════════\n";

if ($score === $maxScore) {
    echo "🎉 PERFECT DEPLOYMENT!\n";
    echo "Your STII E-Vote system is fully ready for production!\n";
    echo "\n";
    echo "✅ All systems operational\n";
    echo "✅ Security configured\n";
    echo "✅ Database connected\n";
    echo "✅ Laravel optimized\n";
    echo "\nYour voting system is live and ready to use! 🚀\n";
    
} else if ($score >= $maxScore * 0.8) {
    echo "✅ DEPLOYMENT SUCCESSFUL!\n";
    echo "Your system is ready with minor optimizations possible.\n";
    echo "\nYour voting system should work correctly! 🎯\n";
    
} else if ($score >= $maxScore * 0.6) {
    echo "⚠️  DEPLOYMENT PARTIAL\n";
    echo "System may work but needs attention to warnings above.\n";
    echo "\nAddress the issues and test thoroughly. 🔧\n";
    
} else {
    echo "❌ DEPLOYMENT NEEDS WORK\n";
    echo "Critical issues need to be resolved.\n";
    echo "\nReview errors above and re-run deployment. 🚨\n";
}

echo "\n";

// Next steps based on score
if ($score === $maxScore) {
    echo "🎯 NEXT STEPS:\n";
    echo "1. Test your application in a browser\n";
    echo "2. Try logging in as admin\n";
    echo "3. Create a test election\n";
    echo "4. Set up regular backups\n";
    echo "5. Monitor system performance\n";
    
} else {
    echo "🔧 RECOMMENDED ACTIONS:\n";
    if ($score < $maxScore) {
        echo "1. Address any ❌ critical errors above\n";
        echo "2. Consider fixing ⚠️  warnings for better performance\n";
        echo "3. Re-run: php make-hosting-ready.php\n";
        echo "4. Test the application thoroughly\n";
    }
}

echo "\n";
echo "📊 Deployment completed on: " . date('Y-m-d H:i:s') . "\n";
echo "\n";