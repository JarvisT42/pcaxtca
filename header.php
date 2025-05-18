 <?php
    include 'connect/connection.php';


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




    ?>


 <header class="header_area ">


     <div class="classy-nav-container breakpoint-off d-flex align-items-center justify-content-between">
         <!-- Classy Menu -->
         <nav class="classy-navbar" id="essenceNav">
             <!-- Logo -->
             <a class="nav-brand" href="index">Pcaxtca Shop</a>
             <!-- Navbar Toggler -->
             <div class="classy-navbar-toggler">
                 <span class="navbarToggler"><span></span><span></span><span></span></span>
             </div>
             <!-- Menu -->
             <div class="classy-menu">
                 <!-- close btn -->
                 <div class="classycloseIcon">
                     <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                 </div>
                 <!-- Nav Start -->
                 <div class="classynav">
                     <ul>
                         <li><a href="shop">Shop</a>

                         </li>

                         <li><a href="blog">Blog</a></li>
                         <li><a href="contact">Contact</a></li>
                     </ul>
                 </div>
                 <!-- Nav End -->
             </div>
         </nav>

         <!-- Header Meta Data -->
         <div class="header-meta d-flex clearfix justify-content-end">
             <!-- Search Area -->
             <div class="search-area">
                 <form action="shop.php" method="get">
                     <input type="search" name="search" id="headerSearch"
                         placeholder="Type for search"
                         value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                     <!-- Preserve existing parameters -->
                     <?php
                        foreach ($_GET as $key => $value) {
                            if ($key === 'search' || $key === 'page') continue;
                            if (is_array($value)) {
                                foreach ($value as $val) {
                                    echo '<input type="hidden" name="' . htmlspecialchars($key) . '[]" value="' . htmlspecialchars($val) . '">';
                                }
                            } else {
                                echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                            }
                        }
                        ?>

                     <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                 </form>
             </div>
             <!-- Favourite Area -->

             <!-- User Login Info -->
             <div class="user-login-info dropdown">
                 <?php if ($is_logged_in): ?>
                     <a href="#" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <img src="img/core-img/user.svg" alt="">
                     </a>
                     <div class="dropdown-menu dropdown-menu-right custom-dropdown" aria-labelledby="userDropdown">
                         <a class="dropdown-item" href="user/my-account.php">My Account</a>
                         <a class="dropdown-item" href="user/my-purchase.php">My Purchase</a>
                         <div class="dropdown-divider"></div>
                         <a class="dropdown-item" href="sign-out.php">Logout</a>
                     </div>
                 <?php else: ?>
                     <a href="sign-in.php">
                         <img src="img/core-img/user.svg" alt="">
                     </a>
                 <?php endif; ?>
             </div>
             <style>
                 .dropdown-menu {
                     padding: 0;
                     /* Remove default padding from dropdown menu */
                     border-radius: 4px;
                     /* Optional: maintain rounded corners */
                 }

                 .dropdown-item {
                     padding: 0.75rem 1.5rem;
                     /* Ensure proper padding */
                     display: block;
                     /* Make links block elements */
                     transition: all 0.2s ease;
                     /* Smooth transition */
                 }

                 .dropdown-menu .dropdown-item:hover,
                 .dropdown-menu .dropdown-item:focus,
                 .dropdown-menu .dropdown-item:active {
                     background-color: blue;
                     color: white !important;
                     /* Better contrast for text */
                     margin: 0;
                     /* Remove any potential margins */
                     width: 100%;
                     /* Ensure full width */
                 }
             </style>
             <!-- Cart Area -->
             <div class="cart-area">
                 <a href="#" id="essenceCartBtn">
                     <img src="img/core-img/bag.svg" alt="Cart">
                     <span><?= count($cartItems) ?></span>
                 </a>
             </div>
         </div>

     </div>
 </header>

 <div class="cart-bg-overlay"></div>

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
         <div class="cart-amount-summary ">
             <h2>Summary</h2>
             <ul class="summary-table ">
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
             <!-- <div class="checkout-btn mt-100">
                 <a href="checkout.php" class="btn essence-btn">check out</a>
                 <a href="view.php" class="btn essence-btn">view shopping cart</a>

             </div> -->
             <div class="checkout-btn mt-5 d-flex gap-3 ">
                 <a href="checkout.php" class="btn essence-btn">Check Out</a>
                 <a href="cart.php" class="btn essence-btn">View My Cart</a>
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