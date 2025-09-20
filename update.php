<?php 
include("db.php");

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['id'])){
    $id = intval($_POST['id']);
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $query = "UPDATE users SET fullname='$fullname', email='$email', role='$role' WHERE id = $id";
    mysqli_query($conn, $query);
}

header("Location: staff.php");
exit();
?>
