<?php
/**
 * Minimal Laravel Index for InfinityFree
 * This version includes error handling and step-by-step loading
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

echo "<!-- Loading STII E-Vote System -->\n";

try {
    // Step 1: Define Laravel start time
    define('LARAVEL_START', microtime(true));
    echo "<!-- Step 1: Laravel start time defined -->\n";
    
    // Step 2: Check for maintenance mode
    $maintenanceFile = __DIR__ . '/storage/framework/maintenance.php';
    if (file_exists($maintenanceFile)) {
        require $maintenanceFile;
        echo "<!-- Step 2: Maintenance mode loaded -->\n";
    } else {
        echo "<!-- Step 2: No maintenance mode -->\n";
    }
    
    // Step 3: Load Composer autoloader
    $autoloadFile = __DIR__ . '/vendor/autoload.php';
    if (!file_exists($autoloadFile)) {
        throw new Exception("Composer autoloader not found at: $autoloadFile");
    }
    require $autoloadFile;
    echo "<!-- Step 3: Composer autoloader loaded -->\n";
    
    // Step 4: Bootstrap Laravel
    $bootstrapFile = __DIR__ . '/bootstrap/app.php';
    if (!file_exists($bootstrapFile)) {
        throw new Exception("Laravel bootstrap not found at: $bootstrapFile");
    }
    $app = require_once $bootstrapFile;
    echo "<!-- Step 4: Laravel bootstrap loaded -->\n";
    
    // Step 5: Handle the request
    $request = \Illuminate\Http\Request::capture();
    echo "<!-- Step 5: Request captured -->\n";
    
    // Clear our debug output
    ob_clean();
    
    // Handle the request through Laravel
    $app->handleRequest($request);
    
} catch (Exception $e) {
    // Clear output buffer
    ob_clean();
    
    // Show detailed error page
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>STII E-Vote System - Loading Error</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
            .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 5px; margin: 20px 0; }
            .info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 20px; border-radius: 5px; margin: 20px 0; }
            .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 5px; margin: 20px 0; }
            pre { background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; border-radius: 3px; overflow-x: auto; }
            .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🗳️ STII E-Vote System</h1>
            <h2>Loading Error Detected</h2>
            
            <div class="error">
                <h3>❌ Error Details</h3>
                <p><strong>Message:</strong> <?php echo htmlspecialchars($e->getMessage()); ?></p>
                <p><strong>File:</strong> <?php echo htmlspecialchars($e->getFile()); ?></p>
                <p><strong>Line:</strong> <?php echo $e->getLine(); ?></p>
            </div>
            
            <div class="info">
                <h3>🔍 System Information</h3>
                <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
                <p><strong>Current Directory:</strong> <?php echo htmlspecialchars(__DIR__); ?></p>
                <p><strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
            
            <div class="info">
                <h3>📁 File Check</h3>
                <?php
                $requiredFiles = [
                    'vendor/autoload.php' => 'Composer autoloader',
                    'bootstrap/app.php' => 'Laravel bootstrap',
                    '.env' => 'Environment configuration',
                    'config/app.php' => 'Application configuration',
                    'app/Http/Kernel.php' => 'HTTP Kernel'
                ];
                
                foreach ($requiredFiles as $file => $desc) {
                    $exists = file_exists(__DIR__ . '/' . $file);
                    $status = $exists ? '✅' : '❌';
                    echo "<p>$status <strong>$file</strong> - $desc</p>";
                }
                ?>
            </div>
            
            <div class="success">
                <h3>🛠️ Troubleshooting</h3>
                <p>Your STII E-Vote system is almost ready! Here's what to check:</p>
                <ol>
                    <li><strong>Run Complete Diagnostic:</strong> 
                        <a href="complete-diagnostic.php" class="btn">🔧 Full System Check</a>
                    </li>
                    <li><strong>Check File Structure:</strong> Ensure all Laravel files are uploaded correctly</li>
                    <li><strong>Verify Environment:</strong> Make sure .env file has correct settings</li>
                    <li><strong>Database Connection:</strong> Test database credentials</li>
                </ol>
            </div>
            
            <div class="info">
                <h3>📞 Quick Fixes</h3>
                <p>If you're seeing this error, try these solutions:</p>
                <ul>
                    <li>Upload missing files indicated above</li>
                    <li>Check that vendor/ folder is completely uploaded</li>
                    <li>Verify .env file exists and has correct database settings</li>
                    <li>Run the complete diagnostic tool for detailed analysis</li>
                </ul>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>