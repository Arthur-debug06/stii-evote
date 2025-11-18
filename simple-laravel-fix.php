<?php
/**
 * 🔧 INFINITYFREE COMPLETE FIX - SIMPLIFIED
 * This script fixes all common InfinityFree issues without heavy processing
 */

// Simple error handling
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

// Set content type
header('Content-Type: text/html; charset=UTF-8');

echo '<!DOCTYPE html>
<html>
<head>
    <title>InfinityFree Laravel Fix</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        pre { background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 InfinityFree Laravel Fix</h1>';

// Check current directory contents
$currentDir = getcwd();
$files = array_diff(scandir('.'), array('..', '.'));

echo '<div class="warning">
<h2>📁 Current Directory Analysis</h2>
<p><strong>Location:</strong> ' . $currentDir . '</p>
<p><strong>Files found:</strong> ' . count($files) . '</p>
</div>';

// Check for Laravel structure
$laravelDirs = ['app', 'bootstrap', 'config', 'vendor'];
$laravelFiles = ['artisan', 'composer.json'];
$publicFiles = ['index.php', '.htaccess'];

$hasLaravel = false;
$hasPublic = false;

echo '<h2>🔍 Structure Analysis</h2>';

// Check Laravel directories
echo '<h3>Laravel Directories:</h3><ul>';
foreach ($laravelDirs as $dir) {
    $exists = is_dir($dir);
    if ($exists) $hasLaravel = true;
    echo '<li>' . ($exists ? '✅' : '❌') . ' ' . $dir . '/</li>';
}
echo '</ul>';

// Check Laravel files
echo '<h3>Laravel Files:</h3><ul>';
foreach ($laravelFiles as $file) {
    $exists = file_exists($file);
    if ($exists) $hasLaravel = true;
    echo '<li>' . ($exists ? '✅' : '❌') . ' ' . $file . '</li>';
}
echo '</ul>';

// Check public files
echo '<h3>Public Files:</h3><ul>';
foreach ($publicFiles as $file) {
    $exists = file_exists($file);
    if ($exists) $hasPublic = true;
    echo '<li>' . ($exists ? '✅' : '❌') . ' ' . $file . '</li>';
}
echo '</ul>';

// Diagnosis
if ($hasLaravel && $hasPublic) {
    echo '<div class="error">
    <h2>🚨 FOUND THE PROBLEM!</h2>
    <p><strong>Issue:</strong> Laravel index.php is looking for files in wrong location</p>
    <p><strong>Solution:</strong> The index.php expects files in parent directory (../) but they\'re in same directory</p>
    </div>';
    
    // Check if index.php needs fixing
    if (file_exists('index.php')) {
        $indexContent = file_get_contents('index.php');
        if (strpos($indexContent, '../') !== false) {
            echo '<div class="warning">
            <h3>🔧 Fix Required: index.php Path Issue</h3>
            <p>Your index.php is looking for files in parent directory, but they\'re in the same directory.</p>
            </div>';
            
            // Offer to fix it
            if (isset($_POST['fix_index'])) {
                $newContent = str_replace(
                    ["__DIR__.'/../", "__DIR__ . '/../"],
                    ["__DIR__.'/", "__DIR__ . '/"],
                    $indexContent
                );
                
                if (file_put_contents('index.php', $newContent)) {
                    echo '<div class="success">✅ <strong>index.php FIXED!</strong> Try accessing your site now.</div>';
                } else {
                    echo '<div class="error">❌ Could not fix index.php automatically</div>';
                }
            } else {
                echo '<form method="post">
                <input type="hidden" name="fix_index" value="1">
                <button type="submit" class="btn" style="background: green;">🔧 Fix index.php Paths</button>
                </form>';
            }
        } else {
            echo '<div class="success">✅ index.php paths look correct</div>';
        }
    }
    
} else if ($hasLaravel && !$hasPublic) {
    echo '<div class="warning">
    <h2>⚠️ Missing Public Files</h2>
    <p>Laravel application files are present, but missing public files (index.php, .htaccess)</p>
    <p><strong>Solution:</strong> Upload files from your Laravel public/ folder to this directory</p>
    </div>';
    
} else if (!$hasLaravel && $hasPublic) {
    echo '<div class="warning">
    <h2>⚠️ Missing Laravel Application</h2>
    <p>Public files are present, but missing Laravel application folders</p>
    <p><strong>Solution:</strong> Upload Laravel application folders (app, bootstrap, config, vendor) to this directory</p>
    </div>';
    
} else {
    echo '<div class="error">
    <h2>❌ No Laravel Files Found</h2>
    <p>Neither Laravel application files nor public files are detected</p>
    <p><strong>Solution:</strong> Upload your complete Laravel project files to this directory</p>
    </div>';
}

// Quick tests
echo '<h2>🧪 Quick Tests</h2>';

// Test .env
if (file_exists('.env')) {
    echo '<p>✅ .env file exists</p>';
} else {
    echo '<p>❌ .env file missing</p>';
}

// Test composer autoload
if (file_exists('vendor/autoload.php')) {
    echo '<p>✅ Composer autoloader exists</p>';
} else {
    echo '<p>❌ Composer autoloader missing</p>';
}

// Test bootstrap
if (file_exists('bootstrap/app.php')) {
    echo '<p>✅ Laravel bootstrap exists</p>';
} else {
    echo '<p>❌ Laravel bootstrap missing</p>';
}

echo '<div class="success">
<h2>📋 InfinityFree Upload Checklist</h2>
<p><strong>Correct structure for htdocs:</strong></p>
<pre>htdocs/
├── index.php          (from public/, with FIXED paths)
├── .htaccess          (from public/)
├── css/               (from public/css/)
├── js/                (from public/js/)
├── app/               (Laravel app folder)
├── bootstrap/         (Laravel bootstrap folder)  
├── config/            (Laravel config folder)
├── database/          (Laravel database folder)
├── resources/         (Laravel resources folder)
├── routes/            (Laravel routes folder)
├── storage/           (Laravel storage folder)
├── vendor/            (Laravel vendor folder)
├── .env               (your environment file)
├── artisan            (Laravel artisan)
└── composer.json      (Composer config)</pre>
</div>';

echo '</div></body></html>';
?>