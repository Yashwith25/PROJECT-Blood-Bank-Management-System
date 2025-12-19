<?php
$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = intval($_GET['id']);

// Delete donor by ID
$conn->query("DELETE FROM donations WHERE id = $id");

$conn->close();
header("Location: admin_dashboard.php");
exit;
