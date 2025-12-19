<?php
$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM donations WHERE id = $id");
if ($result->num_rows == 0) {
    die("Donor not found.");
}
$donor = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST['name'];
    $new_age = $_POST['age'];
    $new_blood_type = $_POST['blood_type'];
    $new_units = $_POST['units'];
    $new_disease = $_POST['disease'];
    $new_phone = $_POST['phone'];
    $new_email = $_POST['email'];
    $new_address = $_POST['address'];

    $old_type = $donor['blood_type'];
    $old_units = $donor['units'];

    if ($old_type !== $new_blood_type || $old_units != $new_units) {
        $conn->query("UPDATE blood_stock SET units = units - $old_units WHERE blood_type = '$old_type'");
        $exists = $conn->query("SELECT * FROM blood_stock WHERE blood_type = '$new_blood_type'");
        if ($exists->num_rows > 0) {
            $conn->query("UPDATE blood_stock SET units = units + $new_units WHERE blood_type = '$new_blood_type'");
        } else {
            $conn->query("INSERT INTO blood_stock (blood_type, units) VALUES ('$new_blood_type', $new_units)");
        }
    }

    $update = $conn->prepare("UPDATE donations SET name=?, age=?, blood_type=?, units=?, disease=?, phone=?, email=?, address=? WHERE id=?");
    $update->bind_param("sisissssi", $new_name, $new_age, $new_blood_type, $new_units, $new_disease, $new_phone, $new_email, $new_address, $id);
    $update->execute();

    // Show success message and styled return link
    echo "
    <div style='
        max-width: 500px;
        margin: 60px auto;
        background: #e8f5e9;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 0 15px rgba(0, 128, 0, 0.1);
        border: 2px solid #81c784;
        font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif;
    '>
        <h2 style='color: #2e7d32;'>✅ Donor updated successfully!</h2>
        <p>
            <a href='admin_dashboard.php' style='
                display: inline-block;
                margin-top: 15px;
                padding: 10px 20px;
                background-color: #388e3c;
                color: white;
                text-decoration: none;
                border-radius: 6px;
                transition: background-color 0.3s ease;
                font-weight: bold;
            ' onmouseover='this.style.backgroundColor=\"#2e7d32\"' onmouseout='this.style.backgroundColor=\"#388e3c\"'>
                ← Back to Admin Dashboard
            </a>
        </p>
    </div>";

    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>🩸 Edit Donor - Blood Bank System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff5f5;
            color: #333;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #c62828;
        }

        form {
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(198, 40, 40, 0.1);
            border: 2px solid #ffcdd2;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        input[type="submit"] {
            background-color: #d32f2f;
            color: #fff;
            border: none;
            padding: 12px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #b71c1c;
        }

        .note {
            text-align: center;
            margin-top: 20px;
        }

        .note a {
            color: #d32f2f;
            text-decoration: none;
        }

        .note a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>🩸 Edit Donor Information</h2>

<form method="POST">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($donor['name']) ?>" required>

    <label>Age:</label>
    <input type="number" name="age" value="<?= $donor['age'] ?>" required>

    <label>Blood Type:</label>
    <select name="blood_type" required>
        <?php
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        foreach ($bloodTypes as $type) {
            $selected = ($type == $donor['blood_type']) ? "selected" : "";
            echo "<option value='$type' $selected>$type</option>";
        }
        ?>
    </select>

    <label>Units:</label>
    <input type="number" name="units" value="<?= $donor['units'] ?>" required>

    <label>Disease:</label>
    <input type="text" name="disease" value="<?= htmlspecialchars($donor['disease']) ?>">

    <label>Phone:</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($donor['phone']) ?>">

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($donor['email']) ?>">

    <label>Address:</label>
    <textarea name="address" rows="3"><?= htmlspecialchars($donor['address']) ?></textarea>

    <input type="submit" value="Update Donor">
</form>

<div class="note">
    <a href="admin_dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
