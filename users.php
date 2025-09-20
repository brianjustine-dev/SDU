<?php
session_start();
include("db.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch users
$result = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fc;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: auto;
        }

        h2 {
            color: #0056b3;
            text-align: center;
            margin-bottom: 20px;
        }

        .top-links {
            margin-bottom: 15px;
            text-align: center;
        }

        .top-links a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            background: #0056b3;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .top-links a:hover {
            background: #004494;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            text-align: left;
        }

        th {
            background: #0056b3;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #eef4ff;
        }

        .actions a {
            margin: 0 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .edit {
            color: #006600;
        }

        .edit:hover {
            text-decoration: underline;
        }

        .delete {
            color: #cc0000;
        }

        .delete:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>👥 Manage Users</h2>

    <div class="top-links">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
        <a href="registration.php">➕ Add New User</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['fullname'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= ucfirst($row['role']) ?></td>
            <td class="actions">
                <a class="edit" href="edit_user.php?id=<?= $row['id'] ?>">✏ Edit</a> | 
                <a class="delete" href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete user?')">🗑 Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>