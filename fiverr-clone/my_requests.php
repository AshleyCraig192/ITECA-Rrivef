<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

if ($user['role'] !== 'buyer') {
    echo "⛔ Access denied.";
    exit;
}

$sql = "
    SELECT r.id, s.title, r.message
    FROM requests r
    JOIN services s ON r.service_id = s.id
    WHERE r.buyer_id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ SQL Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .request {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .request h3 {
            margin: 0 0 10px;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📄 My Service Requests</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="request">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($row['message'])) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You haven't made any requests yet.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>
