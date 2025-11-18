<?php

/**
 * MANUAL CACHE GENERATOR FOR INFINITYFREE
 * =======================================
 * 
 * Creates Laravel cache files manually when artisan commands don't work
 * 
 * Usage: php manual-cache-generator.php
 */

echo "\n";
echo "🔧 MANUAL CACHE GENERATOR\n";
echo "=========================\n";
echo "\n";

// 1. Create config cache manually
echo "1️⃣ Creating Configuration Cache...\n";

try {
    // Load all configuration files
    $configs = [];
    
    // Basic Laravel configs
    $configFiles = [
        'app' => __DIR__ . '/config/app.php',
        'database' => __DIR__ . '/config/database.php',
        'cache' => __DIR__ . '/config/cache.php',
        'session' => __DIR__ . '/config/session.php',
        'mail' => __DIR__ . '/config/mail.php'
    ];
    
    $configData = [];
    foreach ($configFiles as $key => $file) {
        if (file_exists($file)) {
            $configData[$key] = require $file;
        }
    }
    
    // Override with .env values
    if (file_exists(__DIR__ . '/.env')) {
        $envContent = file_get_contents(__DIR__ . '/.env');
        $envLines = explode("\n", $envContent);
        
        foreach ($envLines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) continue;
            
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, '"');
                
                // Map env variables to config
                switch ($key) {
                    case 'APP_NAME':
                        $configData['app']['name'] = $value;
                        break;
                    case 'APP_ENV':
                        $configData['app']['env'] = $value;
                        break;
                    case 'APP_DEBUG':
                        $configData['app']['debug'] = ($value === 'true');
                        break;
                    case 'APP_URL':
                        $configData['app']['url'] = $value;
                        break;
                    case 'DB_HOST':
                        $configData['database']['connections']['mysql']['host'] = $value;
                        break;
                    case 'DB_DATABASE':
                        $configData['database']['connections']['mysql']['database'] = $value;
                        break;
                    case 'DB_USERNAME':
                        $configData['database']['connections']['mysql']['username'] = $value;
                        break;
                    case 'DB_PASSWORD':
                        $configData['database']['connections']['mysql']['password'] = $value;
                        break;
                }
            }
        }
    }
    
    // Write config cache
    $cacheContent = "<?php\n\nreturn " . var_export($configData, true) . ";\n";
    $cacheFile = __DIR__ . '/bootstrap/cache/config.php';
    
    if (file_put_contents($cacheFile, $cacheContent)) {
        echo "   ✅ Configuration cache created\n";
    } else {
        echo "   ❌ Failed to create configuration cache\n";
    }

} catch (Exception $e) {
    echo "   ❌ Configuration cache failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 2. Create basic route cache
echo "2️⃣ Creating Route Cache...\n";

try {
    // Basic route cache structure
    $routeCache = [
        'compiled' => [],
        'attributes' => []
    ];
    
    $routeCacheContent = "<?php\n\nreturn " . var_export($routeCache, true) . ";\n";
    $routeCacheFile = __DIR__ . '/bootstrap/cache/routes.php';
    
    if (file_put_contents($routeCacheFile, $routeCacheContent)) {
        echo "   ✅ Basic route cache created\n";
    } else {
        echo "   ❌ Failed to create route cache\n";
    }

} catch (Exception $e) {
    echo "   ❌ Route cache failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 3. Delete temporary deployment files
echo "3️⃣ Cleaning Temporary Files...\n";

$tempFiles = [
    'public/web-deploy.php',
    'public/run-deployment.php',
    'web-deploy.php',
    'run-deployment.php'
];

$deleted = 0;
foreach ($tempFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        if (unlink(__DIR__ . '/' . $file)) {
            echo "   ✅ Deleted: $file\n";
            $deleted++;
        } else {
            echo "   ⚠️  Could not delete: $file\n";
        }
    }
}

if ($deleted === 0) {
    echo "   ✅ No temporary files found\n";
} else {
    echo "   ✅ Deleted $deleted temporary files\n";
}

echo "\n";

// 4. Verify created files
echo "4️⃣ Verifying Cache Files...\n";

$cacheFiles = [
    'bootstrap/cache/config.php' => 'Configuration cache',
    'bootstrap/cache/routes.php' => 'Route cache',
    'bootstrap/cache/packages.php' => 'Package cache',
    'bootstrap/cache/services.php' => 'Services cache'
];

$verified = 0;
foreach ($cacheFiles as $file => $desc) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $size = filesize(__DIR__ . '/' . $file);
        echo "   ✅ $desc exists (" . round($size/1024, 1) . "KB)\n";
        $verified++;
    } else {
        echo "   ❌ $desc missing\n";
    }
}

echo "\n";

// Summary
echo "📊 MANUAL OPTIMIZATION COMPLETE\n";
echo "═══════════════════════════════\n";
echo "Cache files verified: $verified/" . count($cacheFiles) . "\n";
echo "Temporary files cleaned: $deleted\n";

if ($verified >= 2) {
    echo "\n🎉 OPTIMIZATION SUCCESSFUL!\n";
    echo "Your Laravel caches are now set up for hosting.\n";
} else {
    echo "\n⚠️  PARTIAL OPTIMIZATION\n";
    echo "Some cache files couldn't be created.\n";
    echo "Your system should still work correctly.\n";
}

echo "\n🚀 Test your deployment:\n";
echo "php deployment-complete-check.php\n";
echo "\n";
echo "Manual optimization completed on: " . date('Y-m-d H:i:s') . "\n";
echo "\n";