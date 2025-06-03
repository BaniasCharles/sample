<?php
session_start(); // Start the session
include 'banias-db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: banias-login.php');
    exit();
}

// Get user ID from username in session
$username = $_SESSION['username'];
$user_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
$user_query->bind_param("s", $username);
$user_query->execute();
$user_result = $user_query->get_result();

if ($user_result->num_rows === 0) {
    // Handle case where user is not found (shouldn't happen if login works)
    echo "Error: User not found.";
    exit();
}

$user = $user_result->fetch_assoc();
$user_id = $user['id'];

// Prepare data
$type = $conn->real_escape_string($_POST['type']);
$task_name = isset($_POST['task_name']) ? $conn->real_escape_string($_POST['task_name']) : null;
$task_desc = isset($_POST['task_desc']) ? $conn->real_escape_string($_POST['task_desc']) : null;
$start_time = isset($_POST['start_time']) ? $conn->real_escape_string($_POST['start_time']) : null;
$end_time = isset($_POST['end_time']) ? $conn->real_escape_string($_POST['end_time']) : null;
$status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : null;
$weekly_goals = isset($_POST['weekly_goals']) ? $conn->real_escape_string($_POST['weekly_goals']) : null;
$achievements = isset($_POST['achievements']) ? $conn->real_escape_string($_POST['achievements']) : null;
$challenges = isset($_POST['challenges']) ? $conn->real_escape_string($_POST['challenges']) : null;
$lessons = isset($_POST['lessons']) ? $conn->real_escape_string($_POST['lessons']) : null;

// SQL to insert data into logs table, including user_id
$sql = "INSERT INTO logs (user_id, type, task_name, task_desc, start_time, end_time, status, weekly_goals, achievements, challenges, lessons)
        VALUES ('$user_id', '$type', '$task_name', '$task_desc', '$start_time', '$end_time', '$status', '$weekly_goals', '$achievements', '$challenges', '$lessons')";

if ($conn->query($sql)) {
    header("Location: banias-index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
