<?php
session_start();
include 'db.php';

$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = $_POST['job_id'];
    $applicant_id = $_SESSION['user_id']; // Assumes user ID is stored in session
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $resume_text = $_POST['resume'];

    // Handling file upload
    if (isset($_FILES['resume_file']) && $_FILES['resume_file']['error'] == 0) {
        $resume_file = $_FILES['resume_file']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($resume_file);
        move_uploaded_file($_FILES['resume_file']['tmp_name'], $target_file);
    } else {
        $target_file = null;
    }

    // Insert application data into database
    $stmt = $conn->prepare("INSERT INTO applications (vacancy_id, applicant_id, name, email, phone, resume, resume_file) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssss", $job_id, $applicant_id, $name, $email, $phone, $resume_text, $target_file);
    
    if ($stmt->execute()) {
        $success_message = "Application submitted successfully!";
    } else {
        $success_message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <style>
        /* Background styling for glass effect */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background-image: url('https://i.ibb.co/jW5Rq4m/image.png');
            background-size: cover;
            background-attachment: fixed;
            margin: 0;
            overflow: hidden;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            0% { background-position: 0% center; }
            50% { background-position: 100% center; }
            100% { background-position: 0% center; }
        }

        .home-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: rgba(0, 123, 255, 0.7);
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .home-button:hover {
            background-color: rgba(0, 86, 179, 0.7);
        }

        .application-form {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            width: 400px;
            text-align: center;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .application-form h2 {
            margin-bottom: 20px;
            color: #ffcc00;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .application-form input,
        .application-form textarea,
        .application-form button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.5);
            color: #333;
            font-size: 16px;
        }

        .application-form button {
            background-color: rgba(40, 167, 69, 0.7);
            color: #ffffff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .application-form button:hover {
            background-color: rgba(33, 136, 56, 0.7);
        }

        .rocket-container {
            display: none;
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
        }

        .rocket {
            width: 50px;
            height: 100px;
            background-color: #FF5733;
            clip-path: polygon(50% 0%, 100% 100%, 0% 100%);
            position: relative;
            animation: rocketFly 2s forwards;
        }

        .rocket:before {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 10px;
            height: 20px;
            background-color: #333;
            border-radius: 50%;
        }

        @keyframes rocketFly {
            0% { transform: translateY(0) rotate(0); opacity: 1; }
            50% { transform: translateY(-250px) rotate(180deg); opacity: 1; }
            100% { transform: translateY(-500px) rotate(360deg); opacity: 0; }
        }

    </style>
</head>
<body>

    <button class="home-button" onclick="window.location.href='home.php'">Home</button>

    <div class="application-form">
        <h2>Job Application Form</h2>

        <?php if (isset($success_message) && $success_message): ?>
            <div class="rocket-container" id="rocketContainer">
                <div class="rocket" id="rocket"></div>
            </div>

            <div class="success-message" id="rocketMessage" style="display: none;">
                <?php echo $success_message; ?>
            </div>

            <script>
                // Show success message and rocket animation after form submission
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        document.getElementById('rocketMessage').style.display = 'block';
                        document.getElementById('rocketContainer').style.display = 'block';
                        setTimeout(function() {
                            document.getElementById('rocket').style.animation = 'rocketFly 2s forwards';
                        }, 100);
                    }, 500);
                });
            </script>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="job_id" value="<?php echo $_GET['job_id']; ?>">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <textarea name="resume" placeholder="Enter your resume details here" required></textarea>
            <input type="file" name="resume_file" accept=".pdf,.doc,.docx" required>
            <button type="submit">
                <span class="rocket-button"></span> Submit Application
            </button>
        </form>
    </div>

</body>
</html>
