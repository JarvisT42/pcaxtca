<?php
include 'connect/connection.php';
session_start(); // Start the session

// Check if the order was successfully placed
$order_success = $_SESSION['order_success'] ?? false;
$order_id = $_GET['order_id'] ?? null;

// Clear the success flag to prevent showing message on refresh
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
                    <?php if ($order_success && $order_id): ?>
                        <div class="order-success-message text-center">
                            <div class="checkmark-circle">
                                <div class="checkmark"></div>
                            </div>
                            <h2 class="mb-4">Thank You For Your Order!</h2>
                            <p class="lead">Your order has been placed successfully.</p>
                            <div class="order-details">
                                <p>Order ID: <strong>#<?= htmlspecialchars($order_id) ?></strong></p>
                                <p>We've sent a confirmation email to your registered email address.</p>
                            </div>
                            <div class="action-buttons mt-5">
                                <a href="shop.php" class="btn essence-btn">Continue Shopping</a>
                                <a href="order_history.php" class="btn essence-btn ml-3">View Order Details</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger text-center">
                            <h4>Order Not Found</h4>
                            <p>There was a problem processing your order. Please contact support.</p>
                            <a href="contact.php" class="btn essence-btn">Contact Support</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>

</body>

</html>