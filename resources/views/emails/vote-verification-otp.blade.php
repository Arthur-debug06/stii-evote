<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vote Verification Code</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2563eb; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
        .otp-code { background-color: #2563eb; color: white; font-size: 28px; font-weight: bold; text-align: center; padding: 16px; border-radius: 8px; letter-spacing: 6px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
        .warning { background-color: #fef3c7; border: 1px solid #f59e0b; color: #92400e; padding: 12px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Vote Verification</h1>
    </div>
    <div class="content">
        <h2>Hello {{ $student->first_name ?? 'Voter' }},</h2>
        <p>You are about to submit your vote. Use this one-time code to verify your email and complete your vote:</p>
        <div class="otp-code">{{ $otp }}</div>
        <div class="warning">
            <strong>Important:</strong> This code expires in 10 minutes. Do not share it with anyone. If you did not request this, please ignore this email and contact support.
        </div>
        <p>Enter this 6-digit code in the voting page to confirm and submit your vote.</p>
        <p>Best regards,<br>Student Government Election</p>
    </div>
    <div class="footer">
        <p>This is an automated message. Please do not reply.</p>
    </div>
</body>
</html>
