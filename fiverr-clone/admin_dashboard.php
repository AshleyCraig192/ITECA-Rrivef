<?php
session_start();
require 'db.php';

// Only allow admin with specific email
if (!isset($_SESSION['user']) || $_SESSION['user']['email'] !== 'ashleycraig@admin.com') {
    header("Location: login.php");
    exit;
}

// Fetch all services
$services_stmt = $conn->prepare("SELECT services.id, services.title, services.description, services.price, users.name, services.status FROM services JOIN users ON services.user_id = users.id ORDER BY services.id DESC");
$services_stmt->execute();
$services_result = $services_stmt->get_result();

// Fetch all requests
$requests_stmt = $conn->prepare("SELECT requests.id, requests.message, users.name AS buyer_name, services.title FROM requests JOIN users ON requests.buyer_id = users.id JOIN services ON requests.service_id = services.id ORDER BY requests.id DESC");
$requests_stmt->execute();
$requests_result = $requests_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Rrevif</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            margin-top: 40px;
            color: #444;
        }

        .record {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fafafa;
        }

        .record p {
            margin: 5px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .logo {
            width: 130px;
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="dashboard.php"><img src="images/rrevif_logo.jpg" alt="Rrevif Logo" class="logo"></a>
    <h1>Administrator Dashboard</h1>

    <h2>All Services</h2>
    <?php while ($row = $services_result->fetch_assoc()): ?>
        <div class="record">
            <p><strong>Title:</strong> <?= htmlspecialchars($row['title']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
            <p><strong>Price:</strong> R<?= number_format($row['price'], 2) ?></p>
            <p><strong>Posted by:</strong> <?= htmlspecialchars($row['name']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($row['status'] ?? 'Active') ?></p>
        </div>
    <?php endwhile; ?>

    <h2>All Service Requests</h2>
    <?php while ($req = $requests_result->fetch_assoc()): ?>
        <div class="record">
            <p><strong>Service:</strong> <?= htmlspecialchars($req['title']) ?></p>
            <p><strong>Buyer:</strong> <?= htmlspecialchars($req['buyer_name']) ?></p>
            <p><strong>Message:</strong> <?= htmlspecialchars($req['message']) ?></p>
        </div>
    <?php endwhile; ?>

    <a class="btn" href="logout.php">Logout</a>
</div>
</body>
</html>
