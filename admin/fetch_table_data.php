<?php

include '../connect/connection.php';

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$stmt = $conn->prepare("
    SELECT 
        p.product_name,
        SUM(oi.cost_price * oi.quantity) AS total_cost,
        SUM(oi.total_amount) AS total_amount,
        SUM(oi.total_amount - (oi.cost_price * oi.quantity)) AS total_profit,
        oi.status
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE DATE(oi.completed_date) BETWEEN ? AND ?
    GROUP BY oi.product_id, oi.status
");



$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
