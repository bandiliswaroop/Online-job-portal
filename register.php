<?php
include 'db.php';

$registration_success = false;

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $username, $password, $role);
    if ($stmt->execute()) {
        $registration_success = true;
    } else {
        echo "<script>alert('Registration failed: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* Background styling */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('https://i.ibb.co/cwm1Y3H/image.png') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        .register-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .register-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            width: 350px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .register-box:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 20px rgba(255, 153, 51, 0.6);
        }

        .register-box h1 {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #ffffff;
        }

        .register-box .password-container {
            position: relative;
            width: 100%;
        }

        .register-box input[type="text"],
        .register-box input[type="password"],
        .register-box select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid transparent;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .register-box input[type="text"]:focus,
        .register-box input[type="password"]:focus {
            outline: none;
            box-shadow: 0 0 10px 2px rgba(255, 153, 51, 0.7), 0 0 20px rgba(255, 153, 51, 0.5);
        }

        /* Eye icon styling */
        .eye-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #ffffff;
            opacity: 0.7;
            font-size: 20px;
        }

        .eye-icon:hover {
            opacity: 1;
        }

        .register-box button {
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

        .register-box button:hover {
            background-color: #e68a00;
        }

        .login-link {
            display: block;
            margin-top: 20px;
            color: #ffffff;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .login-link:hover {
            opacity: 1;
        }

        /* Success message styling */
        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: #fff;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            font-weight: bold;
            animation: fadeOut 5s forwards; /* Fades out after 5 seconds */
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            90% { opacity: 0.9; }
            100% { opacity: 0; display: none; }
        }
    </style>
</head>
<body>
    <?php if ($registration_success): ?>
        <div class="success-message">User registered successfully!</div>
    <?php endif; ?>

    <div class="register-container">
        <div class="register-box">
            <h1>Register for Job Portal</h1>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                
                <div class="password-container">
                    <input type="password" name="password" placeholder="Password" required id="password">
                    <span class="eye-icon" onclick="togglePassword()">👁️</span>
                </div>

                <select name="role" required>
                    <option value="applicant">Applicant</option>
                    <option value="director">Director</option>
                </select>
                <button type="submit">Register</button>
            </form>
            <a href="index.php" class="login-link">Already have an account? Login</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const type = passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;
        }
    </script>
</body>
</html>
