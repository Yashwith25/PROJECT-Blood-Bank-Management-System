<?php
session_start();
$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$name = $conn->real_escape_string($_POST['name']);
$age = (int)$_POST['age'];
$blood_type = $conn->real_escape_string($_POST['blood_type']);
$units = (int)$_POST['units'];
$disease = $conn->real_escape_string($_POST['disease']);
$phone = $conn->real_escape_string($_POST['phone']);
$email = $conn->real_escape_string($_POST['email']);
$address = $conn->real_escape_string($_POST['address']);

// Add status = 'pending' and timestamp
$sql = "INSERT INTO pending_donations (user_id, name, age, blood_type, units, disease, phone, email, address, status, requested_at)
        VALUES ($user_id, '$name', $age, '$blood_type', $units, '$disease', '$phone', '$email', '$address', 'pending', NOW())";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Donation Submitted</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .message-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(204, 0, 0, 0.3);
            text-align: center;
        }

        h2 {
            color: #cc0000;
        }

        p {
            margin: 20px 0;
            font-size: 16px;
        }

        .button {
            display: inline-block;
            padding: 10px 16px;
            background-color: #cc0000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #a30000;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <?php if ($conn->query($sql)): ?>
            <h2>Donation Submitted!</h2>
            <p>Your donation request is under review. You can check the status on your dashboard.</p>
        <?php else: ?>
            <h2>Error</h2>
            <p><?= htmlspecialchars($conn->error) ?></p>
        <?php endif; ?>
        <a class="button" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>
