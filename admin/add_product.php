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
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $sale_price = floatval($_POST['sale_price']);

    $quantity = intval($_POST['quantity']);
    $image_path = '';

    // Validate inputs
    if (empty($product_name) || empty($description) || $price <= 0 || $quantity < 0) {
        $error = "Please fill in all required fields correctly!";
    } else {
        // Handle file upload
        if (isset($_FILES['image'])) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $file_name = basename($_FILES["image"]["name"]);
            $target_file = $target_dir . uniqid() . '_' . $file_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validate image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                $error = "File is not an image.";
            } elseif ($_FILES["image"]["size"] > 500000) {
                $error = "Sorry, your file is too large.";
            } elseif (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
            } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }

        if (empty($error)) {
            try {
                // Insert into database using prepared statement
                $stmt =  $conn->prepare("INSERT INTO products 
                    (product_name, 	product_category_id, description, price, sale_price, quantity, image_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)");

                $stmt->execute([
                    $product_name,
                    $category,
                    $description,
                    $price,
                    $sale_price,
                    $quantity,
                    $image_path
                ]);

                $success = "Product added successfully!";
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
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
                                        $result = $conn->query("SELECT id, product_category FROM product_category");

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['product_category']) . '</option>';
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
                                    <label for="sale_price" class="form-label">Product Sale Price (₱)</label>
                                    <input type="number" name="sale_price" id="sale_price" class="form-control" step="0.01" min="0.01">
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
                                    <input type="file" name="image" id="image" class="form-control"
                                        accept="image/*" required>
                                    <small class="form-text text-muted">
                                        Max file size: 500KB. Allowed formats: JPG, PNG, GIF
                                    </small>
                                </div>

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