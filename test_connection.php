<?php
// Quick test to check if database connection works
include 'config/database.php';

echo "<h2>Database Connection Test</h2>";

if ($conn) {
    echo "<p style='color: green; font-size: 20px;'>✅ Connected to <b>student_management</b> database successfully!</p>";

    // Count how many students are in the table
    $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
    $row = mysqli_fetch_assoc($result);
    echo "<p>Total students in database: <b>" . $row['total'] . "</b></p>";
} else {
    echo "<p style='color: red;'>❌ Connection failed!</p>";
}
?>
