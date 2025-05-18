<?php

include 'connect/connection.php';



?>


<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>


<body>



    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Shop Grid Area Start ##### -->

    <div class="search-area">
        <form action="" method="get">
            <input type="search" name="search" id="headerSearch"
                placeholder="Type for search"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <!-- Preserve existing parameters -->
            <?php if (isset($_GET['category'])): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
            <?php endif; ?>
            <?php if (isset($_GET['sort'])): ?>
                <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']) ?>">
            <?php endif; ?>
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
                                        $categories = mysqli_query($conn, "SELECT * FROM product_categorys");
                                        while ($category = mysqli_fetch_assoc($categories)):
                                            $isChecked = isset($_GET['category']) && $_GET['category'] == $category['id'];
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
                                        // Initialize filter parameters
                                        $selected_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
                                        $selected_brands = isset($_GET['brand']) ? array_map('intval', $_GET['brand']) : [];
                                        $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
                                        $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 9999;
                                        $current_sort = $_GET['sort'] ?? 'highest_rated';

                                        // Base query
                                        $product_query = "SELECT p.*, pos.on_sale_quantity, pb.product_brand AS brand_name 
                                          FROM products p
                                          INNER JOIN product_on_sales pos ON p.id = pos.product_id
                                          LEFT JOIN product_brands pb ON p.product_brand_id = pb.id
                                          WHERE p.sale_price BETWEEN $min_price AND $max_price";

                                        // Add category filter
                                        if ($selected_category > 0) {
                                            $product_query .= " AND p.product_category_id = $selected_category";
                                        }

                                        // Add brand filter
                                        if (!empty($selected_brands)) {
                                            $brand_ids = implode(',', $selected_brands);
                                            $product_query .= " AND p.product_brand_id IN ($brand_ids)";
                                        }

                                        // Get total products count
                                        $count_query = str_replace('p.*, pos.on_sale_quantity, pb.product_brand AS brand_name', 'COUNT(*) AS total', $product_query);
                                        $total_result = mysqli_query($conn, $count_query);
                                        $total_products = mysqli_fetch_assoc($total_result);
                                        ?>
                                        <p><span><?= $total_products['total'] ?? 0 ?></span> products found</p>
                                    </div>

                                    <!-- Sorting -->
                                    <div class="product-sorting d-flex">
                                        <form method="GET" id="sortForm">
                                            <?php if ($selected_category > 0): ?>
                                                <input type="hidden" name="category" value="<?= $selected_category ?>">
                                            <?php endif; ?>
                                            <?php if (!empty($selected_brands)): ?>
                                                <?php foreach ($selected_brands as $brand): ?>
                                                    <input type="hidden" name="brand[]" value="<?= $brand ?>">
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <input type="hidden" name="min_price" value="<?= $min_price ?>">
                                            <input type="hidden" name="max_price" value="<?= $max_price ?>">

                                            <select name="sort" id="sortByselect" class="form-control">
                                                <option value="highest_rated" <?= $current_sort === 'highest_rated' ? 'selected' : '' ?>>Highest Rated</option>
                                                <option value="newest" <?= $current_sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                                                <option value="price_low_high" <?= $current_sort === 'price_low_high' ? 'selected' : '' ?>>Price: $ - $$</option>
                                                <option value="price_high_low" <?= $current_sort === 'price_high_low' ? 'selected' : '' ?>>Price: $$ - $</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                            // Add sorting to main query
                            switch ($current_sort) {
                                case 'newest':
                                    $product_query .= " ORDER BY p.created_at DESC";
                                    break;
                                case 'price_low_high':
                                    $product_query .= " ORDER BY p.sale_price ASC";
                                    break;
                                case 'price_high_low':
                                    $product_query .= " ORDER BY p.sale_price DESC";
                                    break;
                                default:
                                    $product_query .= " ORDER BY p.rating DESC"; // Assuming there's a rating column
                                    break;
                            }

                            // Add pagination
                            $per_page = 12;
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $offset = ($page - 1) * $per_page;
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