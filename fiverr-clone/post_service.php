<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);

    if (!empty($title) && !empty($description) && $price > 0) {
        $user_id = $_SESSION['user']['id'];

        $stmt = $conn->prepare("INSERT INTO services (user_id, title, description, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $title, $description, $price);

        if ($stmt->execute()) {
            $success = "✅ Service posted successfully!";
        } else {
            $error = "❌ Failed to post service. Please try again.";
        }

        $stmt->close();
    } else {
        $error = "❌ Please fill in all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post a Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 700px;
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

        .message {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 5px;
        }

        textarea {
            resize: vertical;
        }

        button {
            margin-top: 20px;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Post a New Service</h2>

    <?php if ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" rows="5" required></textarea>

        <label>Price (ZAR):</label>
        <input type="number" name="price" step="0.01" required>

        <button type="submit">Post Service</button>
    </form>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
