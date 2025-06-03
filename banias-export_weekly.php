<?php
include 'banias-db_connect.php';

$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('monday this week'));
$end_date = $_GET['end_date'] ?? date('Y-m-d', strtotime('sunday this week'));

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=weekly_summary_".date('Y-m-d').".csv");

$output = fopen("php://output", "w");

// Write headers
fputcsv($output, ["Date", "Task", "Time Spent", "Challenges", "Improvements"]);

// Write data
$query = "SELECT 
            timestamp as date,
            task_name as task,
            CONCAT(start_time, ' - ', end_time) as time_spent,
            challenges,
            lessons as improvements
          FROM logs 
          WHERE timestamp BETWEEN '$start_date' AND '$end_date'
          ORDER BY timestamp DESC";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
$conn->close();
exit();
?>