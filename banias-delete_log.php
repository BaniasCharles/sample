<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "intern_logs");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get log ID to delete
$id = $_GET['id'];

// Delete from database
$sql = "DELETE FROM logs WHERE id=$id";

if ($conn->query($sql)) {
    header("Location: banias-index.php"); // Redirect back to the main page
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>