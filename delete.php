<?php 
include ("db.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $query = "DELETE FROM users WHERE id = $id";
    mysqli_query($conn, $query);
}
header("Location: users.php");
exit();
?>
