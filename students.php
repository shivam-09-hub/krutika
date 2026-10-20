<?php
// ==========================================
// students.php - All Students Directory
// Real-time search, filter, and management
// ==========================================

$page_title = "Student Directory";
include 'includes/header.php';
include 'config/database.php';

// Search and filter parameters
$search     = trim($_GET['search'] ?? '');
$filter_dept   = trim($_GET['department'] ?? '');
$filter_course = trim($_GET['course'] ?? '');

// Build dynamic WHERE clause
$where_clauses = [];

if ($search !== "") {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $where_clauses[] = "(full_name LIKE '%$safe_search%' OR roll_number LIKE '%$safe_search%' OR email LIKE '%$safe_search%')";
}

if ($filter_dept !== "") {
    $safe_dept = mysqli_real_escape_string($conn, $filter_dept);
    $where_clauses[] = "department = '$safe_dept'";
}

if ($filter_course !== "") {
    $safe_course = mysqli_real_escape_string($conn, $filter_course);
    $where_clauses[] = "course = '$safe_course'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}

// Fetch query
$sql = "SELECT * FROM students $where_sql ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$total = mysqli_num_rows($result);

// Dropdown options for filters
$all_depts = [];
$dept_res = mysqli_query($conn, "SELECT DISTINCT department FROM students ORDER BY department ASC");
if ($dept_res) {
    while ($d = mysqli_fetch_assoc($dept_res)) {
        $all_depts[] = $d['department'];
    }
}

$all_courses = [];
$course_res = mysqli_query($conn, "SELECT DISTINCT course FROM students ORDER BY course ASC");
if ($course_res) {
    while ($c = mysqli_fetch_assoc($course_res)) {
        $all_courses[] = $c['course'];
    }
}

// Message alert
$msg = $_GET['msg'] ?? '';

// Helpers
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

<!-- Breadcrumbs -->
<div class="breadcrumb-wrap">
    <a href="dashboard.php">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">All Students</span>
</div>

<!-- Alerts -->
<?php if ($msg === 'deleted'): ?>
    <div class="modern-alert alert-success-v2">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>Student record has been successfully deleted from the database.</span>
    </div>
<?php elseif ($msg === 'updated'): ?>
    <div class="modern-alert alert-success-v2">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>Student record details have been updated successfully.</span>
    </div>
<?php endif; ?>

<!-- ===== TABLE CARD ===== -->
<div class="table-card-v2">
    <!-- Header with Filters & Search -->
    <div class="table-header-v2">
        <div class="table-title-group">
            <h3>
                <span>Student Directory</span>
                <span class="badge-counter"><?php echo $total; ?> <?php echo ($total === 1) ? 'Record' : 'Records'; ?></span>
            </h3>
            <p>Filter, search, and manage registered student profiles</p>
        </div>

        <form method="GET" action="students.php" class="table-actions-group">
            <!-- Search field -->
            <div class="search-input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" class="search-field-v2" placeholder="Search name or roll..." value="<?php echo htmlspecialchars($search); ?>">
            </div>

            <!-- Department Filter -->
            <select name="department" class="filter-select-v2" onchange="this.form.submit()">
                <option value="">All Departments</option>
                <?php foreach ($all_depts as $dept): ?>
                    <option value="<?php echo htmlspecialchars($dept); ?>" <?php echo ($filter_dept === $dept) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($dept); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Course Filter -->
            <select name="course" class="filter-select-v2" onchange="this.form.submit()">
                <option value="">All Courses</option>
                <?php foreach ($all_courses as $c): ?>
                    <option value="<?php echo htmlspecialchars($c); ?>" <?php echo ($filter_course === $c) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-v2 btn-v2-primary btn-v2-sm">Filter</button>

            <?php if (!empty($search) || !empty($filter_dept) || !empty($filter_course)): ?>
                <a href="students.php" class="btn-v2 btn-v2-secondary btn-v2-sm" title="Clear All Filters">Reset</a>
            <?php endif; ?>

            <!-- Print / Export for Teacher Viva Demonstration -->
            <button type="button" onclick="window.print()" class="btn-v2 btn-v2-secondary btn-v2-sm" title="Print student roster for viva">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect width="12" height="8" x="6" y="14"/>
                </svg>
                Print Roster
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="modern-data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Student Profile</th>
                    <th>Roll Number</th>
                    <th>Contact</th>
                    <th>Program & Dept</th>
                    <th>Semester</th>
                    <th>Gender</th>
                    <th>Admission</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total > 0): ?>
                    <?php $i = 1; ?>
                    <?php while ($student = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td style="color: var(--slate-400); font-weight: 600; font-size: 12px;"><?php echo $i++; ?></td>
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
                                <div style="font-size: 13px; font-weight: 500; color: var(--slate-700);">
                                    <?php echo htmlspecialchars($student['phone']); ?>
                                </div>
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
                            <td>
                                <?php
                                $g = $student['gender'];
                                $g_class = ($g === 'Male') ? 'pill-male' : (($g === 'Female') ? 'pill-female' : 'pill-other');
                                ?>
                                <span class="pill-badge <?php echo $g_class; ?>">
                                    <span class="gender-dot"></span>
                                    <?php echo htmlspecialchars($g); ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 12.5px; color: var(--slate-500);">
                                    <?php echo date('d M Y', strtotime($student['admission_date'])); ?>
                                </span>
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
                        <td colspan="9">
                            <div class="empty-data-state">
                                <div class="empty-icon-circle">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                    </svg>
                                </div>
                                <h4>No students match your query</h4>
                                <p>Try clearing your search keyword or resetting the department filters.</p>
                                <a href="students.php" class="btn-v2 btn-v2-secondary btn-v2-sm">Reset Filter</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
