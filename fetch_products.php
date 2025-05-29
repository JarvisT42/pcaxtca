<?php
require_once 'connect/connection.php'; // Include your database configuration

// Retrieve and sanitize parameters
$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$selectedBrands = isset($_GET['brand']) ? array_map('intval', $_GET['brand']) : [];
$minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 9999;
$currentSort = $_GET['sort'] ?? 'highest_rated';
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12;

// Build the product query (same as main file)
$productQuery = "SELECT p.*, pos.on_sale_quantity, pb.product_brand AS brand_name 
                FROM products p
                INNER JOIN product_on_sales pos ON p.id = pos.product_id
                LEFT JOIN product_brands pb ON p.product_brand_id = pb.id
                WHERE p.sell_price BETWEEN $minPrice AND $maxPrice";

if (!empty($searchTerm)) {
    $productQuery .= " AND (p.product_name LIKE '%$searchTerm%' OR p.description LIKE '%$searchTerm%')";
}

if ($selectedCategory > 0) {
    $productQuery .= " AND p.product_category_id = $selectedCategory";
}

if (!empty($selectedBrands)) {
    $brand_ids = implode(',', $selectedBrands);
    $productQuery .= " AND p.product_brand_id IN ($brand_ids)";
}

// Apply sorting
switch ($currentSort) {
    case 'newest':
        $productQuery .= " ORDER BY pos.created_at DESC";
        break;
    case 'price_low_high':
        $productQuery .= " ORDER BY p.sell_price ASC";
        break;
    case 'price_high_low':
        $productQuery .= " ORDER BY p.sell_price DESC";
        break;
    default:
        $productQuery .= " ORDER BY p.rating DESC";
        break;
}

// Pagination
$offset = ($page - 1) * $perPage;
$productQuery .= " LIMIT $perPage OFFSET $offset";

$products = mysqli_query($conn, $productQuery);

// Count total products
$countQuery = "SELECT COUNT(*) AS total FROM ($productQuery) AS count_query";
$totalResult = mysqli_query($conn, $countQuery);
$totalProducts = mysqli_fetch_assoc($totalResult);
$totalPages = ceil(($totalProducts['total'] ?? 0) / $perPage);

// Generate HTML output
ob_start();
?>
<div class="shop_grid_product_area">
    <div class="row">
        <?php if (mysqli_num_rows($products) > 0): ?>
            <?php while ($product = mysqli_fetch_assoc($products)): ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="single-product-wrapper">
                        <!-- Your product HTML structure here -->
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">No products found.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<nav aria-label="Page navigation">
    <ul class="pagination mt-50 mb-70 justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="#" onclick="loadPage(<?= $i ?>)">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php
echo ob_get_clean();
?>