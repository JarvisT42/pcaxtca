<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Essence - Fashion Ecommerce Template</title>
    <link rel="icon" href="../img/core-img/favicon.ico">
    <link rel="stylesheet" href="../bootstrap-5.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/core-style.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <style>
        .slider-range-price {
            margin: 20px 0;
            height: 10px;
        }

        .product-image {
            display: block;
            width: 200px;
            height: 250px;
            overflow: hidden;
        }

        .cart-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dropdown-menu {
            padding: 0;
            border-radius: 4px;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: blue;
            color: white !important;
        }
    </style>
</head>

<body>

    <header class="header_area">
        <div class="classy-nav-container breakpoint-off d-flex align-items-center justify-content-between">
            <nav class="classy-navbar" id="essenceNav">
                <a class="nav-brand" href="../shop.php">Pcaxtca Shop</a>
                <div class="classy-navbar-toggler">
                    <span class="navbarToggler"><span></span><span></span><span></span></span>
                </div>
                <div class="classy-menu">
                    <div class="classycloseIcon">
                        <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                    </div>
                    <div class="classynav">
                        <ul>
                            <li><a href="../shop">Shop</a></li>
                            <li><a href="blog">Blog</a></li>
                            <li><a href="contact">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="header-meta d-flex clearfix justify-content-end">
                <div class="search-area">
                    <form action="shop.php" method="get">
                        <input type="search" name="search" id="headerSearch" placeholder="Type for search">
                        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>

                <div class="user-login-info dropdown">
                    <a href="sign-in.php">
                        <img src="../img/core-img/user.svg" alt="">
                    </a>
                </div>

                <div class="cart-area">
                    <a href="#" id="essenceCartBtn">
                        <img src="../img/core-img/bag.svg" alt="Cart">
                        <span>2</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="cart-bg-overlay"></div>

    <div class="right-side-cart-area">
        <div class="cart-button">
            <a href="#" id="rightSideCart">
                <img src="../img/core-img/bag.svg" alt="">
                <span>2</span>
            </a>
        </div>

        <div class="cart-content d-flex">
            <div class="cart-list">
                <div class="single-cart-item">
                    <a href="#" class="product-image">
                        <img src="../img/product-img/product-1.jpg" class="cart-thumb" alt="Product">
                        <div class="cart-item-desc">
                            <span class="product-remove"><i class="fa fa-close" aria-hidden="true"></i></span>
                            <span class="badge">Mango</span>
                            <h6>Summer T-Shirt</h6>
                            <p class="size">Size: M</p>
                            <p class="size">Quantity: 1</p>
                            <p class="color">Color: Red</p>
                            <p class="price">$29.99</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="cart-amount-summary">
                <h2>Summary</h2>
                <ul class="summary-table">
                    <li><span>subtotal:</span> <span>$59.98</span></li>
                    <li><span>delivery:</span> <span>Free</span></li>
                    <li><span>discount:</span> <span>$0.00</span></li>
                    <li><span>total:</span> <span>$59.98</span></li>
                </ul>
                <div class="checkout-btn mt-100">
                    <a href="checkout.php" class="btn essence-btn">check out</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Add this CSS -->
    <style>
        /* Cart Overlay */
        .cart-bg-overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
            transition: all 0.3s ease-in-out;
        }

        .right-side-cart-area {
            position: fixed;
            width: 400px;
            height: 100%;
            top: 0;
            right: -400px;
            background: #fff;
            z-index: 1000;
            transition: all 0.3s ease-in-out;
        }

        .right-side-cart-area.show {
            right: 0;
        }

        .cart-bg-overlay.show {
            display: block;
        }
    </style>

    <!-- Add this JavaScript at the end before </body> -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartBtn = document.getElementById('essenceCartBtn');
            const rightSideCart = document.getElementById('rightSideCart');
            const cartOverlay = document.querySelector('.cart-bg-overlay');
            const cartPanel = document.querySelector('.right-side-cart-area');

            // Open cart
            [cartBtn, rightSideCart].forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    cartOverlay.classList.add('show');
                    cartPanel.classList.add('show');
                });
            });

            // Close cart when clicking overlay
            cartOverlay.addEventListener('click', function() {
                cartOverlay.classList.remove('show');
                cartPanel.classList.remove('show');
            });

            // Prevent cart panel from closing when clicking inside
            cartPanel.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/plugins.js"></script>
    <script src="../js/classy-nav.min.js"></script>
    <script src="../js/active.js"></script>

</body>

</html>