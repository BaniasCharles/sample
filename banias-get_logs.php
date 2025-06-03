<?php
include 'banias-db_connect.php'; // Connect to database

// Step 1: Identify the correct date column dynamically
$result = $conn->query("SHOW COLUMNS FROM logs");
$date_column = null;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        if (strpos($row['Field'], 'date') !== false) { 
            // Identify the date-related column
            $date_column = $row['Field'];
            break;
        }
    }
}

// Fallback in case no date column is found
if (!$date_column) {
    die(json_encode(['error' => 'No date column found in the logs table.']));
}

// Step 2: Use the correct column name in the query
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '2025-03-18';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '2025-03-24';

$query = "SELECT * FROM logs WHERE $date_column >= '$start_date' AND $date_column <= '$end_date'";
$result = $conn->query($query);

$logs = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($logs);
?>