<?php
session_start();
$admin_user = 'admin';
$admin_pass = 'admin123'; // change as needed
if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
    $_SESSION['admin'] = true;
    header("Location: admin.html");
    exit();
} else {
    echo "Invalid admin login";
}
?>