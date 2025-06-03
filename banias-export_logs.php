<?php
$conn = new mysqli("localhost", "root", "", "intern_logs");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=intern_logs.csv");

$output = fopen("php://output", "w");
fputcsv($output, ["ID", "Type", "Task Name", "Task Description", "Start Time", "End Time", "Status", "Weekly Goals", "Achievements", "Challenges", "Lessons", "Timestamp"]);

$sql = "SELECT * FROM logs";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
$conn->close();
exit();
?>