<?php
include 'connect/connection.php';

session_start();

$product_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Delete from database
$stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->close();

// After processing the cart removal
header("Location: single-product-details.php?id=" . urlencode($product_id));
exit;
