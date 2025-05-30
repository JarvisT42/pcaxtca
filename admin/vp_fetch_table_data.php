<?php
include '../connect/connection.php';

$product_id = $_POST['product_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$stmt = $conn->prepare("
    SELECT qty, movement_type, DATE(movement_date) AS movement_date
    FROM stock_movements
    WHERE product_id = ? AND DATE(movement_date) BETWEEN ? AND ?
");

$stmt->bind_param("sss", $product_id, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
$total_stock = 0;
$total_sold = 0;

while ($row = $result->fetch_assoc()) {
    // Sum totals based on movement_type
    if (strtolower($row['movement_type']) === 'initial_stock' || strtolower($row['movement_type']) === 'restock') {
        $total_stock += $row['qty'];
    } elseif (strtolower($row['movement_type']) === 'sold') {
        $total_sold += $row['qty'];
    }

    $orders[] = $row;
}

echo json_encode([
    'orders' => $orders,
    'total_stock' => $total_stock,
    'total_sold' => $total_sold
]);
