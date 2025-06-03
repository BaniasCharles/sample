<?php
include 'banias-db_connect.php';

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

$sql = "INSERT INTO logs (type, task_name, task_desc, start_time, end_time, status, weekly_goals, achievements, challenges, lessons)
        VALUES ('$type', '$task_name', '$task_desc', '$start_time', '$end_time', '$status', '$weekly_goals', '$achievements', '$challenges', '$lessons')";

if ($conn->query($sql)) {
    header("Location: banias-index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>