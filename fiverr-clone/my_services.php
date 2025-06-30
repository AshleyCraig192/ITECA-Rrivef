<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: https://rrivef.infinityfreeapp.com/login.php");
    exit();
}

$user = $_SESSION['user'];

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $service_id = intval($_GET['delete']);
    
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $service_id, $user['id']);
    $stmt->execute();
    
    header("Location: https://rrivef.infinityfreeapp.com/my_services.php");
    exit();
}

// Fetch services for this seller
$stmt = $conn->prepare("SELECT * FROM services WHERE user_id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Services</title>
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

        h2, h3 {
            text-align: center;
        }

        .service {
            border: 1px solid #ccc;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .btn-delete,
        .btn-edit {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            margin-right: 10px;
        }

        .btn-delete {
            background-color: #f44336;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }

        .btn-edit:hover {
            background-color: #388e3c;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            display: inline-block;
            margin: 0 10px;
            text-decoration: none;
            color: #4CAF50;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
    <h3>🛠 My Posted Services</h3>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($service = $result->fetch_assoc()): ?>
            <div class="service">
                <strong><?= htmlspecialchars($service['title']) ?></strong><br>
                <?= nl2br(htmlspecialchars($service['description'])) ?><br>
                <strong>Price:</strong> R<?= htmlspecialchars($service['price']) ?><br>
                <strong>Posted on:</strong> <?= htmlspecialchars($service['created_at']) ?><br>

                <a class="btn-delete" href="https://rrivef.infinityfreeapp.com/my_services.php?delete=<?= $service['id'] ?>" onclick="return confirm('Are you sure you want to delete this service?');">🗑 Delete</a>
                <a href="https://rrivef.infinityfreeapp.com/edit_services.php?id=<?= $service['id'] ?>" class="btn-edit">✏️ Edit</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">You haven't posted any services yet.</p>
    <?php endif; ?>

    <div class="links">
        <a href="https://rrivef.infinityfreeapp.com/post_service.php">➕ Post a New Service</a> |
        <a href="https://rrivef.infinityfreeapp.com/index.php">⬅ Back to Dashboard</a>
    </div>
</div>

</body>
</html>
