<?php 
include("db.php");
if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
}
?>

<h2>Edit User</h2>
<form method="POST" action="update.php">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">
    <label>Full Name:</label>
    <input type="text" name="fullname" value="<?= $user['fullname'] ?>"><br>

    <label>Email:</label>
    <input type="text" name="email" value="<?= $user['email'] ?>"><br>

    <label>Role:</label>
    <select name="role">
        <option value="admin" <?= $user['role']=='admin'?'selected':''; ?>>Admin</option>
        <option value="office_head" <?= $user['role']=='office_head'?'selected':''; ?>>Office Head</option>
        <option value="staff" <?= $user['role']=='staff'?'selected':''; ?>>Staff</option>
    </select><br>

    <button type="submit">Update</button>
</form>
