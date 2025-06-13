<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Mark a request as completed
if (isset($_GET['complete']) && is_numeric($_GET['complete'])) {
    $completeId = intval($_GET['complete']);
    $status = 'completed';

    $stmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $status, $completeId);
        $stmt->execute();
    }
    header("Location: view_requests.php");
    exit;
}

// Fetch all requests for the seller's services where status is pending
$stmt = $conn->prepare("
    SELECT r.id, r.message, r.status, s.title, u.email AS buyer_email
    FROM requests r
    JOIN services s ON r.service_id = s.id
    JOIN users u ON r.buyer_id = u.id
    WHERE s.user_id = ? AND (r.status = 'pending' OR r.status IS NULL)
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Requests</title>
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

        .request {
            border: 1px solid #ccc;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .request p {
            margin: 5px 0;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #555;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📬 View Service Requests</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="request">
                <p><strong>Service:</strong> <?= htmlspecialchars($row['title']) ?></p>
                <p><strong>Buyer:</strong> <?= htmlspecialchars($row['buyer_email']) ?></p>
                <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($row['message'])) ?></p>
                <a href="view_requests.php?complete=<?= $row['id'] ?>" class="btn">Mark as Completed</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No new service requests.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="back-link">⬅ Back to Dashboard</a>
</div>

</body>
</html>
