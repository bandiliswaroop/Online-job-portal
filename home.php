<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Home</title>
    <style>
        /* Reset default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #83a4d4, #b6fbff);
            display: flex;
            justify-content: center;
            padding: 20px;
            color: #333;
            min-height: 100vh;
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1200px;
        }

        /* Sidebar styling */
        .sidebar {
            width: 250px;
            background-color: #ffffffcc;
            padding: 20px;
            border-radius: 8px;
            margin-right: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .sidebar:hover {
            transform: translateY(-3px);
        }
        .sidebar h2 {
            font-size: 1.5em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 20px;
        }
        .sidebar label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }
        .sidebar select,
        .sidebar button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .sidebar button {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .sidebar button:hover {
            background-color: #0056b3;
        }
        .sidebar a {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .sidebar a:hover {
            color: #0056b3;
        }

        /* Main content area */
        .content {
            flex: 1;
        }

        .content h2 {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        /* Job card grid */
        .job-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* Job card styling */
        .job-card {
            width: calc(33% - 20px);
            background-color: #ffffffcc;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }
        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Job card content */
        .job-card img {
            width: 120px;
            height: 120px;
            margin-bottom: 15px;
            object-fit: contain;
            border-radius: 50%;
            border: 3px solid #007bff;
            transition: transform 0.3s ease;
        }
        .job-card img:hover {
            transform: scale(1.05);
        }
        .job-card h3 {
            font-size: 1.3em;
            margin-bottom: 10px;
            color: #333;
        }
        .job-card p {
            margin: 5px 0;
            color: #555;
        }

        /* Apply button styling */
        .apply-btn {
            display: inline-block;
            width: 100%;
            padding: 10px;
            text-align: center;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .apply-btn:hover {
            background-color: #218838;
        }

        /* No jobs message */
        .no-jobs {
            text-align: center;
            margin-top: 50px;
            color: #777;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .no-jobs img {
            width: 180px;
            margin-bottom: 20px;
            border-radius: 50%;
            border: 4px solid #ff6b6b;
        }
        .no-jobs h3 {
            font-size: 1.6em;
            margin-bottom: 10px;
            color: #ff6b6b;
        }
        .no-jobs p {
            font-size: 1em;
            color: #555;
        }
        /* Job card styling */
.job-card {
    width: calc(33% - 20px);
    background-color: #ffffffcc;
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease, border 0.3s ease;
    text-align: center;
    position: relative;
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    border: 2px solid transparent;
}

/* Glowing effect with a colorful gradient */
.job-card:hover::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 12px;
    box-shadow: 0 0 20px 8px rgba(255, 105, 180, 0.8), /* Pink */
                0 0 25px 12px rgba(255, 165, 0, 0.7),   /* Orange */
                0 0 30px 16px rgba(34, 193, 195, 0.8);   /* Aqua */
    z-index: -1; /* Place behind the content */
}

    </style>
</head>
<body>

<div class="container">
    <!-- Sidebar for filters -->
    <div class="sidebar">
        <h2>Filters</h2>
        <form action="" method="GET">
        
            <label for="location">Location</label>
            <select name="location" id="location">
                <option value="">Select Location</option>
                <option value="Chennai">Chennai</option>
                <option value="Bengaluru">Bengaluru</option>
                <option value="Delhi">Delhi</option>
                <option value="Mumbai">Mumbai</option>
                <option value="Hyderabad">Hyderabad</option>
                <option value="Pune">Pune</option>
                <option value="Kolkata">Kolkata</option>
                <option value="Ahmedabad">Ahmedabad</option>
                <option value="Jaipur">Jaipur</option>
                <option value="Lucknow">Lucknow</option>
                <option value="Indore">Indore</option>
                <option value="Coimbatore">Coimbatore</option>
                <option value="Chandigarh">Chandigarh</option>
                <option value="Bhopal">Bhopal</option>
            </select>

            <label for="category">Category</label>
            <select name="category" id="category">
                <option value="">Select Category</option>
                <option value="Software Developer">Software Developer</option>
                <option value="Data Scientist">Data Scientist</option>
                <option value="Product Manager">Product Manager</option>
                <option value="Graphic Designer">Graphic Designer</option>
                <option value="Digital Marketing">Digital Marketing</option>
                <option value="Human Resources">Human Resources</option>
                <option value="Sales">Sales</option>
                <option value="Operations Manager">Operations Manager</option>
                <option value="Business Analyst">Business Analyst</option>
                <option value="Project Manager">Project Manager</option>
                <option value="Customer Support">Customer Support</option>
                <option value="Content Writer">Content Writer</option>
                <option value="Financial Analyst">Financial Analyst</option>
                <option value="Network Engineer">Network Engineer</option>
                <option value="Data Engineer">Data Engineer</option>
                <option value="Data Analyst">Data Analyst</option>
                <option value="Cybersecurity Analyst">Cybersecurity Analyst</option>
                <option value="HR Manager">HR Manager</option>
                <option value="IT Support">IT Support</option>
                <option value="Cloud Architect">Cloud Architect</option>
                <option value="Database Administrator">Database Administrator</option>
                <option value="Embedded Systems Engineer">Embedded Systems Engineer</option>
                <option value="Telecom Engineer">Telecom Engineer</option>
                <option value="Mobile App Developer">Mobile App Developer</option>
                <option value="Firmware Engineer">Firmware Engineer</option>
            </select>

            <button type="submit">Apply Filters</button>
            <button type="button" onclick="window.location.href='status.php'">Status</button>
        </form>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content area for job listings -->
    <div class="content">
        <h2>Recommended Jobs</h2>
        <div class="job-list">
            <?php
            // Filter job listings
            $location = isset($_GET['location']) ? $_GET['location'] : '';
            $category = isset($_GET['category']) ? $_GET['category'] : '';
            $sql = "SELECT * FROM jobs WHERE 1=1";
            $params = [];
            $types = "";

            if (!empty($location)) {
                $sql .= " AND location = ?";
                $params[] = $location;
                $types .= "s";
            }
            if (!empty($category)) {
                $sql .= " AND category = ?";
                $params[] = $category;
                $types .= "s";
            }

            $stmt = $conn->prepare($sql);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            // Display job listings
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='job-card'>";
                    if (!empty($row['logo'])) {
                        echo "<img src='images/" . htmlspecialchars($row['logo']) . "' alt='" . htmlspecialchars($row['company']) . " logo'>";
                    }
                    echo "<h3>" . htmlspecialchars($row['company']) . "</h3>";
                    echo "<p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>";
                    echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>";
                    echo "<p><strong>Vacancies:</strong> " . htmlspecialchars($row['vacancies']) . "</p>";
                    echo "<p><strong>Requirements:</strong> " . htmlspecialchars($row['requirements']) . "</p>";
                    echo "<p><strong>Salary:</strong> " . htmlspecialchars($row['salary']) . " LPA</p>";

                    echo "<a href='apply.php?job_id=" . htmlspecialchars($row['id']) . "' class='apply-btn'>Apply</a>";
                    echo "</div>";
                }
            } else {
                echo "<div class='no-jobs'>";
                echo "<img src='https://i.ibb.co/rpPvQ4x/download.jpg' alt='No jobs available'>";
                echo "<h3>No jobs found</h3>";
                echo "<p>We're sorry, but no jobs were found that match your criteria.<br>Please try adjusting your filters or check back later.</p>";
                echo "</div>";
            }

            // Close connection
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
</div>

</body>
</html>
