<?php
/**
 * STSG Dashboard
 * Student Supreme Government dashboard for managing elections
 */

session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'classes/Auth.php';
require_once 'classes/Student.php';

// Check if user is logged in and has STSG role
if (!isLoggedIn() || $_SESSION['user_role'] !== 'stsg') {
    redirect('login.php');
}

$auth = new Auth();
$student = new Student();

// Get dashboard statistics
$db = Database::getInstance();
$stats = $db->fetch("
    SELECT 
        (SELECT COUNT(*) FROM students WHERE status = 'active') as total_students,
        (SELECT COUNT(*) FROM students WHERE status = 'pending') as pending_registrations,
        (SELECT COUNT(*) FROM applied_candidacy WHERE status = 'approved') as approved_candidates,
        (SELECT COUNT(*) FROM applied_candidacy WHERE status = 'pending') as pending_candidates
");

// Get recent activities
$recent_students = $db->fetchAll("
    SELECT s.*, c.course_name, d.department_name 
    FROM students s 
    LEFT JOIN course c ON s.course_id = c.id 
    LEFT JOIN department d ON s.department_id = d.id 
    WHERE s.status = 'pending' 
    ORDER BY s.created_at DESC 
    LIMIT 5
");

$recent_candidates = $db->fetchAll("
    SELECT ac.*, s.first_name, s.last_name, s.student_id, p.position_name 
    FROM applied_candidacy ac 
    JOIN students s ON ac.student_id = s.id 
    JOIN position p ON ac.position_id = p.id 
    WHERE ac.status = 'pending' 
    ORDER BY ac.created_at DESC 
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STSG Dashboard - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar sidebar-gradient" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <i class="fas fa-vote-yea sidebar-brand-icon"></i>
                <span class="sidebar-brand-text">E-Vote System</span>
            </div>
            <button type="button" class="sidebar-toggle d-lg-none" id="sidebarToggle">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="index.php?dashboard=stsg" class="nav-link active">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="student-management.php" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <span class="nav-text">Student Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="registration-requests.php" class="nav-link">
                        <i class="fas fa-user-clock nav-icon"></i>
                        <span class="nav-text">Registration Requests</span>
                        <?php if ($stats['pending_registrations'] > 0): ?>
                            <span class="badge bg-warning ms-auto"><?= $stats['pending_registrations'] ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="candidacy-management.php" class="nav-link">
                        <i class="fas fa-user-tie nav-icon"></i>
                        <span class="nav-text">Candidacy Management</span>
                        <?php if ($stats['pending_candidates'] > 0): ?>
                            <span class="badge bg-warning ms-auto"><?= $stats['pending_candidates'] ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="election-management.php" class="nav-link">
                        <i class="fas fa-poll nav-icon"></i>
                        <span class="nav-text">Election Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="voting-results.php" class="nav-link">
                        <i class="fas fa-chart-bar nav-icon"></i>
                        <span class="nav-text">Voting Results</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="settings.php" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-details">
                    <div class="user-name"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
                    <div class="user-role">STSG Administrator</div>
                </div>
            </div>
            <a href="logout.php" class="btn btn-outline-light btn-sm w-100 mt-2">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="page-title">
                            <i class="fas fa-tachometer-alt me-2"></i>STSG Dashboard
                        </h1>
                        <p class="page-subtitle">Student Supreme Government Management Portal</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="header-actions">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#announcementModal">
                                <i class="fas fa-bullhorn me-2"></i>New Announcement
                            </button>
                            <button type="button" class="btn btn-outline-secondary ms-2 d-lg-none" id="mobileSidebarToggle">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['total_students']) ?></div>
                                    <div class="stat-label">Total Students</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="student-management.php" class="stat-card-link">
                                    View Details <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card stat-card-warning">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['pending_registrations']) ?></div>
                                    <div class="stat-label">Pending Registrations</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="registration-requests.php" class="stat-card-link">
                                    Review Now <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card stat-card-success">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['approved_candidates']) ?></div>
                                    <div class="stat-label">Approved Candidates</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="candidacy-management.php" class="stat-card-link">
                                    View Candidates <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card stat-card-info">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['pending_candidates']) ?></div>
                                    <div class="stat-label">Pending Candidates</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="candidacy-management.php?filter=pending" class="stat-card-link">
                                    Review Applications <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="activity-card">
                            <div class="activity-card-header">
                                <h5 class="activity-card-title">
                                    <i class="fas fa-user-plus me-2"></i>Recent Registration Requests
                                </h5>
                                <a href="registration-requests.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="activity-card-body">
                                <?php if (empty($recent_students)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-inbox"></i>
                                        <p>No pending registration requests</p>
                                    </div>
                                <?php else: ?>
                                    <div class="activity-list">
                                        <?php foreach ($recent_students as $student): ?>
                                            <div class="activity-item">
                                                <div class="activity-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="activity-content">
                                                    <div class="activity-title">
                                                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                                    </div>
                                                    <div class="activity-subtitle">
                                                        <?= htmlspecialchars($student['student_id']) ?> • 
                                                        <?= htmlspecialchars($student['course_name']) ?>
                                                    </div>
                                                    <div class="activity-time">
                                                        <?= timeAgo($student['created_at']) ?>
                                                    </div>
                                                </div>
                                                <div class="activity-actions">
                                                    <button class="btn btn-sm btn-success" onclick="approveStudent(<?= $student['id'] ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="rejectStudent(<?= $student['id'] ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="activity-card">
                            <div class="activity-card-header">
                                <h5 class="activity-card-title">
                                    <i class="fas fa-user-tie me-2"></i>Recent Candidacy Applications
                                </h5>
                                <a href="candidacy-management.php?filter=pending" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="activity-card-body">
                                <?php if (empty($recent_candidates)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-inbox"></i>
                                        <p>No pending candidacy applications</p>
                                    </div>
                                <?php else: ?>
                                    <div class="activity-list">
                                        <?php foreach ($recent_candidates as $candidate): ?>
                                            <div class="activity-item">
                                                <div class="activity-avatar">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <div class="activity-content">
                                                    <div class="activity-title">
                                                        <?= htmlspecialchars($candidate['first_name'] . ' ' . $candidate['last_name']) ?>
                                                    </div>
                                                    <div class="activity-subtitle">
                                                        <?= htmlspecialchars($candidate['student_id']) ?> • 
                                                        <?= htmlspecialchars($candidate['position_name']) ?>
                                                    </div>
                                                    <div class="activity-time">
                                                        <?= timeAgo($candidate['created_at']) ?>
                                                    </div>
                                                </div>
                                                <div class="activity-actions">
                                                    <button class="btn btn-sm btn-success" onclick="approveCandidate(<?= $candidate['id'] ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="rejectCandidate(<?= $candidate['id'] ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-12">
                        <div class="quick-actions-card">
                            <div class="quick-actions-header">
                                <h5 class="quick-actions-title">
                                    <i class="fas fa-rocket me-2"></i>Quick Actions
                                </h5>
                            </div>
                            <div class="quick-actions-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <a href="registration-requests.php" class="quick-action">
                                            <div class="quick-action-icon bg-warning">
                                                <i class="fas fa-user-clock"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">Review Registrations</div>
                                                <div class="quick-action-subtitle">Approve or reject student accounts</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="candidacy-management.php" class="quick-action">
                                            <div class="quick-action-icon bg-info">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">Manage Candidates</div>
                                                <div class="quick-action-subtitle">Review candidacy applications</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="election-management.php" class="quick-action">
                                            <div class="quick-action-icon bg-success">
                                                <i class="fas fa-poll"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">Election Setup</div>
                                                <div class="quick-action-subtitle">Configure voting parameters</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="voting-results.php" class="quick-action">
                                            <div class="quick-action-icon bg-primary">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">View Results</div>
                                                <div class="quick-action-subtitle">Check election outcomes</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcement Modal -->
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="announcementModalLabel">
                        <i class="fas fa-bullhorn me-2"></i>New Announcement
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="announcementForm">
                        <div class="mb-3">
                            <label for="announcementTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="announcementTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="announcementContent" class="form-label">Content</label>
                            <textarea class="form-control" id="announcementContent" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="announcementPriority" class="form-label">Priority</label>
                            <select class="form-control" id="announcementPriority">
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="publishAnnouncement()">
                        <i class="fas fa-bullhorn me-2"></i>Publish
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            STIIApp.init();
        });

        // Approve student registration
        function approveStudent(studentId) {
            if (confirm('Are you sure you want to approve this student registration?')) {
                fetch('api/approve-student.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ student_id: studentId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        STIIApp.showNotification('Student registration approved successfully', 'success');
                        location.reload();
                    } else {
                        STIIApp.showNotification(data.message || 'Failed to approve registration', 'error');
                    }
                });
            }
        }

        // Reject student registration
        function rejectStudent(studentId) {
            const reason = prompt('Please provide a reason for rejection:');
            if (reason) {
                fetch('api/reject-student.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ student_id: studentId, reason: reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        STIIApp.showNotification('Student registration rejected', 'success');
                        location.reload();
                    } else {
                        STIIApp.showNotification(data.message || 'Failed to reject registration', 'error');
                    }
                });
            }
        }

        // Approve candidate application
        function approveCandidate(candidateId) {
            if (confirm('Are you sure you want to approve this candidacy application?')) {
                fetch('api/approve-candidate.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ candidate_id: candidateId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        STIIApp.showNotification('Candidacy application approved successfully', 'success');
                        location.reload();
                    } else {
                        STIIApp.showNotification(data.message || 'Failed to approve application', 'error');
                    }
                });
            }
        }

        // Reject candidate application
        function rejectCandidate(candidateId) {
            const reason = prompt('Please provide a reason for rejection:');
            if (reason) {
                fetch('api/reject-candidate.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ candidate_id: candidateId, reason: reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        STIIApp.showNotification('Candidacy application rejected', 'success');
                        location.reload();
                    } else {
                        STIIApp.showNotification(data.message || 'Failed to reject application', 'error');
                    }
                });
            }
        }

        // Publish announcement
        function publishAnnouncement() {
            const form = document.getElementById('announcementForm');
            
            const data = {
                title: document.getElementById('announcementTitle').value,
                content: document.getElementById('announcementContent').value,
                priority: document.getElementById('announcementPriority').value
            };

            if (!data.title || !data.content) {
                STIIApp.showNotification('Please fill in all required fields', 'error');
                return;
            }

            fetch('api/create-announcement.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    STIIApp.showNotification('Announcement published successfully', 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('announcementModal'));
                    modal.hide();
                    form.reset();
                } else {
                    STIIApp.showNotification(data.message || 'Failed to publish announcement', 'error');
                }
            });
        }
    </script>
</body>
</html>