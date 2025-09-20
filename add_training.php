<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['save'])) {
    $training_name = $_POST['training_name'];
    $training_date = $_POST['training_date'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO trainings (user_id, training_name, training_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $training_name, $training_date);
    $stmt->execute();

    header("Location: trainings.php");
    exit();
}
?>

<h2>Add Training</h2>
<form method="POST">
    Training Name: <input type="text" name="training_name" required><br><br>
    Training Date: <input type="date" name="training_date" required><br><br>
    <button type="submit" name="save">Save</button>
</form>
<a href="trainings.php">Back</a>
