<?php 
session_start();
include("db.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        // User not found, redirect or show error
        header("Location: users.php");
        exit();
    }
} else {
    // No id provided, redirect
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
            background: #f0f6ff;
            color: #333;
            margin: 30px;
        }

        h2 {
            color: #0056b3;
            margin-bottom: 20px;
        }

        form {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            max-width: 400px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
            color: #0056b3;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #b3d1ff;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            margin-top: 18px;
            padding: 10px 16px;
            background-color: #0056b3;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }

        button:hover {
            background-color: #003d80;
        }
    </style>
</head>
<body>
<h2>Edit User</h2>
<form method="POST" action="update.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
    
    <label>Full Name:</label>
    <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

    <label>Role:</label>
    <select name="role" required>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="office_head" <?= $user['role'] === 'office_head' ? 'selected' : '' ?>>Office Head</option>
        <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
    </select><br><br>

    <button type="submit">Update</button>
</form>
