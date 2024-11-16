<?php
// Include the database connection file
include('db.php');

// Get location and category from the filter form (assuming they're passed via GET method)
$location = isset($_GET['location']) ? $_GET['location'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare the SQL query dynamically based on available filters
$sql = "SELECT * FROM jobs WHERE 1=1";
$params = [];
$types = "";

// Add location to the query if selected
if (!empty($location)) {
    $sql .= " AND location = ?";
    $params[] = $location;
    $types .= "s";
}

// Add category to the query if selected
if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

$stmt = $conn->prepare($sql);

// Bind parameters if any filters were applied
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Display available jobs if found
if ($result->num_rows > 0) {
    echo "<h2 style='text-align: center; color: #FF9800;'>Available Jobs" . (!empty($location) ? " in $location" : "") . (!empty($category) ? " for $category" : "") . "</h2>";

    while ($row = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ddd; padding: 20px; margin: 10px 0; background-color: rgba(255, 255, 255, 0.8); border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);'>";
        
        // Display company logo if available
        if (!empty($row['logo'])) {
            echo "<img src='images/" . htmlspecialchars($row['logo']) . "' alt='" . htmlspecialchars($row['company']) . " logo' style='width: 60px; height: 60px; float: left; margin-right: 20px; border-radius: 50%;'>";
        }

        

        // Display job details
        echo "<h3 style='font-size: 24px; color: #333;'>" . htmlspecialchars($row['company']) . "</h3>";
        echo "<p style='font-size: 16px; color: #555;'><strong>Vacancies:</strong> " . htmlspecialchars($row['vacancies']) . "</p>";
        echo "<p style='font-size: 16px; color: #555;'><strong>Requirements:</strong> " . htmlspecialchars($row['requirements']) . "</p>";

        // Apply button that links to the application page with job ID
        echo "<form action='apply.php' method='GET' style='margin-top: 15px;'>";
        echo "<input type='hidden' name='job_id' value='" . htmlspecialchars($row['id']) . "'>";
        echo "<button type='submit' style='padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 6px; cursor: pointer; transition: background-color 0.3s;'>Apply</button>";
        echo "</form>";

        echo "<div style='clear: both;'></div>";
        echo "</div>";
    }
} else {
    echo "<h3 style='text-align: center; color: #FF9800;'>No jobs found" . (!empty($location) ? " in $location" : "") . (!empty($category) ? " for $category" : "") . ".</h3>";
}

// Close the database connection
$stmt->close();
$conn->close();
?>
