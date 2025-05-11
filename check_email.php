<?php
require_once 'connect/connection.php'; // should define $conn = new mysqli(...)

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    echo json_encode(['available' => $stmt->num_rows === 0]);
    exit;
}
