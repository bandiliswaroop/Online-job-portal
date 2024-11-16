<?php
include 'db.php';

$app_id = $_GET['id'];

// Fetch application details
$query = "SELECT * FROM applications WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $app_id);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();

// Check if the form was submitted to update status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];
    $update_query = "UPDATE applications SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_status, $app_id);
    
    if ($update_stmt->execute()) {
        $application['status'] = $new_status;
        echo "Status updated successfully!";
    } else {
        echo "Error updating status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Details</title>
</head>
<body>
    <h2>Application Details</h2>
    <p><strong>Applicant Name:</strong> <?php echo $application['name']; ?></p>
    <p><strong>Email:</strong> <?php echo $application['email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $application['phone']; ?></p>
    <p><strong>Resume Text:</strong> <?php echo nl2br($application['resume']); ?></p>
    <p><strong>Uploaded Resume:</strong> 
        <?php if ($application['resume_file']): ?>
            <a href="uploads/<?php echo $application['resume_file']; ?>" target="_blank">View File</a>
        <?php else: ?>
            No file uploaded
        <?php endif; ?>
    </p>
    <p><strong>Status:</strong> <?php echo $application['status']; ?></p>

    <form action="" method="POST">
        <label for="status">Update Status:</label>
        <select name="status" id="status">
            <option value="Pending" <?php echo ($application['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Approved" <?php echo ($application['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
            <option value="Rejected" <?php echo ($application['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
        </select>
        <button type="submit">Update Status</button>
    </form>
</body>
</html>
