<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['save'])) {
    $activity_name = $_POST['activity_name'];
    $activity_date = $_POST['activity_date'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO activities (user_id, activity_name, activity_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $activity_name, $activity_date);
    $stmt->execute();

    header("Location: activities.php");
    exit();
}
?>

<h2>Add Activity</h2>
<form method="POST">
    Activity Name: <input type="text" name="activity_name" required><br><br>
    Activity Date: <input type="date" name="activity_date" required><br><br>
    <button type="submit" name="save">Save</button>
</form>
<a href="activities.php">Back</a>
