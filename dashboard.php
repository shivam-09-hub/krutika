<?php
// ==========================================
// dashboard.php - Executive Dashboard
// High-impact statistics & analytics view
// ==========================================

$page_title = "Academic Dashboard";
include 'includes/header.php';
include 'config/database.php';

// Total enrolled students
$result_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$total_students = mysqli_fetch_assoc($result_total)['total'] ?? 0;

// Unique courses
$result_courses = mysqli_query($conn, "SELECT COUNT(DISTINCT course) AS total FROM students");
$total_courses = mysqli_fetch_assoc($result_courses)['total'] ?? 0;

// Unique departments
$result_depts = mysqli_query($conn, "SELECT COUNT(DISTINCT department) AS total FROM students");
$total_departments = mysqli_fetch_assoc($result_depts)['total'] ?? 0;

// Department breakdown for visual progress widgets
$dept_counts = [];
$res_dept_list = mysqli_query($conn, "SELECT department, COUNT(*) as cnt FROM students GROUP BY department ORDER BY cnt DESC");
if ($res_dept_list) {
    while ($r = mysqli_fetch_assoc($res_dept_list)) {
        $dept_counts[] = $r;
    }
}

// Gender breakdown
$male_count = 0;
$female_count = 0;
$res_gender = mysqli_query($conn, "SELECT gender, COUNT(*) as cnt FROM students GROUP BY gender");
if ($res_gender) {
    while ($g = mysqli_fetch_assoc($res_gender)) {
        if ($g['gender'] === 'Male') $male_count = $g['cnt'];
        if ($g['gender'] === 'Female') $female_count = $g['cnt'];
    }
}

// Recent 6 students
$result_recent = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC LIMIT 6");

// Greeting based on time
$hour = (int)date('H');
if ($hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour < 17) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}

// Avatar color generator function based on name
function getAvatarGradient($name) {
    $gradients = [
        'linear-gradient(135deg, #6366f1 0%, #4338ca 100%)',
        'linear-gradient(135deg, #ec4899 0%, #be185d 100%)',
        'linear-gradient(135deg, #10b981 0%, #047857 100%)',
        'linear-gradient(135deg, #f59e0b 0%, #b45309 100%)',
        'linear-gradient(135deg, #06b6d4 0%, #0e7490 100%)',
        'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)'
    ];
    $idx = abs(crc32($name)) % count($gradients);
    return $gradients[$idx];
}

function getInitials($name) {
    $parts = explode(' ', trim($name));
    if (count($parts) >= 2) {
        return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
    }
    return strtoupper(substr($name, 0, 2));
}
?>

<!-- Welcome Greeting Banner -->
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.5px;">
        <?php echo $greeting; ?>, <?php echo htmlspecialchars($admin_name); ?> 👋
    </h1>
    <p style="font-size: 14px; color: var(--slate-500); margin-top: 4px;">
        Here is the latest snapshot of student enrollments and academic distributions today.
    </p>
</div>

<!-- ===== 4 KPI METRIC CARDS ===== -->
<div class="stats-grid">
    <!-- Total Students -->
    <div class="stat-card-v2 primary">
        <div class="stat-info-wrap">
            <span class="stat-label-text">Total Students</span>
            <div class="stat-number-val"><?php echo number_format($total_students); ?></div>
            <span class="stat-trend-pill trend-indigo">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                Active Records
            </span>
        </div>
        <div class="stat-icon-bubble bubble-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
    </div>

    <!-- Active Courses -->
    <div class="stat-card-v2 success">
        <div class="stat-info-wrap">
            <span class="stat-label-text">Active Courses</span>
            <div class="stat-number-val"><?php echo number_format($total_courses); ?></div>
            <span class="stat-trend-pill trend-emerald">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                BCA, MCA, IT & More
            </span>
        </div>
        <div class="stat-icon-bubble bubble-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                <path d="M6 6h10"/>
                <path d="M6 10h10"/>
            </svg>
        </div>
    </div>

    <!-- Academic Departments -->
    <div class="stat-card-v2 amber">
        <div class="stat-info-wrap">
            <span class="stat-label-text">Departments</span>
            <div class="stat-number-val"><?php echo number_format($total_departments); ?></div>
            <span class="stat-trend-pill trend-amber">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>
                Faculty Divisions
            </span>
        </div>
        <div class="stat-icon-bubble bubble-amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 21h18"/>
                <path d="M3 10h18"/>
                <path d="M5 6l7-3 7 3"/>
                <path d="M4 10v11"/>
                <path d="M20 10v11"/>
                <path d="M8 14v4"/>
                <path d="M12 14v4"/>
                <path d="M16 14v4"/>
            </svg>
        </div>
    </div>

    <!-- Academic Year -->
    <div class="stat-card-v2 cyan">
        <div class="stat-info-wrap">
            <span class="stat-label-text">Academic Cycle</span>
            <div class="stat-number-val"><?php echo date('Y'); ?>-<?php echo substr(date('Y') + 1, -2); ?></div>
            <span class="stat-trend-pill trend-cyan">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Current Term
            </span>
        </div>
        <div class="stat-icon-bubble bubble-cyan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
        </div>
    </div>
</div>

<!-- ===== DUAL COLUMN DASHBOARD CONTENT ===== -->
<div class="dashboard-grid-layout">
    <!-- Left: Recently Enrolled Students Table -->
    <div class="table-card-v2">
        <div class="table-header-v2">
            <div class="table-title-group">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    </svg>
                    Recent Student Enrollments
                </h3>
                <p>Showing latest additions to the database</p>
            </div>
            <a href="students.php" class="btn-v2 btn-v2-secondary btn-v2-sm">
                View All Directory →
            </a>
        </div>

        <div class="table-responsive">
            <table class="modern-data-table">
                <thead>
                    <tr>
                        <th>Student Profile</th>
                        <th>Roll Number</th>
                        <th>Course / Dept</th>
                        <th>Semester</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result_recent) > 0): ?>
                        <?php while ($student = mysqli_fetch_assoc($result_recent)): ?>
                            <tr>
                                <td>
                                    <div class="student-profile-cell">
                                        <div class="avatar-badge" style="background: <?php echo getAvatarGradient($student['full_name']); ?>;">
                                            <?php echo getInitials($student['full_name']); ?>
                                        </div>
                                        <div class="student-meta-info">
                                            <div class="name"><?php echo htmlspecialchars($student['full_name']); ?></div>
                                            <div class="email"><?php echo htmlspecialchars($student['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="roll-pill"><?php echo htmlspecialchars($student['roll_number']); ?></span>
                                </td>
                                <td>
                                    <span class="pill-badge pill-primary"><?php echo htmlspecialchars($student['course']); ?></span>
                                    <div style="font-size: 11.5px; color: var(--slate-400); margin-top: 3px;">
                                        <?php echo htmlspecialchars($student['department']); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="pill-badge pill-slate">Sem <?php echo $student['semester']; ?></span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-btn-group" style="justify-content: flex-end;">
                                        <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="action-icon-btn edit" title="Edit Student">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                            </svg>
                                        </a>
                                        <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="action-icon-btn delete" title="Delete Student">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-data-state">
                                    <div class="empty-icon-circle">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                    <h4>No student records found</h4>
                                    <p>Get started by adding your first student to the system.</p>
                                    <a href="add_student.php" class="btn-v2 btn-v2-primary btn-v2-sm">Add First Student</a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right: Analytics & Quick Actions Widgets -->
    <div>
        <!-- Department Distribution Progress Widget -->
        <div class="side-widget-card">
            <h4>
                <span>Department Enrollment</span>
                <span class="badge-counter"><?php echo count($dept_counts); ?></span>
            </h4>

            <?php if (!empty($dept_counts)): ?>
                <?php
                $bar_colors = ['#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#8b5cf6'];
                $color_idx = 0;
                foreach ($dept_counts as $dc):
                    $pct = ($total_students > 0) ? round(($dc['cnt'] / $total_students) * 100) : 0;
                    $currentColor = $bar_colors[$color_idx % count($bar_colors)];
                    $color_idx++;
                ?>
                    <div class="progress-metric-item">
                        <div class="progress-label-wrap">
                            <span><?php echo htmlspecialchars($dc['department']); ?></span>
                            <span style="color: var(--slate-400); font-size: 12px;"><?php echo $dc['cnt']; ?> (<?php echo $pct; ?>%)</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-bar-fill" style="width: <?php echo $pct; ?>%; background: <?php echo $currentColor; ?>;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--slate-400); font-size: 13px;">No departmental data available.</p>
            <?php endif; ?>
        </div>

        <!-- Demographics Mini Card -->
        <div class="side-widget-card">
            <h4>
                <span>Student Demographics</span>
            </h4>
            <div style="display: flex; gap: 12px; margin-top: 8px;">
                <div style="flex: 1; padding: 14px; background: #eff6ff; border-radius: var(--radius-md); text-align: center;">
                    <div style="font-size: 11px; font-weight: 700; color: #1d4ed8; text-transform: uppercase;">Male</div>
                    <div style="font-size: 22px; font-weight: 800; color: #1e3a8a; margin-top: 4px;"><?php echo $male_count; ?></div>
                </div>
                <div style="flex: 1; padding: 14px; background: #fdf2f8; border-radius: var(--radius-md); text-align: center;">
                    <div style="font-size: 11px; font-weight: 700; color: #be185d; text-transform: uppercase;">Female</div>
                    <div style="font-size: 22px; font-weight: 800; color: #831843; margin-top: 4px;"><?php echo $female_count; ?></div>
                </div>
            </div>
        </div>

        <!-- Quick Launch Pad -->
        <div class="side-widget-card" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); color: white; border: none;">
            <h4 style="color: white; margin-bottom: 8px;">Academic Operations</h4>
            <p style="font-size: 13px; color: rgba(255, 255, 255, 0.8); margin-bottom: 16px; line-height: 1.5;">
                Need to add or modify student records for this academic session?
            </p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="add_student.php" class="btn-v2" style="background: white; color: #4338ca; width: 100%;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Register New Student
                </a>
                <a href="students.php" class="btn-v2" style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.2); width: 100%;">
                    Browse Full Directory
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
