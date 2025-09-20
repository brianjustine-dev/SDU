<?php 
include("db.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['id'])){
    $id = intval($_POST['id']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    // Basic validation
    if (empty($fullname) || empty($email) || empty($role)) {
        die("Please fill all required fields.");
    }

    $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, role=? WHERE id = ?");
    $stmt->bind_param("sssi", $fullname, $email, $role, $id);
    $stmt->execute();
}

header("Location: users.php");
exit();
?>
