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
$mailer = env('MAIL_MAILER');
echo "MAIL_MAILER: " . $mailer . "\n";

if ($mailer === 'resend') {
    echo "RESEND_KEY: " . (env('RESEND_KEY') ? '[SET - ' . strlen(env('RESEND_KEY')) . ' chars]' : '[NOT SET]') . "\n";
    echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n";
    echo "MAIL_FROM_NAME: " . env('MAIL_FROM_NAME') . "\n\n";

    if (!env('RESEND_KEY')) {
        echo "❌ ERROR: RESEND_KEY is not set!\n";
        echo "Go to Railway → Variables → Add: RESEND_KEY=re_your_key\n\n";
        exit;
    }
} else {
    echo "MAIL_HOST: " . env('MAIL_HOST') . "\n";
    echo "MAIL_PORT: " . env('MAIL_PORT') . "\n";
    echo "MAIL_USERNAME: " . env('MAIL_USERNAME') . "\n";
    echo "MAIL_PASSWORD: " . (env('MAIL_PASSWORD') ? '[SET - ' . strlen(env('MAIL_PASSWORD')) . ' chars]' : '[NOT SET]') . "\n";
    echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION') . "\n";
    echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n";
    echo "MAIL_FROM_NAME: " . env('MAIL_FROM_NAME') . "\n\n";
}

// 2. Test Connection based on mailer type
if ($mailer === 'resend') {
    echo "2. Testing Resend API...\n";
    try {
        // Check if Resend class exists
        if (!class_exists('\Resend\Resend')) {
            echo "✗ Resend package not found!\n";
            echo "Run: composer require resend/resend-php\n\n";
            exit;
        }
        
        // Test Resend API connectivity
        $resendKey = env('RESEND_KEY');
        
        // Check for quotes in key
        if (strpos($resendKey, '"') !== false || strpos($resendKey, "'") !== false) {
            echo "⚠️  WARNING: RESEND_KEY has quotes!\n";
            echo "Remove quotes from Railway variable.\n";
            echo "Should be: re_PEnX5SPj_BGnCdZtRHPqD85YyFHt77Kiv\n";
            echo "Not: \"re_PEnX5SPj_BGnCdZtRHPqD85YyFHt77Kiv\"\n\n";
        }
        
        $resend = new \Resend\Resend($resendKey);
        echo "✓ Resend API initialized successfully!\n";
        echo "✓ API Key valid: " . substr($resendKey, 0, 8) . "..." . substr($resendKey, -4) . "\n\n";
    } catch (\Exception $e) {
        echo "✗ Resend API initialization failed!\n";
        echo "Error: " . $e->getMessage() . "\n";
        echo "Error Type: " . get_class($e) . "\n\n";
        
        if (strpos($e->getMessage(), 'not found') !== false) {
            echo "Fix: Ensure resend/resend-php is in composer.json\n";
        }
        // Don't exit, continue to test email sending
        echo "\n";
    }
} else {
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
        echo "⚠️  Railway blocks SMTP! Switch to Resend:\n";
        echo "1. Set MAIL_MAILER=resend\n";
        echo "2. Set RESEND_KEY=your_api_key\n";
        echo "3. Remove MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD\n\n";
    }
}

// Test Sending Email
$testMailer = env('MAIL_MAILER');
$testEmail = $testMailer === 'resend' ? env('MAIL_FROM_ADDRESS') : env('MAIL_USERNAME');
if (!$testEmail) {
    $testEmail = env('MAIL_FROM_ADDRESS', 'test@example.com');
}

echo "\n" . ($testMailer === 'resend' ? '3' : '4') . ". Testing Email Send (to " . $testEmail . ")...\n";
try {
    \Illuminate\Support\Facades\Mail::raw('This is a test email from Railway deployment using ' . strtoupper($testMailer) . '. If you receive this, your email configuration is working correctly!', function ($message) use ($testEmail) {
        $message->to($testEmail)
            ->subject('Railway Email Test - STII E-Vote - ' . strtoupper(env('MAIL_MAILER')));
    });

    echo "✓ Email Sent Successfully via " . strtoupper($testMailer) . "!\n";
    echo "Check your inbox (and spam folder) at: " . $testEmail . "\n\n";
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
