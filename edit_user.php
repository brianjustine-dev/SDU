<?php 
include("db.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
} else {
    header("Location: users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f8ff;
        margin: 0;
        padding: 20px;
        color: #333;
    }
    h2 {
        color: #004aad;
        text-align: center;
    }
    form {
        width: 50%;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border: 1px solid #cce0ff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    label {
        font-weight: bold;
        color: #004aad;
        display: block;
        margin-bottom: 5px;
    }
    input[type="text"], input[type="email"], textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #cce0ff;
        border-radius: 5px;
    }
    textarea {
        min-height: 100px;
        resize: vertical;
    }
    button {
        background-color: #004aad;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
    }
    button:hover {
        background-color: #00337a;
    }
    a {
        display: inline-block;
        margin-top: 15px;
        text-decoration: none;
        color: #004aad;
        font-weight: bold;
    }
    a:hover {
        text-decoration: underline;
    }
    .back-link {
        text-align: center;
    }
</style>
</head>
<body>

<h2>Edit User</h2>
<form method="POST" action="update.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
    <label>Full Name:</label>
    <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

    <label>Role:</label>
    <select name="role" required>
        <option value="admin" <?= $user['role']=='admin'?'selected':''; ?>>Admin</option>
        <option value="office_head" <?= $user['role']=='office_head'?'selected':''; ?>>Office Head</option>
        <option value="staff" <?= $user['role']=='staff'?'selected':''; ?>>Staff</option>
    </select><br><br>

    <button type="submit">Update</button>
</form>
<a href="users.php">Cancel</a>
