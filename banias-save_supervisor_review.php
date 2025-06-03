<?php
include 'banias-db_connect.php'; 

// Get form data
$feedback = $_POST['feedback'];
$rating = $_POST['rating'];

// Insert the review into the database
$query = "INSERT INTO supervisor_reviews (feedback, rating, review_date) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $feedback, $rating);

if ($stmt->execute()) {
    echo "<script>alert('Review saved successfully.'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Failed to save review.'); window.history.back();</script>";
}
$stmt->close();
$conn->close();
?>