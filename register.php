<?php
$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$message = "";

// checking for email exist query 
$check = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($check);

if ($result->num_rows > 0) {
    $message = "X Email already exists. Please use another one.";
} else {
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    if ($conn->query($sql)) {
        $message = " Registered successfully.";
    } else {
        $message = " X Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Result</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #fff0f0;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .result-container {
            text-align: center;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(204, 0, 0, 0.4);
            max-width: 500px;
        }

        .result-container h2 {
            color: #cc0000;
        }

        .blood-image {
            width: 100px;
            margin-bottom: 20px;
        }

        .login-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #cc0000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .login-button:hover {
            background-color: #a30000;
        }
		.Back-button:hover{
		background-color: #a30000;
		}
		.Back-button{
		display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #cc0000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;	
		}
    </style>
</head>
<body>
    <div class="result-container">
        <img src="https://cdn-icons-png.flaticon.com/512/3159/3159310.png" class="blood-image" alt="Blood Drop">
        <h2><?php echo $message; ?></h2>
        <a href="login.html" class="login-button">Go to Login</a><br>
		<a href="register.html" class="Back-button">Back</a>
    </div>
</body>
</html>
