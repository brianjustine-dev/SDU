<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM activities WHERE user_id='$user_id'");
?>

<h2>My Activities</h2>
<a href="add_activity.php">Add Activity</a> | 
<a href="dashboard.php">Back to Dashboard</a>
<br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Activity Name</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['activity_name']; ?></td>
            <td><?php echo $row['activity_date']; ?></td>
            <td>
                <a href="edit_activity.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="delete_activity.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this activity?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
