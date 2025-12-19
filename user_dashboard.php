<?php
session_start();
$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['name'] ?? 'User';

// Fetch donation requests
$donations = $conn->query("SELECT * FROM pending_donations WHERE user_id = $user_id ORDER BY requested_at DESC");

// Fetch blood requests
$requests = $conn->query("SELECT * FROM requests WHERE user_id = $user_id ORDER BY requested_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url() no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        .dashboard {
            background-color: rgba(255, 255, 255, 0.95);
            margin: 60px auto;
            padding: 40px;
            max-width: 900px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(204, 0, 0, 0.3);
        }

        h2 {
            color: #cc0000;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #cc0000;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .nav-links {
            text-align: center;
            margin-bottom: 20px;
        }

        .nav-links a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 15px;
            background-color: #cc0000;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links a:hover {
            background-color: #a30000;
        }

        .status-pending {
            color: #ff9900;
            font-weight: bold;
        }

        .status-approved {
            color: green;
            font-weight: bold;
        }

        .status-denied {
            color: red;
            font-weight: bold;
        }

        .section-title {
            margin-top: 50px;
            font-size: 1.3em;
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Welcome, <?= htmlspecialchars($username) ?>!</h2>

        <div class="nav-links">
            <a href="dashboard.php">Back</a>
            <a href="logout.php">Logout</a>
        </div>

        <h3 class="section-title">Your Donation Requests</h3>
        <table>
            <tr>
                <th>Blood Type</th>
                <th>Units</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
            <?php while($row = $donations->fetch_assoc()): ?>
            <tr>
                <td><?= $row['blood_type'] ?></td>
                <td><?= $row['units'] ?></td>
                <td class="status-<?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></td>
                <td><?= $row['requested_at'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3 class="section-title">Your Blood Requests</h3>
        <table>
            <tr>
                <th>Blood Type</th>
                <th>Units</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
            <?php while($row = $requests->fetch_assoc()): ?>
            <tr>
                <td><?= $row['blood_type'] ?></td>
                <td><?= $row['units'] ?></td>
                <td class="status-<?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></td>
                <td><?= $row['requested_at'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
