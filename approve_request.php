<?php
session_start();
if (!isset($_SESSION['admin'])) die("Access Denied");

$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Sanitize and fetch inputs
$id = (int)$_POST['id'];
$action = $_POST['action'];
$blood_type = $conn->real_escape_string($_POST['blood_type']);
$units = (int)$_POST['units'];

if ($action === 'approve') {
    $result = $conn->query("SELECT units FROM blood_stock WHERE blood_type = '$blood_type'");
    
    if ($result && $result->num_rows > 0) {
        $stock = $result->fetch_assoc();
        if ($stock['units'] >= $units) {
            // Deduct units and approve
            $conn->query("UPDATE blood_stock SET units = units - $units WHERE blood_type = '$blood_type'");
            $conn->query("UPDATE requests SET status = 'approved' WHERE id = $id");
        } else {
            echo "Not enough blood stock.";
            exit();
        }
    } else {
        echo "Blood type not found in stock.";
        exit();
    }
} elseif ($action === 'deny') {
    $conn->query("UPDATE requests SET status = 'denied' WHERE id = $id");
}

$conn->close();
header("Location: admin_dashboard.php");
exit();
?>
