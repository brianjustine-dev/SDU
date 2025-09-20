<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if (isset($_POST['save'])) {
    $training_name = trim($_POST['training_name']);
    $training_date = $_POST['training_date'];
    $user_id = $_SESSION['user_id'];

    if (empty($training_name) || empty($training_date)) {
        $error = "Please fill in all required fields.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $training_date)) {
        $error = "Invalid date format.";
    } else {
        $stmt = $conn->prepare("INSERT INTO trainings (user_id, training_name, training_date) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $training_name, $training_date);
        $stmt->execute();

        header("Location: trainings.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Training</title>
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
    input[type="text"], input[type="date"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #cce0ff;
        border-radius: 5px;
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
</style>
</head>
<body>

<h2>Add Training</h2>
<form method="POST">
    <label for="training_name">Training Name:</label>
    <input type="text" id="training_name" name="training_name" required>

    <label for="training_date">Training Date:</label>
    <input type="date" id="training_date" name="training_date" required>

    <button type="submit" name="save">Save</button>
</form>

<div style="text-align:center;">
    <a href="trainings.php">Back</a>
</div>

</body>
</html>
