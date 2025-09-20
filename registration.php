<?php 
session_start();
include("db.php");

$error = "";

if (isset($_POST['submit'])) {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $role = $_POST['role']; // admin, office_head, staff

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $error = "This email is already registered. Please log in.";
        } else {
            $sql = $conn->prepare("INSERT INTO users(fullname, email, password, role) VALUES (?, ?, ?, ?)");
            $sql->bind_param("ssss", $fullName, $email, $hashedPassword, $role);

            if ($sql->execute()) {
                header("Location: login.php?registered=true");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
            $sql->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h2>Create an Account</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="fullName" required><br>

        <label>Email</label>
        <input type="email" name="email" required><br>

        <label>Password</label>
        <input type="password" name="password" required><br>

        <label>Confirm Password</label>
        <input type="password" name="confirmPassword" required><br>

        <label>Role</label>
        <select name="role" required>
            <option value="staff">Staff</option>
            <option value="office_head">Office Head</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit" name="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
