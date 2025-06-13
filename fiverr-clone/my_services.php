<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: /fiverr-clone/login.php");
    exit();
}

$user = $_SESSION['user'];
$stmt = $conn->prepare("SELECT * FROM services WHERE user_id = ?");
$stmt->execute([$user['id']]);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Services</title>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
<h3>My Posted Services</h3>

<?php if (count($services) > 0): ?>
    <ul>
        <?php foreach ($services as $service): ?>
            <li>
                <strong><?= htmlspecialchars($service['title']) ?></strong><br>
                <?= nl2br(htmlspecialchars($service['description'])) ?><br>
                Price: R<?= htmlspecialchars($service['price']) ?><br>
                Posted on: <?= $service['created_at'] ?><br><br>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You haven't posted any services yet.</p>
<?php endif; ?>

<p><a href="/fiverr-clone/post_service.php">Post a New Service</a></p>
<p><a href="/fiverr-clone/dashboard.php">Back to Dashboard</a></p>

</body>
</html>
