<?php
include 'connect/connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it is not started
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    if ($user_id > 0) {
        // Loop through cart items
        if (!empty($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $sc_id => $quantity) {
                $sc_id = intval($sc_id);
                $quantity = intval($quantity);

                // Validate quantity
                if ($quantity > 0) {
                    $stmt = $conn->prepare("UPDATE shopping_cart SET qty = ? WHERE id = ? AND user_id = ?");
                    $stmt->bind_param("iii", $quantity, $sc_id, $user_id);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    // Remove item if quantity is 0 or less
                    $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE id = ? AND user_id = ?");
                    $stmt->bind_param("ii", $sc_id, $user_id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';



?>


<body>
    <?php
    include 'header.php';
    ?>

    <!-- ##### Right Side Cart Area ##### -->

    <!-- ##### Right Side Cart End ##### -->

    <!-- ##### Breadcumb Area Start ##### -->
    <div class="breadcumb_area bg-img" style="background-image: url(img/bg-img/breadcumb.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="page-title text-center">
                        <h2>dresses</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Breadcumb Area End ##### -->

    <?php

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;



    $cartItems = [];
    $subtotal = 0;
    $tax = 10.00; // Example tax
    $shipping = 0.00;
    $total = 0;

    if ($user_id > 0) {
        // Get cart items from database
        $cartQuery = $conn->prepare("SELECT * FROM shopping_cart WHERE user_id = ?");
        $cartQuery->bind_param("i", $user_id);
        $cartQuery->execute();
        $cartResult = $cartQuery->get_result();

        while ($cartItem = $cartResult->fetch_assoc()) {
            $product_id = $cartItem['product_id'];
            $quantity = $cartItem['qty'];
            $sc_id = $cartItem['id'];

            // Check product availability
            $stockQuery = $conn->prepare("SELECT on_sale_quantity FROM product_on_sales WHERE product_id = ?");
            $stockQuery->bind_param("i", $product_id);
            $stockQuery->execute();
            $stockResult = $stockQuery->get_result();

            if ($stockResult->num_rows > 0) {
                $stock = $stockResult->fetch_assoc();
                if ($stock['on_sale_quantity'] < $quantity) {
                    // Product quantity insufficient
                    $cartItems[] = [
                        'available' => false,
                        'sc_id' => $sc_id,



                        'product_id' => $product_id,
                        'on_sale_quantity' => $stock['on_sale_quantity'],

                        'message' => 'Product not available'
                    ];
                    continue;
                }
            } else {
                // Product not found in sales
                $cartItems[] = [
                    'available' => false,
                    'sc_id' => $sc_id,
                    'product_id' => $product_id,
                    'on_sale_quantity' => 0,
                    'message' => 'Product not found'
                ];
                continue;
            }

            // Get product details
            $productQuery = $conn->prepare("SELECT product_name, sale_price, image_path FROM products WHERE id = ?");
            $productQuery->bind_param("i", $product_id);
            $productQuery->execute();
            $productResult = $productQuery->get_result();

            if ($productResult->num_rows > 0) {
                $product = $productResult->fetch_assoc();
                $price = $product['sale_price'];
                $itemTotal = $price * $quantity;
                $subtotal += $itemTotal;

                $cartItems[] = [
                    'available' => true,
                    'sc_id' => $sc_id,
                    'product_id' => $product_id,
                    'image_path' => $product['image_path'],
                    'on_sale_quantity' => $stock['on_sale_quantity'],

                    'name' => $product['product_name'],
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $itemTotal
                ];
            }

            $productQuery->close();
            $stockQuery->close();
        }

        $cartQuery->close();
        $total = $subtotal + $tax + $shipping;
    }

    $conn->close();
    ?>
    
    <section class="shop_grid_area section-padding-80">
        <div class="container">
            <?php if (!empty($cartItems)) : ?>
                <form method="POST" action="">
                    <div class="row"> <!-- Form now wraps the entire row -->
                        <!-- Left Column -->
                        <div class="col-12 col-lg-8">
                            <div class="cart-title mt-50">
                                <h2>Shopping Cart</h2>
                            </div>

                            <div class="cart-table clearfix">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cartItems as $item): ?>
                                            <tr>
                                                <td class="cart_product_img">
                                                    <input type="hidden" name="quantities[<?= $item['sc_id'] ?>]" value="<?= $item['sc_id'] ?>">
                                                    <img src="admin/<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                                    <h5><?= htmlspecialchars($item['name'] ?? 'Product') ?></h5>
                                                    <?php if (!$item['available']): ?>
                                                        <div class="text-danger"><?= $item['message'] ?></div>
                                                    <?php endif; ?>
                                                </td>
                                                <?php if ($item['available']): ?>
                                                    <td class="price"><span>$<?= number_format($item['price'], 2) ?></span></td>
                                                    <td class="qty">
                                                        <div class="quantity">
                                                            <span class="qty-minus" onclick="changeQty(this, -1)">
                                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                                            </span>

                                                            <input type="number"
                                                                class="qty-text"
                                                                name="quantities[<?= $item['sc_id'] ?>]"
                                                                value="<?= $item['quantity'] ?>"
                                                                min="1"
                                                                max="<?= $item['on_sale_quantity'] ?>">
                                                            <span class="qty-plus" onclick="changeQty(this, 1)">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="total_price"><span>$<?= number_format($item['total'], 2) ?></span></td>
                                                <?php else: ?>
                                                    <td colspan="3" class="text-danger">Not Available</td>
                                                <?php endif; ?>
                                                <td class="cart_product_remove">
                                                    <a href="#"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-12 col-lg-4">
                            <div class="cart-summary">
                                <h5>Cart Total</h5>
                                <ul class="summary-table">
                                    <li><span>subtotal:</span> <span>$<?= number_format($subtotal, 2) ?></span></li>
                                    <li><span>tax:</span> <span>$<?= number_format($tax, 2) ?></span></li>
                                    <li><span>shipping:</span> <span>Free</span></li>
                                    <li><span>total:</span> <span>$<?= number_format($total, 2) ?></span></li>
                                </ul>
                                <div class="cart-btn mt-100">
                                    <button type="submit" name="update_cart" class="btn essence-btn btn-block">
                                        Update Cart
                                    </button>
                                    <a href="checkout.php" class="btn essence-btn btn-block mt-3">Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End of row -->
                </form>
            <?php else: ?>
                <!-- Empty cart message remains the same -->
            <?php endif; ?>
        </div>
    </section>
    <script>
        function changeQty(elem, change) {
            const input = elem.parentElement.querySelector('.qty-text');
            let qty = parseInt(input.value);
            if (!isNaN(qty)) {
                qty += change;
                if (qty >= parseInt(input.min) && qty <= parseInt(input.max)) {
                    input.value = qty;
                }
            }
        }
    </script>

    <?php
    include 'footer.php';
    ?>



</body>

</html>