<?php
session_start();
if (!isset($_SESSION['admin'])) {
    die("Access denied");
}

$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = intval($_GET['id']);
$action = $_GET['action'] ?? '';

if (!$id || !in_array($action, ['approve', 'deny', 'delete'])) {
    die("Invalid request");
}

// Fetch the request details
$stmt = $conn->prepare("SELECT * FROM requests WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$request = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$request) {
    die("Request not found");
}

$blood_type = $request['blood_type'];
$units = intval($request['units']);

function showMessage($title, $message, $icon = '✅') {
    echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>{$title}</title>
    <style>
        body {
            background-color: #fff5f5;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .message-box {
            background-color: #ffffff;
            border: 2px solid #ffcccc;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(204, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
        }
        .message-box h2 {
            color: #cc0000;
            margin-bottom: 20px;
        }
        .message-box p {
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
        }
        .btn {
            padding: 12px 20px;
            background-color: #cc0000;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #a30000;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>{$icon} {$title}</h2>
        <p>{$message}</p>
        <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>
HTML;
    exit;
}

if ($action === 'approve') {
    $stmt = $conn->prepare("SELECT units FROM blood_stock WHERE blood_type = ?");
    $stmt->bind_param("s", $blood_type);
    $stmt->execute();
    $stock = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$stock || $stock['units'] < $units) {
        showMessage("Insufficient Stock", "Not enough blood units available to approve the request.", "⚠️");
    }

    $stmt = $conn->prepare("UPDATE blood_stock SET units = units - ? WHERE blood_type = ?");
    $stmt->bind_param("is", $units, $blood_type);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE requests SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    showMessage("Request Approved", "The request for <strong>$units</strong> unit(s) of <strong>$blood_type</strong> blood has been approved.");
}

if ($action === 'deny') {
    $stmt = $conn->prepare("UPDATE requests SET status = 'denied' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    showMessage("Request Denied", "The blood request has been denied.", "❌");
}

if ($action === 'delete') {
    $stmt = $conn->prepare("DELETE FROM requests WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    showMessage("Request Deleted", "The blood request has been successfully deleted from the system.", "🗑️");
}

$conn->close();
header("Location: admin_dashboard.php");
exit;
