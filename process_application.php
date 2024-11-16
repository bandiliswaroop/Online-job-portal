
<?php
include 'db.php';

$application_id = $_GET['id'];
$status = $_GET['status'];

$stmt = $conn->prepare("UPDATE applications SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $application_id);
$stmt->execute();

header('Location: director_dashboard.php');
?>