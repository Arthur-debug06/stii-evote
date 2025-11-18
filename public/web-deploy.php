<?php
/**
 * WEB-ACCESSIBLE DEPLOYMENT SCRIPT
 * ================================
 * 
 * Use this ONLY if you cannot access SSH or terminal on your hosting
 * 
 * SECURITY WARNING:
 * - Only use this during initial deployment
 * - DELETE THIS FILE immediately after use
 * - Never leave this accessible in production
 * 
 * Usage: Visit https://yourdomain.com/public/web-deploy.php?confirm=yes-deploy-now
 */

// Security check
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes-deploy-now') {
    http_response_code(403);
    die('
    <h1>Access Denied</h1>
    <p>This deployment script requires confirmation.</p>
    <p>Add <code>?confirm=yes-deploy-now</code> to the URL if you want to proceed.</p>
    <p><strong>WARNING:</strong> Only use this during initial deployment and delete immediately after!</p>
    ');
}

// Change to project root (assuming this file is in public folder)
$projectRoot = dirname(__DIR__);
chdir($projectRoot);

?>
<!DOCTYPE html>
<html>
<head>
    <title>STII E-Vote Deployment</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { color: #ffc107; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .step { margin: 15px 0; padding: 10px; border-left: 4px solid #007bff; background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 STII E-Vote System Deployment</h1>
        
        <div class="warning">
            <strong>⚠️ SECURITY WARNING:</strong> Delete this file immediately after deployment!
        </div>

        <div class="step">
            <h3>Step 1: Checking Environment</h3>
            <?php
            echo "PHP Version: " . PHP_VERSION . "<br>";
            echo "Current Directory: " . getcwd() . "<br>";
            echo "Project Root: " . $projectRoot . "<br>";
            
            if (file_exists('hosting-config.php')) {
                echo "<span class='success'>✅ hosting-config.php found</span><br>";
            } else {
                echo "<span class='error'>❌ hosting-config.php not found</span><br>";
                die('Please ensure hosting-config.php exists in the project root.');
            }
            
            if (file_exists('make-hosting-ready.php')) {
                echo "<span class='success'>✅ make-hosting-ready.php found</span><br>";
            } else {
                echo "<span class='error'>❌ make-hosting-ready.php not found</span><br>";
                die('Please ensure all deployment files are uploaded.');
            }
            ?>
        </div>

        <div class="step">
            <h3>Step 2: Running Deployment</h3>
            <pre><?php
            
            // Capture output
            ob_start();
            
            try {
                // Include the deployment script
                include 'make-hosting-ready.php';
                $deploymentOutput = ob_get_contents();
                ob_end_clean();
                
                echo htmlspecialchars($deploymentOutput);
                
            } catch (Exception $e) {
                ob_end_clean();
                echo "<span class='error'>❌ Error during deployment: " . htmlspecialchars($e->getMessage()) . "</span>";
            }
            
            ?></pre>
        </div>

        <div class="step">
            <h3>Step 3: Verification</h3>
            <?php
            
            // Check if .env was created
            if (file_exists('.env')) {
                echo "<span class='success'>✅ .env file created</span><br>";
            } else {
                echo "<span class='error'>❌ .env file not created</span><br>";
            }
            
            // Check if directories exist
            $dirs = ['storage/logs', 'bootstrap/cache', 'storage/framework/cache'];
            foreach ($dirs as $dir) {
                if (is_dir($dir)) {
                    echo "<span class='success'>✅ Directory exists: $dir</span><br>";
                } else {
                    echo "<span class='error'>❌ Directory missing: $dir</span><br>";
                }
            }
            
            ?>
        </div>

        <div class="step">
            <h3>✅ Deployment Complete!</h3>
            <p>Your STII E-Vote system should now be ready.</p>
            
            <h4>Next Steps:</h4>
            <ol>
                <li><strong>🗑️ DELETE THIS FILE NOW</strong> (web-deploy.php)</li>
                <li>Test your application at: <a href="<?= dirname($_SERVER['PHP_SELF']) ?>">Your Domain</a></li>
                <li>If database is empty, import your SQL file via hosting control panel</li>
                <li>Check all functionality works correctly</li>
            </ol>
        </div>

        <div class="warning">
            <strong>🚨 CRITICAL:</strong> Delete this file (web-deploy.php) immediately for security reasons!
        </div>

        <hr>
        <p><small>Deployment completed at: <?= date('Y-m-d H:i:s') ?></small></p>
    </div>
</body>
</html>