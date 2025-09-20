<?php 
session_start();
include("db.php");

$error = "";

if (isset($_POST['submit'])) {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $role = $_POST['role']; // admin, office_head, staff

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f6ff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 12px 0 5px;
            font-weight: bold;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #0056b3;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover {
            background: #003d80;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        a {
            color: #0056b3;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create an Account</h2>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="fullName" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirmPassword" required>

            <label>Role</label>
            <select name="role" required>
                <option value="staff">Staff</option>
                <option value="office_head">Office Head</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit" name="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>