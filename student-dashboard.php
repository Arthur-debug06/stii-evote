<?php
/**
 * Student Dashboard
 * Dashboard for registered students to view elections and apply for candidacy
 */

session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'classes/Auth.php';
require_once 'classes/Student.php';

// Check if user is logged in and has student role
if (!isLoggedIn() || $_SESSION['user_role'] !== 'student') {
    redirect('login.php');
}

$auth = new Auth();
$student = new Student();

// Get current student information
$current_student = $student->getStudentById($_SESSION['user_id']);

// Get dashboard statistics
$db = Database::getInstance();
$stats = $db->fetch("
    SELECT 
        (SELECT COUNT(*) FROM applied_candidacy WHERE student_id = ?) as my_applications,
        (SELECT COUNT(*) FROM applied_candidacy WHERE student_id = ? AND status = 'approved') as approved_applications,
        (SELECT COUNT(*) FROM position WHERE status = 'active') as available_positions,
        (SELECT COUNT(*) FROM students WHERE status = 'active') as total_voters
", [$_SESSION['user_id'], $_SESSION['user_id']]);

// Get my candidacy applications
$my_applications = $db->fetchAll("
    SELECT ac.*, p.position_name, p.description as position_description
    FROM applied_candidacy ac 
    JOIN position p ON ac.position_id = p.id 
    WHERE ac.student_id = ?
    ORDER BY ac.created_at DESC 
    LIMIT 5
", [$_SESSION['user_id']]);

// Get available positions for application
$available_positions = $db->fetchAll("
    SELECT p.*, 
           (SELECT COUNT(*) FROM applied_candidacy ac WHERE ac.position_id = p.id AND ac.student_id = ?) as applied
    FROM position p 
    WHERE p.status = 'active' 
    ORDER BY p.position_name
", [$_SESSION['user_id']]);

// Get recent announcements
$announcements = $db->fetchAll("
    SELECT * FROM announcements 
    WHERE status = 'active' 
    ORDER BY created_at DESC 
    LIMIT 3
");

// Get voting schedule
$voting_schedule = $db->fetchAll("
    SELECT * FROM election_schedule 
    WHERE activity_type IN ('voting_day', 'campaign_period') 
    AND schedule_date >= CURDATE()
    ORDER BY schedule_date ASC 
    LIMIT 3
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - <?= APP_NAME ?></title>
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
                    <a href="index.php?dashboard=student" class="nav-link active">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="my-profile.php" class="nav-link">
                        <i class="fas fa-user nav-icon"></i>
                        <span class="nav-text">My Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="apply-candidacy.php" class="nav-link">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <span class="nav-text">Apply for Candidacy</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="my-applications.php" class="nav-link">
                        <i class="fas fa-clipboard-list nav-icon"></i>
                        <span class="nav-text">My Applications</span>
                        <?php if ($stats['my_applications'] > 0): ?>
                            <span class="badge bg-info ms-auto"><?= $stats['my_applications'] ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="election-info.php" class="nav-link">
                        <i class="fas fa-info-circle nav-icon"></i>
                        <span class="nav-text">Election Information</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="vote.php" class="nav-link">
                        <i class="fas fa-check-square nav-icon"></i>
                        <span class="nav-text">Cast Vote</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="election-results.php" class="nav-link">
                        <i class="fas fa-chart-bar nav-icon"></i>
                        <span class="nav-text">Election Results</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <?php if (!empty($current_student['profile_picture'])): ?>
                        <img src="uploads/profiles/<?= htmlspecialchars($current_student['profile_picture']) ?>" alt="Profile">
                    <?php else: ?>
                        <i class="fas fa-user-circle"></i>
                    <?php endif; ?>
                </div>
                <div class="user-details">
                    <div class="user-name"><?= htmlspecialchars($current_student['first_name'] . ' ' . $current_student['last_name']) ?></div>
                    <div class="user-role"><?= htmlspecialchars($current_student['student_id']) ?></div>
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
                            <i class="fas fa-tachometer-alt me-2"></i>Student Dashboard
                        </h1>
                        <p class="page-subtitle">Welcome, <?= htmlspecialchars($current_student['first_name']) ?>!</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="header-actions">
                            <a href="apply-candidacy.php" class="btn btn-primary">
                                <i class="fas fa-file-alt me-2"></i>Apply for Candidacy
                            </a>
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
                        <div class="stat-card stat-card-info">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['my_applications']) ?></div>
                                    <div class="stat-label">My Applications</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="my-applications.php" class="stat-card-link">
                                    View Details <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card stat-card-success">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['approved_applications']) ?></div>
                                    <div class="stat-label">Approved Applications</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="my-applications.php?status=approved" class="stat-card-link">
                                    View Approved <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['available_positions']) ?></div>
                                    <div class="stat-label">Available Positions</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="apply-candidacy.php" class="stat-card-link">
                                    Apply Now <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card stat-card-warning">
                            <div class="stat-card-body">
                                <div class="stat-card-content">
                                    <div class="stat-number"><?= number_format($stats['total_voters']) ?></div>
                                    <div class="stat-label">Registered Voters</div>
                                </div>
                                <div class="stat-card-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="stat-card-footer">
                                <a href="election-info.php" class="stat-card-link">
                                    View Info <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Row -->
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <!-- Announcements -->
                        <div class="activity-card mb-4">
                            <div class="activity-card-header">
                                <h5 class="activity-card-title">
                                    <i class="fas fa-bullhorn me-2"></i>Latest Announcements
                                </h5>
                            </div>
                            <div class="activity-card-body">
                                <?php if (empty($announcements)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-bullhorn"></i>
                                        <p>No announcements at this time</p>
                                    </div>
                                <?php else: ?>
                                    <div class="announcement-list">
                                        <?php foreach ($announcements as $announcement): ?>
                                            <div class="announcement-item">
                                                <div class="announcement-header">
                                                    <h6 class="announcement-title">
                                                        <?= htmlspecialchars($announcement['title']) ?>
                                                    </h6>
                                                    <span class="announcement-date">
                                                        <?= date('M d, Y', strtotime($announcement['created_at'])) ?>
                                                    </span>
                                                </div>
                                                <div class="announcement-content">
                                                    <?= nl2br(htmlspecialchars($announcement['content'])) ?>
                                                </div>
                                                <?php if ($announcement['priority'] === 'urgent'): ?>
                                                    <span class="badge bg-danger">Urgent</span>
                                                <?php elseif ($announcement['priority'] === 'high'): ?>
                                                    <span class="badge bg-warning">High Priority</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- My Applications -->
                        <div class="activity-card">
                            <div class="activity-card-header">
                                <h5 class="activity-card-title">
                                    <i class="fas fa-clipboard-list me-2"></i>My Recent Applications
                                </h5>
                                <a href="my-applications.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="activity-card-body">
                                <?php if (empty($my_applications)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-clipboard"></i>
                                        <p>You haven't submitted any applications yet</p>
                                        <a href="apply-candidacy.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>Apply for Candidacy
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="activity-list">
                                        <?php foreach ($my_applications as $application): ?>
                                            <div class="activity-item">
                                                <div class="activity-avatar">
                                                    <?php if ($application['status'] === 'approved'): ?>
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    <?php elseif ($application['status'] === 'rejected'): ?>
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-clock text-warning"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="activity-content">
                                                    <div class="activity-title">
                                                        <?= htmlspecialchars($application['position_name']) ?>
                                                    </div>
                                                    <div class="activity-subtitle">
                                                        Status: <span class="badge bg-<?= $application['status'] === 'approved' ? 'success' : ($application['status'] === 'rejected' ? 'danger' : 'warning') ?>">
                                                            <?= ucfirst($application['status']) ?>
                                                        </span>
                                                    </div>
                                                    <div class="activity-time">
                                                        Applied <?= timeAgo($application['created_at']) ?>
                                                    </div>
                                                </div>
                                                <div class="activity-actions">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewApplication(<?= $application['id'] ?>)">
                                                        <i class="fas fa-eye"></i>
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
                        <div class="activity-card mb-4">
                            <div class="activity-card-header">
                                <h5 class="activity-card-title">
                                    <i class="fas fa-calendar-alt me-2"></i>Election Schedule
                                </h5>
                            </div>
                            <div class="activity-card-body">
                                <?php if (empty($voting_schedule)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-calendar-times"></i>
                                        <p>No scheduled activities</p>
                                    </div>
                                <?php else: ?>
                                    <div class="timeline">
                                        <?php foreach ($voting_schedule as $schedule): ?>
                                            <div class="timeline-item">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <div class="timeline-title">
                                                        <?= htmlspecialchars($schedule['activity_name']) ?>
                                                    </div>
                                                    <div class="timeline-date">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        <?= date('M d, Y g:i A', strtotime($schedule['schedule_date'])) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Available Positions -->
                        <div class="activity-card">
                            <div class="activity-card-header">
                                <h5 class="activity-card-title">
                                    <i class="fas fa-sitemap me-2"></i>Available Positions
                                </h5>
                                <a href="apply-candidacy.php" class="btn btn-sm btn-outline-success">Apply Now</a>
                            </div>
                            <div class="activity-card-body">
                                <?php if (empty($available_positions)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-sitemap"></i>
                                        <p>No positions available</p>
                                    </div>
                                <?php else: ?>
                                    <div class="position-list">
                                        <?php foreach (array_slice($available_positions, 0, 5) as $position): ?>
                                            <div class="position-item">
                                                <div class="position-name">
                                                    <?= htmlspecialchars($position['position_name']) ?>
                                                </div>
                                                <div class="position-actions">
                                                    <?php if ($position['applied'] > 0): ?>
                                                        <span class="badge bg-info">Applied</span>
                                                    <?php else: ?>
                                                        <a href="apply-candidacy.php?position=<?= $position['id'] ?>" class="btn btn-sm btn-success">
                                                            Apply
                                                        </a>
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
                                        <a href="apply-candidacy.php" class="quick-action">
                                            <div class="quick-action-icon bg-primary">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">Apply for Candidacy</div>
                                                <div class="quick-action-subtitle">Submit your application</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="my-profile.php" class="quick-action">
                                            <div class="quick-action-icon bg-info">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">Update Profile</div>
                                                <div class="quick-action-subtitle">Manage your information</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="vote.php" class="quick-action">
                                            <div class="quick-action-icon bg-success">
                                                <i class="fas fa-check-square"></i>
                                            </div>
                                            <div class="quick-action-content">
                                                <div class="quick-action-title">Cast Your Vote</div>
                                                <div class="quick-action-subtitle">Participate in elections</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="election-results.php" class="quick-action">
                                            <div class="quick-action-icon bg-warning">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            STIIApp.init();
        });

        // View application details
        function viewApplication(applicationId) {
            window.location.href = 'my-applications.php?view=' + applicationId;
        }
    </script>
</body>
</html>