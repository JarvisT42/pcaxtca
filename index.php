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
                        <a href="checkout.html" class="btn essence-btn">check out</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ##### Right Side Cart End ##### -->

        <!-- ##### Welcome Area Start ##### -->
        <section class="welcome_area bg-img background-overlay" style="background-image: url(img/bg-img/bgnew.png);">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-12">
                        <div class="hero-content">

                            <!-- <a href="#" class="btn essence-btn">view collection</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ##### Welcome Area End ##### -->

        <!-- ##### Top Catagory Area Start ##### -->

        <!-- ##### Top Catagory Area End ##### -->

        <!-- ##### CTA Area Start ##### -->
        <div class="cta-area">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="cta-content bg-img background-overlay" style="background-image: url(img/bg-img/bgnew2.png);">
                            <div class="h-100 d-flex align-items-center justify-content-end">
                                <div class="cta--text">
                                    <h6>-60%</h6>
                                    <h2 class="custom-heading">Global Sale</h2>

                                    <style>
                                        .custom-heading {
                                            background-color: rgb(172, 203, 236);
                                            /* blue */
                                            color: white;
                                            padding: 10px 20px;
                                            border-radius: 6px;
                                        }
                                    </style>
                                    <a href="#" class="btn essence-btn">Buy Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ##### CTA Area End ##### -->

        <!-- ##### New Arrivals Area Start ##### -->
        <section class="new_arrivals_area section-padding-80 clearfix">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-heading text-center">
                            <h2>Popular Products</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="popular-products-slides owl-carousel">
                            <?php
                            // Database connection
                            include 'connect/connection.php';

                            // Query to get products on sale
                            $query = "SELECT p.*, pos.on_sale_quantity 
                         FROM product_on_sales pos
                         JOIN products p ON pos.product_id = p.id
                         WHERE pos.on_sale_quantity > 0";

                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($product = $result->fetch_assoc()) {
                                    // Calculate price display
                                    $price = number_format($product['price'], 2);
                                    $sale_price = number_format($product['sale_price'], 2);
                                    $has_sale = ($product['sale_price'] > 0);
                            ?>
                                    <!-- Single Product -->
                                    <div class="single-product-wrapper">
                                        <div class="product-img" style="position: relative; aspect-ratio: 1/1; overflow: hidden;">
                                            <img src="admin/<?php echo $product['image_path']; ?>"
                                                alt="<?php echo $product['product_name']; ?>"
                                                style="width: 100%; height: 100%; object-fit: cover; display: block;">

                                            <!-- Product Badge -->
                                            <?php if ($has_sale) : ?>
                                                <div class="product-badge offer-badge" style="position: absolute; top: 10px; left: 10px; background: red; color: white; padding: 5px 10px; border-radius: 3px;">
                                                    <span>-<?php echo ceil(100 - ($product['sale_price'] / $product['price'] * 100)); ?>%</span>
                                                </div>
                                            <?php endif; ?>

                                            <div class="product-favourite" style="position: absolute; top: 10px; right: 10px;">
                                                <a href="#" class="favme fa fa-heart" style="color: white; font-size: 1.2rem;"></a>
                                            </div>
                                        </div>

                                        <div class="product-description" style="padding: 15px; text-align: center;">
                                            <span style="display: block; color: #666; font-size: 0.9rem; margin-bottom: 5px;">
                                                <?php echo htmlspecialchars($product['product_brand_id']); ?>
                                            </span>
                                            <a href="single-product-details.php?id=<?php echo $product['id']; ?>" style="color: inherit; text-decoration: none;">
                                                <h6 style="margin: 10px 0; font-size: 1rem; min-height: 40px;"><?php echo htmlspecialchars($product['product_name']); ?></h6>
                                            </a>

                                            <?php if ($has_sale) : ?>
                                                <p style="margin: 10px 0;">
                                                    <span style="text-decoration: line-through; color: #999; margin-right: 5px;">$<?php echo $price; ?></span>
                                                    <span style="color: #333; font-weight: bold;">$<?php echo $sale_price; ?></span>
                                                </p>
                                            <?php else : ?>
                                                <p style="margin: 10px 0; color: #333; font-weight: bold;">$<?php echo $price; ?></p>
                                            <?php endif; ?>

                                            <div class="hover-content" style="margin-top: 15px;">
                                                <div class="add-to-cart-btn">
                                                    <a href="add_to_cart.php?id=<?php echo $product['id']; ?>"
                                                        class="btn essence-btn"
                                                        style="background: #4CAF50; color: white; padding: 8px 20px; border-radius: 20px; font-size: 0.9rem;">
                                                        Add to Cart
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add this CSS to your stylesheet -->
                                    <style>
                                        .single-product-wrapper {
                                            border: 1px solid #eee;
                                            border-radius: 8px;
                                            overflow: hidden;
                                            transition: all 0.3s ease;
                                        }

                                        .single-product-wrapper:hover {
                                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                                            transform: translateY(-5px);
                                        }

                                        .product-img {
                                            position: relative;
                                            background: #f8f8f8;
                                        }

                                        .product-img img {
                                            transition: transform 0.3s ease;
                                        }

                                        .single-product-wrapper:hover .product-img img {
                                            transform: scale(1.05);
                                        }
                                    </style>


                            <?php
                                }
                            } else {
                                echo "<p>No products currently on sale</p>";
                            }
                            $conn->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ##### New Arrivals Area End ##### -->

        <!-- ##### Brands Area Start ##### -->

        <!-- ##### Brands Area End ##### -->

        <!-- ##### Footer Area Start ##### -->
        <?php include 'footer.php'; ?>

    </body>

    </html>