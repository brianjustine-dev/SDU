<?php
session_start();
include("db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$query = "
    SELECT u.fullname, u.role,
           t.training_name, t.training_date,
           a.activity_name, a.activity_date
    FROM users u
    LEFT JOIN trainings t ON u.id = t.user_id
    LEFT JOIN activities a ON u.id = a.user_id
    ORDER BY u.fullname, t.training_date, a.activity_date
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #800000; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #800000; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
    </style>
</head>
<body>

<h2>📊 Staff Reports</h2>
<p>Here is the summary of trainings and activities per staff:</p>

<table>
    <tr>
        <th>Staff Name</th>
        <th>Role</th>
        <th>Training</th>
        <th>Training Date</th>
        <th>Activity</th>
        <th>Activity Date</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td><?= htmlspecialchars($row['training_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['training_date'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['activity_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['activity_date'] ?? '-') ?></td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
