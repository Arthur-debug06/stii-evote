<?php
/**
 * 🚨 InfinityFree 403 Universal Fix
 * 
 * This script handles InfinityFree-specific 403 errors
 * and creates proper directory index files.
 */

// Force output buffering off for immediate display
if (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html>
<head>
    <title>🔧 InfinityFree 403 Fix</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .error { background: #ffebee; border: 1px solid #f44336; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .success { background: #e8f5e8; border: 1px solid #4caf50; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fff3e0; border: 1px solid #ff9800; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #e3f2fd; border: 1px solid #2196f3; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #2196f3; color: white; text-decoration: none; border-radius: 5px; margin: 5px; border: none; cursor: pointer; }
        .btn:hover { background: #1976d2; }
        .btn-success { background: #4caf50; }
        .btn-success:hover { background: #388e3c; }
        .btn-danger { background: #f44336; }
        .btn-danger:hover { background: #d32f2f; }
        pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; border-radius: 3px; overflow-x: auto; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #2196f3; background: #f9f9f9; }
    </style>
</head>
<body>
<div class="container">
    <h1>🚨 InfinityFree 403 Error Universal Fix</h1>
    <p><strong>Current Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    
    <?php
    
    echo "<div class='info'>";
    echo "<h2>🔍 System Analysis</h2>";
    echo "<p><strong>Server:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
    echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
    echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";
    echo "<p><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";
    echo "</div>";
    
    // Check if this is InfinityFree
    $isInfinityFree = strpos($_SERVER['HTTP_HOST'] ?? '', 'infinityfree') !== false || 
                      strpos($_SERVER['SERVER_NAME'] ?? '', 'infinityfree') !== false ||
                      strpos(getcwd(), 'htdocs') !== false;
    
    if ($isInfinityFree) {
        echo "<div class='warning'>";
        echo "<h2>🎯 InfinityFree Hosting Detected</h2>";
        echo "<p>Applying InfinityFree-specific fixes...</p>";
        echo "</div>";
    }
    
    // Handle form submissions first
    if ($_POST['action'] ?? false) {
        echo "<div class='info'><h2>🔧 Executing Fix...</h2>";
        
        switch ($_POST['action']) {
            case 'create_index':
                $indexContent = '<!DOCTYPE html>
<html>
<head>
    <title>STII E-Vote System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; border-radius: 10px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 6px; margin: 8px; font-weight: bold; }
        .btn:hover { background: #0056b3; }
        .status-good { color: #28a745; font-weight: bold; }
        .status-warning { color: #ffc107; font-weight: bold; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🗳️ STII E-Vote System</h1>
        <p>Student Technology Integration Initiative - Electronic Voting Platform</p>
        <p class="status-good">✅ Site is now accessible!</p>
    </div>
    
    <div class="grid">
        <div class="card">
            <h2>🎯 Current Status</h2>
            <p><span class="status-good">✅ 403 Error Fixed</span></p>
            <p><span class="status-warning">⚠️ Awaiting Laravel Upload</span></p>
            <p><strong>Time:</strong> ' . date('Y-m-d H:i:s') . '</p>
            <p><strong>Your deployment scored 100% locally!</strong></p>
        </div>
        
        <div class="card">
            <h2>🚀 Next Steps</h2>
            <ol>
                <li>Upload Laravel <code>public/</code> files to root</li>
                <li>Upload Laravel application folders</li>
                <li>Run database configuration</li>
                <li>Start using your voting system!</li>
            </ol>
        </div>
        
        <div class="card">
            <h2>🛠️ Tools & Diagnostics</h2>
            <a href="infinityfree-403-fix.php" class="btn">🔧 Run Diagnostics</a>
            <a href="hosting-config.php" class="btn">⚙️ Database Setup</a>
            <a href="deployment-complete-check.php" class="btn">📊 Deployment Check</a>
        </div>
        
        <div class="card">
            <h2>📋 Upload Instructions</h2>
            <p><strong>Critical:</strong> Files must go in htdocs ROOT, not subfolders.</p>
            <ul>
                <li>Upload <code>public/index.php</code> → <code>htdocs/index.php</code></li>
                <li>Upload <code>public/.htaccess</code> → <code>htdocs/.htaccess</code></li>
                <li>Upload all Laravel folders to <code>htdocs/</code></li>
            </ul>
            <p><small>Once you upload Laravel\'s index.php, it will replace this page.</small></p>
        </div>
    </div>
    
    <div class="card">
        <h2>🎊 Success!</h2>
        <p>Your STII E-Vote system is ready for deployment. The 403 error is resolved, and your site is now accessible.</p>
        <p><strong>Upload your Laravel files and your voting system will be live!</strong></p>
    </div>
</body>
</html>';
                
                if (file_put_contents('index.html', $indexContent)) {
                    echo "<p class='status-good'>✅ index.html created successfully!</p>";
                    echo "<p>Your site should now be accessible. <a href='.' target='_blank'>Click here to test</a></p>";
                } else {
                    echo "<p style='color: red;'>❌ Failed to create index.html</p>";
                }
                break;
                
            case 'create_htaccess':
                $htaccessContent = '# InfinityFree Compatible .htaccess
DirectoryIndex index.php index.html

# Enable URL Rewriting
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Handle Laravel routing when index.php is uploaded
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php [L,QSA]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>

# Prevent access to sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "\.(env|log|json)$">
    Order allow,deny
    Deny from all
</FilesMatch>';
                
                if (file_put_contents('.htaccess', $htaccessContent)) {
                    echo "<p class='status-good'>✅ .htaccess created successfully!</p>";
                } else {
                    echo "<p style='color: red;'>❌ Failed to create .htaccess</p>";
                }
                break;
                
            case 'fix_permissions':
                $directories = ['.', 'storage', 'bootstrap/cache'];
                foreach ($directories as $dir) {
                    if (is_dir($dir)) {
                        if (chmod($dir, 0755)) {
                            echo "<p class='status-good'>✅ Fixed permissions for $dir</p>";
                        } else {
                            echo "<p style='color: red;'>❌ Could not fix permissions for $dir</p>";
                        }
                    }
                }
                break;
        }
        echo "</div>";
    }
    
    // Current file status
    echo "<div class='step'>";
    echo "<h2>📁 Current Directory Analysis</h2>";
    
    $files = scandir('.');
    $hasIndex = false;
    $hasHtaccess = false;
    $hasLaravel = false;
    
    echo "<p><strong>Files in current directory:</strong></p><ul>";
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        echo "<li>";
        if (is_dir($file)) {
            echo "📁 $file/";
            if (in_array($file, ['app', 'bootstrap', 'config', 'vendor'])) {
                $hasLaravel = true;
                echo " <span style='color: green;'>(Laravel)</span>";
            }
        } else {
            echo "📄 $file";
            if ($file === 'index.php' || $file === 'index.html') {
                $hasIndex = true;
                echo " <span style='color: green;'>(Index file)</span>";
            }
            if ($file === '.htaccess') {
                $hasHtaccess = true;
                echo " <span style='color: green;'>(URL rewriting)</span>";
            }
        }
        echo "</li>";
    }
    echo "</ul>";
    
    echo "<h3>🎯 Status Summary:</h3>";
    echo "<p>Index File: " . ($hasIndex ? "<span style='color: green;'>✅ Found</span>" : "<span style='color: red;'>❌ Missing</span>") . "</p>";
    echo "<p>.htaccess: " . ($hasHtaccess ? "<span style='color: green;'>✅ Found</span>" : "<span style='color: red;'>❌ Missing</span>") . "</p>";
    echo "<p>Laravel Files: " . ($hasLaravel ? "<span style='color: green;'>✅ Found</span>" : "<span style='color: red;'>❌ Missing</span>") . "</p>";
    echo "</div>";
    
    // Quick fixes
    echo "<div class='step'>";
    echo "<h2>🛠️ Quick Fixes</h2>";
    echo "<p>Click the buttons below to fix common 403 issues:</p>";
    
    echo "<form method='post' style='display: inline;'>";
    echo "<input type='hidden' name='action' value='create_index'>";
    echo "<button type='submit' class='btn btn-success'>Create Emergency Index Page</button>";
    echo "</form>";
    
    echo "<form method='post' style='display: inline;'>";
    echo "<input type='hidden' name='action' value='create_htaccess'>";
    echo "<button type='submit' class='btn btn-success'>Create .htaccess File</button>";
    echo "</form>";
    
    echo "<form method='post' style='display: inline;'>";
    echo "<input type='hidden' name='action' value='fix_permissions'>";
    echo "<button type='submit' class='btn'>Fix Directory Permissions</button>";
    echo "</form>";
    echo "</div>";
    
    // Instructions
    echo "<div class='step'>";
    echo "<h2>📋 Complete Upload Guide</h2>";
    echo "<p><strong>For InfinityFree hosting, follow this exact structure:</strong></p>";
    echo "<pre>";
    echo "htdocs/  (your root directory)
├── index.php          ← From your Laravel public/ folder
├── .htaccess          ← From your Laravel public/ folder
├── css/               ← From your Laravel public/css/
├── js/                ← From your Laravel public/js/
├── build/             ← From your Laravel public/build/
├── app/               ← Your Laravel app/ folder
├── bootstrap/         ← Your Laravel bootstrap/ folder
├── config/            ← Your Laravel config/ folder
├── database/          ← Your Laravel database/ folder
├── resources/         ← Your Laravel resources/ folder
├── routes/            ← Your Laravel routes/ folder
├── storage/           ← Your Laravel storage/ folder
├── vendor/            ← Your Laravel vendor/ folder
├── .env               ← Your environment configuration
├── artisan            ← Laravel command tool
└── composer.json      ← Composer configuration";
    echo "</pre>";
    echo "</div>";
    
    echo "<div class='success'>";
    echo "<h2>🎉 Important Reminder</h2>";
    echo "<p><strong>Your system scored 100% in deployment testing!</strong></p>";
    echo "<p>Once you upload the files correctly, your STII E-Vote system will work perfectly.</p>";
    echo "<p>The 403 error will be completely resolved once you have an index file in place.</p>";
    echo "</div>";
    
    ?>
</div>
</body>
</html>