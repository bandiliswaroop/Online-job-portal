<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user ID from session
$user_id = $_SESSION['user_id'];

// Update the column name in the WHERE clause as needed
$sql = "SELECT applications.job_id, applications.status, jobs.company, jobs.category 
        FROM applications 
        JOIN jobs ON applications.job_id = jobs.id 
        WHERE applications.applicant_id = ?"; // Replace 'applicant_id' with the correct column name if needed
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <style>
        /* Add your CSS styling here */
    </style>
</head>
<body>

<div class="status-container">
    <h2>Your Application Status</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='application-card'>";
            echo "<p><strong>Company:</strong> " . htmlspecialchars($row['company']) . "</p>";
            echo "<p><strong>Position:</strong> " . htmlspecialchars($row['category']) . "</p>";
            echo "<p><strong>Status:</strong> ";
            if ($row['status'] === 'Approved') {
                echo "<span class='status-approved'>Approved</span>";
            } elseif ($row['status'] === 'Rejected') {
                echo "<span class='status-rejected'>Rejected</span>";
            } else {
                echo "<span class='status-pending'>Pending</span>";
            }
            echo "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No applications found.</p>";
    }
    $stmt->close();
    $conn->close();
    ?>
</div>

</body>
</html>
