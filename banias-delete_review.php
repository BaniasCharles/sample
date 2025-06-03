<?php
include 'banias-db_connect.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "No review ID provided.";
    header("Location: banias-weekly_summary.php");
    exit;
}

$id = intval($_GET['id']);

// Prepare the delete statement
$stmt = $conn->prepare("DELETE FROM supervisor_reviews WHERE id = ?");
if ($stmt === false) {
    $_SESSION['error'] = "Prepare failed: " . $conn->error;
    header("Location: banias-weekly_summary.php");
    exit;
}

// Bind the parameter and execute
$stmt->bind_param("i", $id);
$result = $stmt->execute();

if ($result) {
    $_SESSION['success'] = "Review deleted successfully.";
} else {
    $_SESSION['error'] = "Error deleting review: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();

// Redirect back
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'banias-weekly_summary.php';
header("Location: $redirect");
exit;
?>