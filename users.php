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
</head>
<body>
<h2>👥 Manage Users</h2>
<a href="dashboard.php">⬅ Back to Dashboard</a> | 
<a href="registration.php">➕ Add New User</a>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID</th><th>Full Name</th><th>Email</th><th>Role</th><th>Action</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['fullname'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['role'] ?></td>
        <td>
            <a href="edit_user.php?id=<?= $row['id'] ?>">✏ Edit</a> | 
            <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete user?')">🗑 Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
