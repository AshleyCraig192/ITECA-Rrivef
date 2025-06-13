<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    echo "❌ You must be logged in to request a service.";
    exit;
}

$user = $_SESSION['user'];

if (!isset($_GET['service_id']) || empty($_GET['service_id'])) {
    echo "❌ Invalid request.";
    exit;
}

$service_id = $_GET['service_id'];
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO requests (service_id, buyer_id, message) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iis", $service_id, $user['id'], $message);
            if ($stmt->execute()) {
                $success = "✅ Service request submitted!";
            } else {
                $error = "❌ Failed to submit request.";
            }
        } else {
            $error = "❌ Database error.";
        }
    } else {
        $error = "❌ Message is required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
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

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            resize: vertical;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 15px;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 10px;
            text-align: center;
            font-size: 16px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            text-decoration: none;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Request This Service</h2>

    <?php if (!empty($success)): ?>
        <p class="message success"><?= htmlspecialchars($success) ?></p>
    <?php elseif (!empty($error)): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="message">Message to Seller:</label>
        <textarea name="message" id="message" rows="5" required></textarea>

        <button type="submit">Send Request</button>
    </form>

    <a class="back-link" href="marketplace.php">⬅ Back to Marketplace</a>
</div>

</body>
</html>
