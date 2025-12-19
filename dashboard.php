<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];

// Get username from database
$user_result = $conn->query("SELECT name FROM users WHERE id = $user_id");
$user_row = $user_result->fetch_assoc();
$username = $user_row['name'];

// Fetch donation requests by user
$donation_requests = $conn->query("SELECT * FROM pending_donations WHERE user_id = $user_id ORDER BY requested_at DESC");

// Fetch blood requests by user
$blood_requests = $conn->query("SELECT * FROM requests WHERE user_id = $user_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff0f0;
            margin: 0;
            padding: 30px;
        }

        .dashboard-container {
            max-width: 1000px;
            margin: auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(204, 0, 0, 0.2);
        }

        h2 {
            color: #cc0000;
            margin-bottom: 20px;
        }

        .nav-buttons {
            margin-bottom: 30px;
        }

        .nav-buttons a {
            display: inline-block;
            margin-right: 10px;
            background-color: #cc0000;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .nav-buttons a:hover {
            background-color: #a30000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 40px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: #f9caca;
            color: #660000;
        }

        .status-button {
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }

        .status-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <h2>Welcome, <?= htmlspecialchars($username) ?>!</h2>

    <div class="nav-buttons">
        <a href="donate.html">Donate Blood</a>
        <a href="request_blood.html">Request Blood</a>
        <a href="logout.php">Logout</a>
    </div>

    <h3>Your Donation Requests</h3>
    <table>
        <tr>
            <th>Blood Type</th>
            <th>Units</th>
            <th>Status</th>
            <th>Requested On</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $donation_requests->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['blood_type']) ?></td>
            <td><?= htmlspecialchars($row['units']) ?></td>
            <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
            <td><?= htmlspecialchars($row['requested_at']) ?></td>
            <td>
                <?php if ($row['status'] === 'approved'): ?>
                    <a href="generate_certificate.php?id=<?= $row['id'] ?>" class="status-button" target="_blank">Avail Donation Certificate</a>
                <?php else: ?>
                    <a href="user_dashboard.php" class="status-button">Check Status</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <h3>Your Blood Requests</h3>
    <table>
        <tr>
            <th>Blood Type</th>
            <th>Units</th>
            <th>Status</th>
            <th>Requested On</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $blood_requests->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['blood_type']) ?></td>
            <td><?= htmlspecialchars($row['units']) ?></td>
            <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
            <td><?= htmlspecialchars($row['requested_at'] ?? '') ?></td>
            <td><a href="user_dashboard.php" class="status-button">Check Status</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    
</div>
</body>
</html>

<?php $conn->close(); ?>


