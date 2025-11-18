<?php
/**
 * SELECOM Dashboard
 * Selection Committee dashboard for managing election processes
 */

session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'classes/Auth.php';
require_once 'classes/Student.php';

// Check if user is logged in and has SELECOM role
if (!isLoggedIn() || $_SESSION['user_role'] !== 'selecom') {
    redirect('login.php');
}

$auth = new Auth();
$student = new Student();

// Get dashboard statistics
$db = Database::getInstance();
$stats = $db->fetch("
    SELECT 
        (SELECT COUNT(*) FROM applied_candidacy WHERE status = 'approved') as total_candidates,
        (SELECT COUNT(*) FROM applied_candidacy WHERE status = 'pending') as pending_applications,
        (SELECT COUNT(*) FROM position WHERE status = 'active') as total_positions,
        (SELECT COUNT(*) FROM students WHERE status = 'active') as registered_voters
");

// Get recent candidacy applications
$recent_applications = $db->fetchAll("
    SELECT ac.*, s.first_name, s.last_name, s.student_id, p.position_name, c.course_name
    FROM applied_candidacy ac 
    JOIN students s ON ac.student_id = s.id 
    JOIN position p ON ac.position_id = p.id 
    JOIN course c ON s.course_id = c.id
    WHERE ac.status = 'pending'
    ORDER BY ac.created_at DESC 
    LIMIT 5
");

// Get upcoming election activities
$upcoming_activities = $db->fetchAll("
    SELECT * FROM election_schedule 
    WHERE schedule_date >= CURDATE() 
    ORDER BY schedule_date ASC 
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SELECOM Dashboard - <?= APP_NAME ?></title>
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
                    <a href="index.php?dashboard=selecom" class="nav-link active">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="election-schedule.php" class="nav-link">
                        <i class="fas fa-calendar-alt nav-icon"></i>
                        <span class="nav-text">Election Schedule</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="candidacy-review.php" class="nav-link">
                        <i class="fas fa-clipboard-check nav-icon"></i>
                        <span class="nav-text">Candidacy Review</span>
                        <?php if ($stats['pending_applications'] > 0): ?>
                            <span class="badge bg-warning ms-auto"><?= $stats['pending_applications'] ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="position-management.php" class="nav-link">
                        <i class="fas fa-sitemap nav-icon"></i>
                        <span class="nav-text">Position Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="candidate-profiles.php" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <span class="nav-text">Candidate Profiles</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="voting-configuration.php" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <span class="nav-text">Voting Configuration</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="election-monitoring.php" class="nav-link">
                        <i class="fas fa-chart-line nav-icon"></i>
                        <span class="nav-text">Election Monitoring</span>
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
                    <div class="user-role">SELECOM Officer</div>
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
                            <i class="fas fa-tachometer-alt me-2"></i>SELECOM Dashboard
                        </h1>
                        <p class="page-subtitle">Selection Committee Control Center</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="header-actions">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                                <i class="fas fa-calendar-plus me-2"></i>Schedule Activity
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
                        <div class="stat-card stat-card-success">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['total_candidates']) ?></div>
                                    <div class="stat-label">Approved Candidates</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="candidate-profiles.php" class="stat-card-link">
                                    View Candidates <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card stat-card-warning">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['pending_applications']) ?></div>
                                    <div class="stat-label">Pending Applications</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="candidacy-review.php" class="stat-card-link">
                                    Review Now <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card stat-card-info">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['total_positions']) ?></div>
                                    <div class="stat-label">Available Positions</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="position-management.php" class="stat-card-link">
                                    Manage Positions <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['registered_voters']) ?></div>
                                    <div class="stat-label">Registered Voters</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="voter-registry.php" class="stat-card-link">
                                    View Registry <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Row -->
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <!-- Candidacy Applications -->
                        <div class="activity-card">
                            <div class="activity-card-header">
                                <h5 class="activity-card-title">
                                    <i class="fas fa-clipboard-list me-2"></i>Recent Candidacy Applications
                                </h5>
                                <a href="candidacy-review.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="activity-card-body">
                                <?php if (empty($recent_applications)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-inbox"></i>
                                        <p>No pending candidacy applications</p>
                                    </div>
                                <?php else: ?>
                                    <div class="activity-list">
                                        <?php foreach ($recent_applications as $application): ?>
                                            <div class="activity-item">
                                                <div class="activity-avatar">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <div class="activity-content">
                                                    <div class="activity-title">
                                                        <?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?>
                                                    </div>
                                                    <div class="activity-subtitle">
                                                        <?= htmlspecialchars($application['student_id']) ?> • 
                                                        <?= htmlspecialchars($application['position_name']) ?> • 
                                                        <?= htmlspecialchars($application['course_name']) ?>
                                                    </div>
                                                    <div class="activity-time">
                                                        <?= timeAgo($application['created_at']) ?>
                                                    </div>
                                                </div>
                                                <div class="activity-actions">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewApplication(<?= $application['id'] ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-success" onclick="approveApplication(<?= $application['id'] ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="rejectApplication(<?= $application['id'] ?>)">
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
                    
                    <div class="col-lg-4 mb-4">
                        <!-- Election Schedule -->
                        <div class="activity-card">
                            <div class="activity-card-header">
                                <h5 class="activity-card-title">
                                    <i class="fas fa-calendar-alt me-2"></i>Upcoming Activities
                                </h5>
                                <a href="election-schedule.php" class="btn btn-sm btn-outline-success">View Schedule</a>
                            </div>
                            <div class="activity-card-body">
                                <?php if (empty($upcoming_activities)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-calendar-times"></i>
                                        <p>No scheduled activities</p>
                                    </div>
                                <?php else: ?>
                                    <div class="timeline">
                                        <?php foreach ($upcoming_activities as $activity): ?>
                                            <div class="timeline-item">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <div class="timeline-title">
                                                        <?= htmlspecialchars($activity['activity_name']) ?>
                                                    </div>
                                                    <div class="timeline-date">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        <?= date('M d, Y g:i A', strtotime($activity['schedule_date'])) ?>
                                                    </div>
                                                    <?php if (!empty($activity['description'])): ?>
                                                        <div class="timeline-description">
                                                            <?= htmlspecialchars($activity['description']) ?>
                                                        </div>
                                                    <?php endif; ?>
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
                                        <a href="candidacy-review.php" class="quick-action">
                                            <div class="quick-action-icon bg-warning">
                                                <i class="fas fa-clipboard-check"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">Review Applications</div>
                                                <div class="quick-action-subtitle">Process candidacy submissions</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="position-management.php" class="quick-action">
                                            <div class="quick-action-icon bg-info">
                                                <i class="fas fa-sitemap"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">Manage Positions</div>
                                                <div class="quick-action-subtitle">Configure election positions</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="election-schedule.php" class="quick-action">
                                            <div class="quick-action-icon bg-success">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">Schedule Events</div>
                                                <div class="quick-action-subtitle">Plan election activities</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="voting-configuration.php" class="quick-action">
                                            <div class="quick-action-icon bg-primary">
                                                <i class="fas fa-cogs"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">Configure Voting</div>
                                                <div class="quick-action-subtitle">Set voting parameters</div>
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

    <!-- Schedule Activity Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">
                        <i class="fas fa-calendar-plus me-2"></i>Schedule Activity
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="scheduleForm">
                        <div class="mb-3">
                            <label for="activityName" class="form-label">Activity Name</label>
                            <input type="text" class="form-control" id="activityName" required>
                        </div>
                        <div class="mb-3">
                            <label for="activityDate" class="form-label">Date & Time</label>
                            <input type="datetime-local" class="form-control" id="activityDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="activityType" class="form-label">Activity Type</label>
                            <select class="form-control" id="activityType" required>
                                <option value="">Select Type</option>
                                <option value="candidacy_filing">Candidacy Filing</option>
                                <option value="campaign_period">Campaign Period</option>
                                <option value="voting_day">Voting Day</option>
                                <option value="canvassing">Canvassing</option>
                                <option value="proclamation">Proclamation</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="activityDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="activityDescription" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="scheduleActivity()">
                        <i class="fas fa-calendar-plus me-2"></i>Schedule
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

        // View application details
        function viewApplication(applicationId) {
            window.location.href = 'candidacy-review.php?view=' + applicationId;
        }

        // Approve application
        function approveApplication(applicationId) {
            if (confirm('Are you sure you want to approve this candidacy application?')) {
                fetch('api/approve-candidacy.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ application_id: applicationId })
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

        // Reject application
        function rejectApplication(applicationId) {
            const reason = prompt('Please provide a reason for rejection:');
            if (reason) {
                fetch('api/reject-candidacy.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ application_id: applicationId, reason: reason })
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

        // Schedule activity
        function scheduleActivity() {
            const form = document.getElementById('scheduleForm');
            
            const data = {
                activity_name: document.getElementById('activityName').value,
                schedule_date: document.getElementById('activityDate').value,
                activity_type: document.getElementById('activityType').value,
                description: document.getElementById('activityDescription').value
            };

            if (!data.activity_name || !data.schedule_date || !data.activity_type) {
                STIIApp.showNotification('Please fill in all required fields', 'error');
                return;
            }

            fetch('api/schedule-activity.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    STIIApp.showNotification('Activity scheduled successfully', 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('scheduleModal'));
                    modal.hide();
                    form.reset();
                    location.reload();
                } else {
                    STIIApp.showNotification(data.message || 'Failed to schedule activity', 'error');
                }
            });
        }
    </script>
</body>
</html>