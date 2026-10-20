<?php
// ==========================================
// login.php - Admin Login Page
// Modern, executive authentication portal
// ==========================================

session_start();

// If admin is already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$admin_username = "admin";
$admin_password = "admin123";

$error = "";

// Handle login POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username']  = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Student Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-screen-wrapper">
    <!-- Left Showcase Panel -->
    <div class="login-left-showcase">
        <div class="showcase-brand">
            <div class="showcase-brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
            </div>
            <h1>EduManage Pro</h1>
        </div>

        <div class="showcase-main">
            <div class="showcase-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>
                </svg>
                BCA College Demonstration System
            </div>
            <h2 class="showcase-heading">
                Intelligent Student <span>Records & Academic</span> Management
            </h2>
            <p class="showcase-subtext">
                Streamline enrollment, monitor course metrics, and manage student lifecycle operations with clean real-time insights.
            </p>

            <div class="feature-pill-list">
                <div class="feature-pill-item">
                    <div class="feature-check-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span>Complete CRUD operations with live MySQL database sync</span>
                </div>
                <div class="feature-pill-item">
                    <div class="feature-check-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span>Search, filter, and department-level distribution analytics</span>
                </div>
                <div class="feature-pill-item">
                    <div class="feature-check-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span>Responsive interface tailored for viva presentation and evaluation</span>
                </div>
            </div>
        </div>

        <div class="showcase-footer">
            <span>Core PHP 8.x • MySQL • Apache (XAMPP)</span>
            <span>v2.4 Academic Edition</span>
        </div>
    </div>

    <!-- Right Login Card Panel -->
    <div class="login-right-panel">
        <div class="login-glass-card">
            <div class="login-card-head">
                <h2>Welcome Back</h2>
                <p>Sign in with your administrative credentials</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="modern-alert alert-danger-v2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" id="loginForm">
                <div class="login-field">
                    <label for="username">Admin Username</label>
                    <div class="login-input-group">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" id="username" name="username" class="login-input" placeholder="e.g. admin" required autocomplete="username">
                    </div>
                </div>

                <div class="login-field">
                    <label for="password">Password</label>
                    <div class="login-input-group">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input type="password" id="password" name="password" class="login-input" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn-v2 btn-v2-primary btn-v2-full" style="margin-top: 10px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Sign In to Dashboard
                </button>
            </form>

            <!-- 1-Click Auto-fill Demo Box -->
            <div class="demo-credentials-badge" onclick="fillDemo()" title="Click to auto-fill credentials">
                <div class="demo-badge-text">
                    Demo Credentials: <strong>admin</strong> / <strong>admin123</strong>
                </div>
                <span class="demo-btn-copy">Quick Fill</span>
            </div>
        </div>
    </div>
</div>

<script>
function fillDemo() {
    document.getElementById('username').value = 'admin';
    document.getElementById('password').value = 'admin123';
}
</script>

</body>
</html>
