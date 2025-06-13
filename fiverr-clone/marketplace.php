<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    echo "You must be logged in to view the marketplace.";
    exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Get services based on search (if any)
if (!empty($search)) {
    $searchTerm = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT services.id, services.title, services.description, services.price, users.name 
                            FROM services 
                            JOIN users ON services.user_id = users.id 
                            WHERE services.title LIKE ? OR services.description LIKE ?
                            ORDER BY services.id DESC");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT services.id, services.title, services.description, services.price, users.name 
                            FROM services 
                            JOIN users ON services.user_id = users.id 
                            ORDER BY services.id DESC");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Marketplace</title>
    <style>
        .logo-link {
            position: absolute;
            top: 20px;
            left: 30px;
            text-decoration: none;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo img {
            height: 50px;
            max-height: 50px;
            width: auto;
            object-fit: contain;
            cursor: pointer;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar {
            text-align: center;
            margin-bottom: 30px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 60%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .search-bar button {
            padding: 10px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 8px;
            transition: background 0.3s;
        }
        
        .search-bar button:hover {
            background-color: #45a049;
        }

        .service-card {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
        }

        .service-card h2 {
            margin: 0;
        }

        .price {
            color: green;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            background: #007bff;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="logo">
    <a href="dashboard.php">
        <img src="images/rrevif_logo.jpg" alt="Rrevif Logo">
    </a>
</div>



    <h1>🛍️ Services Marketplace</h1>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search for services..." value="<?= htmlspecialchars($search) ?>" required>
            <button type="submit">🔍 Search</button>
        </form>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="service-card">
                <h2><?= htmlspecialchars($row['title']) ?></h2>
                <p><?= htmlspecialchars($row['description']) ?></p>
                <p class="price">R<?= number_format($row['price'], 2) ?></p>
                <p>Posted by: <?= htmlspecialchars($row['name']) ?></p>
                <a class="btn" href="request_service.php?service_id=<?= $row['id'] ?>">Request Service</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">🔍 No services found matching "<strong><?= htmlspecialchars($search) ?></strong>".</p>
    <?php endif; ?>

    <div class="back-btn">
        <a class="btn" href="dashboard.php">⬅ Back to Dashboard</a>
    </div>

</body>
</html>
