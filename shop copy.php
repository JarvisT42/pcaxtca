<?php

include 'connect/connection.php';



?>


<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>


<body>

    <?php include 'header.php'; ?>


    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Shop Grid Area Start ##### -->

    <div class="search-area">
        <form action="" method="get">
            <input type="search" name="search" id="headerSearch"
                placeholder="Type for search"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

            <!-- Preserve all existing parameters except search and page -->
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


    <?php include 'connect/connection.php'; ?>

    <section class="shop_grid_area section-padding-80">
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
                                        $price_result = mysqli_query($conn, "SELECT MIN(sale_price) AS min_price, MAX(sale_price) AS max_price FROM products");
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
                                        WHERE p.sale_price BETWEEN $min_price AND $max_price";

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
                                    $product_query .= " ORDER BY p.sale_price ASC";
                                    break;
                                case 'price_desc':
                                    $product_query .= " ORDER BY p.sale_price DESC";
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
                                            <div class="product-img">
                                                <img src="admin/<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                                                <div class="product-favourite">
                                                    <a href="#" class="favme fa fa-heart"></a>
                                                </div>
                                            </div>
                                            <div class="product-description">
                                                <span><?= htmlspecialchars($product['brand_name']) ?></span>
                                                <a href="single-product-details.php?id=<?= $product['id'] ?>">
                                                    <h6><?= htmlspecialchars($product['product_name']) ?></h6>
                                                </a>
                                                <p class="product-price">$<?= number_format($product['sale_price'], 2) ?></p>
                                                <div class="hover-content">
                                                    <div class="add-to-cart-btn">
                                                        <a href="cart.php?add=<?= $product['id'] ?>" class="btn essence-btn">Add to Cart</a>
                                                    </div>
                                                </div>
                                            </div>
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
                                <div class="col-12">
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

</body>

</html>