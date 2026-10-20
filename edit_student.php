<?php
// ==========================================
// edit_student.php - Modify Student Profile
// Clean pre-populated profile editor
// ==========================================

ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Authentication guard
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

include 'config/database.php';

$error = "";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: students.php");
    exit();
}

// Fetch record
$res = mysqli_query($conn, "SELECT * FROM students WHERE id = $id");
if (!$res || mysqli_num_rows($res) === 0) {
    header("Location: students.php");
    exit();
}

$student = mysqli_fetch_assoc($res);

// Handle POST Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name      = trim($_POST['full_name'] ?? '');
    $roll_number    = trim($_POST['roll_number'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $course         = trim($_POST['course'] ?? '');
    $department     = trim($_POST['department'] ?? '');
    $semester       = (int)($_POST['semester'] ?? 1);
    $gender         = trim($_POST['gender'] ?? 'Male');
    $admission_date = trim($_POST['admission_date'] ?? $student['admission_date']);

    // Check roll number conflict with another student
    $safe_roll = mysqli_real_escape_string($conn, $roll_number);
    $check = mysqli_query($conn, "SELECT id FROM students WHERE roll_number = '$safe_roll' AND id != $id");

    if (mysqli_num_rows($check) > 0) {
        $error = "Roll Number '$roll_number' is already assigned to another student.";
    } else {
        $safe_name  = mysqli_real_escape_string($conn, $full_name);
        $safe_email = mysqli_real_escape_string($conn, $email);
        $safe_phone = mysqli_real_escape_string($conn, $phone);
        $safe_crse  = mysqli_real_escape_string($conn, $course);
        $safe_dept  = mysqli_real_escape_string($conn, $department);
        $safe_gen   = mysqli_real_escape_string($conn, $gender);
        $safe_date  = mysqli_real_escape_string($conn, $admission_date);

        $sql = "UPDATE students SET 
                    full_name      = '$safe_name',
                    roll_number    = '$safe_roll',
                    email          = '$safe_email',
                    phone          = '$safe_phone',
                    course         = '$safe_crse',
                    department     = '$safe_dept',
                    semester       = $semester,
                    gender         = '$safe_gen',
                    admission_date = '$safe_date'
                WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            header("Location: students.php?msg=updated");
            exit();
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Render page layout
$page_title = "Edit Student Profile";
include 'includes/header.php';
?>

<!-- Breadcrumbs -->
<div class="breadcrumb-wrap">
    <a href="dashboard.php">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <a href="students.php">Students</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Edit Student</span>
</div>

<!-- Error Alert -->
<?php if (!empty($error)): ?>
    <div class="modern-alert alert-danger-v2">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div><strong>Update Error:</strong> <?php echo htmlspecialchars($error); ?></div>
    </div>
<?php endif; ?>

<!-- Edit Card -->
<div class="form-container-card">
    <div class="form-header-bar">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
            </svg>
            Edit Student: <?php echo htmlspecialchars($student['full_name']); ?>
        </h3>
        <p>Modify academic status, departmental allocation, or contact information.</p>
    </div>

    <form method="POST" action="edit_student.php?id=<?php echo $id; ?>" class="form-body-content">
        <!-- Section 1: Personal Information -->
        <div class="form-fieldset-group">
            <div class="fieldset-title">Personal Information</div>

            <div class="form-grid-2">
                <div class="form-group-v2">
                    <label for="full_name">Full Name <span style="color: var(--rose-500);">*</span></label>
                    <div class="input-with-icon-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" id="full_name" name="full_name" class="input-control-v2" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                    </div>
                </div>

                <div class="form-group-v2">
                    <label for="gender">Gender <span style="color: var(--rose-500);">*</span></label>
                    <select id="gender" name="gender" class="input-control-v2 no-icon" required>
                        <option value="Male"   <?php echo ($student['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($student['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other"  <?php echo ($student['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="form-group-v2">
                    <label for="email">Email Address <span style="color: var(--rose-500);">*</span></label>
                    <div class="input-with-icon-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                        <input type="email" id="email" name="email" class="input-control-v2" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                </div>

                <div class="form-group-v2">
                    <label for="phone">Contact Number <span style="color: var(--rose-500);">*</span></label>
                    <div class="input-with-icon-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <input type="tel" id="phone" name="phone" class="input-control-v2" value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Academic Details -->
        <div class="form-fieldset-group">
            <div class="fieldset-title">Academic & Institutional Details</div>

            <div class="form-grid-2">
                <div class="form-group-v2">
                    <label for="roll_number">Roll / Registration Number <span style="color: var(--rose-500);">*</span></label>
                    <div class="input-with-icon-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h10"/><path d="M7 12h10"/><path d="M7 17h10"/>
                        </svg>
                        <input type="text" id="roll_number" name="roll_number" class="input-control-v2" value="<?php echo htmlspecialchars($student['roll_number']); ?>" required>
                    </div>
                </div>

                <div class="form-group-v2">
                    <label for="course">Program / Course <span style="color: var(--rose-500);">*</span></label>
                    <select id="course" name="course" class="input-control-v2 no-icon" required>
                        <option value="BCA"    <?php echo ($student['course'] === 'BCA') ? 'selected' : ''; ?>>Bachelor of Computer Applications (BCA)</option>
                        <option value="MCA"    <?php echo ($student['course'] === 'MCA') ? 'selected' : ''; ?>>Master of Computer Applications (MCA)</option>
                        <option value="B.Sc IT" <?php echo ($student['course'] === 'B.Sc IT') ? 'selected' : ''; ?>>B.Sc Information Technology</option>
                        <option value="BBA"    <?php echo ($student['course'] === 'BBA') ? 'selected' : ''; ?>>Bachelor of Business Administration (BBA)</option>
                        <option value="MBA"    <?php echo ($student['course'] === 'MBA') ? 'selected' : ''; ?>>Master of Business Administration (MBA)</option>
                    </select>
                </div>

                <div class="form-group-v2">
                    <label for="department">Department <span style="color: var(--rose-500);">*</span></label>
                    <select id="department" name="department" class="input-control-v2 no-icon" required>
                        <option value="Computer Science"        <?php echo ($student['department'] === 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                        <option value="Information Technology"   <?php echo ($student['department'] === 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                        <option value="Business Administration"  <?php echo ($student['department'] === 'Business Administration') ? 'selected' : ''; ?>>Business Administration</option>
                    </select>
                </div>

                <div class="form-group-v2">
                    <label for="semester">Current Semester <span style="color: var(--rose-500);">*</span></label>
                    <select id="semester" name="semester" class="input-control-v2 no-icon" required>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo ($student['semester'] == $i) ? 'selected' : ''; ?>>
                                Semester <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group-v2" style="grid-column: 1 / -1;">
                    <label for="admission_date">Admission Date <span style="color: var(--rose-500);">*</span></label>
                    <div class="input-with-icon-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <input type="date" id="admission_date" name="admission_date" class="input-control-v2" value="<?php echo $student['admission_date']; ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions-bar">
            <a href="students.php" class="btn-v2 btn-v2-secondary">Cancel</a>
            <button type="submit" class="btn-v2 btn-v2-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
