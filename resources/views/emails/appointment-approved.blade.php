<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Approved</title>
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
            background-color: #10b981;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .appointment-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .status-badge {
            background-color: #10b981;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Appointment Approved!</h1>
        <p>Your appointment has been confirmed</p>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $appointment->fullname }}</strong>,</p>
        
        <p>Great news! Your appointment request has been <span class="status-badge">APPROVED</span> and confirmed.</p>
        
        <div class="appointment-details">
            <h3 style="margin-top: 0; color: #10b981;">📅 Appointment Details</h3>
            
            <div class="detail-row">
                <span class="label">Patient Name:</span>
                <span class="value">{{ $appointment->fullname }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Email:</span>
                <span class="value">{{ $appointment->email }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Contact Number:</span>
                <span class="value">{{ $appointment->contact_number }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Appointment Date & Time:</span>
                <span class="value">{{ $appointment->appointment_datetime->format('F d, Y - h:i A') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Reason for Visit:</span>
                <span class="value">{{ $appointment->reason }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Status:</span>
                <span class="value"><span class="status-badge">CONFIRMED</span></span>
            </div>
        </div>
        
        <p><strong>📋 What to expect:</strong></p>
        <ul>
            <li>Please arrive 15 minutes before your scheduled appointment</li>
            <li>Bring a valid ID and any relevant medical documents</li>
            <li>If you need to reschedule, please contact us at least 24 hours in advance</li>
        </ul>
        
        <p>If you have any questions or need to make changes to your appointment, please contact our clinic.</p>
        
        <p>We look forward to seeing you!</p>
        
    <p>Best regards,<br>
    <strong>stii-evote</strong></p>
    </div>
    
    <div class="footer">
    <p>This is an automated email. Please do not reply to this message.</p>
    <p>&copy; {{ date('Y') }} stii-evote. All rights reserved.</p>
    </div>
</body>
</html>
