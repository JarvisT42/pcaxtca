<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'connect/connection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it is not started
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$user_id = $_SESSION['user_id'] ?? null;

// Get product ID from URL

// Check if product is already in the cart
$isInCart = false;

if ($product_id > 0 && $user_id > 0) {
    $cart_query = $conn->prepare("
        SELECT product_id 
        FROM shopping_cart 
        WHERE user_id = ? AND product_id = ?
    ");
    $cart_query->bind_param("ii", $user_id, $product_id);
    $cart_query->execute();
    $result = $cart_query->get_result();
    $isInCart = $result->num_rows > 0;
    $cart_query->close();
}



// Fetch product details
$product = [];
if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

if (empty($product)) {
    die("Product not found!");
}

// Handle Add to Cart


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['addtocart'])) {
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $product_id = intval($_POST['product_id']);
        $user_id = intval($_POST['user_id']);

        // DB connection




        // 3. Insert into shopping_cart_item
        $stmt = $conn->prepare("INSERT INTO shopping_cart (user_id, product_id, qty) VALUES (?, ?, ?)");
        $stmt->bind_param("iii",  $user_id, $product_id, $quantity);
        if (!$stmt->execute()) {
            die("Failed to insert shopping_cart_item: " . $stmt->error);
        }
        $stmt->close();
        $conn->close();

        // Redirect back to same page
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $product_id);
        exit();
    }
    if (isset($_POST['removefromcart'])) {
        $product_id = intval($_POST['product_id']);
        $user_id = intval($_POST['user_id']);

        $sql = "
        DELETE 
        FROM shopping_cart
        
        WHERE product_id = ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",  $product_id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $product_id);
        } else {
            echo "Error removing product: " . $stmt->error;
        }

        $stmt->close();
    }
}




// Fetch cart items from database if user is logged in    // Fetch cart items from database if user is logged in





$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'] ?? null;
$product = [];
$cartItems = [];
$subtotal = 0;

// Fetch product details if product_id is valid
if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// Fetch cart items from database if user is logged in
if ($user_id) {
    $stmt = $conn->prepare("
        SELECT p.id, p.product_name, p.sale_price, p.image_path, sc.qty
        FROM shopping_cart sc 
        INNER JOIN products p ON sc.product_id = p.id 
        WHERE sc.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItems = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Calculate subtotal
    foreach ($cartItems as $item) {
        $subtotal += $item['sale_price'] * $item['qty'];
    }
}

if (empty($product)) {
    die("Product not found!");
}

// Fetch cart items from database if user is logged in

// Fetch cart items from database if user is logged in

?>
<?php include 'head.php'; ?>


<body>
    <!-- ##### Header Area Start ##### -->
    <?php include 'header.php'; ?>

    <!-- ##### Header Area End ##### -->

    <!-- ##### Right Side Cart Area ##### -->
    <div class="cart-bg-overlay"></div>

    <?php

    ?>

    <div class="right-side-cart-area">
        <!-- Cart Button -->
        <div class="cart-button">
            <a href="#" id="rightSideCart">
                <img src="img/core-img/bag.svg" alt="">
                <span><?= count($cartItems) ?></span>
            </a>
        </div>

        <div class="cart-content d-flex">
            <!-- Cart List Area -->
            <div class="cart-list">
                <?php if (!empty($cartItems)) : ?>
                    <?php foreach ($cartItems as $item) : ?>
                        <div class="single-cart-item">
                            <a href="#" class="product-image">
                                <!-- Main product image with fixed size -->
                                <img src="<?= htmlspecialchars('admin/' . $item['image_path']) ?>"
                                    class="cart-thumb"
                                    alt="<?= htmlspecialchars($item['product_name']) ?>">



                                <div class="cart-item-desc">
                                    <span class="product-remove" data-id="<?= $item['id'] ?>">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </span>
                                    <span class="badge">Mango</span>
                                    <h6><?= htmlspecialchars($item['product_name']) ?></h6>
                                    <p class="size">Size: S</p>
                                    <p class="size">Quantity: <?= $item['qty'] ?></p>
                                    <p class="color">Color: Red</p>
                                    <p class="price">$<?= number_format($item['sale_price'], 2) ?></p>
                                </div>
                                <style>
                                    /* Add fixed size for product images */
                                    .product-image {
                                        display: block;
                                        width: 200px;
                                        /* Adjust as needed */
                                        height: 250px;
                                        /* Adjust as needed */
                                        overflow: hidden;
                                    }

                                    .cart-thumb {
                                        width: 100%;
                                        height: 100%;
                                        object-fit: cover;
                                        /* This ensures images maintain aspect ratio */
                                    }
                                </style>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="p-3">Your cart is empty</p>
                <?php endif; ?>
            </div>

            <!-- Cart Summary -->
            <div class="cart-amount-summary">
                <h2>Summary</h2>
                <ul class="summary-table">
                    <?php
                    $discount = 0;
                    $delivery = 0;
                    $total = $subtotal - $discount + $delivery;
                    ?>
                    <li><span>subtotal:</span> <span>$<?= number_format($subtotal, 2) ?></span></li>
                    <li><span>delivery:</span> <span><?= $delivery === 0 ? 'Free' : '$' . number_format($delivery, 2) ?></span></li>
                    <li><span>discount:</span> <span>-$<?= number_format($discount, 2) ?></span></li>
                    <li><span>total:</span> <span>$<?= number_format($total, 2) ?></span></li>
                </ul>
                <div class="checkout-btn mt-100">
                    <a href="checkout.php" class="btn essence-btn">check out</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.product-remove').forEach(function(el) {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-id');
                window.location.href = 'remove_from_cart.php?id=' + productId;
            });
        });
    </script>
    <!-- ##### Right Side Cart End ##### -->

    <!-- ##### Single Product Details Area Start ##### -->
    <section class="single_product_details_area d-flex align-items-center mt-100">

        <!-- Single Product Thumb -->
        <div class="single_product_thumb clearfix">
            <div class="product_thumbnail_slides owl-carousel">
                <img src="admin/<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                <?php if (!empty($product['image_path2'])): ?>
                    <img src="admin/<?= htmlspecialchars($product['image_path2']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                <?php endif; ?>
            </div>


        </div>

        <!-- Single Product Description -->
        <div class="single_product_desc clearfix">
            <span>mango</span>
            <a href="cart.php">
                <h2><?= htmlspecialchars($product['product_name']) ?></h2>

            </a>
            <p class="product-price">
                <?php if ($product['sale_price'] > 0): ?>
                    <span class="old-price">$<?= number_format($product['price'], 2) ?></span>
                <?php endif; ?>
                $<?= number_format(($product['sale_price'] > 0 ? $product['sale_price'] : $product['price']), 2) ?>
            </p>
            <p class="product-desc"><?= htmlspecialchars($product['description']) ?></p>


            <!-- Form -->

            <form class="cart-form clearfix" method="post" action="">

                <!-- Select Box -->


                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">





                <div class="quantity-controls mt-50 mb-30">
                    <label for="quantity">Quantity</label>
                    <div class="quantity-box d-flex align-items-center mb-30">
                        <button type="button" class="btn btn-sm btn-outline-secondary qty-btn decrement">-</button>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="1"
                            min="1"
                            max="<?= $product['quantity'] ?>"
                            class="form-control mx-2 qty-input"
                            style="width: 60px; text-align: center;">
                        <button type="button" class="btn btn-sm btn-outline-secondary qty-btn increment">+</button>
                    </div>
                    <small>Available: <?= $product['quantity'] ?> items</small>
                </div>





                <!-- Quantity Box -->


                <div class="cart-fav-box d-flex align-items-center">
                    <?php if ($isInCart): ?>
                        <button type="submit" name="removefromcart" class="btn essence-delete-btn">
                            Remove from cart
                        </button>
                    <?php else: ?>
                        <button type="submit" name="addtocart" class="btn essence-btn">
                            Add to cart
                        </button>
                    <?php endif; ?>
                    <button type="submit" name="buynow" class="btn essence-btn">Buy now</button>
                </div>
            </form>



            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const qtyInput = document.querySelector('.qty-input');
                    const decrementBtn = document.querySelector('.decrement');
                    const incrementBtn = document.querySelector('.increment');
                    const maxQuantity = parseInt(qtyInput.getAttribute('max')) || 1000;

                    decrementBtn.addEventListener('click', () => {
                        let currentVal = parseInt(qtyInput.value) || 1;
                        qtyInput.value = currentVal > 1 ? currentVal - 1 : 1;
                    });

                    incrementBtn.addEventListener('click', () => {
                        let currentVal = parseInt(qtyInput.value) || 1;
                        qtyInput.value = currentVal < maxQuantity ? currentVal + 1 : maxQuantity;
                    });

                    qtyInput.addEventListener('input', function() {
                        let value = parseInt(this.value);
                        if (isNaN(value)) value = 1;
                        this.value = Math.min(Math.max(value, 1), maxQuantity);
                    });
                });
            </script>
            <script>
                function changeQty(amount) {
                    const qtyInput = document.getElementById('quantity');
                    let current = parseInt(qtyInput.value);
                    if (!isNaN(current)) {
                        let newValue = current + amount;
                        if (newValue >= 1) {
                            qtyInput.value = newValue;
                        }
                    }
                }
            </script>

        </div>
    </section>
    <?php include 'footer.php'; ?>

</body>

</html>