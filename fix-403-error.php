<?php
/**
 * 🔧 InfinityFree 403 Error Fixer
 * 
 * This script diagnoses and fixes common 403 Forbidden errors
 * on InfinityFree hosting for Laravel applications.
 */

echo "<h1>🔧 InfinityFree 403 Error Diagnosis</h1>";
echo "<p><strong>Current Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<hr>";

// Check 1: Index file existence
echo "<h2>1️⃣ Index File Check</h2>";
$indexFiles = ['index.php', 'index.html', 'index.htm'];
$foundIndex = false;

foreach ($indexFiles as $file) {
    if (file_exists($file)) {
        echo "✅ Found: $file<br>";
        $foundIndex = true;
        
        // Check if it's readable
        if (is_readable($file)) {
            echo "   ✅ File is readable<br>";
        } else {
            echo "   ❌ File is NOT readable (permission issue)<br>";
        }
        
        // Check file size
        $size = filesize($file);
        echo "   📊 File size: " . number_format($size) . " bytes<br>";
        
        if ($file === 'index.php') {
            echo "   🔍 Checking Laravel index.php content...<br>";
            $content = file_get_contents($file);
            if (strpos($content, 'Laravel') !== false) {
                echo "   ✅ Valid Laravel index.php detected<br>";
            } else {
                echo "   ⚠️ This doesn't look like Laravel's index.php<br>";
            }
        }
    } else {
        echo "❌ Missing: $file<br>";
    }
}

if (!$foundIndex) {
    echo "<div style='background: #ffe6e6; padding: 10px; border: 1px solid red;'>";
    echo "<strong>🚨 CRITICAL ISSUE:</strong> No index file found!<br>";
    echo "You need to upload <code>public/index.php</code> from your Laravel project to this directory.";
    echo "</div>";
}

echo "<br>";

// Check 2: .htaccess file
echo "<h2>2️⃣ .htaccess Configuration</h2>";
if (file_exists('.htaccess')) {
    echo "✅ .htaccess file exists<br>";
    
    if (is_readable('.htaccess')) {
        echo "✅ .htaccess is readable<br>";
        
        $htaccess = file_get_contents('.htaccess');
        echo "📋 .htaccess content:<br>";
        echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ccc;'>";
        echo htmlspecialchars($htaccess);
        echo "</pre>";
        
        // Check for common Laravel .htaccess patterns
        if (strpos($htaccess, 'RewriteEngine On') !== false) {
            echo "✅ URL rewriting enabled<br>";
        } else {
            echo "⚠️ URL rewriting not found<br>";
        }
        
        if (strpos($htaccess, 'index.php') !== false) {
            echo "✅ Laravel routing configuration found<br>";
        } else {
            echo "⚠️ Laravel routing not configured<br>";
        }
    } else {
        echo "❌ .htaccess is NOT readable<br>";
    }
} else {
    echo "❌ .htaccess file missing<br>";
    echo "<div style='background: #fff3cd; padding: 10px; border: 1px solid orange;'>";
    echo "<strong>⚠️ WARNING:</strong> .htaccess file is missing!<br>";
    echo "You need to upload <code>public/.htaccess</code> from your Laravel project.";
    echo "</div>";
}

echo "<br>";

// Check 3: Directory permissions
echo "<h2>3️⃣ Directory Structure & Permissions</h2>";
$requiredDirs = [
    'app' => 'Laravel application logic',
    'bootstrap' => 'Laravel bootstrap files',
    'config' => 'Configuration files',
    'resources' => 'Views and assets',
    'routes' => 'Route definitions',
    'storage' => 'Laravel storage (logs, cache)',
    'vendor' => 'Composer dependencies'
];

$dirsFound = 0;
foreach ($requiredDirs as $dir => $desc) {
    if (is_dir($dir)) {
        echo "✅ $dir/ - $desc<br>";
        $dirsFound++;
        
        if (is_readable($dir)) {
            echo "   ✅ Directory is readable<br>";
        } else {
            echo "   ❌ Directory is NOT readable<br>";
        }
    } else {
        echo "❌ $dir/ - $desc (MISSING)<br>";
    }
}

if ($dirsFound < count($requiredDirs)) {
    echo "<div style='background: #ffe6e6; padding: 10px; border: 1px solid red;'>";
    echo "<strong>🚨 CRITICAL ISSUE:</strong> Laravel application directories are missing!<br>";
    echo "You need to upload the Laravel application folders to this directory.";
    echo "</div>";
}

echo "<br>";

// Check 4: PHP Configuration
echo "<h2>4️⃣ PHP Environment</h2>";
echo "✅ PHP Version: " . PHP_VERSION . "<br>";

if (version_compare(PHP_VERSION, '8.1', '>=')) {
    echo "✅ PHP version is compatible with Laravel<br>";
} else {
    echo "⚠️ PHP version might be too old for Laravel<br>";
}

// Check for required extensions
$requiredExtensions = ['pdo', 'pdo_mysql', 'openssl', 'json', 'mbstring'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext extension loaded<br>";
    } else {
        echo "❌ $ext extension missing<br>";
    }
}

echo "<br>";

// Check 5: File suggestions
echo "<h2>5️⃣ Quick Fix Suggestions</h2>";

if (!file_exists('index.php')) {
    echo "<div style='background: #e6f3ff; padding: 15px; border: 1px solid blue; margin: 10px 0;'>";
    echo "<h3>🔧 Create Emergency Index File</h3>";
    echo "<p>If you can't upload the Laravel files right now, create a simple index.php:</p>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='create_emergency_index' value='1'>";
    echo "<button type='submit' style='background: blue; color: white; padding: 10px; border: none; cursor: pointer;'>Create Emergency Index</button>";
    echo "</form>";
    echo "</div>";
}

if (!file_exists('.htaccess')) {
    echo "<div style='background: #e6f3ff; padding: 15px; border: 1px solid blue; margin: 10px 0;'>";
    echo "<h3>🔧 Create Laravel .htaccess</h3>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='create_htaccess' value='1'>";
    echo "<button type='submit' style='background: green; color: white; padding: 10px; border: none; cursor: pointer;'>Create Laravel .htaccess</button>";
    echo "</form>";
    echo "</div>";
}

// Handle form submissions
if ($_POST['create_emergency_index'] ?? false) {
    $emergencyIndex = '<?php
echo "<h1>🚀 Laravel Site Loading...</h1>";
echo "<p>Your Laravel application will be here once fully uploaded.</p>";
echo "<p>Current time: " . date("Y-m-d H:i:s") . "</p>";
echo "<hr>";
echo "<h2>📋 Upload Status</h2>";

$requiredFiles = [
    "Laravel index.php from public/ folder",
    "Laravel .htaccess from public/ folder", 
    "app/ directory",
    "bootstrap/ directory",
    "config/ directory",
    "vendor/ directory"
];

foreach ($requiredFiles as $file) {
    echo "<li>$file</li>";
}

echo "<p><strong>Next:</strong> Upload your Laravel files and refresh this page.</p>";
?>';
    
    if (file_put_contents('index.php', $emergencyIndex)) {
        echo "<div style='background: #d4edda; padding: 10px; border: 1px solid green;'>";
        echo "✅ Emergency index.php created successfully!<br>";
        echo "Your site should now load. Refresh the page to see it.";
        echo "</div>";
    }
}

if ($_POST['create_htaccess'] ?? false) {
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
        echo "<div style='background: #d4edda; padding: 10px; border: 1px solid green;'>";
        echo "✅ Laravel .htaccess created successfully!";
        echo "</div>";
    }
}

echo "<hr>";
echo "<h2>📚 Complete Upload Instructions</h2>";
echo "<p>For complete setup instructions, visit: <strong>your-site.com/INFINITYFREE-UPLOAD-GUIDE.md</strong></p>";
echo "<p><strong>Remember:</strong> Upload Laravel's <code>public/</code> folder contents to your htdocs root, then upload the Laravel application folders.</p>";
?>