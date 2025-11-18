<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Account List</title>
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
        
        .role-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .role-header {
            background-color: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }
        
        .role-title {
            color: #1e40af;
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .role-count {
            color: #1d4ed8;
            font-size: 14px;
            margin: 0;
        }
        
        .admins-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .admins-table th {
            background-color: #f1f5f9;
            color: #334155;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #cbd5e1;
            font-size: 11px;
            font-weight: bold;
        }
        
        .admins-table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: top;
        }
        
        .admins-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .admin-name {
            font-weight: bold;
            color: #1e293b;
        }
        
        .admin-id {
            color: #64748b;
            font-family: monospace;
        }
        
        .email {
            color: #2563eb;
            font-size: 9px;
        }
        
        .role-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
            background-color: #e9d5ff;
            color: #7c3aed;
        }
        
        .online-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .online-yes {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .online-no {
            background-color: #f3f4f6;
            color: #6b7280;
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
        
        .no-admins {
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
            
            .role-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ADMIN ACCOUNT LIST</h1>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Admins:</strong> {{ $totalAdmins }}</p>
        <p><strong>Generated on:</strong> {{ date('F d, Y \a\t h:i A') }}</p>
    </div>

    @if(count($adminsByRole) > 0)
        @foreach($adminsByRole as $roleName => $admins)
            <div class="role-section">
                <div class="role-header">
                    <h3 class="role-title">{{ $roleName }}</h3>
                    <p class="role-count">{{ count($admins) }} admin(s) with this role</p>
                </div>

                <table class="admins-table">
                    <thead>
                        <tr>
                            <th style="width: 3%;">#</th>
                            <th style="width: 25%;">Admin Name</th>
                            <th style="width: 10%;">Admin ID</th>
                            <th style="width: 25%;">Email</th>
                            <th style="width: 12%;">Role</th>
                            <th style="width: 10%;">Online</th>
                            <th style="width: 15%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $index => $admin)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="admin-name">{{ $admin->name }}</td>
                                <td class="admin-id">{{ $admin->id }}</td>
                                <td class="email">{{ $admin->email }}</td>
                                <td>
                                    <span class="role-badge">
                                        {{ ucfirst($admin->role ?? 'Admin') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="online-badge {{ $admin->is_online ? 'online-yes' : 'online-no' }}">
                                        {{ $admin->is_online ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $admin->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                        {{ ucfirst($admin->status ?? 'Unknown') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <div class="no-admins">
            <h3>No Admins Found</h3>
            <p>There are currently no admins in the system.</p>
        </div>
    @endif

    <div class="footer">
    <p>This document was generated automatically by stii-evote</p>
        <p>Generated on {{ date('F d, Y \a\t h:i A') }}</p>
    </div>
</body>
</html>
