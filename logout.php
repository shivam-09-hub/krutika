<?php
// ==========================================
// logout.php - Admin Logout
// ==========================================
// Destroys the session and redirects the
// admin back to the login page.
// ==========================================

session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>
