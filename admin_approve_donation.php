<?php
$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = intval($_GET['id']); // donation request ID
$action = $_GET['action']; // approve or deny

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

// Get pending donation entry
$donation = $conn->query("SELECT * FROM pending_donations WHERE id = $id")->fetch_assoc();

if (!$donation) {
    showMessage("Donation Not Found", "The donation entry with ID $id does not exist.", "❌");
}

if ($action === 'approve') {
    // Insert into donations table
    $stmt = $conn->prepare("INSERT INTO donations (name, age, blood_type, units, disease, phone, email, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sisissss",
        $donation['name'], $donation['age'], $donation['blood_type'], $donation['units'],
        $donation['disease'], $donation['phone'], $donation['email'], $donation['address']
    );
    $stmt->execute();
    $stmt->close();

    // Update blood_stock
    $blood_type = $donation['blood_type'];
    $units = $donation['units'];
    $check = $conn->query("SELECT * FROM blood_stock WHERE blood_type = '$blood_type'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE blood_stock SET units = units + $units WHERE blood_type = '$blood_type'");
    } else {
        $conn->query("INSERT INTO blood_stock (blood_type, units) VALUES ('$blood_type', $units)");
    }

    // Update pending_donations status
    $conn->query("UPDATE pending_donations SET status = 'approved' WHERE id = $id");

    showMessage("Donation Approved", "Donation of <strong>$units</strong> unit(s) of <strong>$blood_type</strong> blood has been approved and added to inventory.");
} elseif ($action === 'deny') {
    $conn->query("UPDATE pending_donations SET status = 'denied' WHERE id = $id");
    showMessage("Donation Denied", "The donation request has been denied.", "❌");
} else {
    showMessage("Invalid Action", "The specified action is not valid.", "⚠️");
}

$conn->close();
?>
