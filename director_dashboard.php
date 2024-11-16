<?php
session_start();
include 'db.php';

// Fetch the selected status filter from the URL or set default as 'pending'
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'pending';

// SQL query to get applications based on the selected status
$sql = "SELECT * FROM applications WHERE status = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $statusFilter);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Director Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #83a4d4, #b6fbff);
            padding: 20px;
            transition: background-color 0.3s ease;
        }

        .container {
            display: flex;
            justify-content: space-between;
            transition: transform 0.3s ease;
        }

        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            margin-right: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .sidebar:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .content {
            flex: 1;
            transition: transform 0.3s ease;
        }

        .job-card {
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .job-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .job-card h3 {
            margin-top: 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .job-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .job-card p strong {
            color: #333;
        }

        .status-btn {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            background-color: #2d9cdb;
            border-radius: 5px;
            margin-right: 10px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .status-btn:hover {
            background-color: #1e7bbf;
            transform: scale(1.05);
        }

        .status-btn:active {
            background-color: #1a5b87;
        }

        .logout-btn {
            display: inline-block;
            background-color: #ff4d4d;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .logout-btn:hover {
            background-color: #ff1a1a;
            transform: scale(1.05);
        }

        .logout-btn:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(255, 77, 77, 0.8);
        }

        .delete-checkbox {
            margin-right: 10px;
            transform: scale(1);
            transition: transform 0.2s ease;
        }

        .delete-checkbox:hover {
            transform: scale(1.1);
        }

        .select-all {
            font-size: 16px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .select-all:hover {
            transform: scale(1.05);
        }

        .delete-btn {
            display: inline-block;
            background-color: #e74c3c;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 10px;
        }

        .delete-btn:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }

        .delete-btn:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(231, 76, 60, 0.8);
        }

        .dropdown select {
            background-color: #fff;
            color: #333;
            border: 2px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .dropdown select:hover {
            background-color: #f0f0f0;
            transform: scale(1.05);
        }

        .dropdown select:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 128, 0, 0.8);
            transform: scale(1.05);
        }
    </style>
    <script>
        // JavaScript function to select or deselect all checkboxes
        function toggleSelectAll(source) {
            checkboxes = document.querySelectorAll('.delete-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</head>
<body>

<div class="container">
    <!-- Sidebar for filters -->
    <div class="sidebar">
        <h2>Filters</h2>
        <form action="" method="GET">
            <label for="status">Application Status:</label>
            <div class="dropdown">
                <select name="status" id="status" onchange="this.form.submit()">
                    <option value="pending" <?php if ($statusFilter == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="accepted" <?php if ($statusFilter == 'accepted') echo 'selected'; ?>>Accepted</option>
                    <option value="rejected" <?php if ($statusFilter == 'rejected') echo 'selected'; ?>>Rejected</option>
                </select>
            </div>
        </form>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Main content area for job listings -->
    <div class="content">
        <h2>Applications (Status: <?php echo ucfirst($statusFilter); ?>)</h2>

        <!-- Form to submit selected applications for deletion -->
        <form action="delete_applications.php" method="POST">
            <label class="select-all">
                <input type="checkbox" onclick="toggleSelectAll(this)"> Select All
            </label>
            <div class="job-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($application = $result->fetch_assoc()) {
                        echo "<div class='job-card'>";
                        echo "<input type='checkbox' name='application_ids[]' value='{$application['id']}' class='delete-checkbox'>";
                        echo "<h3>Application ID: {$application['id']}</h3>";
                        echo "<p><strong>Name:</strong> {$application['name']}</p>";
                        echo "<p><strong>Email:</strong> {$application['email']}</p>";
                        echo "<p><strong>Phone:</strong> {$application['phone']}</p>";
                        echo "<p><strong>Resume Text:</strong> {$application['resume']}</p>";

                        // Display resume file if available
                        if (!empty($application['resume_file'])) {
                            echo "<p><a href='{$application['resume_file']}' target='_blank'>Download Resume</a></p>";
                        }

                        // Accept and Reject buttons
                        echo "<a href='process_application.php?id={$application['id']}&status=accepted' class='status-btn'>Accept</a>";
                        echo "<a href='process_application.php?id={$application['id']}&status=rejected' class='status-btn'>Reject</a>";

                        echo "</div>";
                    }
                } else {
                    echo "<p>No applications found.</p>";
                }
                ?>
            </div>
            <input type="submit" name="delete_selected" value="Delete Selected" class="delete-btn">
        </form>
    </div>
</div>

</body>
</html>
