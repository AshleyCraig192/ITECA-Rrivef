<?php
session_start();
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user; // Store user info
    if ($user['email'] === 'ashleycraig@admin.com') {
    header("Location: admin_dashboard.php");
} else {
    header("Location: dashboard.php");
}
exit();

}

            } else {
                $error = "❌ Incorrect password.";
            }
        } else {
            $error = "❌ No user found with that email.";
        }
    } else {
        $error = "❌ Please fill in all fields.";
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="link">
        Don't have an account? <a href="signup.php">Sign up here</a>
    </div>
</div>

</body>
</html>
