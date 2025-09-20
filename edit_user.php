<?php
session_start();
include("db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $position = $_POST['position'];
    $program  = $_POST['program'];
    $job_functions = $_POST['job_functions'];

    mysqli_query($conn, "UPDATE users 
                               SET fullname='$fullname', email='$email', 
                                   position='$position', program='$program', 
                                   job_functions='$job_functions' 
                               WHERE id=$id");

    header("Location: users.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
<h2>Edit User</h2>
<form method="POST">
    <label>Full Name:</label><br>
    <input type="text" name="fullname" value="<?= $user['fullname'] ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= $user['email'] ?>" required><br><br>

    <label>Position:</label><br>
    <input type="text" name="position" value="<?= $user['position'] ?>"><br><br>

    <label>Program:</label><br>
    <input type="text" name="program" value="<?= $user['program'] ?>"><br><br>

    <label>Job Functions:</label><br>
    <textarea name="job_functions"><?= $user['job_functions'] ?></textarea><br><br>

    <button type="submit">Save Changes</button>
</form>
<a href="users.php">Cancel</a>
</body>
</html>
