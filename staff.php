<?php
session_start();
include("db.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM users");
?>

<h2>Staff Management</h2>
<a href="registration.php">➕ Add New User</a>
<table border="1" cellpadding="10">
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
            <a href="edit.php?id=<?= $row['id']?>">Edit</a> | 
            <a href="delete.php?id=<?= $row['id']?>" onclick="return confirm('Delete this user?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
