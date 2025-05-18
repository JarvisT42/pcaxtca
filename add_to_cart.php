<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'])) {
    require 'connect/connection.php'; // your DB connection file
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);

    // Optional: Check if already in cart
    $check = $conn->prepare("SELECT id FROM shopping_cart WHERE user_id = ? AND product_id = ?");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "This product is already in your cart.";
    } else {
        $stmt = $conn->prepare("INSERT INTO shopping_cart (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $product_id);
        if ($stmt->execute()) {
            echo "Product added to cart.";
        } else {
            echo "Error adding to cart.";
        }
    }

    $check->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
