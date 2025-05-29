<?php

include 'connect/connection.php';



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


?>


<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>


<body>

    <?php include 'header.php'; ?>


    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Shop Grid Area Start ##### -->





    <div class="breadcumb_area bg-img mt-custom" style="background-image: url(img/bg-img/breadcumb.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="page-title text-center">
                        <h2>Shop</h2>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .mt-custom {
                margin-top: 80px;
                /* or 500px, whatever you need */
            }
        </style>
    </div>

    <section class="shop_grid_area section-padding-80 ">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="shop_sidebar_area">
                        <form method="GET" action="">
                            <!-- Categories Filter -->
                            <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">


                            <div class="widget catagory mb-50">
                                <h6 class="widget-title mb-30">Categories</h6>
                                <div class="catagories-menu">
                                    <ul class="sub-menu">
                                        <?php
                                        $selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
                                        ?>
                                        <!-- "All" option -->
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value=""
                                                    <?= $selectedCategory === '' ? 'checked' : '' ?> class="category-checkbox">
                                                All
                                            </label>
                                        </li>

                                        <?php
                                        $categories = mysqli_query($conn, "SELECT * FROM product_categorys");
                                        while ($category = mysqli_fetch_assoc($categories)):
                                            $isChecked = $selectedCategory == $category['id'];
                                        ?>
                                            <li>
                                                <label>
                                                    <input type="radio" name="category" value="<?= $category['id'] ?>"
                                                        <?= $isChecked ? 'checked' : '' ?> class="category-checkbox">
                                                    <?= htmlspecialchars($category['product_category']) ?>
                                                </label>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                            </div>


                            <!-- Brands Filter -->
                            <div class="widget brands mb-50">
                                <h6 class="widget-title mb-30">Brands</h6>
                                <div class="brands-menu">
                                    <ul class="sub-menu" id="brands-list">
                                        <?php
                                        // Load initially selected brands
                                        if (isset($_GET['category'])) {
                                            $selectedCategory = (int)$_GET['category'];
                                            $brands = mysqli_query($conn, "SELECT * FROM product_brands WHERE product_category_id = $selectedCategory");
                                            while ($brand = mysqli_fetch_assoc($brands)) {
                                                $isChecked = isset($_GET['brand']) && in_array($brand['id'], $_GET['brand']);
                                                echo '<li><label><input type="checkbox" name="brand[]" value="' . $brand['id'] . '" ' . ($isChecked ? 'checked' : '') . '> ' . htmlspecialchars($brand['product_brand']) . '</label></li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>

                            <!-- Price Filter -->
                            <div class="widget price mb-50">
                                <h6 class="widget-title mb-30">Filter by</h6>
                                <p class="widget-title2 mb-30">Price</p>








                                <div class="widget-desc">
                                    <div class="slider-range">


                                        <?php
                                        $price_result = mysqli_query($conn, "SELECT MIN(sell_price) AS min_price, MAX(sell_price) AS max_price FROM products");
                                        $price_range = mysqli_fetch_assoc($price_result);
                                        $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : (float)$price_range['min_price'];
                                        $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : (float)$price_range['max_price'];
                                        ?>


                                        <div data-min="<?= $price_range['min_price'] ?>" data-max="<?= $price_range['max_price'] ?>" data-unit="$" class="slider-range-price ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" data-value-min="<?= $min_price ?>" data-value-max="<?= $max_price ?>" data-label-result="Range:">
                                            <div class="ui-slider-range uia-all"></div>
                                            <span class="ui-slider-handle uiarner-all" tabindex="0"></span>
                                            <span class="ui-slider-handlaall" tabindex="0"></span>
                                        </div>

                                        <div class="price-display">
                                            Selected Range: $<?= number_format($min_price, 2) ?> - $<?= number_format($max_price, 2) ?>
                                        </div>


                                        <input type="hidden" name="min_price" id="min_price" value="<?= $min_price ?>">
                                        <input type="hidden" name="max_price" id="max_price" value="<?= $max_price ?>">
                                        <button type="submit" class="btn essence-btn w-100">Apply Filter</button>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
                <script>
                    $(function() {
                        const slider = $('.slider-range-price');
                        const minPrice = parseFloat(slider.data('min'));
                        const maxPrice = parseFloat(slider.data('max'));

                        slider.slider({
                            range: true,
                            min: minPrice,
                            max: maxPrice,
                            values: [
                                parseFloat(slider.data('value-min')),
                                parseFloat(slider.data('value-max'))
                            ],
                            slide: function(event, ui) {
                                $('#min_price').val(ui.values[0].toFixed(2));
                                $('#max_price').val(ui.values[1].toFixed(2));
                                $('.price-display').text(
                                    `Range: ₱${ui.values[0].toFixed(2)} - ₱${ui.values[1].toFixed(2)}`
                                );
                            }
                        });

                        // Initial display update
                        $('.price-display').text(
                            `Range: ₱${slider.slider('values', 0).toFixed(2)} - ₱${slider.slider('values', 1).toFixed(2)}`
                        );
                    });
                </script>











                <script>
                    document.querySelectorAll('.category-checkbox').forEach(radio => {
                        radio.addEventListener('change', async function() {
                            const categoryId = this.value;
                            const brandList = document.getElementById('brands-list');
                            brandList.innerHTML = 'Loading...';

                            try {
                                const response = await fetch('get_brands.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        category_id: categoryId
                                    })
                                });

                                const data = await response.json();
                                brandList.innerHTML = '';

                                data.forEach(brand => {
                                    const isChecked = <?= json_encode(isset($_GET['brand']) ? $_GET['brand'] : []) ?>.includes(brand.id.toString());
                                    const li = document.createElement('li');
                                    li.innerHTML = `
                    <label>
                        <input type="checkbox" name="brand[]" value="${brand.id}" ${isChecked ? 'checked' : ''}>
                        ${brand.product_brand}
                    </label>`;
                                    brandList.appendChild(li);
                                });
                            } catch (error) {
                                brandList.innerHTML = '<li>Error loading brands</li>';
                            }
                        });
                    });
                </script>






                <div class="col-12 col-md-8 col-lg-9">
                    <div class="shop_grid_product_area">
                        <div class="row">
                            <div class="col-12">
                                <div class="product-topbar d-flex align-items-center justify-content-between">
                                    <!-- Product Count -->



                                    <div class="total-products">
                                        <?php




                                        ?>
                                        <p><span><?= $total_products['total'] ?? 0 ?></span> products found</p>
                                    </div>

                                    <!-- Sorting -->
                                    <div class="product-sorting d-flex">
                                        <form method=" GET" action="" id="sortForm">
                                            <?php
                                            // Preserve all existing GET parameters
                                            foreach ($_GET as $key => $value) {
                                                if ($key === 'sort' || $key === 'page') continue;
                                                if (is_array($value)) {
                                                    foreach ($value as $val) {
                                                        echo '<input type="hidden" name="' . htmlspecialchars($key) . '[]" value="' . htmlspecialchars($val) . '">';
                                                    }
                                                } else {
                                                    echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                                                }
                                            }
                                            ?>
                                            <select name="sort" id="sortByselect" class="form-control" onchange="this.form.submit()">
                                                <option value="">Default Sorting</option>
                                                <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                                                <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                                                <option value="name_asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'name_asc' ? 'selected' : '' ?>>Name: A to Z</option>
                                                <option value="name_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'name_desc' ? 'selected' : '' ?>>Name: Z to A</option>
                                            </select>
                                        </form>
                                    </div>



                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                            // Initialize sorting
                            $sort = $_GET['sort'] ?? '';

                            $selected_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
                            $selected_brands = isset($_GET['brand']) ? array_map('intval', $_GET['brand']) : [];
                            $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
                            $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 9999;

                            // Base query
                            $search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

                            // Modified Product Query with Sorting
                            $product_query = "SELECT p.*, pos.on_sale_quantity, pb.product_brand AS brand_name
                                        FROM products p
                                        INNER JOIN product_on_sales pos ON p.id = pos.product_id
                                        LEFT JOIN product_brands pb ON p.product_brand_id = pb.id
                                        WHERE p.sell_price BETWEEN $min_price AND $max_price";

                            // Existing filter conditions
                            if (!empty($search_term)) {
                                $product_query .= " AND (p.product_name LIKE '%$search_term%' OR p.description LIKE '%$search_term%')";
                            }
                            if ($selected_category > 0) {
                                $product_query .= " AND p.product_category_id = $selected_category";
                            }
                            if (!empty($selected_brands)) {
                                $brand_ids = implode(',', $selected_brands);
                                $product_query .= " AND p.product_brand_id IN ($brand_ids)";
                            }

                            // NEW: Add Sorting
                            switch ($sort) {
                                case 'price_asc':
                                    $product_query .= " ORDER BY p.sell_price ASC";
                                    break;
                                case 'price_desc':
                                    $product_query .= " ORDER BY p.sell_price DESC";
                                    break;
                                case 'name_asc':
                                    $product_query .= " ORDER BY p.product_name ASC";
                                    break;
                                case 'name_desc':
                                    $product_query .= " ORDER BY p.product_name DESC";
                                    break;
                                default:
                                    // Default sorting (optional)
                                    // $product_query .= " ORDER BY p.id DESC";
                                    break;
                            }

                            $per_page = 12;
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $offset = ($page - 1) * $per_page;

                            // Pagination
                            $product_query .= " LIMIT $per_page OFFSET $offset";
                            $products = mysqli_query($conn, $product_query);

                            if (mysqli_num_rows($products) > 0):
                                while ($product = mysqli_fetch_assoc($products)):
                            ?>



                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <div class="single-product-wrapper">
                                            <form class="cart-form clearfix" method="post" action="">

                                                <!-- Select Box -->
                                                <?php

                                                $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
                                                $product_id = isset($product['id']) ? $product['id'] : 0;

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
                                                ?>

                                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">

                                                <div class="product-img">
                                                    <a href="single-product-details.php?id=<?= htmlspecialchars($product_id) ?>">
                                                        <img src="admin/<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                                                    </a>
                                                    <div class="product-favourite">
                                                        <a href="#" class="favme fa fa-heart"></a>
                                                    </div>
                                                </div>

                                                <div class="product-description">
                                                    <span><?= htmlspecialchars($product['brand_name']) ?></span>
                                                    <a href="single-product-details.php?id=<?= htmlspecialchars($product_id) ?>">
                                                        <h6><?= htmlspecialchars($product['product_name']) ?></h6>
                                                    </a>
                                                    <p class="product-price">$<?= number_format($product['sell_price'], 2) ?></p>

                                                    <div class="hover-content">
                                                        <div class="add-to-cart-btn">
                                                            <?php if ($isInCart): ?>
                                                                <button type="submit" name="removefromcart" class="btn essence-delete-btn">
                                                                    Remove from cart
                                                                </button>
                                                            <?php else: ?>
                                                                <?php if ($is_logged_in): ?>

                                                                    <button type="submit" name="addtocart" class="btn essence-delete-btn">
                                                                        Add to cart
                                                                    </button>





                                                                <?php else: ?>
                                                                    <a href="sign-in.php" class="btn essence-btn">Add to Cart</a>
                                                                <?php endif; ?>



                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>

                                    <style>
                                        /* Set fixed dimensions for image container */
                                        .product-img {
                                            height: 300px;
                                            /* Adjust this value as needed */
                                            width: 100%;
                                            overflow: hidden;
                                            position: relative;
                                        }

                                        /* Make image fill container while maintaining aspect ratio */
                                        .product-img img {
                                            width: 100%;
                                            height: 100%;
                                            object-fit: cover;
                                            object-position: center center;
                                        }

                                        .product-img {
                                            aspect-ratio: 1/1;
                                            width: 100%;
                                            overflow: hidden;
                                        }

                                        .product-img:hover img {
                                            transform: scale(1.1);
                                            /* 10% zoom */
                                        }
                                    </style>


                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class=" col-12">
                                    <div class="alert alert-warning">No products found matching your criteria.</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination mt-50 mb-70 justify-content-center">
                            <?php
                            $total_pages = ceil(($total_products['total'] ?? 0) / $per_page);
                            for ($i = 1; $i <= $total_pages; $i++):
                            ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>

                <script>
                    // Add live sort form submission
                    document.getElementById('sortByselect').addEventListener('change', function() {
                        document.getElementById('sortForm').submit();
                    });
                </script>
            </div>
        </div>
    </section>

    <!-- Add jQuery and Slider Script -->


    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="js/plugins.js"></script>
    <!-- Classy Nav js -->
    <script src="js/classy-nav.min.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>
    <script src="bootstrap-5.3.6/js/bootstrap.bundle.min.js"></script>

</body>

</html>