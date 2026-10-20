<?php
// ==========================================
// delete_student.php - Confirm Record Deletion
// High-clarity safety dialog
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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: students.php");
    exit();
}

// Fetch student
$result = mysqli_query($conn, "SELECT * FROM students WHERE id = $id");
if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: students.php");
    exit();
}

$student = mysqli_fetch_assoc($result);

// Handle POST Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "DELETE FROM students WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: students.php?msg=deleted");
        exit();
    }
}

// Render page layout
$page_title = "Delete Student Record";
include 'includes/header.php';

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
    <a href="students.php">Students</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Confirm Deletion</span>
</div>

<!-- Danger Confirmation Card -->
<div class="danger-modal-card">
    <div class="danger-icon-circle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            <line x1="10" y1="11" x2="10" y2="17"/>
            <line x1="14" y1="11" x2="14" y2="17"/>
        </svg>
    </div>

    <h3>Delete Student Record?</h3>
    <p>
        Are you sure you want to permanently delete this student from the institutional database? This action is permanent and cannot be reversed.
    </p>

    <!-- Student Preview Box -->
    <div class="student-highlight-box">
        <div class="avatar-badge" style="background: <?php echo getAvatarGradient($student['full_name']); ?>;">
            <?php echo getInitials($student['full_name']); ?>
        </div>
        <div>
            <div style="font-weight: 700; color: var(--slate-900); font-size: 14.5px;">
                <?php echo htmlspecialchars($student['full_name']); ?>
            </div>
            <div style="font-size: 12px; color: var(--slate-500); margin-top: 2px;">
                Roll: <strong style="color: var(--slate-700);"><?php echo htmlspecialchars($student['roll_number']); ?></strong> • 
                <?php echo htmlspecialchars($student['course']); ?> (<?php echo htmlspecialchars($student['department']); ?>)
            </div>
        </div>
    </div>

    <!-- Confirm Action Buttons -->
    <div style="display: flex; gap: 12px; justify-content: center;">
        <a href="students.php" class="btn-v2 btn-v2-secondary" style="flex: 1;">
            Cancel & Keep
        </a>
        <form method="POST" action="delete_student.php?id=<?php echo $id; ?>" style="flex: 1;">
            <button type="submit" class="btn-v2 btn-v2-danger" style="width: 100%;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
                Yes, Delete
            </button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
