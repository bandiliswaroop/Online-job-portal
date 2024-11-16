<?php
include 'db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize form inputs
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitizing email
    $phone = htmlspecialchars($_POST['phone']);
    $resume = htmlspecialchars($_POST['resume']);

    // Handling file upload for resume
    $resume_file = '';
    if (isset($_FILES['resume_file']) && $_FILES['resume_file']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['resume_file']['name']);
        
        // Ensure the uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Check file type (optional: only allow specific types)
        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (in_array($_FILES['resume_file']['type'], $allowed_types)) {
            // Move file to the server's directory
            if (move_uploaded_file($_FILES['resume_file']['tmp_name'], $target_file)) {
                $resume_file = $target_file;
            } else {
                die("Error: Failed to upload file.");
            }
        } else {
            die("Error: Invalid file type. Only PDF and Word documents are allowed.");
        }
    } else {
        die("Error: No file uploaded or file upload error.");
    }

    // Prepare SQL statement
    $sql = "INSERT INTO applications (name, email, phone, resume, resume_file, status) VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $resume, $resume_file);

    // Execute and check for errors
    if ($stmt->execute()) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}
?>
