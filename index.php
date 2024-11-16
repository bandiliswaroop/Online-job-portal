<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'director') {
            header('Location: director_dashboard.php');
        } else {
            header('Location: home.php');
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Invalid credentials</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Background styling */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('https://i.ibb.co/pJv6t4c/image.png') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        /* Welcome Text */
        .welcome-text {
            font-size: 50px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(45deg, #FF9800, #FF4081, #00E5FF, #76FF03);
            background-clip: text;
            -webkit-background-clip: text;
            text-fill-color: transparent;
            text-align: center;
            animation: shine 1.5s ease-in-out infinite alternate;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.6), 0 0 30px rgba(255, 255, 255, 0.4);
            margin-top: 20px;
            margin-bottom: 50px; /* Added space between welcome text and login box */
        }

        @keyframes shine {
            0% {
                text-shadow: 0 0 20px rgba(255, 255, 255, 0.6), 0 0 30px rgba(255, 255, 255, 0.5), 0 0 40px rgba(255, 255, 255, 0.3);
            }
            100% {
                text-shadow: 0 0 20px rgba(255, 255, 255, 1), 0 0 40px rgba(255, 255, 255, 0.8), 0 0 60px rgba(255, 255, 255, 0.5);
            }
        }

        /* Centered login box */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            width: 300px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Pop effect on hover for login box */
        .login-box:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 20px rgba(255, 153, 51, 0.6);
        }

        /* Title styling */
        .login-box h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #ffffff;
        }

        /* Input fields styling */
        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid transparent;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            position: relative;
            z-index: 1;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Pop effect on hover/focus for input fields */
        .login-box input[type="text"]:hover,
        .login-box input[type="password"]:hover,
        .login-box input[type="text"]:focus,
        .login-box input[type="password"]:focus {
            transform: scale(1.05);
            box-shadow: 0 0 10px 2px rgba(255, 153, 51, 0.7), 0 0 20px rgba(255, 153, 51, 0.5);
        }

        /* Eye icon styling */
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            z-index: 10; /* Ensure it's above the input field */
        }

        /* Button styling */
        .login-box button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            border: none;
            border-radius: 10px;
            background-color: #FF9800;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-box button:hover {
            background-color: #e68a00;
        }

        /* Register link styling */
        .register-link {
            display: block;
            margin-top: 20px;
            color: #ffffff;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .register-link:hover {
            opacity: 1;
        }

        /* Footer styling */
        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="welcome-text">
        Welcome to Job Portal
    </div>
    <div class="login-container">
        <div class="login-box">
            <h1>Login to Job Portal</h1>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <span class="eye-icon" id="eye-icon">&#128065;</span> <!-- Eye Icon -->
                </div>
                <button type="submit">Login</button>
            </form>
            <a href="register.php" class="register-link">Register</a>
        </div>
    </div>

    <script>
        // JavaScript to toggle password visibility
        const eyeIcon = document.getElementById('eye-icon');
        const passwordInput = document.getElementById('password');

        // Initially, password is hidden with the closed eye icon
        let isPasswordVisible = false;

        eyeIcon.addEventListener('click', function() {
            if (isPasswordVisible) {
                passwordInput.type = 'password';  // Hide password
                eyeIcon.innerHTML = '&#128065;';  // Show closed eye icon
                isPasswordVisible = false;
            } else {
                passwordInput.type = 'text';  // Show password
                eyeIcon.innerHTML = '&#128586;';  // Show open eye icon
                isPasswordVisible = true;
            }
        });
    </script>

    <!-- Footer -->
    <footer>
        &copy; 2024 Job Portal. All rights reserved.
    </footer>
</body>
</html>
