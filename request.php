<?php
session_start();
$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$blood_type = $_POST['blood_type'];
$units = $_POST['units'];

$stmt = $conn->prepare("INSERT INTO requests (user_id, blood_type, units, status) VALUES (?, ?, ?, 'pending')");
$stmt->bind_param("isi", $user_id, $blood_type, $units);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Blood</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fdf0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(204, 0, 0, 0.2);
            text-align: center;
        }
        h2 {
            color: #cc0000;
        }
        a.button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #cc0000;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        a.button:hover {
            background: #a30000;
        }
    </style>
</head>
<body>

<div class="message-box">
    <?php
    if ($stmt->execute()) {
        echo "<h2>Blood request submitted successfully!</h2>";
    } else {
        echo "<h2>Error: " . htmlspecialchars($stmt->error) . "</h2>";
    }
    $stmt->close();
    $conn->close();
    ?>
    <a class="button" href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
