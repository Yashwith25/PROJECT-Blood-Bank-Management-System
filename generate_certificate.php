<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT name FROM users WHERE id = $user_id");
$name = $result->fetch_assoc()['name'];
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blood Donation Certificate</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f7f3f3;
            font-family: 'Georgia', serif;
        }

        .certificate-container {
            width: 1100px;
            height: 780px;
            background: url('certificate.jpeg') no-repeat center center;
            background-size: cover;
            margin: 30px auto;
            position: relative;
            padding: 60px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.4);
        }

        .overlay {
            text-align: center;
            color: #222;
            position: relative;
            z-index: 2;
        }

        .overlay h1 {
            font-size: 40px;
            color: #b30000;
            margin-bottom: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .overlay h2 {
            font-size: 24px;
            margin-top: 30px;
            color: #444;
        }

        .donor-name {
            font-size: 32px;
            font-weight: bold;
            color: #0077cc;
            margin: 20px 0;
        }

        .text-content {
            font-size: 18px;
            color: #333;
            margin: 30px auto;
            max-width: 80%;
            line-height: 1.6;
        }

        .signature {
            position: absolute;
            bottom: 60px;
            right: 80px;
            font-size: 16px;
            font-weight: bold;
            color: #444;
        }

        .issued {
            position: absolute;
            bottom: 60px;
            left: 80px;
            font-size: 14px;
            color: #777;
        }

        .print-button {
            text-align: center;
            margin: 30px auto;
        }

        .print-button button {
            padding: 12px 25px;
            font-size: 16px;
            background-color: #cc0000;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .print-button button:hover {
            background-color: #a30000;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="certificate-container">
    <div class="overlay">
        <h1>Certificate of Appreciation</h1>
        <h2>This certificate is proudly awarded to</h2>
        <div class="donor-name"><?= htmlspecialchars(strtoupper($name)) ?></div>

        <div class="text-content">
            For your generous donation of blood and contribution to saving lives.<br>
            Your act of kindness and humanity is deeply appreciated.<br><br>
            You are a real-life hero! Thank you for making a difference.
        </div>
    </div>

    <div class="signature">Blood Bank Administrator</div>
    <div class="issued">Issued on: <?= date("F d, Y") ?></div>
</div>

<div class="print-button">
    <button onclick="window.print()">Download as PDF</button>
</div>

</body>
</html>

