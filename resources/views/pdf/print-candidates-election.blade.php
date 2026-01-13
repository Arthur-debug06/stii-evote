<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates List - {{ $activeSchoolYear->school_year }} {{ $activeSchoolYear->semester }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
        }
        
        .header h2 {
            color: #64748b;
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        
        .summary {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            color: #1e293b;
            font-size: 16px;
        }
        
        .summary p {
            margin: 5px 0;
            color: #64748b;
            font-size: 14px;
        }
        
        .position-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .position-header {
            background-color: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }
        
        .position-title {
            color: #1e40af;
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .position-count {
            color: #1d4ed8;
            font-size: 14px;
            margin: 0;
        }
        
        .candidates-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .candidates-table th {
            background-color: #f1f5f9;
            color: #334155;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #cbd5e1;
            font-size: 12px;
            font-weight: bold;
        }
        
        .candidates-table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
            vertical-align: top;
        }
        
        .candidates-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .candidates-table tr:hover {
            background-color: #f1f5f9;
        }
        
        .candidate-name {
            font-weight: bold;
            color: #1e293b;
        }
        
        .student-id {
            color: #64748b;
            font-family: monospace;
        }
        
        .course {
            color: #475569;
        }
        
        .email {
            color: #2563eb;
            font-size: 10px;
        }
        
        .status-badge {
            display: inline-block;
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #64748b;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
        
        .no-candidates {
            text-align: center;
            color: #64748b;
            font-style: italic;
            padding: 20px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .position-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ELECTION RESULTS</h1>
        <h2>{{ $activeSchoolYear->school_year }} - {{ $activeSchoolYear->semester }}</h2>
    </div>

    <!-- Re-add a clear page title for printed output -->
    <div style="text-align:center; margin-bottom:18px;">
        <h3 style="margin:0; color:#1e293b; font-size:18px;">Candidates List</h3>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Candidates:</strong> {{ $totalCandidates ?? 0 }}</p>
        <p><strong>School Year:</strong> {{ $activeSchoolYear->school_year }}</p>
        <p><strong>Semester:</strong> {{ $activeSchoolYear->semester }}</p>
        @if(request()->has('search') && !empty(request('search')))
            <p><strong>Filtered by Search:</strong> "{{ request('search') }}"</p>
        @endif
        @if(request()->has('school_year') && !empty(request('school_year')))
            <p><strong>Filtered by School Year:</strong> Yes</p>
        @endif
        @if(request()->has('voting_exclusive') && !empty(request('voting_exclusive')))
            <p><strong>Filtered by Election:</strong> Yes</p>
        @endif
        <p><strong>Active Voting Periods:</strong> {{ $activeVotings ? $activeVotings->count() : 0 }}</p>
        @if($activeVotings && $activeVotings->count() > 0)
            @foreach($activeVotings as $voting)
                <p><strong>Voting Period:</strong> {{ \Carbon\Carbon::parse($voting->start_datetime)->format('M d, Y h:i A') }} - {{ \Carbon\Carbon::parse($voting->end_datetime)->format('M d, Y h:i A') }}</p>
            @endforeach
        @endif
        <p><strong>Generated on:</strong> {{ date('F d, Y \a\t h:i A') }}</p>
    </div>

    @if(!empty($candidatesByPosition) && count($candidatesByPosition) > 0)
        @foreach($candidatesByPosition as $positionName => $candidates)
            <div class="position-section">
                <div class="position-header">
                    <h3 class="position-title">{{ $positionName }}</h3>
                    <!-- <p class="position-count">{{ count($candidates) }} candidate(s) for this position</p> -->
                </div>

                @php
                    // Only include winners in the printed output and ensure ranking is based on number_of_vote descending
                    $sortedCandidates = collect($candidates)->where('status', 'win')->sortByDesc('number_of_vote')->values()->all();
                @endphp

                <table class="candidates-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Rank</th>
                            <th style="width: 20%;">Candidate Name</th>
                            <th style="width: 12%;">Student ID</th>
                            <th style="width: 18%;">Course</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 10%;">Votes</th>
                            <th style="width: 15%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sortedCandidates as $index => $voteCount)
                            @php
                                $student = $voteCount->student;
                                $status = $voteCount->status;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="candidate-name">
                                    {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                                    @if($student->suffix)
                                        {{ $student->suffix }}
                                    @endif
                                </td>
                                <td class="student-id">{{ $student->student_id }}</td>
                                <td class="course">{{ $student->course->course_name ?? 'N/A' }}</td>
                                <td class="email">{{ $student->email }}</td>
                                <td style="text-align: center; font-weight: bold; color: #2563eb;">{{ $voteCount->number_of_vote }}</td>
                                <td>
                                    <span class="status-badge" style="background-color: #dbeafe; color: #1e40af;">{{ strtoupper($status ?? 'UNKNOWN') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <div class="no-candidates">
            <h3>No Election Results</h3>
            <p>There are currently no candidates with vote counts for any position.</p>
        </div>
    @endif

    <div class="footer">
    <p>This document was generated automatically by stii-evote</p>
        <p>Generated on {{ date('F d, Y \a\t h:i A') }}</p>
    </div>

    <script>
        // Optional: Add any PDF viewer enhancements here
        // The PDF will open in browser preview mode
    </script>
</body>
</html>
