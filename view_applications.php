<?php
include 'db.php';

// Fetch all applications
$query = "SELECT * FROM applications";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Applications</title>
</head>
<body>
    <h2>Submitted Applications</h2>
    <table border="1">
        <tr>
            <th>Applicant Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <a href="application_details.php?id=<?php echo $row['id']; ?>">View Details</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
