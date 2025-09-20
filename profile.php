<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$uid");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
</head>
<body>
<h2>My Profile</h2>
<p><b>Name:</b> <?= $user['fullname'] ?></p>
<p><b>Email:</b> <?= $user['email'] ?></p>
<p><b>Position:</b> <?= $user['position'] ?></p>
<p><b>Program:</b> <?= $user['program'] ?></p>
<p><b>Job Functions:</b> <?= $user['job_functions'] ?></p>
<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
