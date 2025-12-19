<?php
$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$email = $_POST['email'];
$password = $_POST['password'];
$result = $conn->query("SELECT * FROM users WHERE email='$email'");

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        session_start();
        $_SESSION['user_id'] = $row['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        // Wrong password
        showError("Incorrect password. Please try again.");
    }
} else {
    // Email not registered
    showError("Email not registered.");
}

$conn->close();

function showError($message) {
    echo "
    <html>
    <head>
        <title>Login Error</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #f85032, #e73827);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .alert-box {
                background: white;
                color: #e73827;
                padding: 30px 40px;
                border-radius: 15px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                text-align: center;
                max-width: 400px;
                animation: slideIn 0.5s ease;
            }
            h2 {
                margin-bottom: 20px;
            }
            .btn {
                background: linear-gradient(135deg, #e73827, #f85032);
                color: white;
                padding: 10px 25px;
                margin: 10px 5px 0;
                border: none;
                border-radius: 30px;
                cursor: pointer;
                font-size: 16px;
                transition: transform 0.2s ease, background 0.3s ease;
            }
            .btn:hover {
                transform: scale(1.05);
                background: linear-gradient(135deg, #d22f1b, #fa6149);
            }
            @keyframes slideIn {
                from {
                    transform: translateY(-50px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        </style>
    </head>
    <body>
        <div class='alert-box'>
            <h2>$message</h2>
            <button class='btn' onclick=\"location.href='register.html'\">Go to Register</button>
            <button class='btn' onclick=\"location.href='login.html'\">Back to Login</button>
        </div>
    </body>
    </html>
    ";
    exit();
}
?>
