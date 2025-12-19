<?php
session_start();
$admin_user = 'admin';
$admin_pass = 'admin123'; // You can change this

if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
    $_SESSION['admin'] = true;
    header("Location: admin_dashboard.php");
    exit();
} else {
    showAdminError("Invalid admin login credentials.");
}

function showAdminError($message) {
    echo "
    <html>
    <head>
        <title>Admin Login Error</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #1f1c2c, #928dab);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #333;
            }
            .alert-box {
                background: #fff;
                padding: 30px 40px;
                border-radius: 15px;
                text-align: center;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                animation: fadeIn 0.6s ease;
            }
            h2 {
                color: #d32f2f;
                margin-bottom: 20px;
            }
            .btn {
                background: linear-gradient(to right, #ff416c, #ff4b2b);
                color: white;
                padding: 10px 22px;
                margin: 10px 8px;
                border: none;
                border-radius: 25px;
                font-size: 15px;
                cursor: pointer;
                transition: transform 0.2s ease;
            }
            .btn:hover {
                transform: scale(1.05);
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body>
        <div class='alert-box'>
            <h2>$message</h2>
            <button class='btn' onclick=\"location.href='admin_login.html'\">Try Again</button>
            <button class='btn' onclick=\"location.href='index.html'\">Back to Home</button>
        </div>
    </body>
    </html>
    ";
    exit();
}
?>
