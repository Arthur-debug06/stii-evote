<?php
/**
 * InfinityFree Laravel Complete Diagnostic
 * This checks ALL possible issues causing 403/500 errors
 */

// Prevent any errors from breaking the output
error_reporting(0);
ini_set('display_errors', 0);

?><!DOCTYPE html>
<html>
<head>
    <title>InfinityFree Laravel Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .success { background: #d4edda; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #ffc107; }
        .info { background: #d1ecf1; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #17a2b8; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; margin: 5px; cursor: pointer; }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        pre { background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; border-radius: 3px; overflow-x: auto; font-size: 12px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #dee2e6; border-radius: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 InfinityFree Laravel Complete Diagnostic</h1>
    <p><strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    
    <?php
    
    // Test 1: Basic PHP functionality
    echo '<div class="section">';
    echo '<h2>1️⃣ PHP Environment Test</h2>';
    echo '<p>✅ PHP Version: ' . PHP_VERSION . '</p>';
    echo '<p>✅ Current Directory: ' . getcwd() . '</p>';
    echo '<p>✅ Script is executing properly</p>';
    echo '</div>';
    
    // Test 2: File structure analysis
    echo '<div class="section">';
    echo '<h2>2️⃣ File Structure Analysis</h2>';
    
    $requiredFiles = [
        'index.php' => 'Laravel entry point',
        '.htaccess' => 'URL rewriting',
        'vendor/autoload.php' => 'Composer autoloader',
        'bootstrap/app.php' => 'Laravel bootstrap',
        '.env' => 'Environment configuration',
        'app/Http/Kernel.php' => 'Laravel HTTP Kernel',
        'config/app.php' => 'Laravel app config'
    ];
    
    $missingFiles = [];
    foreach ($requiredFiles as $file => $desc) {
        if (file_exists($file)) {
            echo "<p>✅ $file - $desc</p>";
        } else {
            echo "<p>❌ $file - $desc <strong>(MISSING)</strong></p>";
            $missingFiles[] = $file;
        }
    }
    
    if (empty($missingFiles)) {
        echo '<div class="success">✅ All required files are present!</div>';
    } else {
        echo '<div class="error">❌ Missing files detected. This could cause 403 errors.</div>';
    }
    echo '</div>';
    
    // Test 3: index.php content analysis
    echo '<div class="section">';
    echo '<h2>3️⃣ Index.php Content Analysis</h2>';
    
    if (file_exists('index.php')) {
        $indexContent = file_get_contents('index.php');
        echo '<p>✅ index.php exists and is readable</p>';
        echo '<p>📊 File size: ' . strlen($indexContent) . ' bytes</p>';
        
        // Check for Laravel markers
        if (strpos($indexContent, 'Laravel') !== false || strpos($indexContent, 'Illuminate') !== false) {
            echo '<p>✅ Laravel markers detected</p>';
        } else {
            echo '<p>⚠️ No Laravel markers found</p>';
        }
        
        // Check path references
        if (strpos($indexContent, '../') !== false) {
            echo '<div class="error">❌ Found ../ path references - this will cause 403 errors on InfinityFree</div>';
            echo '<p><strong>Issue:</strong> index.php still contains parent directory references</p>';
        } else {
            echo '<p>✅ No problematic path references found</p>';
        }
        
        // Show first few lines
        echo '<p><strong>First 10 lines of index.php:</strong></p>';
        echo '<pre>' . htmlspecialchars(implode("\n", array_slice(explode("\n", $indexContent), 0, 10))) . '</pre>';
        
    } else {
        echo '<div class="error">❌ index.php does not exist!</div>';
    }
    echo '</div>';
    
    // Test 4: .htaccess analysis
    echo '<div class="section">';
    echo '<h2>4️⃣ .htaccess Analysis</h2>';
    
    if (file_exists('.htaccess')) {
        $htaccessContent = file_get_contents('.htaccess');
        echo '<p>✅ .htaccess exists</p>';
        echo '<p>📊 File size: ' . strlen($htaccessContent) . ' bytes</p>';
        
        if (strpos($htaccessContent, 'RewriteEngine On') !== false) {
            echo '<p>✅ URL rewriting enabled</p>';
        } else {
            echo '<p>⚠️ URL rewriting not found</p>';
        }
        
        echo '<p><strong>.htaccess content:</strong></p>';
        echo '<pre>' . htmlspecialchars($htaccessContent) . '</pre>';
        
    } else {
        echo '<div class="error">❌ .htaccess does not exist!</div>';
    }
    echo '</div>';
    
    // Test 5: Environment file
    echo '<div class="section">';
    echo '<h2>5️⃣ Environment Configuration</h2>';
    
    if (file_exists('.env')) {
        echo '<p>✅ .env file exists</p>';
        
        $envContent = file_get_contents('.env');
        $envLines = explode("\n", $envContent);
        $envVars = [];
        
        foreach ($envLines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) continue;
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $envVars[trim($key)] = trim($value, '"');
            }
        }
        
        $criticalVars = ['APP_NAME', 'APP_ENV', 'APP_KEY', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
        foreach ($criticalVars as $var) {
            if (isset($envVars[$var]) && !empty($envVars[$var])) {
                $displayValue = $var === 'APP_KEY' ? '[HIDDEN]' : $envVars[$var];
                echo "<p>✅ $var = $displayValue</p>";
            } else {
                echo "<p>❌ $var is missing or empty</p>";
            }
        }
        
    } else {
        echo '<div class="error">❌ .env file does not exist!</div>';
    }
    echo '</div>';
    
    // Test 6: Directory permissions
    echo '<div class="section">';
    echo '<h2>6️⃣ Directory Permissions</h2>';
    
    $directories = ['.', 'storage', 'bootstrap/cache', 'storage/logs', 'storage/framework'];
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $perms = substr(sprintf('%o', fileperms($dir)), -4);
            if (is_writable($dir)) {
                echo "<p>✅ $dir/ - Writable (permissions: $perms)</p>";
            } else {
                echo "<p>⚠️ $dir/ - Not writable (permissions: $perms)</p>";
            }
        } else {
            echo "<p>❌ $dir/ - Directory does not exist</p>";
        }
    }
    echo '</div>';
    
    // Test 7: Create corrected files
    echo '<div class="section">';
    echo '<h2>7️⃣ Emergency File Creation</h2>';
    
    if (isset($_POST['create_corrected_index'])) {
        $correctedIndex = '<?php

use Illuminate\Http\Request;

define(\'LARAVEL_START\', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.\'/storage/framework/maintenance.php\')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.\'/vendor/autoload.php\';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.\'/bootstrap/app.php\')
    ->handleRequest(Request::capture());';
    
        if (file_put_contents('index.php', $correctedIndex)) {
            echo '<div class="success">✅ Created corrected index.php with proper paths!</div>';
        } else {
            echo '<div class="error">❌ Failed to create corrected index.php</div>';
        }
    }
    
    if (isset($_POST['create_htaccess'])) {
        $htaccessContent = '<IfModule mod_rewrite.c>
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
</IfModule>';
        
        if (file_put_contents('.htaccess', $htaccessContent)) {
            echo '<div class="success">✅ Created Laravel .htaccess file!</div>';
        } else {
            echo '<div class="error">❌ Failed to create .htaccess file</div>';
        }
    }
    
    if (isset($_POST['create_emergency_env'])) {
        $envContent = 'APP_NAME="STII E-Vote System"
APP_ENV=production
APP_KEY=base64:' . base64_encode(random_bytes(32)) . '
APP_DEBUG=false
APP_TIMEZONE=Asia/Manila
APP_URL=https://stii-evote.infinityfreeapp.com

LOG_CHANNEL=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=sql300.infinityfree.com
DB_PORT=3306
DB_DATABASE=if0_40083083_stii_evote
DB_USERNAME=if0_40083083
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=database
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120';
        
        if (file_put_contents('.env', $envContent)) {
            echo '<div class="success">✅ Created emergency .env file!</div>';
            echo '<div class="warning">⚠️ Remember to update DB_PASSWORD with your actual database password</div>';
        } else {
            echo '<div class="error">❌ Failed to create .env file</div>';
        }
    }
    
    echo '<form method="post" style="display: inline;">';
    echo '<input type="hidden" name="create_corrected_index" value="1">';
    echo '<button type="submit" class="btn btn-success">Create Corrected index.php</button>';
    echo '</form>';
    
    echo '<form method="post" style="display: inline;">';
    echo '<input type="hidden" name="create_htaccess" value="1">';
    echo '<button type="submit" class="btn btn-success">Create .htaccess</button>';
    echo '</form>';
    
    echo '<form method="post" style="display: inline;">';
    echo '<input type="hidden" name="create_emergency_env" value="1">';
    echo '<button type="submit" class="btn btn-success">Create Emergency .env</button>';
    echo '</form>';
    
    echo '</div>';
    
    // Summary
    echo '<div class="section">';
    echo '<h2>📋 Summary & Next Steps</h2>';
    
    if (empty($missingFiles)) {
        echo '<div class="info">';
        echo '<p><strong>Good news:</strong> All required files are present.</p>';
        echo '<p><strong>If you\'re still getting 403 errors, try:</strong></p>';
        echo '<ol>';
        echo '<li>Click "Create Corrected index.php" above to ensure proper paths</li>';
        echo '<li>Check that your .env file has the correct database credentials</li>';
        echo '<li>Verify storage directories are writable</li>';
        echo '<li>Clear browser cache and try again</li>';
        echo '</ol>';
        echo '</div>';
    } else {
        echo '<div class="error">';
        echo '<p><strong>Issues found:</strong> Missing critical files</p>';
        echo '<p><strong>Action needed:</strong> Use the buttons above to create missing files</p>';
        echo '</div>';
    }
    
    echo '</div>';
    
    ?>
</div>
</body>
</html>