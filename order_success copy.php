<?php
include 'connect/connection.php';
session_start();

// Get order details
$order_id = $_GET['order_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

// Fetch invoice from database
$invoice = '';
if ($order_id && $user_id) {
    $stmt = $conn->prepare("
        SELECT invoice, order_date, total_amount, payment_method 
        FROM orders 
        WHERE order_id = ? AND user_id = ?
    ");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $invoice = $order['invoice'] ?? '';
    $stmt->close();
}

// Clear success flag
unset($_SESSION['order_success']);
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>

<body>
    <?php include 'header.php'; ?>
    <style>
        /* Order Success Styling */
        .order-success-message {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .checkmark-circle {
            width: 80px;
            height: 80px;
            background: #4CAF50;
            border-radius: 50%;
            margin: 0 auto 30px;
            position: relative;
        }

        .checkmark {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
        }

        .checkmark:after {
            content: '';
            display: block;
            width: 15px;
            height: 30px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
            position: absolute;
            left: 12px;
            top: 5px;
        }

        .order-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .action-buttons {
            margin-top: 30px;
        }
    </style>
    <div class="breadcumb_area bg-img" style="background-image: url(img/bg-img/breadcumb.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="page-title text-center">
                        <h2>Checkout</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="shop_grid_area section-padding-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <?php if (!empty($invoice)): ?>
                        <div class="order-success-message text-center">
                            <!-- Checkmark code... -->

                            <div class="order-details">
                                <h5 class="mb-3">Order Summary</h5>
                                <p>Invoice Number: <strong><?= htmlspecialchars($invoice) ?></strong></p>
                                <p>Order Date: <?= htmlspecialchars($order['order_date']) ?></p>
                                <p>Payment Method: <?= strtoupper(htmlspecialchars($order['payment_method'])) ?></p>
                                <p>Total Paid: $<?= number_format($order['total_amount'], 2) ?></p>
                            </div>

                            <!-- Add a "Download Invoice" button -->

                        </div>
                    <?php else: ?>
                        <!-- Error message... -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

</body>

</html>