<?php
session_start();
include("db.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f6ff;
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

        a.add-btn {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 15px;
            background: #0056b3;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        a.add-btn:hover {
            background: #004494;
        }

        table {
            border-collapse: collapse;
            width: 100%;
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
    <h2>👥 Staff Management</h2>
    <a href="registration.php" class="add-btn">➕ Add New User</a>

    <table>
        <tr>
            <th>ID</th><th>Full Name</th><th>Email</th><th>Role</th><th>Action</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td class="actions">
                <a class="edit" href="edit.php?id=<?= $row['id']?>">Edit</a> | 
                <a class="delete" href="delete.php?id=<?= $row['id']?>" onclick="return confirm('Delete this user?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>