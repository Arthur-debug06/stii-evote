<?php
/**
 * Railway Email Test Script
 * Access via: https://your-app.up.railway.app/test-email.php
 */

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

echo "=== RAILWAY EMAIL TEST ===\n\n";

// 1. Check Mail Configuration
echo "1. Checking Mail Configuration...\n";
echo "MAIL_MAILER: " . env('MAIL_MAILER') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME') . "\n";
echo "MAIL_PASSWORD: " . (env('MAIL_PASSWORD') ? '[SET - ' . strlen(env('MAIL_PASSWORD')) . ' chars]' : '[NOT SET]') . "\n";
echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n";
echo "MAIL_FROM_NAME: " . env('MAIL_FROM_NAME') . "\n\n";

// 2. Check password format (spaces issue)
$password = env('MAIL_PASSWORD');
if ($password) {
    echo "2. Password Analysis:\n";
    echo "Length: " . strlen($password) . " characters\n";
    echo "Has quotes: " . (strpos($password, '"') !== false ? 'YES (REMOVE QUOTES!)' : 'NO') . "\n";
    echo "Has spaces: " . (strpos($password, ' ') !== false ? 'YES (Expected for Gmail App Password)' : 'NO') . "\n";
    echo "Trimmed length: " . strlen(trim($password)) . "\n\n";
}

// 3. Test SMTP Connection
echo "3. Testing SMTP Connection...\n";
try {
    $transport = new \Swift_SmtpTransport(env('MAIL_HOST'), env('MAIL_PORT'), env('MAIL_ENCRYPTION'));
    $transport->setUsername(env('MAIL_USERNAME'));
    $transport->setPassword(env('MAIL_PASSWORD'));
    $transport->setTimeout(10);

    $mailer = new \Swift_Mailer($transport);
    $transport->start();

    echo "✓ SMTP Connection Successful!\n\n";
    $transport->stop();
} catch (\Exception $e) {
    echo "✗ SMTP Connection Failed!\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "Common fixes:\n";
    echo "1. Remove quotes from MAIL_PASSWORD in Railway variables\n";
    echo "2. Ensure password is: kdfg lelx egxd sjwk (with spaces, NO quotes)\n";
    echo "3. Verify Gmail App Password is still valid\n";
    echo "4. Check if 2-Step Verification is enabled on Gmail\n\n";
}

// 4. Test Sending Email
echo "4. Testing Email Send (to " . env('MAIL_USERNAME') . ")...\n";
try {
    \Illuminate\Support\Facades\Mail::raw('This is a test email from Railway deployment. If you receive this, your email configuration is working correctly!', function ($message) {
        $message->to(env('MAIL_USERNAME'))
            ->subject('Railway Email Test - STII E-Vote');
    });

    echo "✓ Email Sent Successfully!\n";
    echo "Check your inbox (and spam folder) for the test email.\n\n";
} catch (\Exception $e) {
    echo "✗ Email Send Failed!\n";
    echo "Error: " . $e->getMessage() . "\n\n";

    // Provide specific guidance based on error
    $errorMsg = $e->getMessage();
    if (strpos($errorMsg, 'authentication') !== false || strpos($errorMsg, 'Username and Password not accepted') !== false) {
        echo "⚠️  AUTHENTICATION ERROR - Fix:\n";
        echo "1. Go to Railway Dashboard → Variables\n";
        echo "2. Find MAIL_PASSWORD variable\n";
        echo "3. Remove any quotes: Change from \"kdfg lelx egxd sjwk\" to kdfg lelx egxd sjwk\n";
        echo "4. Keep the spaces in the password\n";
        echo "5. Redeploy the app\n\n";
    } elseif (strpos($errorMsg, 'Connection') !== false || strpos($errorMsg, 'timed out') !== false) {
        echo "⚠️  CONNECTION ERROR - Fix:\n";
        echo "1. Verify MAIL_PORT=587 (not 465)\n";
        echo "2. Verify MAIL_ENCRYPTION=tls (not ssl)\n";
        echo "3. Check Railway allows outbound connections on port 587\n\n";
    }
}

echo "=== TEST COMPLETED ===\n";
echo "\nIf you see errors above, follow the fix instructions.\n";
echo "Then redeploy and run this test again.\n";
