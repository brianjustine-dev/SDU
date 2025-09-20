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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f6ff;
            color: #333;
            margin: 30px;
        }

        h2 {
            color: #0056b3;
            margin-bottom: 20px;
        }

        .profile-card {
            background: #fff;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 400px;
        }

        p {
            margin: 10px 0;
            font-size: 15px;
        }

        b {
            color: #0056b3;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #fff;
            background: #0056b3;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.2s;
        }

        a:hover {
            background: #003d80;
        }
    </style>
</head>
<body>

<h2>My Profile</h2>

<div class="profile-card">
    <p><b>Name:</b> <?= $user['fullname'] ?></p>
    <p><b>Email:</b> <?= $user['email'] ?></p>
    <p><b>Position:</b> <?= $user['position'] ?></p>
    <p><b>Program:</b> <?= $user['program'] ?></p>
    <p><b>Job Functions:</b> <?= $user['job_functions'] ?></p>

    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>