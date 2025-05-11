    <?php include 'head.php'; ?>


    <body>
        <?php include 'header.php'; ?>


        <div class="cart-bg-overlay"></div>

        <div class="right-side-cart-area">

            <!-- Cart Button -->
            <div class="cart-button">
                <a href="#" id="rightSideCart"><img src="img/core-img/bag.svg" alt=""> <span>3</span></a>
            </div>

            <div class="cart-content d-flex">

                <!-- Cart List Area -->
                <div class="cart-list">
                    <!-- Single Cart Item -->
                    <div class="single-cart-item">
                        <a href="#" class="product-image">
                            <img src="img/product-img/product-1.jpg" class="cart-thumb" alt="">
                            <!-- Cart Item Desc -->
                            <div class="cart-item-desc">
                                <span class="product-remove"><i class="fa fa-close" aria-hidden="true"></i></span>
                                <span class="badge">Mango</span>
                                <h6>Button Through Strap Mini Dress</h6>
                                <p class="size">Size: S</p>
                                <p class="color">Color: Red</p>
                                <p class="price">$45.00</p>
                            </div>
                        </a>
                    </div>

                    <!-- Single Cart Item -->
                    <div class="single-cart-item">
                        <a href="#" class="product-image">
                            <img src="img/product-img/product-2.jpg" class="cart-thumb" alt="">
                            <!-- Cart Item Desc -->
                            <div class="cart-item-desc">
                                <span class="product-remove"><i class="fa fa-close" aria-hidden="true"></i></span>
                                <span class="badge">Mango</span>
                                <h6>Button Through Strap Mini Dress</h6>
                                <p class="size">Size: S</p>
                                <p class="color">Color: Red</p>
                                <p class="price">$45.00</p>
                            </div>
                        </a>
                    </div>

                    <!-- Single Cart Item -->
                    <div class="single-cart-item">
                        <a href="#" class="product-image">
                            <img src="img/product-img/product-3.jpg" class="cart-thumb" alt="">
                            <!-- Cart Item Desc -->
                            <div class="cart-item-desc">
                                <span class="product-remove"><i class="fa fa-close" aria-hidden="true"></i></span>
                                <span class="badge">Mango</span>
                                <h6>Button Through Strap Mini Dress</h6>
                                <p class="size">Size: S</p>
                                <p class="color">Color: Red</p>
                                <p class="price">$45.00</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="cart-amount-summary">

                    <h2>Summary</h2>
                    <ul class="summary-table">
                        <li><span>subtotal:</span> <span>$274.00</span></li>
                        <li><span>delivery:</span> <span>Free</span></li>
                        <li><span>discount:</span> <span>-15%</span></li>
                        <li><span>total:</span> <span>$232.00</span></li>
                    </ul>
                    <div class="checkout-btn mt-100">
                        <a href="checkout.html" class="btn essence-btn">check out</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ##### Right Side Cart End ##### -->

        <!-- ##### Single Product Details Area Start ##### -->
        <section class="single_product_details_area">
            <div class="container">
                <div class="row justify-content-between">
                    <!-- Product Gallery with Thumbnails -->
                    <div class="col-lg-6 col-md-6">
                        <div class="product-gallery">
                            <div class="main-image mb-3">
                                <div class="product-main-slider owl-carousel">
                                    <img src="img/product-img/product-big-1.jpg" class="img-fluid rounded-3" alt="Product Image">
                                    <img src="img/product-img/product-big-2.jpg" class="img-fluid rounded-3" alt="Product Image">
                                    <img src="img/product-img/product-big-3.jpg" class="img-fluid rounded-3" alt="Product Image">
                                </div>
                            </div>
                            <div class="thumbnail-slider owl-carousel">
                                <div class="thumb-item"><img src="img/product-img/product-thumb-1.jpg" class="img-fluid" alt="Thumbnail"></div>
                                <div class="thumb-item"><img src="img/product-img/product-thumb-2.jpg" class="img-fluid" alt="Thumbnail"></div>
                                <div class="thumb-item"><img src="img/product-img/product-thumb-3.jpg" class="img-fluid" alt="Thumbnail"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="col-lg-5 col-md-6">
                        <div class="product-details">
                            <span class="product-category badge bg-warning text-dark mb-3">MANGO COLLECTION</span>
                            <h1 class="product-title mb-2">One Shoulder Glitter Midi Dress</h1>

                            <div class="product-price mb-3">
                                <span class="current-price">$49.00</span>
                                <span class="old-price">$65.00</span>
                                <span class="discount-percentage badge bg-danger ms-2">25% OFF</span>
                            </div>

                            <div class="product-rating mb-3">
                                <div class="stars">
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star-half-alt text-warning"></i>
                                </div>
                                <span class="rating-count">(128 reviews)</span>
                            </div>

                            <p class="product-description lead mb-4">
                                Elevate your evening look with our glamorous glitter midi dress. Features a sophisticated one-shoulder design and comfortable stretch fabric.
                            </p>

                            <div class="product-options mb-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Select Size</label>
                                        <div class="size-options d-flex gap-2">
                                            <button type="button" class="btn btn-outline-dark btn-size">S</button>
                                            <button type="button" class="btn btn-outline-dark btn-size active">M</button>
                                            <button type="button" class="btn btn-outline-dark btn-size">L</button>
                                            <button type="button" class="btn btn-outline-dark btn-size">XL</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Choose Color</label>
                                        <div class="color-options d-flex gap-2">
                                            <button type="button" class="btn btn-color active" style="background-color: #000"></button>
                                            <button type="button" class="btn btn-color" style="background-color: #fff"></button>
                                            <button type="button" class="btn btn-color" style="background-color: #dc3545"></button>
                                            <button type="button" class="btn btn-color" style="background-color: #6f42c1"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="product-actions d-flex gap-3">
                                <div class="quantity-selector">
                                    <button class="btn btn-quantity minus">-</button>
                                    <input type="number" value="1" min="1" class="quantity-input">
                                    <button class="btn btn-quantity plus">+</button>
                                </div>
                                <button class="btn btn-dark btn-add-to-cart flex-grow-1">
                                    <i class="fa fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                                <button class="btn btn-outline-dark btn-wishlist">
                                    <i class="fa fa-heart"></i>
                                </button>
                            </div>

                            <div class="product-meta mt-4 pt-3 border-top">
                                <p class="text-muted small mb-1">SKU: DRS-456</p>
                                <p class="text-muted small mb-1">Category: Dresses</p>
                                <p class="text-muted small">Tags: Partywear, Glitter, Evening Dress</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
            .product-gallery {
                position: sticky;
                top: 20px;
            }

            .product-title {
                font-size: 2rem;
                font-weight: 700;
            }

            .product-price .current-price {
                font-size: 1.5rem;
                font-weight: bold;
                color: #dc3545;
            }

            .product-price .old-price {
                font-size: 1.1rem;
                text-decoration: line-through;
                color: #6c757d;
                margin-left: 10px;
            }

            .btn-size {
                min-width: 45px;
                padding: 8px 12px;
            }

            .btn-color {
                width: 35px;
                height: 35px;
                border-radius: 50%;
                border: 2px solid #dee2e6;
                position: relative;
            }

            .btn-color.active::after {
                content: "";
                position: absolute;
                top: -3px;
                left: -3px;
                right: -3px;
                bottom: -3px;
                border: 2px solid #000;
                border-radius: 50%;
            }

            .quantity-selector {
                display: flex;
                align-items: center;
                border: 1px solid #dee2e6;
                border-radius: 5px;
            }

            .btn-quantity {
                background: #f8f9fa;
                border: none;
                padding: 5px 15px;
            }

            .quantity-input {
                width: 50px;
                border: none;
                text-align: center;
                -moz-appearance: textfield;
            }

            .quantity-input::-webkit-outer-spin-button,
            .quantity-input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            .product-actions .btn-add-to-cart {
                padding: 12px 20px;
                transition: all 0.3s;
            }

            .product-actions .btn-add-to-cart:hover {
                transform: translateY(-2px);
            }

            .thumbnail-slider .thumb-item {
                cursor: pointer;
                border: 2px solid transparent;
                transition: all 0.3s;
            }

            .thumbnail-slider .thumb-item.active {
                border-color: #000;
            }
        </style>

        <script>
            // Initialize main carousel with thumbnail controls
            $('.product-main-slider').owlCarousel({
                items: 1,
                thumbs: true,
                thumbImage: true,
                thumbContainerClass: 'thumbnail-slider',
                thumbItemClass: 'thumb-item'
            });

            // Add active class to size buttons
            $('.btn-size').click(function() {
                $('.btn-size').removeClass('active');
                $(this).addClass('active');
            });

            // Quantity selector functionality
            $('.btn-quantity').click(function(e) {
                e.preventDefault();
                var input = $('.quantity-input');
                var currentVal = parseInt(input.val());
                if ($(this).hasClass('plus')) {
                    input.val(currentVal + 1);
                } else {
                    if (currentVal > 1) {
                        input.val(currentVal - 1);
                    }
                }
            });
        </script>
        <!-- ##### Single Product Details Area End ##### -->

        <?php include 'footer.php'; ?>


    </body>

    </html>