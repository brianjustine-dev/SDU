<?php
session_start();
include("db.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = trim($_POST['email']);
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
        body {
            font-family: Arial, sans-serif;
            background: #f0f6ff;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 320px;
            text-align: center;
        }

        h2 {
            color: #0056b3;
            margin-bottom: 20px;
        }

        form {
            text-align: left;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
            color: #0056b3;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #b3d1ff;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            margin-top: 18px;
            padding: 10px;
            width: 100%;
            background-color: #0056b3;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }

        button:hover {
            background-color: #003d80;
        }

        .link {
            margin-top: 15px;
            font-size: 14px;
        }

        .link a {
            color: #0056b3;
            text-decoration: none;
            font-weight: bold;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
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
</div>

</body>
</html>
