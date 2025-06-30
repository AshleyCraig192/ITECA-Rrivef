<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: https://rrivef.infinityfreeapp.com/login.php");
    exit();
}

$user = $_SESSION['user'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid service ID.";
    exit();
}

$service_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM services WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $service_id, $user['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Service not found or access denied.";
    exit();
}

$service = $result->fetch_assoc();

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (!empty($title) && !empty($description) && is_numeric($price)) {
        $update_stmt = $conn->prepare("UPDATE services SET title = ?, description = ?, price = ? WHERE id = ? AND user_id = ?");
        $update_stmt->bind_param("ssdii", $title, $description, $price, $service_id, $user['id']);

        if ($update_stmt->execute()) {
            $success = "✅ Service updated successfully.";
            $service['title'] = $title;
            $service['description'] = $description;
            $service['price'] = $price;
        } else {
            $error = "❌ Failed to update the service.";
        }
    } else {
        $error = "❌ All fields are required and price must be a number.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f4;
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
            margin-bottom: 25px;
        }

        input[type="text"], textarea, input[type="number"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 6px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }

        .back-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✏️ Edit Your Service</h2>

    <?php if (!empty($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($service['title']) ?>" required>

        <label>Description:</label>
        <textarea name="description" rows="5" required><?= htmlspecialchars($service['description']) ?></textarea>

        <label>Price (ZAR):</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($service['price']) ?>" required>

        <button type="submit">Update Service</button>
    </form>

    <div class="back-link">
        <a href="https://rrivef.infinityfreeapp.com/my_services.php">⬅ Back to My Services</a>
    </div>
</div>

</body>
</html>
