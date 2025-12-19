<?php
$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$bloodStock = $conn->query("SELECT * FROM blood_stock");
$pendingDonations = $conn->query("SELECT * FROM pending_donations WHERE status = 'pending'");
$pendingRequests = $conn->query("SELECT * FROM requests WHERE status = 'pending'");
$donors = $conn->query("SELECT * FROM donations");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fdf0f0;
            margin: 0;
            padding: 30px;
            color: #333;
        }

        h2, h3 {
            color: #b30000;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .action-buttons {
            position: absolute;
            top: 0;
            right: 0;
            margin: 10px 20px;
        }

        .action-buttons a {
            text-decoration: none;
            padding: 8px 16px;
            margin-left: 10px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            background-color: #b71c1c;
            color: white;
            transition: background-color 0.3s ease;
        }

        .action-buttons a:hover {
            background-color: #8b0000;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 40px;
        }

        .box {
            flex: 1;
            min-width: 200px;
            background: #fff;
            border: 2px solid #f2dada;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(204, 0, 0, 0.1);
            text-align: center;
        }

        .box h3 {
            margin: 10px 0;
            color: #cc0000;
        }

        .box p {
            font-size: 24px;
            color: #333;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th {
            background-color: #ffcccc;
            color: #800000;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #fff5f5;
        }

        a {
            text-decoration: none;
            padding: 6px 12px;
            margin: 0 2px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 13px;
        }

        a:hover {
            opacity: 0.85;
        }

        a[href*="approve"] {
            background-color: #28a745;
            color: white;
        }

        a[href*="deny"] {
            background-color: #dc3545;
            color: white;
        }

        a[href*="delete"] {
            background-color: #6c757d;
            color: white;
        }

        a[href*="edit"] {
            background-color: #007bff;
            color: white;
        }

        @media (max-width: 768px) {
            .card-container {
                flex-direction: column;
            }

            .action-buttons {
                position: static;
                margin-top: 10px;
                text-align: center;
            }

            .action-buttons a {
                display: inline-block;
                margin: 5px;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-header">
    <h2>🩸 Admin Dashboard - Blood Bank Management</h2>
    <div class="action-buttons">
        <a href="admin_login.html">🔙 Back</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<div class="card-container">
    <div class="box">
        <h3>Total Users</h3>
        <p><?= $totalUsers ?></p>
    </div>

    <?php while ($row = $bloodStock->fetch_assoc()): ?>
    <div class="box">
        <h3><?= $row['blood_type'] ?></h3>
        <p><?= $row['units'] ?> units</p>
    </div>
    <?php endwhile; ?>
</div>

<h3>📝 Pending Donation Requests</h3>
<table>
<tr>
    <th>Name</th>
    <th>Blood Type</th>
    <th>Units</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
<?php while ($row = $pendingDonations->fetch_assoc()): ?>
<tr>
    <td><?= $row['name'] ?></td>
    <td><?= $row['blood_type'] ?></td>
    <td><?= $row['units'] ?></td>
    <td><?= ucfirst($row['status']) ?></td>
    <td>
        <a href="admin_approve_donation.php?id=<?= $row['id'] ?>&action=approve">Approve</a>
        <a href="admin_approve_donation.php?id=<?= $row['id'] ?>&action=deny">Deny</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

<h3>🧾 Pending Blood Requests</h3>
<table>
<tr>
    <th>User ID</th>
    <th>Blood Type</th>
    <th>Units</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
<?php while ($row = $pendingRequests->fetch_assoc()): ?>
<tr>
    <td><?= $row['user_id'] ?></td>
    <td><?= $row['blood_type'] ?></td>
    <td><?= $row['units'] ?></td>
    <td><?= ucfirst($row['status']) ?></td>
    <td>
        <a href="admin_approve_request.php?id=<?= $row['id'] ?>&action=approve">Approve</a>
        <a href="admin_approve_request.php?id=<?= $row['id'] ?>&action=deny">Deny</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

<h3>🙋 Registered Donors</h3>
<table>
<tr>
    <th>Name</th>
    <th>Blood Type</th>
    <th>Units</th>
    <th>Email</th>
    <th>Action</th>
</tr>
<?php while ($row = $donors->fetch_assoc()): ?>
<tr>
    <td><?= $row['name'] ?></td>
    <td><?= $row['blood_type'] ?></td>
    <td><?= $row['units'] ?></td>
    <td><?= $row['email'] ?></td>
    <td>
        <a href="edit_donor.php?id=<?= $row['id'] ?>">Edit</a>
        <a href="delete_donor.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this donor?');">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>

