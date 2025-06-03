<?php
// Database connection
$host = "localhost";
$user = "root";  // Your XAMPP default username
$pass = "";       // Your XAMPP default password (empty by default)
$dbname = "intern_logs";  // Replace with your actual database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>