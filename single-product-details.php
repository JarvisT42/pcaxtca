 <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    include 'connect/connection.php';

    // Get product ID from URL
    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addtocart'])) {
        session_start();

        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $product_id = intval($_POST['product_id']);

        // Fetch product details from database


        // Debug: check if values are being passed correctly

        $query = "SELECT * FROM products WHERE id = $product_id";
        $result = mysqli_query($conn, $query);
        $product = mysqli_fetch_assoc($result);

        if ($product && $quantity > 0 && $quantity <= $product['quantity']) {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'quantity' => $quantity,
                    'name' => $product['product_name'],
                    'price' => $product['sale_price'] > 0 ? $product['sale_price'] : $product['price'],
                    'image' =>  $product['image_path']
                ];
            }
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);

        exit();
    }
    ?>
 <?php include 'head.php'; ?>

 <body>
     <!-- ##### Header Area Start ##### -->
     <?php include 'header.php'; ?>

     <!-- ##### Header Area End ##### -->

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
                     <li><span>total:</span> <span>$<?= isset($total) ? number_format($total, 2) : '0.00' ?></span></li>



                 </ul>
                 <div class="checkout-btn mt-100">
                     <a href="checkout.php" class="btn essence-btn">check out</a>
                 </div>
             </div>
         </div>
     </div>
     <!-- ##### Right Side Cart End ##### -->

     <!-- ##### Single Product Details Area Start ##### -->
     <section class="single_product_details_area d-flex align-items-center">

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
             <a href="cart.html">
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


                 <!-- Cart & Favourite Box -->
                 <div class="cart-fav-box d-flex align-items-center">
                     <button type="submit" name="addtocart" class="btn essence-btn">Add to cart</button>
                     <button type="submit" name="buynow" value="5" class="btn essence-btn">Buy now</button>
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
     <!-- ##### Single Product Details Area End ##### -->

     <!-- ##### Footer Area Start ##### -->
     <?php include 'footer.php'; ?>


 </body>

 </html>