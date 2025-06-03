<?php
include 'banias-db_connect.php';

// Fetch saved logs (combined)
$recent_logs = $conn->query("SELECT * FROM logs ORDER BY timestamp DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intern Performance Logs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #4f46e5;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #64748b;
            --light-gray: #e2e8f0;
            --danger: #ef4444;
            --success: #22c55e;
            --border-radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: #f1f5f9;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header {
            margin-bottom: 3rem;
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--border-radius);
            color: white;
            box-shadow: var(--shadow);
        }
        
        .header-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .header-actions .btn-outline {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .header-actions .btn-outline:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        h1 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        
        .subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2.5rem;
            margin-bottom: 2rem;
            transition: var(--transition);
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .form-group {
            margin-bottom: 2rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--light-gray);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background-color: var(--light);
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .time-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .btn {
            padding: 0.875rem 1.75rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            border: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: white;
            border: 2px solid var(--light-gray);
            color: var(--dark);
        }
        
        .btn-outline:hover {
            background: var(--light);
            border-color: var(--gray);
            transform: translateY(-2px);
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .saved-logs {
            margin-top: 4rem;
        }
        
        .saved-logs h2 {
            color: var(--dark);
            margin-bottom: 2rem;
            font-size: 1.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .log-entry {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid var(--light-gray);
        }
        
        .log-entry:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .log-entry h3 {
            margin-top: 0;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
        }
        
        .log-entry p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }
        
        .log-entry strong {
            color: var(--dark);
            font-weight: 600;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray);
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: var(--light-gray);
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--dark);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .header {
                padding: 1.5rem;
                margin-bottom: 2rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .time-fields {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .card {
                padding: 1.5rem;
            }
            
            .log-entry {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Intern Performance Logs</h1>
            <div class="header-actions">
                <p class="subtitle">Track and manage your activities</p>
                <a href="banias-logout.php" class="btn btn-outline">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
        
        <!-- Combined Log Form -->
        <div class="card">
            <form action="banias-save_log.php" method="POST">
                <input type="hidden" name="type" value="Combined Log">
                
                <h2><i class="fas fa-tasks"></i> Daily Activity</h2>
                <div class="form-group">
                    <label>Task Name:</label>
                    <input type="text" name="task_name" placeholder="What did you work on today?" required>
                </div>
                
                <div class="form-group">
                    <label>Task Description:</label>
                    <textarea name="task_desc" placeholder="Describe your daily work in detail" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Time:</label>
                    <div class="time-fields">
                        <div>
                            <label>Start Time</label>
                            <input type="time" name="start_time" required>
                        </div>
                        <div>
                            <label>End Time</label>
                            <input type="time" name="end_time" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" required>
                        <option value="">Select status</option>
                        <option value="Completed">Completed</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                
                <h2><i class="fas fa-calendar-week"></i> Weekly Reflection</h2>
                <div class="form-group">
                    <label>Weekly Goals:</label>
                    <textarea name="weekly_goals" placeholder="What were your goals for this week?"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Achievements:</label>
                    <textarea name="achievements" placeholder="What did you accomplish this week?"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Challenges:</label>
                    <textarea name="challenges" placeholder="What challenges did you encounter?"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Lessons Learned:</label>
                    <textarea name="lessons" placeholder="What did you learn this week?"></textarea>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save All Logs
                    </button>
                    <button type="button" class="btn btn-outline" onclick="window.location.href='banias-weekly_summary.php'">
                        <i class="fas fa-chart-bar"></i> View Weekly Summary
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Combined Saved Logs Section -->
        <div class="saved-logs">
            <h2><i class="fas fa-history"></i> Recent Logs</h2>
            
            <?php if ($recent_logs->num_rows > 0): ?>
                <?php while ($row = $recent_logs->fetch_assoc()): ?>
                    <div class="log-entry">
                        <h3>
                            <i class="fas fa-calendar-day"></i>
                            <?= date('M j, Y', strtotime($row['timestamp'])) ?>
                        </h3>
                        
                        <?php if (!empty($row['task_name'])): ?>
                            <p><strong>Task:</strong> <?= htmlspecialchars($row['task_name']) ?></p>
                            <p><strong>Description:</strong> <?= htmlspecialchars($row['task_desc']) ?></p>
                            <p><strong>Time:</strong> <?= date('g:i A', strtotime($row['start_time'])) ?> - <?= date('g:i A', strtotime($row['end_time'])) ?></p>
                            <p><strong>Status:</strong> 
                                <span class="badge <?= 
                                    $row['status'] === 'Completed' ? 'badge-success' : 
                                    ($row['status'] === 'In Progress' ? 'badge-warning' : 'badge-danger') 
                                ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </p>
                        <?php endif; ?>
                        
                        <?php if (!empty($row['weekly_goals'])): ?>
                            <p><strong>Weekly Goals:</strong> <?= htmlspecialchars($row['weekly_goals']) ?></p>
                            <p><strong>Achievements:</strong> <?= htmlspecialchars($row['achievements']) ?></p>
                            <p><strong>Challenges:</strong> <?= htmlspecialchars($row['challenges']) ?></p>
                            <p><strong>Lessons Learned:</strong> <?= htmlspecialchars($row['lessons']) ?></p>
                        <?php endif; ?>
                        
                        <div class="action-buttons">
                            <button onclick="deleteLog(<?= $row['id'] ?>)" class="btn btn-outline">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
                
                <div class="btn-group">
                    <button onclick="window.location.href='banias-export_logs.php'" class="btn btn-outline">
                        <i class="fas fa-file-export"></i> Export All Logs
                    </button>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>No logs found</h3>
                    <p>Start by adding your first log above</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function deleteLog(id) {
            if (confirm("Are you sure you want to delete this log?\nThis action cannot be undone.")) {
                window.location.href = `banias-delete_log.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>