<?php
session_start();
include("db.php");

// Prevent caching to help browser back button work correctly
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Default: show all trainings joined with users
$query = "
    SELECT u.fullname, u.role,
           t.training_name, t.training_date
    FROM users u
    LEFT JOIN trainings t ON u.id = t.user_id
    ORDER BY u.fullname, t.training_date
";

$params = [];
$types = "";
$where = "";

$start_date = "";
$end_date = "";
$filter_applied = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['period'])) {
    $period = $_POST['period'];

    $year = date("Y");

    if ($period === 'first_half') {
        $start_date = "$year-01-01";
        $end_date = "$year-06-30";
    } elseif ($period === 'second_half') {
        $start_date = "$year-07-01";
        $end_date = "$year-12-31";
    } elseif ($period === 'custom') {
        $start_date = $_POST['start_date'] ?? "";
        $end_date = $_POST['end_date'] ?? "";
    }

    if ($start_date && $end_date) {
        $filter_applied = true;
        $where = " WHERE t.training_date BETWEEN ? AND ? ";
        $query = "
            SELECT u.fullname, u.role,
                   t.training_name, t.training_date
            FROM users u
            LEFT JOIN trainings t ON u.id = t.user_id
            $where
            ORDER BY u.fullname, t.training_date
        ";
        $params = [$start_date, $end_date];
        $types = "ss";
    }
}

$stmt = $conn->prepare($query);
if ($filter_applied) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
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
            max-width: 1000px;
            margin: auto;
        }

        h2 {
            color: #0056b3;
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 14px;
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

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #0056b3;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    </style>
    <script>
        function toggleCustomDates() {
            var period = document.querySelector('input[name="period"]:checked').value;
            document.getElementById('customDates').style.display = (period === 'custom') ? 'inline-block' : 'none';
        }
        window.onload = toggleCustomDates;
    </script>



<h2>📊 Staff Training Reports</h2>

<p><a href="dashboard.php" class="back-link">⬅ Back to Dashboard</a></p>

<form method="POST" action="reports.php">
    <label><input type="radio" name="period" value="first_half" <?= (isset($period) && $period === 'first_half') ? 'checked' : '' ?>> First Half (Jan - Jun)</label>
    <label><input type="radio" name="period" value="second_half" <?= (isset($period) && $period === 'second_half') ? 'checked' : '' ?>> Second Half (Jul - Dec)</label>
    <label><input type="radio" name="period" value="custom" <?= (isset($period) && $period === 'custom') ? 'checked' : '' ?>> Custom Range</label>

    <span id="customDates" style="display:none;">
        <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
        <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
    </span>

    <button type="submit">Filter</button>
    <a href="reports.php" style="margin-left: 10px;">Reset</a>
</form>

<table>
    <tr>
        <th>Staff Name</th>
        <th>Role</th>
        <th>Training</th>
        <th>Training Date</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td><?= htmlspecialchars($row['training_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['training_date'] ?? '-') ?></td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
