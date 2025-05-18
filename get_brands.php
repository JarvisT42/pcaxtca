<?php
// get_brands.php

// Set response header
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to error.txt
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.txt');

include 'connect/connection.php'; // Adjust this path if needed

// Decode JSON input
$data = json_decode(file_get_contents('php://input'), true);
$category_id = isset($data['category_id']) ? intval($data['category_id']) : 0;

if ($category_id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT id, product_brand FROM product_brands WHERE product_category_id = ?");

    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn));
        echo json_encode([]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        error_log("Execution failed: " . mysqli_error($conn));
        echo json_encode([]);
        exit;
    }

    $brands = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $brands[] = $row;
    }

    echo json_encode($brands);
} else {
    echo json_encode([]);
}
