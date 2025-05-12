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
        try {
            if (empty($_SESSION['cart'])) {
                throw new Exception("Your cart is empty");
            }
            // Validate required fields

            // Start transaction
            $conn->begin_transaction();

            // Save address if checkbox is checked
            if (isset($_POST['save_address'])) {
                $stmt = $conn->prepare("INSERT INTO user_address 
                (user_id, address, postcode, city, state, province, phone)
                VALUES (?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "issssss",
                    $user_id,
                    $_POST['street_address'],
                    $_POST['postcode'],
                    $_POST['city'],
                    $_POST['state'],
                    $_POST['state'], // Using state as province
                    $_POST['phone_number']
                );
                $stmt->execute();
                $stmt->close();
            }

            // Handle payment method
            $payment_method = $_POST['paymentMethod'];
            if ($payment_method === 'paypal') {
                // Add PayPal integration logic here
                throw new Exception("PayPal payments are currently unavailable. Please choose Cash on Delivery.");
            }

            // Create order
            $stmt = $conn->prepare("INSERT INTO orders 
            (user_id, total_amount, payment_method, order_status)
            VALUES (?, ?, ?, 'pending')");

            $stmt->bind_param(
                "ids",
                $user_id,
                $_POST['total'],
                $payment_method
            );
            $stmt->execute();
            $order_id = $conn->insert_id;
            $stmt->close();

            // Create order items
            if (empty($_SESSION['cart'])) {
                throw new Exception("Your cart is empty");
            }

            $stmt = $conn->prepare("INSERT INTO order_items 
            (order_id, product_id, product_name, quantity, price)
            VALUES (?, ?, ?, ?, ?)");

            foreach ($_SESSION['cart'] as $product_id => $item) {
                $stmt->bind_param(
                    "issid",
                    $order_id,
                    $product_id,
                    $item['name'],
                    $item['quantity'],
                    $item['price']
                );
                $stmt->execute();
            }
            $stmt->close();

            // Commit transaction
            $conn->commit();

            // Clear cart and redirect
            unset($_SESSION['cart']);
            $_SESSION['order_id'] = $order_id;
            header("Location: order_success.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $error = $e->getMessage();
        }
    }
    ?>

 <body>
     <?php
        include 'header.php';   ?>

     <!-- ##### Right Side Cart Area ##### -->
     <div class="cart-bg-overlay"></div>
     <div class="right-side-cart-area">

         <!-- Cart Button -->
         <div class="cart-button">
             <a href="#" id="rightSideCart"><img src="img/core-img/bag.svg" alt=""> <span><?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?></span>
             </a>
         </div>

         <div class="cart-content d-flex">

             <!-- Cart List Area -->
             <div class="cart-list">
                 <?php
                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        $subtotal = 0;
                        foreach ($_SESSION['cart'] as $id => $item) {
                            $itemTotal = $item['price'] * $item['quantity'];
                            $subtotal += $itemTotal;
                    ?>
                         <!-- Single Cart Item -->
                         <div class="single-cart-item">
                             <a href="#" class="product-image">
                                 <img src="admin/<?= htmlspecialchars($item['image']) ?>" class="cart-thumb" alt="<?= htmlspecialchars($item['name']) ?>">

                                 <!-- Cart Item Desc -->
                                 <div class="cart-item-desc">

                                     <span class="product-remove" data-id="<?= $id ?>">
                                         <i class="fa fa-close" aria-hidden="true"></i>
                                     </span>

                                     <script>
                                         document.querySelectorAll('.product-remove').forEach(function(el) {
                                             el.addEventListener('click', function() {
                                                 const id = this.getAttribute('data-id');
                                                 window.location.href = 'remove_from_cart.php?id=' + id;
                                             });
                                         });
                                     </script>


                                     <span class="badge">Mango</span>

                                     <h6><?= htmlspecialchars($item['name']) ?></h6>
                                     <p class="size">Size: S</p>
                                     <p class="size">Quantity: <?= $item['quantity'] ?></p>
                                     <p class="color">Color: Red</p>
                                     <p class="price">$<?= number_format($item['price'], 2) ?></p>
                                 </div>
                             </a>
                         </div>
                 <?php
                        }
                    } else {
                        echo '<p class="p-3">Your cart is empty</p>';
                    }
                    ?>
             </div>



             <!-- Cart Summary -->
             <div class="cart-amount-summary">

                 <h2>Summary</h2>
                 <ul class="summary-table">

                     <?php
                        $subtotal = 0; // Set default
                        $discount = 0;
                        $delivery = 0;

                        // If cart is not empty, calculate subtotal
                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $subtotal += $item['price'] * $item['quantity'];
                            }
                        }

                        $total = $subtotal - $discount + $delivery;
                        ?>

                     <li><span>subtotal:</span> <span>$<?= isset($subtotal) ? number_format($subtotal, 2) : '0.00' ?></span></li>
                     <li><span>delivery:</span> <span><?= $delivery === 0 ? 'Free' : '$' . number_format($delivery, 2) ?></span></li>
                     <li><span>discount:</span> <span>-$<?= number_format($discount, 2) ?></span></li>
                     <li><span>total:</span> name total<span>$<?= isset($total) ? number_format($total, 2) : '0.00' ?></span></li>



                 </ul>
                 <div class="checkout-btn mt-100">
                     <a href="checkout.php" class="btn essence-btn">check out</a>
                 </div>
             </div>
         </div>
     </div>

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
                                     <input type="text" class="form-control" name="street_address" required>
                                 </div>

                                 <div class="col-12 mb-3">
                                     <label>Postcode <span>*</span></label>
                                     <input type="text" class="form-control" name="postcode" required>
                                 </div>

                                 <div class="col-12 mb-3">
                                     <label>City <span>*</span></label>
                                     <input type="text" class="form-control" name="city" required>
                                 </div>

                                 <div class="col-12 mb-3">
                                     <label>State <span>*</span></label>
                                     <input type="text" class="form-control" name="state" required>
                                 </div>

                                 <div class="col-12 mb-3">
                                     <label>Phone Number <span>*</span></label>
                                     <input type="tel" class="form-control" name="phone_number" required>
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

                                 <?php
                                    $subtotal = 0; // Initialize subtotal

                                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])):
                                        foreach ($_SESSION['cart'] as $id => $item):
                                            $item_total = $item['price'] * $item['quantity'];
                                            $subtotal += $item_total; // Accumulate total
                                    ?>
                                         <li>
                                             <span><?= htmlspecialchars($item['name']) ?> (Qty: <?= $item['quantity'] ?>)</span>
                                             <span>$<?= number_format($item_total, 2) ?></span>
                                         </li>
                                     <?php
                                        endforeach;
                                    else:
                                        ?>
                                     <li><span>Your cart is empty</span></li>
                                 <?php endif; ?>

                                 <?php
                                    $shipping = 0;
                                    $total = $subtotal + $shipping;
                                    ?>

                                 <!-- CORRECTED SUBTOTAL LINE -->
                                 <li><span>Subtotal</span> <span>$<?= number_format($subtotal, 2) ?></span></li>
                                 <li><span>Shipping</span> <span>Free</span></li>
                                 <li><span>Total:</span> <span>$<?= number_format($total, 2); ?></span></li>

                             </ul>

                             <!-- Payment Method Section -->
                             <div id="payment-methods" role="tablist" class="mb-4">
                                 <div class="card">
                                     <div class="card-header" role="tab">
                                         <h6 class="mb-0">
                                             <div class="custom-control custom-radio">
                                                 <input type="radio" class="custom-control-input" id="paypal" name="paymentMethod" value="paypal">
                                                 <label class="custom-control-label" for="paypal">
                                                     <i class=" mr-3"></i>Paypal
                                                 </label>
                                             </div>
                                         </h6>
                                     </div>
                                     <div id="paypal-content" class="collapse" data-parent="#payment-methods">
                                         <div class="card-body">
                                             <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin pharetra tempor so dales. Phasellus sagittis auctor gravida.</p>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="card">
                                     <div class="card-header" role="tab">
                                         <h6 class="mb-0">
                                             <div class="custom-control custom-radio">
                                                 <input type="radio" class="custom-control-input" id="cod" name="paymentMethod" value="cod">
                                                 <label class="custom-control-label" for="cod">
                                                     <i class=" mr-3"></i>Cash on Delivery
                                                 </label>
                                             </div>
                                         </h6>
                                     </div>
                                     <div id="cod-content" class="collapse" data-parent="#payment-methods">
                                         <div class="card-body">
                                             <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo quis in veritatis officia inventore, tempore provident dignissimos.</p>
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
         </div>
     </div>





     <!-- ##### Checkout Area End ##### -->

     <!-- ##### Footer Area Start ##### -->
     <?php include 'footer.php'; ?>
 </body>

 </html>