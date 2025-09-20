<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT * FROM activities WHERE id='$id' AND user_id='$user_id'");
$activity = mysqli_fetch_assoc($result);

if (!$activity) {
    die("Activity not found or not yours!");
}

if (isset($_POST['update'])) {
    $activity_name = $_POST['activity_name'];
    $activity_date = $_POST['activity_date'];

    $stmt = $conn->prepare("UPDATE activities SET activity_name=?, activity_date=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssii", $activity_name, $activity_date, $id, $user_id);
    $stmt->execute();

    header("Location: activities.php");
    exit();
}
?>

<h2>Edit Activity</h2>
<form method="POST">
    Activity Name: <input type="text" name="activity_name" value="<?php echo $activity['activity_name']; ?>" required><br><br>
    Activity Date: <input type="date" name="activity_date" value="<?php echo $activity['activity_date']; ?>" required><br><br>
    <button type="submit" name="update">Update</button>
</form>
<a href="activities.php">Back</a>
