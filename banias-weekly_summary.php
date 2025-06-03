<?php
include 'banias-db_connect.php';

// Initialize variables
$monday = date('Y-m-d', strtotime('monday this week'));
$sunday = date('Y-m-d', strtotime('sunday this week'));
$start_date = date('M j', strtotime($monday));
$end_date = date('M j, Y', strtotime($sunday));

// Query to get weekly logs
$query = "SELECT 
            id,
            DATE(timestamp) as date,
            task_name as task,
            CONCAT(TIME_FORMAT(start_time, '%h:%i%p'), ' - ', TIME_FORMAT(end_time, '%h:%i%p')) as time_spent,
            challenges,
            lessons as improvements,
            status
          FROM logs 
          WHERE DATE(timestamp) BETWEEN ? AND ?
          ORDER BY timestamp DESC";

$stmt = $conn->prepare($query);
if ($stmt === false) die('Prepare failed: ' . htmlspecialchars($conn->error));

$stmt->bind_param("ss", $monday, $sunday);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) die('Execute failed: ' . htmlspecialchars($stmt->error));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Performance Summary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #2c3e50;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --gray: #7f8c8d;
            --light-gray: #e0e0e0;
            --border-radius: 8px;
            --shadow: 0 2px 10px rgba(0,0,0,0.05);
            --transition: all 0.2s ease;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: #f9f9f9;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header {
            margin-bottom: 2rem;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1rem;
            transition: var(--transition);
        }
        
        .back-btn:hover {
            color: var(--primary-dark);
            transform: translateX(-3px);
        }
        
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .card-header {
            padding: 1.5rem;
            background: var(--secondary);
            color: white;
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        .card-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--light-gray);
            margin-bottom: 1.5rem;
        }
        
        .tab {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            font-weight: 500;
            color: var(--gray);
            border-bottom: 3px solid transparent;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        
        .tab:hover:not(.active) {
            color: var(--dark);
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Intern View Styles */
        .table-container {
            overflow-x: auto;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }
        
        th {
            background-color: var(--light);
            color: var(--dark);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
        }
        
        tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .btn-icon {
            padding: 0.5rem;
            border-radius: 50%;
            width: 2rem;
            height: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            background: transparent;
            border: none;
            cursor: pointer;
        }
        
        .btn-icon.danger {
            color: #dc3545;
        }
        
        .btn-icon.danger:hover {
            background: #f8d7da;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid transparent;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: white;
            border-color: var(--light-gray);
            color: var(--dark);
        }
        
        .btn-outline:hover {
            background: var(--light);
            border-color: var(--gray);
        }
        
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--gray);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--light-gray);
        }
        
        /* Supervisor Review Styles */
        .review-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 1.5rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }
        
        .section-title {
            color: var(--secondary);
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .section-subtitle {
            color: var(--gray);
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--dark);
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .form-textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            min-height: 150px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: var(--transition);
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .rating-select {
            position: relative;
        }
        
        .form-select {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            appearance: none;
            font-size: 0.95rem;
            background-color: white;
            cursor: pointer;
        }
        
        .select-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            pointer-events: none;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: flex-end;
        }
        
        .btn-reset {
            background: white;
            border: 1px solid var(--light-gray);
            color: var(--gray);
        }
        
        .btn-reset:hover {
            background: var(--light);
        }
        
        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
        }
        
        .btn-submit:hover {
            background: var(--primary-dark);
        }
        
        /* Previous Reviews Section */
        .past-reviews {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--light-gray);
        }
        
        .review-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        
        .review-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            align-items: center;
        }
        
        .review-date {
            color: var(--gray);
            font-size: 0.875rem;
        }
        
        .review-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .rating-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .rating-badge.excellent {
            background: #d4edda;
            color: #155724;
        }
        
        .rating-badge.good {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .rating-badge.average {
            background: #fff3cd;
            color: #856404;
        }
        
        .rating-badge.needs-improvement {
            background: #f8d7da;
            color: #721c24;
        }
        
        .no-reviews {
            color: var(--gray);
            font-style: italic;
            text-align: center;
            padding: 2rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .card-header {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .tabs {
                overflow-x: auto;
                padding-bottom: 0.5rem;
            }
            
            .tab {
                white-space: nowrap;
                padding: 0.75rem 1rem;
            }
            
            th, td {
                padding: 0.75rem;
            }
            
            .action-buttons,
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .review-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="banias-index.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Logs
            </a>
            <h1>Weekly Performance Summary</h1>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-title">Week of <?= $start_date ?> - <?= $end_date ?></div>
            </div>
            
            <div class="card-body">
                <div class="tabs">
                    <div class="tab active" onclick="openTab('intern')">
                        <i class="fas fa-user-graduate"></i> Intern View
                    </div>
                    <div class="tab" onclick="openTab('supervisor')">
                        <i class="fas fa-clipboard-check"></i> Supervisor Review
                    </div>
                </div>
                
                <!-- Intern View -->
                <div id="intern" class="tab-content active">
                    <h2>Weekly Activities</h2>
                    
                    <?php if ($result && $result->num_rows > 0): ?>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Task</th>
                                        <th>Time Spent</th>
                                        <th>Status</th>
                                        <th>Challenges</th>
                                        <th>Improvements</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= date('D, M j', strtotime($row['date'])) ?></td>
                                            <td><?= htmlspecialchars($row['task'] ?? 'No task') ?></td>
                                            <td><?= htmlspecialchars($row['time_spent'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="badge <?= 
                                                    $row['status'] === 'Completed' ? 'badge-success' : 
                                                    ($row['status'] === 'In Progress' ? 'badge-warning' : 'badge-danger') 
                                                ?>">
                                                    <?= htmlspecialchars($row['status'] ?? 'Pending') ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($row['challenges'] ?? 'None') ?></td>
                                            <td><?= htmlspecialchars($row['improvements'] ?? 'None') ?></td>
                                            <td>
                                                <button onclick="confirmDelete(<?= $row['id'] ?>)" class="btn-icon danger" title="Delete Entry">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="action-buttons">
                            <button onclick="window.print()" class="btn btn-outline">
                                <i class="fas fa-print"></i> Print Summary
                            </button>
                            <button onclick="window.location.href='banias-export_weekly.php?start_date=<?= $monday ?>&end_date=<?= $sunday ?>'" 
                                class="btn btn-primary">
                                <i class="fas fa-file-export"></i> Export Weekly Data
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <h3>No logs found for this week</h3>
                            <p>Start by adding daily logs to see them appear here</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Supervisor Review -->
                <div id="supervisor" class="tab-content">
                    <div class="review-container">
                        <h2 class="section-title">Supervisor Review</h2>
                        <p class="section-subtitle">Provide feedback on the intern's performance for this week.</p>
                        
                        <form action="banias-save_supervisor_review.php" method="POST" class="review-form">
                            <div class="form-group">
                                <label class="form-label">Feedback</label>
                                <textarea name="feedback" class="form-textarea" placeholder="Provide constructive feedback on the intern's performance..." required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Performance Rating</label>
                                <div class="rating-select">
                                    <select name="rating" class="form-select" required>
                                        <option value="">Select a rating</option>
                                        <option value="Excellent">Excellent</option>
                                        <option value="Good">Good</option>
                                        <option value="Average">Average</option>
                                        <option value="Needs Improvement">Needs Improvement</option>
                                    </select>
                                    <i class="fas fa-chevron-down select-icon"></i>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="reset" class="btn btn-reset">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-submit">
                                    <i class="fas fa-check"></i> Submit Review
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Previous Supervisor Reviews Section -->
                    <div class="past-reviews">
                        <h3>Previous Supervisor Reviews</h3>
                        <?php
                        $review_query = "SELECT id, review_date, rating, feedback FROM supervisor_reviews ORDER BY review_date DESC";
                        $review_result = $conn->query($review_query);
                        
                        if ($review_result && $review_result->num_rows > 0): ?>
                            <div class="review-list">
                                <?php while ($review = $review_result->fetch_assoc()): ?>
                                    <div class="review-card">
                                        <div class="review-header">
                                            <span class="review-date"><?= date('M j, Y', strtotime($review['review_date'])) ?></span>
                                            <div class="review-actions">
                                                <span class="rating-badge <?= strtolower(str_replace(' ', '-', $review['rating'])) ?>">
                                                    <?= $review['rating'] ?>
                                                </span>
                                                <button onclick="confirmDeleteReview(<?= $review['id'] ?>)" class="btn-icon danger" title="Delete Review">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <p><?= nl2br(htmlspecialchars($review['feedback'])) ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-reviews">No reviews submitted yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function openTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Deactivate all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Activate selected tab
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
        
        function confirmDelete(logId) {
            if (confirm("Are you sure you want to delete this log entry?\nThis action cannot be undone.")) {
                window.location.href = `banias-delete_log.php?id=${logId}&redirect=banias-weekly_summary.php`;
            }
        }
        
        function confirmDeleteReview(reviewId) {
            if (confirm("Are you sure you want to delete this supervisor review?\nThis action cannot be undone.")) {
                window.location.href = `banias-delete_review.php?id=${reviewId}&redirect=banias-weekly_summary.php`;
            }
        }
    </script>
</body>
</html>
<?php 
$stmt->close();
$conn->close();
?>