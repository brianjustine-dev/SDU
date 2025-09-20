<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM trainings WHERE user_id='$user_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Trainings</title>
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
            max-width: 800px;
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
    <h2>📘 My Trainings</h2>

    <div class="top-links">
        <a href="add_training.php">➕ Add Training</a>
        <a href="dashboard.php">🏠 Back to Dashboard</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Training Name</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['training_name']) ?></td>
                <td><?= htmlspecialchars($row['training_date']) ?></td>
                <td class="actions">
                    <a class="edit" href="edit_training.php?id=<?= $row['id']; ?>">Edit</a> | 
                    <a class="delete" href="delete_training.php?id=<?= $row['id']; ?>" onclick="return confirm('Delete this training?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>