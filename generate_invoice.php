<?php
require('tcpdf/tcpdf.php'); // Include PDF library
include 'connect/connection.php';

// Verify user session
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Access denied");
}

// Get order details
$order_id = $_GET['order_id'] ?? null;
$user_id = $_SESSION['user_id'];

// Fetch order data
$stmt = $conn->prepare("
    SELECT o.*, oi.*, p.product_name 
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.order_id = ? AND o.user_id = ?
");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Invoice: ' . $order_items[0]['invoice'], 0, 1);
// Add more content...
$pdf->Output('invoice.pdf', 'D'); // Force download
