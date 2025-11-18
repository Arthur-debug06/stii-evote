<?php

/**
 * QUICK HOSTING OPTIMIZATION
 * ==========================
 * 
 * Fixes common deployment issues and optimizes Laravel for hosting
 * 
 * Usage: php quick-optimize.php
 */

echo "\n";
echo "⚡ QUICK HOSTING OPTIMIZATION\n";
echo "=============================\n";
echo "\n";

$fixed = 0;
$issues = 0;

// 1. Generate Laravel caches
echo "1️⃣ Generating Laravel Caches...\n";

$commands = [
    'config:cache' => 'Configuration cache',
    'route:cache' => 'Route cache',  
    'view:cache' => 'View cache'
];

foreach ($commands as $command => $description) {
    echo "   Generating $description...\n";
    $output = [];
    $returnCode = 0;
    
    // Try to run Laravel artisan command
    exec("php artisan $command 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ $description created\n";
        $fixed++;
    } else {
        echo "   ⚠️  $description failed - " . implode(' ', $output) . "\n";
        $issues++;
    }
}

echo "\n";

// 2. Clean up temporary files
echo "2️⃣ Cleaning Up Temporary Files...\n";

$tempFiles = [
    'public/web-deploy.php',
    'public/run-deployment.php',
    'deploy-temp.php'
];

$cleaned = 0;
foreach ($tempFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        if (unlink(__DIR__ . '/' . $file)) {
            echo "   ✅ Deleted: $file\n";
            $cleaned++;
        } else {
            echo "   ⚠️  Could not delete: $file\n";
        }
    }
}

if ($cleaned === 0) {
    echo "   ✅ No temporary files found\n";
}

echo "\n";

// 3. Set proper file permissions (if on Unix-like system)
echo "3️⃣ Setting File Permissions...\n";

if (DIRECTORY_SEPARATOR === '/') {
    // Unix-like system
    $commands = [
        'find . -type f -exec chmod 644 {} \;' => 'Files to 644',
        'find . -type d -exec chmod 755 {} \;' => 'Directories to 755',
        'chmod -R 775 storage bootstrap/cache' => 'Writable directories'
    ];
    
    foreach ($commands as $cmd => $desc) {
        $output = [];
        $returnCode = 0;
        exec($cmd . ' 2>&1', $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "   ✅ $desc\n";
            $fixed++;
        } else {
            echo "   ⚠️  $desc failed\n";
            $issues++;
        }
    }
} else {
    echo "   ✅ Windows system - permissions managed by filesystem\n";
}

echo "\n";

// 4. Verify optimizations
echo "4️⃣ Verifying Optimizations...\n";

$cacheFiles = [
    'bootstrap/cache/config.php' => 'Configuration cache',
    'bootstrap/cache/routes.php' => 'Route cache',
    'storage/framework/views' => 'View cache directory'
];

$verified = 0;
foreach ($cacheFiles as $file => $desc) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "   ✅ $desc verified\n";
        $verified++;
    } else {
        echo "   ❌ $desc missing\n";
    }
}

echo "\n";

// 5. Test basic Laravel functionality
echo "5️⃣ Testing Laravel...\n";

try {
    // Test artisan command
    $output = [];
    $returnCode = 0;
    exec('php artisan --version 2>&1', $output, $returnCode);
    
    if ($returnCode === 0 && !empty($output)) {
        echo "   ✅ Laravel Artisan working: " . trim($output[0]) . "\n";
        $fixed++;
    } else {
        echo "   ❌ Laravel Artisan not responding\n";
        $issues++;
    }
} catch (Exception $e) {
    echo "   ❌ Laravel test failed: " . $e->getMessage() . "\n";
    $issues++;
}

echo "\n";

// Summary
echo "📊 OPTIMIZATION SUMMARY\n";
echo "═══════════════════════\n";
echo "Fixes applied: $fixed\n";
echo "Issues remaining: $issues\n";
echo "Cache files verified: $verified/" . count($cacheFiles) . "\n";

if ($issues === 0) {
    echo "\n🎉 ALL OPTIMIZATIONS SUCCESSFUL!\n";
    echo "Your hosting deployment is now optimized.\n";
    echo "\n🚀 Run this to verify:\n";
    echo "php deployment-complete-check.php\n";
} else {
    echo "\n⚠️  SOME OPTIMIZATIONS FAILED\n";
    echo "Check the issues above and consider:\n";
    echo "1. Running commands manually\n";
    echo "2. Checking file permissions\n";
    echo "3. Verifying PHP/Laravel installation\n";
    echo "\n🔧 Still worth testing:\n";
    echo "php deployment-complete-check.php\n";
}

echo "\n";
echo "Optimization completed on: " . date('Y-m-d H:i:s') . "\n";
echo "\n";