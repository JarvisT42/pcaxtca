<?php
require_once 'connect/connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    $stmt = $conn->prepare("SELECT id, auth_provider FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(['status' => 'not_registered']);
    } else {
        $stmt->bind_result($id, $auth_provider);
        $stmt->fetch();

        if ($auth_provider === 'google') {
            echo json_encode(['status' => 'google_auth']);
        } else {
            echo json_encode(['status' => 'local']);
        }
    }

    $stmt->close();
    exit;
}
