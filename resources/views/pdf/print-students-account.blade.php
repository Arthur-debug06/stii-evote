<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Account List - {{ $activeSchoolYear->school_year }} {{ $activeSchoolYear->semester }}</title>
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
        
        .course-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .course-header {
            background-color: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }
        
        .course-title {
            color: #1e40af;
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .course-count {
            color: #1d4ed8;
            font-size: 14px;
            margin: 0;
        }
        
        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .students-table th {
            background-color: #f1f5f9;
            color: #334155;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #cbd5e1;
            font-size: 11px;
            font-weight: bold;
        }
        
        .students-table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: top;
        }
        
        .students-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .student-name {
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
            font-size: 9px;
        }
        
        .gender-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .gender-male {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .gender-female {
            background-color: #fce7f3;
            color: #be185d;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-inactive {
            background-color: #fee2e2;
            color: #dc2626;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #64748b;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
        
        .no-students {
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
            
            .course-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>STUDENTS ACCOUNT LIST</h1>
        <h2>{{ $activeSchoolYear->school_year }} - {{ $activeSchoolYear->semester }}</h2>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Students:</strong> {{ $totalStudents }}</p>
        <p><strong>School Year:</strong> {{ $activeSchoolYear->school_year }}</p>
        <p><strong>Semester:</strong> {{ $activeSchoolYear->semester }}</p>
        <p><strong>Generated on:</strong> {{ date('F d, Y \a\t h:i A') }}</p>
    </div>

    @if(count($studentsByCourse) > 0)
        @foreach($studentsByCourse as $courseName => $students)
            <div class="course-section">
                <div class="course-header">
                    <h3 class="course-title">{{ $courseName }}</h3>
                    <p class="course-count">{{ count($students) }} student(s) in this course</p>
                </div>

                <table class="students-table">
                    <thead>
                        <tr>
                            <th style="width: 3%;">#</th>
                            <th style="width: 20%;">Student Name</th>
                            <th style="width: 12%;">Student ID</th>
                            <th style="width: 15%;">Course</th>
                            <th style="width: 12%;">Department</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 8%;">Gender</th>
                            <th style="width: 5%;">Age</th>
                            <th style="width: 5%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="student-name">
                                    {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                                    @if($student->suffix)
                                        {{ $student->suffix }}
                                    @endif
                                </td>
                                <td class="student-id">{{ $student->student_id }}</td>
                                <td class="course">{{ $student->course->course_name ?? 'N/A' }}</td>
                                <td>{{ $student->department->department_name ?? 'N/A' }}</td>
                                <td class="email">{{ $student->email }}</td>
                                <td>
                                    <span class="gender-badge {{ $student->gender === 'Male' ? 'gender-male' : 'gender-female' }}">
                                        {{ $student->gender ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $student->age ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge {{ $student->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                        {{ ucfirst($student->status ?? 'Unknown') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <div class="no-students">
            <h3>No Students Found</h3>
            <p>There are currently no students in the system.</p>
        </div>
    @endif

    <div class="footer">
    <p>This document was generated automatically by stii-evote</p>
        <p>Generated on {{ date('F d, Y \a\t h:i A') }}</p>
    </div>
</body>
</html>
