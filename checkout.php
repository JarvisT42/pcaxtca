<?php
// Start session and include dependencies

include 'connect/connection.php';
include 'head.php';

// Initialize variables
$error = '';
$firstname = $lastname = '';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname);
$stmt->fetch();
$stmt->close();

// Handle form submission



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    $paymentMethod = $_POST['paymentMethod'] ?? '';

    $paymentMapping = [
        'paypal' => 'online',
        'cod' => 'cod',
        'pickup' => 'pick-up'
    ];

    if (!array_key_exists($paymentMethod, $paymentMapping)) {
        die("Invalid payment method");
    }

    $dbPaymentMethod = $paymentMapping[$paymentMethod];

    // Validate pickup store if payment method is pickup
    if ($dbPaymentMethod === 'pick-up') {
        $pickupStoreId = $_POST['pickup_store'] ?? null;
        if (!$pickupStoreId) {
            die("Please select a pickup store");
        }
    }

    // Get current cart items
    $cartQuery = $conn->prepare("
        SELECT sc.product_id, sc.qty, p.product_name, p.sale_price, pos.on_sale_quantity 
        FROM shopping_cart sc
        LEFT JOIN products p ON sc.product_id = p.id
        LEFT JOIN product_on_sales pos ON sc.product_id = pos.product_id
        WHERE sc.user_id = ?
    ");
    $cartQuery->bind_param("i", $user_id);
    $cartQuery->execute();
    $cartResult = $cartQuery->get_result();
    $cartItems = $cartResult->fetch_all(MYSQLI_ASSOC);
    $cartQuery->close();

    if (empty($cartItems)) {
        die("Your cart is empty");
    }

    // Validate stock and calculate total
    $subtotal = 0;
    $validItems = [];
    foreach ($cartItems as $item) {
        if ($item['on_sale_quantity'] === null || $item['on_sale_quantity'] < $item['qty']) {
            die("Product {$item['product_name']} is no longer available");
        }
        $subtotal += $item['sale_price'] * $item['qty'];
        $validItems[] = $item;
    }

    // Validate total matches
    if ((float)$_POST['total'] !== $subtotal) {
        die("Cart total mismatch. Please refresh the page.");
    }
    $invoice = 'INV-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(16))); // 32 chars

    // Generate invoice

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert order
        $orderStmt = $conn->prepare("
            INSERT INTO orders (invoice, user_id, total_amount, payment_method, order_status)
            VALUES (?, ?, ?, ?, 'processing')
        ");
        $orderStmt->bind_param("sids", $invoice, $user_id, $subtotal, $dbPaymentMethod);
        $orderStmt->execute();
        $orderId = $conn->insert_id;
        $orderStmt->close();

        // Insert order items and update stock
        foreach ($validItems as $item) {
            // Insert order item
            $itemStmt = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, amount)
                VALUES (?, ?, ?, ?)
            ");
            $amount = $item['sale_price'] * $item['qty'];
            $itemStmt->bind_param("iiid", $orderId, $item['product_id'], $item['qty'], $amount);
            $itemStmt->execute();
            $itemStmt->close();

            // Update product stock
            $updateStmt = $conn->prepare("
                UPDATE product_on_sales
                SET on_sale_quantity = on_sale_quantity - ?
                WHERE product_id = ?
            ");
            $updateStmt->bind_param("ii", $item['qty'], $item['product_id']);
            $updateStmt->execute();
            $updateStmt->close();
        }

        // Clear shopping cart
        $clearCart = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
        $clearCart->bind_param("i", $user_id);
        $clearCart->execute();
        $clearCart->close();

        $conn->commit();

        // Redirect to success page
        $_SESSION['order_success'] = true;
        header("Location: order_success.php?order_id=$orderId");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Order processing failed: " . $e->getMessage());
    }
}
?>

<body>
    <?php
    include 'header.php';   ?>



    <!-- ##### Right Side Cart End ##### -->

    <!-- ##### Breadcumb Area Start ##### -->
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
    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Checkout Area Start ##### -->
    <?php
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $subtotal = 0;
    $cartItems = [];

    if ($user_id > 0) {
        // Get cart items with product details
        $cartQuery = $conn->prepare("
    SELECT sc.*, p.product_name, p.sale_price, pos.on_sale_quantity 
    FROM shopping_cart sc
    LEFT JOIN products p ON sc.product_id = p.id
    LEFT JOIN product_on_sales pos ON sc.product_id = pos.product_id
    WHERE sc.user_id = ?
");
        $cartQuery->bind_param("i", $user_id);
        $cartQuery->execute();
        $cartResult = $cartQuery->get_result();

        while ($item = $cartResult->fetch_assoc()) {
            $price = $item['sale_price'];
            $quantity = $item['qty'];
            $itemTotal = $price * $quantity;

            $cartItems[] = [
                'name' => $item['product_name'],
                'price' => $price,
                'quantity' => $quantity,
                'total' => $itemTotal,
                'available' => $item['on_sale_quantity'] >= $quantity
            ];

            if ($item['on_sale_quantity'] >= $quantity) {
                $subtotal += $itemTotal;
            }
        }
        $cartQuery->close();
    }

    $shipping = 0;
    $total = $subtotal + $shipping;
    ?>

    <div class="checkout_area section-padding-80">
        <div class="container">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST" id="order-form">
                <input type="hidden" name="total" value="<?= isset($total) ? $total : 0 ?>">

                <div class="row">
                    <!-- Billing Address Column -->
                    <div class="col-12 col-md-6">
                        <div class="checkout_details_area mt-50 clearfix">
                            <div class="cart-page-heading mb-30">
                                <h5>Billing Address</h5>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>First Name <span>*</span></label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($firstname) ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Last Name <span>*</span></label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($lastname) ?>" readonly>
                                </div>

                                <div class="col-12 mb-3">
                                    <label>Street Address <span>*</span></label>
                                    <input type="text" class="form-control" name="street_address">
                                </div>

                                <div class="col-12 mb-3">
                                    <label>Postcode <span>*</span></label>
                                    <input type="text" class="form-control" name="postcode">
                                </div>

                                <div class="col-12 mb-3">
                                    <label>City <span>*</span></label>
                                    <input type="text" class="form-control" name="city">
                                </div>

                                <div class="col-12 mb-3">
                                    <label>State <span>*</span></label>
                                    <input type="text" class="form-control" name="state">
                                </div>

                                <div class="col-12 mb-3">
                                    <label>Phone Number <span>*</span></label>
                                    <input type="tel" class="form-control" name="phone_number">
                                </div>

                                <div class="col-12">
                                    <div class="custom-control custom-checkbox d-block">
                                        <input type="checkbox" class="custom-control-input"
                                            id="saveAddress" name="save_address">
                                        <label class="custom-control-label" for="saveAddress">
                                            Save this address
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Column -->
                    <div class="col-12 col-md-6 col-lg-5 ml-lg-auto">
                        <div class="order-details-confirmation">
                            <div class="cart-page-heading">
                                <h5>Your Order</h5>
                                <p>The Details</p>
                            </div>
                            <ul class="order-details-form mb-4">
                                <li><span>Product</span> <span>Total</span></li>

                                <?php if (!empty($cartItems)): ?>
                                    <?php foreach ($cartItems as $item): ?>
                                        <li>
                                            <span>
                                                <?= htmlspecialchars($item['name']) ?> (Qty: <?= $item['quantity'] ?>)
                                                <?php if (!$item['available']): ?>
                                                    <span class="text-danger"> - Out of Stock</span>
                                                <?php endif; ?>
                                            </span>
                                            <span>$<?= number_format($item['total'], 2) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li><span>Your cart is empty</span></li>
                                <?php endif; ?>

                                <li><span>Subtotal</span> <span>$<?= number_format($subtotal, 2) ?></span></li>
                                <li><span>Shipping</span> <span>Free</span></li>
                                <li><span>Total:</span> <span>$<?= number_format($total, 2) ?></span></li>
                            </ul>

                            <!-- Payment Method Section -->
                            <div id="payment-methods" role="tablist" class="mb-4">
                                <!-- PayPal -->
                                <div class="card">
                                    <div class="card-header" role="tab">
                                        <h6 class="mb-0">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="paypal" name="paymentMethod" value="paypal" data-toggle="collapse" data-target="#paypal-content">
                                                <label class="custom-control-label" for="paypal">
                                                    <i class="mr-3"></i>Paypal
                                                </label>
                                            </div>
                                        </h6>
                                    </div>
                                    <div id="paypal-content" class="collapse" data-parent="#payment-methods">
                                        <div class="card-body">
                                            <p>Pay securely using your PayPal account.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cash on Delivery -->
                                <div class="card">
                                    <div class="card-header" role="tab">
                                        <h6 class="mb-0">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="cod" name="paymentMethod" value="cod" data-toggle="collapse" data-target="#cod-content">
                                                <label class="custom-control-label" for="cod">
                                                    <i class="mr-3"></i>Cash on Delivery
                                                </label>
                                            </div>
                                        </h6>
                                    </div>
                                    <div id="cod-content" class="collapse" data-parent="#payment-methods">
                                        <div class="card-body">
                                            <p>Pay with cash when your order is delivered.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pick Up at Store -->
                                <div class="card">
                                    <div class="card-header" role="tab">
                                        <h6 class="mb-0">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="pickup" name="paymentMethod" value="pickup" data-toggle="collapse" data-target="#pickup-content">
                                                <label class="custom-control-label" for="pickup">
                                                    <i class="mr-3"></i>Pick Up at Store
                                                </label>
                                            </div>
                                        </h6>
                                    </div>
                                    <?php
                                    // Make sure to include your DB connection before this block
                                    $query = "SELECT id, store_branch, store_location FROM pm_pickup_store";
                                    $result = mysqli_query($conn, $query);
                                    ?>

                                    <div id="pickup-content" class="collapse" data-parent="#payment-methods">
                                        <div class="card-body">
                                            <div id="accordion" role="tablist" class="mb-4">

                                                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                                    <?php
                                                    $index = 0; // to make IDs unique
                                                    while ($row = mysqli_fetch_assoc($result)):
                                                        $collapseId = "collapsePickup" . $index;
                                                        $headingId = "headingPickup" . $index;
                                                    ?>
                                                        <div class="card">
                                                            <div class="card-header" role="tab" id="<?= $headingId ?>">


                                                                <h6 class="mb-0 d-flex align-items-center">
                                                                    <input type="radio" name="pickup_store" value="<?= $row['id'] ?> class=" collapsed d-flex align-items-center justify-content-between" data-toggle="collapse" href="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>" class="mr-2">
                                                                    <span><?= htmlspecialchars($row['store_branch']) ?></span>
                                                                </h6>

                                                            </div>
                                                            <div id="<?= $collapseId ?>" class="collapse" role="tabpanel" aria-labelledby="<?= $headingId ?>" data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <p><?= htmlspecialchars($row['store_location']) ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                        $index++;
                                                    endwhile;
                                                    ?>
                                                <?php else: ?>
                                                    <p class="text-muted">No pickup locations available at this time.</p>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <button type="submit" name="submit" class="btn essence-btn btn-block">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>


    <script>
        document.getElementById("order-form").addEventListener("submit", function(event) {
            // Check if a payment method is selected
            var paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

            if (!paymentMethod) {
                // If no payment method is selected, prevent form submission and alert the user
                event.preventDefault(); // Prevent form submission
                alert("Please select a payment method.");
            }
        });
    </script>


    <!-- ##### Checkout Area End ##### -->

    <!-- ##### Footer Area Start ##### -->
    <?php include 'footer.php'; ?>
</body>

</html>