<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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
        padding-top: 90px;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        position: relative;
    }

    h1, h2 {
        text-align: center;
        color: #333;
    }

    .welcome {
        text-align: center;
        margin-bottom: 30px;
        font-size: 18px;
    }

    .btn {
        display: block;
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        text-align: center;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 16px;
        transition: background 0.3s;
    }

    .btn:hover {
        background-color: #45a049;
    }

    .logout {
        background-color: #f44336;
    }

    .logout:hover {
        background-color: #d32f2f;
    }

    .logo-link {
        position: absolute;
        top: 20px;
        left: 20px;
        text-decoration: none;
    }

    .logo {
        height: 45px;
        width: auto;
        border-radius: 8px;
        transition: transform 0.2s;
    }

    .logo:hover {
        transform: scale(1.05);
    }

    .search-bar {
        text-align: center;
        margin: 20px 0;
    }

    .search-bar input[type="text"] {
        width: 80%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .footer {
        margin-top: 50px;
        text-align: center;
        font-size: 14px;
        color: #777;
        flex-wrap: wrap;
    }

    .footer a {
        margin: 0 10px;
        color: #555;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }

    /* 🔽 Responsive Styles for Small Screens */
    @media (max-width: 600px) {
        .container {
            padding: 20px;
            padding-top: 80px;
        }

        .btn {
            font-size: 15px;
            padding: 10px;
        }

        .search-bar input[type="text"] {
            width: 95%;
        }

        .logo {
            height: 40px;
        }

        .logo-link {
            left: 15px;
            top: 15px;
        }

        .footer a {
            display: block;
            margin: 5px 0;
        }
    }
</style>

</head>
<body>

<a href="dashboard.php" class="logo-link">
    <img src="images/rrevif_logo.jpg" alt="Rrevif Logo" class="logo">
</a>

<div class="container">
    <h1>Welcome to Rrevif</h1>
    <h2>Your Dashboard</h2>

    <p class="welcome">Logged in as <strong><?= htmlspecialchars($user['name']) ?></strong> (<?= htmlspecialchars($user['role']) ?>)</p>

    <div class="search-bar">
        <form action="marketplace.php" method="GET">
            <input type="text" name="search" placeholder="🔍 Search for services..." />
        </form>
    </div>

    <a href="marketplace.php" class="btn">🛒 View Marketplace</a>

    <?php if ($user['role'] === 'seller'): ?>
        <a href="post_service.php" class="btn">➕ Post a Service</a>
        <a href="view_requests.php" class="btn">📬 View Service Requests</a>
    <?php elseif ($user['role'] === 'buyer'): ?>
        <a href="my_requests.php" class="btn">📄 My Requests</a>
    <?php endif; ?>

    <a href="logout.php" class="btn logout">🚪 Logout</a>

    <div class="footer">
        <a href="#">About Us</a> |
        <a href="#">FAQs</a> |
        <a href="#">Contact Us</a> |
        <a href="#">Privacy Policy</a> |
        <a href="#">Terms of Service</a>
    </div>
</div>

</body>
</html>
