<?php
session_start();
include 'db.php';

// Fetch pending applications for the director to review
$query = "SELECT * FROM applications WHERE status = 'Pending' ORDER BY application_date DESC";
$result = $conn->query($query);

// If the director updates the status
if (isset($_GET['update_status']) && isset($_GET['application_id'])) {
    $application_id = $_GET['application_id'];
    $new_status = $_GET['update_status'];

    // Update the application status in the database
    $update_query = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $update_query->bind_param("si", $new_status, $application_id);
    if ($update_query->execute()) {
        echo "Status updated successfully!";
    } else {
        echo "Error: " . $update_query->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Director's Panel</title>
    <style>
        .application {
            padding: 10px;
            border: 1px solid #ddd;
            margin: 10px 0;
        }

        .status-pending {
            background-color: yellow;
        }

        .status-accepted {
            background-color: green;
            color: white;
        }

        .status-rejected {
            background-color: red;
            color: white;
        }

        .status-buttons a {
            margin-right: 10px;
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .status-buttons .accept {
            background-color: green;
        }

        .status-buttons .reject {
            background-color: red;
        }
    </style>
</head>
<body>
    <h2>Director's Application Panel</h2>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="application">
            <p>Name: <?php echo $row['name']; ?></p>
            <p>Email: <?php echo $row['email']; ?></p>
            <p>Status: 
                <span class="status-<?php echo strtolower($row['status']); ?>">
                    <?php echo ucfirst($row['status']); ?>
                </span>
            </p>

            <div class="status-buttons">
                <?php if ($row['status'] == 'Pending') { ?>
                    <a href="?update_status=Accepted&application_id=<?php echo $row['id']; ?>" class="accept">Accept</a>
                    <a href="?update_status=Rejected&application_id=<?php echo $row['id']; ?>" class="reject">Reject</a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</body>
</html>
