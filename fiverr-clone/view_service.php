<?php
session_start();
require 'db.php';

if (!isset($_GET['id'])) {
    echo "No service ID provided.";
    exit;
}

$service_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT services.title, services.description, services.price, users.email 
                        FROM services 
                        JOIN users ON services.user_id = users.id 
                        WHERE services.id = ?");
$stmt->bind_param("i", $service_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<h1>" . htmlspecialchars($row['title']) . "</h1>";
    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
    echo "<p>Price: R" . number_format($row['price'], 2) . "</p>";
    echo "<p>Posted by: " . htmlspecialchars($row['email']) . "</p>";
} else {
    echo "Service not found.";
}

$stmt->close();
?>
