<?php
// ==========================================
// Database Connection File
// ==========================================
// This file connects our PHP project to the
// MySQL database using mysqli.
// We include this file in every page that
// needs to access the database.
// ==========================================

$host     = "localhost";           // Server name (localhost for XAMPP)
$username = "root";                // Default XAMPP MySQL username
$password = "";                    // Default XAMPP MySQL password (empty)
$database = "student_management";  // Our database name

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
