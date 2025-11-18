<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vote Confirmation - stii-evote</title>
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
        .vote-summary {
            background-color: #dbeafe;
            border: 1px solid #3b82f6;
            color: #1e40af;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .candidate-list {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        .candidate-item {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .candidate-item:last-child {
            border-bottom: none;
        }
        .position-title {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
            padding: 8px 12px;
            background-color: #f3f4f6;
            border-radius: 6px;
        }
        .candidate-name {
            font-weight: 600;
            color: #374151;
        }
        .candidate-details {
            font-size: 14px;
            color: #6b7280;
        }
        .success-badge {
            background-color: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .info-box {
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
        <h1>Vote Confirmation - stii-evote</h1>
    </div>
    
    <div class="content">
        <h2>Hello {{ $student->first_name }} {{ $student->last_name }},</h2>
        
        <div class="success-badge">✓ Vote Successfully Submitted</div>
        
        <p>Thank you for participating in the student government election. Your vote has been successfully recorded and submitted.</p>
        
        <div class="vote-summary">
            <h3>Your Vote Summary</h3>
            <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
            <p><strong>Vote Date:</strong> {{ $voteDate }}</p>
            <p><strong>Total Candidates Voted:</strong> {{ $totalVotes }}</p>
        </div>
        
        <h3>Your Selected Candidates:</h3>
        
        @foreach($votedCandidates as $position => $candidates)
        <div class="candidate-list">
            <div class="position-title">{{ $position }}</div>
            @foreach($candidates as $candidate)
            <div class="candidate-item">
                <div>
                    <div class="candidate-name">{{ $candidate['name'] }}</div>
                    <div class="candidate-details">
                        Student ID: {{ $candidate['student_id'] }} | 
                        Course: {{ $candidate['course'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
        
        <div class="info-box">
            <strong>Important Information:</strong>
            <ul>
                <li>Your vote has been securely recorded and cannot be changed</li>
                <li>Election results will be announced after the voting period ends</li>
                <li>Keep this email as your voting receipt</li>
                <li>If you have any concerns, please contact the election committee</li>
            </ul>
        </div>
        
        <p>Thank you for exercising your right to vote and participating in the democratic process of our student government.</p>
        
    <p>Best regards,<br>
    The Election Committee<br>
    stii-evote</p>
    </div>
    
    <div class="footer">
    <p>This is an automated confirmation message. Please do not reply to this email.</p>
    <p>&copy; {{ date('Y') }} stii-evote. All rights reserved.</p>
    </div>
</body>
</html>
