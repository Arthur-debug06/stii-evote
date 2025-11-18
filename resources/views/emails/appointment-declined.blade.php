<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Declined</title>
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
            background-color: #ef4444;
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
            border-left: 4px solid #ef4444;
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
            background-color: #ef4444;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .reason-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .reason-title {
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 10px;
        }
        .reason-text {
            color: #374151;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>❌ Appointment Declined</h1>
        <p>We regret to inform you about your appointment</p>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $appointment->fullname }}</strong>,</p>
        
        <p>We regret to inform you that your appointment request has been <span class="status-badge">DECLINED</span>.</p>
        
        <div class="appointment-details">
            <h3 style="margin-top: 0; color: #ef4444;">📅 Appointment Details</h3>
            
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
                <span class="label">Requested Date & Time:</span>
                <span class="value">{{ $appointment->appointment_datetime->format('F d, Y - h:i A') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Reason for Visit:</span>
                <span class="value">{{ $appointment->reason }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Status:</span>
                <span class="value"><span class="status-badge">CANCELLED</span></span>
            </div>
        </div>
        
        @if($appointment->remarks)
        <div class="reason-box">
            <div class="reason-title">📝 Reason for Decline:</div>
            <div class="reason-text">"{{ $appointment->remarks }}"</div>
        </div>
        @endif
        
        <p><strong>🔄 What you can do next:</strong></p>
        <ul>
            <li>Contact our clinic to discuss alternative appointment dates</li>
            <li>Submit a new appointment request with different date/time preferences</li>
            <li>Speak with our staff to understand the reason and find a suitable solution</li>
        </ul>
        
        <p>We apologize for any inconvenience this may cause. Our team is committed to providing you with the best possible service, and we hope to accommodate your needs in the future.</p>
        
        <p>Please feel free to contact us if you have any questions or would like to reschedule.</p>
        
    <p>Best regards,<br>
    <strong>stii-evote</strong></p>
    </div>
    
    <div class="footer">
    <p>This is an automated email. Please do not reply to this message.</p>
    <p>&copy; {{ date('Y') }} stii-evote. All rights reserved.</p>
    </div>
</body>
</html>
