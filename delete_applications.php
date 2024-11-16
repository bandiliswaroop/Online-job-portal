<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Deletion Status</title>
    <style>
        /* Ocean effect background with gradient */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #0077be, #0099cc, #33ccff, #66d9ff, #99e6ff);
            background-size: 200% 200%;
            transition: background-position 0.1s ease;
            color: #333;
            overflow: hidden;
        }

        /* Message box styling with fade and scale animation */
        .message-box {
            padding: 20px;
            max-width: 450px;
            text-align: center;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent for subtle overlay effect */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
            animation: fadeInBox 1.5s ease-out;
        }

        /* Text styling with a staggered fade-in effect */
        .success, .error, .no-selection {
            font-size: 18px;
            font-weight: bold;
            opacity: 0; /* Start hidden */
            animation: fadeInText 1.5s ease-out 0.7s forwards; /* Delayed fade-in */
        }
        .success { color: #4caf50; }
        .error { color: #e53935; }
        .no-selection { color: #fb8c00; }

        /* Button styling */
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background-color: #0077be;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        /* Button hover effect */
        .back-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Animation for message box */
        @keyframes fadeInBox {
            from { opacity: 0; transform: translateY(-30px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Animation for text */
        @keyframes fadeInText {
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="message-box">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['application_ids'])) {
            // Prepare the SQL statement with placeholders for application IDs
            $applicationIds = $_POST['application_ids'];
            $placeholders = implode(',', array_fill(0, count($applicationIds), '?'));
            $sql = "DELETE FROM applications WHERE id IN ($placeholders)";

            $stmt = $conn->prepare($sql);
            
            // Bind each application ID to the statement
            $types = str_repeat('i', count($applicationIds)); // 'i' indicates integer type
            $stmt->bind_param($types, ...$applicationIds);
            
            if ($stmt->execute()) {
                echo "<p class='success'>Selected applications deleted successfully.</p>";
            } else {
                echo "<p class='error'>Error deleting applications: " . $stmt->error . "</p>";
            }

            $stmt->close();
            $conn->close();
            // Redirect back to the dashboard after deletion
            header("Location: director_dashboard.php?status=" . $_GET['status']);
            exit();
        } else {
            echo "<p class='no-selection'>No applications selected for deletion.</p>";
        }
        ?>

        <!-- Back to Apply Page Button -->
        <a href="apply.php" class="back-button">Go to Apply Page</a>
    </div>

    <!-- JavaScript for ocean effect that follows the cursor -->
    <script>
        document.body.addEventListener('mousemove', (e) => {
            const { innerWidth: width, innerHeight: height } = window;
            const xPos = e.clientX / width;
            const yPos = e.clientY / height;
            
            // Adjust background position based on cursor position
            document.body.style.backgroundPosition = `${xPos * 100}% ${yPos * 100}%`;
        });
    </script>
</body>
</html>
