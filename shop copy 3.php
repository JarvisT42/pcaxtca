<?php
session_start();
include 'connect/connection.php';







// Initialize variables
$category_filter = "";
$sort_order = "ORDER BY p.id DESC";
$params = [];
$types = "";

// Handle category filter
if (isset($_GET['category']) && $_GET['category'] !== 'all') {
    $category_id = intval($_GET['category']);
    $category_filter = "AND p.product_category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

// Handle sorting
if (isset($_GET['sort'])) {
    switch ($_GET['sort']) {
        case 'price_desc':
            $sort_order = "ORDER BY p.price DESC";
            break;
        case 'price_asc':
            $sort_order = "ORDER BY p.price ASC";
            break;
        case 'newest':
        default:
            $sort_order = "ORDER BY p.id DESC";
    }
}

// Main products query
$productsQuery = "SELECT p.*, pc.product_category, pos.on_sale_quantity 
                FROM products p
                JOIN product_categorys pc ON p.product_category_id = pc.id
                INNER JOIN product_on_sales pos ON p.id = pos.product_id
                WHERE pos.on_sale_quantity > 0 
                AND pos.sale_date >= CURDATE()
                $category_filter
                $sort_order";

// Prepare and execute query
$stmt = $conn->prepare($productsQuery);
if ($category_filter !== "") {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$products = $stmt->get_result();

// Count query for pagination
$countQuery = "SELECT COUNT(*) AS total 
              FROM products p
              INNER JOIN product_on_sales pos ON p.id = pos.product_id
              WHERE pos.on_sale_quantity > 0 
              AND pos.sale_date >= CURDATE()
              $category_filter";

$countStmt = $conn->prepare($countQuery);
if ($category_filter !== "") {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$totalProducts = $countStmt->get_result()->fetch_assoc()['total'];
?>


<?php include 'head.php'; ?>

<body>
    <!-- ##### Header Area Start ##### -->

    <?php include 'header.php'; ?>

    <!-- ##### Right Side Cart Area ##### -->
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
                        <h2>dresses</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Shop Grid Area Start ##### -->
    <section class="shop_grid_area section-padding-80">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                    <!-- Sidebar with categories -->
                    <div class="shop_sidebar_area">
                        <div class="widget catagory mb-50">
                            <h6 class="widget-title mb-30">Categories</h6>
                            <div class="catagories-menu">
                                <ul>
                                    <li><a href="?category=all">All Categories</a></li>
                                    <?php
                                    $categories = $conn->query("SELECT id, product_category FROM product_categorys");
                                    while ($category = $categories->fetch_assoc()) {
                                        echo '<li><a href="?category=' . $category['id'] . '">'
                                            . htmlspecialchars($category['product_category']) . '</a></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-8 col-lg-9">
                    <div class="shop_grid_product_area">
                        <div class="row">
                            <div class="col-12">
                                <div class="product-topbar d-flex align-items-center justify-content-between">
                                    <div class="total-products">
                                        <p><span><?= $totalProducts ?></span> products found</p>
                                    </div>
                                    <div class="product-sorting d-flex">
                                        <p>Sort by:</p>
                                        <form action="" method="get">
                                            <?php if (isset($_GET['category'])): ?>
                                                <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
                                            <?php endif; ?>
                                            <select name="sort" id="sortByselect" onchange="this.form.submit()">
                                                <option value="newest" <?= ($_GET['sort'] ?? 'newest') === 'newest' ? 'selected' : '' ?>>Newest</option>
                                                <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                                                <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <?php if ($products && $products->num_rows > 0): ?>
                                <?php while ($product = $products->fetch_assoc()):
                                    $discount = round(($product['price'] - $product['sale_price']) / $product['price'] * 100);

                                    $link = $is_logged_in
                                        ? "single-product-details.php?id=" . $product['id']
                                        : "sign-in.php";
                                ?>
                                    <div class="col-12 col-sm-6 col-lg-4 mb-4">
                                        <a href="<?= htmlspecialchars($link) ?>" class="text-decoration-none text-dark">
                                            <div class="single-product-wrapper position-relative shadow-sm rounded overflow-hidden bg-white"
                                                style="transition: all 0.3s ease; cursor: pointer;">

                                                <div class="product-img position-relative overflow-hidden">
                                                    <img src="<?= htmlspecialchars('admin/' . $product['image_path']) ?>"
                                                        class="img-fluid w-100"
                                                        alt="<?= htmlspecialchars($product['product_name']) ?>"
                                                        style="transition: transform 0.3s ease;">

                                                    <?php if ($product['sale_price'] < $product['price']): ?>
                                                        <div class="product-badge offer-badge position-absolute top-0 start-0 bg-danger text-white m-2 px-3 py-1 rounded">
                                                            <span>-<?= $discount ?>%</span>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-0"
                                                        style="transition: opacity 0.3s ease;"></div>
                                                </div>

                                                <div class="product-description p-3">
                                                    <span class="d-block text-muted small mb-1"><?= htmlspecialchars($product['product_category']) ?></span>
                                                    <h6 class="fw-bold"><?= htmlspecialchars($product['product_name']) ?></h6>

                                                    <div class="sale-info">
                                                        <!-- Add price/sale price display here -->
                                                    </div>
                                                </div>

                                                <div class="hover-content position-absolute bottom-0 start-0 w-100 text-center pb-3 opacity-0"
                                                    style="transition: opacity 0.3s ease;">
                                                    <button class="btn btn-success btn-sm"
                                                        onclick="handleCartClick(<?= $product['id'] ?>); event.stopPropagation(); return false;">
                                                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <p class="text-center text-muted py-5">No products currently on sale.</p>
                                </div>
                            <?php endif; ?>
                        </div>


                        <script>
                            function handleCartClick(productId) {
                                <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']): ?>
                                    // Add to cart logic here
                                    console.log('Adding product', productId, 'to cart');
                                    // You can use AJAX to add to cart without page reload
                                <?php else: ?>
                                    window.location.href = 'sign-in.php';
                                <?php endif; ?>
                            }
                        </script>

                        <style>
                            .single-product-wrapper:hover {
                                transform: translateY(-5px);
                                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
                            }

                            .single-product-wrapper:hover .hover-overlay {
                                opacity: 0.1 !important;
                            }

                            .single-product-wrapper:hover img {
                                transform: scale(1.05);
                            }

                            .single-product-wrapper:hover .hover-content {
                                opacity: 1 !important;
                            }

                            .single-product-wrapper:hover h6 {
                                color: #198754 !important;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>


</body>

</html>