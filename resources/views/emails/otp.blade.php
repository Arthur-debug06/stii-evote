<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset OTP - Developer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4f46e5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .otp-code {
            background-color: #4f46e5;
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            letter-spacing: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Developer - Password Reset</h1>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->name }},</h2>
        
        <p>You have requested to reset your password for your account. Please use the following One-Time Password (OTP) to complete the process:</p>
        
        <div class="otp-code">
            {{ $otp }}
        </div>
        
        <div class="warning">
            <strong>Important:</strong>
            <ul>
                <li>This OTP is valid for 10 minutes only</li>
                <li>Do not share this code with anyone</li>
                <li>If you did not request this password reset, please ignore this email</li>
            </ul>
        </div>
        
        <p>To reset your password:</p>
        <ol>
            <li>Go to the OTP verification page</li>
            <li>Enter the 6-digit code above</li>
            <li>Create your new password</li>
            <li>Confirm your new password</li>
        </ol>
        
        <p>If you're having trouble, please contact our support team.</p>
        
        <p>Best regards,<br>
        The Developer Team</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Developer. All rights reserved.</p>
    </div>
</body>
</html>
