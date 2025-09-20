<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT * FROM trainings WHERE id='$id' AND user_id='$user_id'");
$training = mysqli_fetch_assoc($result);

if (!$training) {
    die("Training not found or not yours!");
}

if (isset($_POST['update'])) {
    $training_name = trim($_POST['training_name']);
    $training_date = $_POST['training_date'];

    if (empty($training_name) || empty($training_date)) {
        $error = "Please fill in all required fields.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $training_date)) {
        $error = "Invalid date format.";
    } else {
        $stmt = $conn->prepare("UPDATE trainings SET training_name=?, training_date=? WHERE id=? AND user_id=?");
        $stmt->bind_param("ssii", $training_name, $training_date, $id, $user_id);
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
<title>Edit Training</title>
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
    .back-link {
        text-align: center;
    }
</style>
</head>
<body>
<h2>Edit Training</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    Training Name: <input type="text" name="training_name" value="<?= htmlspecialchars($training['training_name']) ?>" required><br><br>
    Training Date: <input type="date" name="training_date" value="<?= htmlspecialchars($training['training_date']) ?>" required><br><br>
    <button type="submit" name="update">Update</button>
</form>
<a href="trainings.php">Back</a>
