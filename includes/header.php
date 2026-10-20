<?php
ob_start();
// ==========================================
// header.php - Modern Application Shell
// Sidebar Navigation + Dynamic Topbar
// ==========================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Authentication guard
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
$admin_name   = $_SESSION['admin_username'] ?? 'Administrator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' — EduManage Pro' : 'Student Management System'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="app-wrapper">
    <!-- ===== MODERN SIDEBAR ===== -->
    <aside class="sidebar" id="sidebarNav">
        <div class="sidebar-brand">
            <div class="brand-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
            </div>
            <div class="brand-text">
                <h2>EduManage</h2>
                <span>Academic Portal</span>
            </div>
        </div>

        <div class="sidebar-nav-container">
            <div class="nav-section-title">Navigation</div>
            <ul class="sidebar-nav">
                <li>
                    <a href="dashboard.php" class="<?php echo ($current_page === 'dashboard.php') ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="9" x="3" y="3" rx="1"/>
                            <rect width="7" height="5" x="14" y="3" rx="1"/>
                            <rect width="7" height="9" x="14" y="12" rx="1"/>
                            <rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="students.php" class="<?php echo ($current_page === 'students.php') ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        All Students
                    </a>
                </li>
                <li>
                    <a href="add_student.php" class="<?php echo ($current_page === 'add_student.php') ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="16"/>
                            <line x1="8" y1="12" x2="16" y2="12"/>
                        </svg>
                        Add Student
                    </a>
                </li>
            </ul>

            <div class="nav-section-title" style="margin-top: 24px;">System</div>
            <ul class="sidebar-nav">
                <li>
                    <a href="logout.php">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Sign Out
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <div class="admin-card-mini">
                <div class="admin-avatar">
                    <?php echo strtoupper(substr($admin_name, 0, 2)); ?>
                </div>
                <div class="admin-details">
                    <div class="admin-name"><?php echo htmlspecialchars($admin_name); ?></div>
                    <div class="admin-role">System Admin</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT REGION ===== -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <h2 class="page-heading"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h2>
                <span class="topbar-badge"><?php echo date('M Y'); ?></span>
            </div>

            <div class="topbar-right">
                <?php if ($current_page !== 'add_student.php'): ?>
                    <a href="add_student.php" class="quick-action-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        New Student
                    </a>
                <?php endif; ?>

                <a href="logout.php" class="logout-icon-btn" title="Sign Out">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </a>
            </div>
        </header>

        <!-- Page View Container -->
        <main class="page-content">
