<?php
// ==========================================
// add_student.php - Register New Student
// Clean two-column responsive form
// ==========================================

$page_title = "Register New Student";
include 'includes/header.php';
include 'config/database.php';

$success = "";
$error   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name      = trim($_POST['full_name'] ?? '');
    $roll_number    = trim($_POST['roll_number'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $course         = trim($_POST['course'] ?? '');
    $department     = trim($_POST['department'] ?? '');
    $semester       = (int)($_POST['semester'] ?? 1);
    $gender         = trim($_POST['gender'] ?? 'Male');
    $admission_date = trim($_POST['admission_date'] ?? date('Y-m-d'));

    // Check duplicate roll number
    $safe_roll = mysqli_real_escape_string($conn, $roll_number);
    $check = mysqli_query($conn, "SELECT id FROM students WHERE roll_number = '$safe_roll'");

    if (mysqli_num_rows($check) > 0) {
        $error = "A student with Roll Number '$roll_number' is already registered in the system.";
    } else {
        $safe_name  = mysqli_real_escape_string($conn, $full_name);
        $safe_email = mysqli_real_escape_string($conn, $email);
        $safe_phone = mysqli_real_escape_string($conn, $phone);
        $safe_crse  = mysqli_real_escape_string($conn, $course);
        $safe_dept  = mysqli_real_escape_string($conn, $department);
        $safe_gen   = mysqli_real_escape_string($conn, $gender);
        $safe_date  = mysqli_real_escape_string($conn, $admission_date);

        $sql = "INSERT INTO students (full_name, roll_number, email, phone, course, department, semester, gender, admission_date)
                VALUES ('$safe_name', '$safe_roll', '$safe_email', '$safe_phone', '$safe_crse', '$safe_dept', $semester, '$safe_gen', '$safe_date')";

        if (mysqli_query($conn, $sql)) {
            $success = "Student '$full_name' has been registered successfully!";
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!-- Breadcrumbs -->
<div class="breadcrumb-wrap">
    <a href="dashboard.php">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <a href="students.php">Students</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Register New Student</span>
</div>

<!-- Notifications -->
<?php if (!empty($success)): ?>
    <div class="modern-alert alert-success-v2">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <div>
            <strong>Success!</strong> <?php echo htmlspecialchars($success); ?>
            <a href="students.php" style="text-decoration: underline; margin-left: 8px; font-weight: 600;">View in Directory →</a>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="modern-alert alert-danger-v2">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div><strong>Registration Error:</strong> <?php echo htmlspecialchars($error); ?></div>
    </div>
<?php endif; ?>

<!-- Registration Card -->
<div class="form-container-card">
    <div class="form-header-bar">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <line x1="19" y1="8" x2="19" y2="14"/>
                <line x1="22" y1="11" x2="16" y2="11"/>
            </svg>
            Student Enrollment Form
        </h3>
        <p>Complete student profile details to generate an official institutional record.</p>
    </div>

    <form method="POST" action="add_student.php" class="form-body-content">
        <!-- Section 1: Personal Details -->
        <div class="form-fieldset-group">
            <div class="fieldset-title">Personal Information</div>

            <div class="form-grid-2">
                <div class="form-group-v2">
                    <label for="full_name">Full Name <span style="color: var(--rose-500);">*</span></label>
                    <div class="input-with-icon-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" id="full_name" name="full_name" class="input-control-v2" placeholder="e.g. Aarav Sharma" required>
                    </div>
                </div>

                <div class="form-group-v2">
                    <label for="gender">Gender <span style="color: var(--rose-500);">*</span></label>
                    <div class="input-with-icon-wrap">
                        <select id="gender" name="gender" class="input-control-v2 no-icon" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-v2">
                    <label for="email">Email Address <span style="color: var(--rose-500);">*</span></label>
                    <div class="input-with-icon-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                        <input type="email" id="email" name="email" class="input-control-v2" placeholder="aarav@college.edu" required>
                    </div>
                </div>

                <div class="form-group-v2">
                    <label for="phone">Contact Number <span style="color: var(--rose-500);">*</span></label>
                    <div class="input-with-icon-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <input type="tel" id="phone" name="phone" class="input-control-v2" placeholder="9876543210" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Academic Program -->
        <div class="form-fieldset-group">
            <div class="fieldset-title">Academic & Institutional Details</div>

            <div class="form-grid-2">
                <div class="form-group-v2">
                    <label for="roll_number">Roll / Registration Number <span style="color: var(--rose-500);">*</span></label>
                    <div class="input-with-icon-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h10"/><path d="M7 12h10"/><path d="M7 17h10"/>
                        </svg>
                        <input type="text" id="roll_number" name="roll_number" class="input-control-v2" placeholder="e.g. BCA2024010" required>
                    </div>
                </div>

                <div class="form-group-v2">
                    <label for="course">Program / Course <span style="color: var(--rose-500);">*</span></label>
                    <select id="course" name="course" class="input-control-v2 no-icon" required>
                        <option value="">Select Degree Course</option>
                        <option value="BCA">Bachelor of Computer Applications (BCA)</option>
                        <option value="MCA">Master of Computer Applications (MCA)</option>
                        <option value="B.Sc IT">B.Sc Information Technology</option>
                        <option value="BBA">Bachelor of Business Administration (BBA)</option>
                        <option value="MBA">Master of Business Administration (MBA)</option>
                    </select>
                </div>

                <div class="form-group-v2">
                    <label for="department">Department <span style="color: var(--rose-500);">*</span></label>
                    <select id="department" name="department" class="input-control-v2 no-icon" required>
                        <option value="">Select Faculty Department</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Business Administration">Business Administration</option>
                    </select>
                </div>

                <div class="form-group-v2">
                    <label for="semester">Current Semester <span style="color: var(--rose-500);">*</span></label>
                    <select id="semester" name="semester" class="input-control-v2 no-icon" required>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                        <option value="3" selected>Semester 3</option>
                        <option value="4">Semester 4</option>
                        <option value="5">Semester 5</option>
                        <option value="6">Semester 6</option>
                        <option value="7">Semester 7</option>
                        <option value="8">Semester 8</option>
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
                        <input type="date" id="admission_date" name="admission_date" class="input-control-v2" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions-bar">
            <a href="students.php" class="btn-v2 btn-v2-secondary">Cancel</a>
            <button type="submit" class="btn-v2 btn-v2-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Confirm & Register Student
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
