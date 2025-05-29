<?php
include 'auth_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection (replace with your actual connection code)
include '../connect/connection.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $product_name = htmlspecialchars($_POST['product_name']);
    $category = htmlspecialchars($_POST['category']);
    $brand = htmlspecialchars($_POST['brand']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $sell_price = floatval($_POST['sell_price']);
    $quantity = intval($_POST['quantity']);

    // Initialize image paths
    $image_path = $image_path2 = $image_path3 = '';

    // Handle file uploads without validation
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Process first image
    if (!empty($_FILES['image']['name'])) {
        $temp_name = $_FILES['image']['tmp_name'];
        $file_name = uniqid() . '_' . $_FILES['image']['name'];
        move_uploaded_file($temp_name, $target_dir . $file_name);
        $image_path = $target_dir . $file_name;
    }

    // Process second image
    if (!empty($_FILES['image2']['name'])) {
        $temp_name = $_FILES['image2']['tmp_name'];
        $file_name = uniqid() . '_' . $_FILES['image2']['name'];
        move_uploaded_file($temp_name, $target_dir . $file_name);
        $image_path2 = $target_dir . $file_name;
    }

    // Process third image
    if (!empty($_FILES['image3']['name'])) {
        $temp_name = $_FILES['image3']['tmp_name'];
        $file_name = uniqid() . '_' . $_FILES['image3']['name'];
        move_uploaded_file($temp_name, $target_dir . $file_name);
        $image_path3 = $target_dir . $file_name;
    }

    // Insert into database
    try {
        // Insert into products table
        $stmt = $conn->prepare("INSERT INTO products 
        (product_name, product_category_id, product_brand_id, description, 
         price, sell_price, image_path, image_path2, image_path3)     
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $product_name,
            $category,
            $brand,
            $description,
            $price,
            $sell_price,
            $image_path,
            $image_path2,
            $image_path3
        ]);

        // ✅ Correct way to get the last inserted ID with PDO
        $product_id = $conn->insert_id;

        // Insert into product_qty table
        $qty_stmt = $conn->prepare("INSERT INTO product_qty (product_id, qty) VALUES (?, ?)");
        $qty_stmt->execute([$product_id, $quantity]);

        // Redirect
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<?php include 'admin_header.php'; ?>



<body class="g-sidenav-show  bg-gray-100">
    <?php include 'sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <?php include 'navbar.php'; ?>

        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Add New Product</h6>
                        </div>
                        <div class="card-body px-4 pt-0 pb-2">
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>

                            <?php if ($success): ?>
                                <div class="alert alert-success"><?= $success ?></div>
                            <?php endif; ?>

                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="product_name" class="form-label">Product Name</label>
                                    <input type="text" name="product_name" id="product_name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select name="category" id="category" class="form-select" required>
                                        <option value="" disabled selected>Select a category</option>

                                        <?php
                                        $result = $conn->query("SELECT id, product_category FROM product_categorys");

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['product_category']) . '</option>';
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="brand" class="form-label">Brand</label>
                                    <select name="brand" id="brand" class="form-select" required>
                                        <option value="" disabled selected>Select a brand</option>

                                        <?php
                                        $result = $conn->query("SELECT id, product_brand FROM product_brands");

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['product_brand']) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>



                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Product Price (₱)</label>
                                    <input type="number" name="price" id="price" class="form-control"
                                        step="0.01" min="0.01" required>
                                </div>

                                <div class="mb-3">
                                    <label for="sell_price" class="form-label">Product Sale Price (₱)</label>
                                    <input type="number" name="sell_price" id="sell_price" class="form-control" step="0.01" min="0.01">
                                </div>
                                <div class="alert alert-warning" role="alert">
                                    <strong>Note:</strong> The item is not set on sale.
                                </div>

                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control"
                                        min="0" required>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    <input type="file" name="image" id="image" class="form-control" accept="image/jpeg, image/png" required>
                                    <div id="imageError" class="invalid-feedback"></div>

                                    <label for="image2" class="form-label">Product Image2</label>
                                    <input type="file" name="image2" id="image2" class="form-control" accept="image/jpeg, image/png">
                                    <div id="image2Error" class="invalid-feedback"></div>

                                    <label for="image3" class="form-label">Product Image3</label>
                                    <input type="file" name="image3" id="image3" class="form-control" accept="image/jpeg, image/png">
                                    <div id="image3Error" class="invalid-feedback"></div>

                                    <small class="form-text text-muted">
                                        Max file size: 500KB. Allowed formats: JPG, PNG
                                    </small>
                                </div>

                                <script>
                                    function validateImage(input, isRequired = false) {
                                        const file = input.files[0];
                                        const errorElement = document.getElementById(input.id + 'Error');
                                        const allowedTypes = ['image/jpeg', 'image/png'];
                                        const maxSize = 500 * 1024; // 500KB

                                        if (file) {
                                            if (!allowedTypes.includes(file.type)) {
                                                errorElement.textContent = 'Only JPG or PNG files are allowed.';
                                                input.classList.add('is-invalid');
                                                return false;
                                            }

                                            if (file.size > maxSize) {
                                                errorElement.textContent = 'File size must be less than 500KB.';
                                                input.classList.add('is-invalid');
                                                return false;
                                            }

                                            errorElement.textContent = '';
                                            input.classList.remove('is-invalid');
                                            return true;
                                        } else if (isRequired) {
                                            errorElement.textContent = 'This field is required.';
                                            input.classList.add('is-invalid');
                                            return false;
                                        } else {
                                            errorElement.textContent = '';
                                            input.classList.remove('is-invalid');
                                            return true;
                                        }
                                    }

                                    document.querySelector('form').addEventListener('submit', function(e) {
                                        const image1 = document.getElementById('image');
                                        const image2 = document.getElementById('image2');
                                        const image3 = document.getElementById('image3');

                                        const valid1 = validateImage(image1, true); // Required
                                        const valid2 = validateImage(image2); // Optional
                                        const valid3 = validateImage(image3); // Optional

                                        if (!valid1 || !valid2 || !valid3) {
                                            e.preventDefault();
                                        }
                                    });

                                    // Validate on change
                                    ['image', 'image2', 'image3'].forEach(id => {
                                        const input = document.getElementById(id);
                                        input.addEventListener('change', () => {
                                            validateImage(input, id === 'image');
                                        });
                                    });
                                </script>


                                <button type="submit" name="submit" class="btn btn-primary">
                                    Add Product
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!--   Core JS Files   -->

</body>

<?php include 'admin_footer.php'; ?>