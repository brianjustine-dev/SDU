<?php
session_start();
include("db.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$fullname = $_SESSION['user'];
$role = $_SESSION['role']; // roles: staff, office_head, admin
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background-color: #f4f8ff;
            color: #333;
        }
        h2 { 
            color: #fd0000ff; 
            text-align: center;
        }
        .nav { 
            margin: 20px auto;
            padding: 15px;
            background: #fff;
            border: 1px solid #cce0ff;
            border-radius: 8px;
            width: 80%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .nav a { 
            display: inline-block;
            margin: 8px 12px;
            padding: 10px 16px;
            background: #004aad;
            color: white; 
            font-weight: bold;
            border-radius: 6px;
            text-decoration: none; 
            transition: background 0.2s ease-in-out;
        }
        .nav a:hover { 
            background: #00337a;
        }
        p { 
            font-size: 15px;
            color: #333;
            text-align: center;
            margin-top: 25px;
        }
    </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($fullname) ?> (<?= ucfirst(htmlspecialchars($role)) ?>)</h2>

<div class="nav">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="logout.php">🚪 Logout</a>

    <?php if ($role == 'staff') { ?>
        <a href="profile.php">👤 My Profile</a>
        <a href="trainings.php">📘 My Trainings</a>
    <?php } ?>

    <?php if ($role == 'office_head') { ?>
        <a href="users.php">👥 Manage My Staff</a>
        <a href="trainings.php">📘 Staff Trainings</a>
    <?php } ?>

    <?php if ($role == 'admin') { ?>
        <a href="users.php">👥 Manage Users</a>
        <a href="trainings.php">📘 Manage Trainings</a>
        <a href="reports.php">📊 Reports</a>
    <?php } ?>
</div>

<p>✅ Use the menu above to navigate the system.</p>

</body>
</html>
