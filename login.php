<?php
session_start();
include("db.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $user['fullname'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_id'] = $user['id'];

                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid email or password!";
                }
            } else {
                $error = "Invalid email or password!";
            }
            $stmt->close();
        } else {
            $error = "Database query failed.";
        }
    } else {
        $error = "Please fill in both fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #800000; }
        form { margin-bottom: 15px; }
        label { display: block; margin: 8px 0 4px; }
        input { padding: 8px; width: 200px; }
        button { padding: 8px 15px; margin-top: 10px; }
        .link { margin-top: 15px; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Login</h2>

<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>

<div class="link">
    <p>Don't have an account? <a href="registration.php">Register here</a></p>
</div>

</body>
</html>
