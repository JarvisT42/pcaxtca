<?php
include 'auth_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection (replace with your actual connection code)
include '../connect/connection.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $product_category = htmlspecialchars($_POST['product_name']);

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO product_category (product_category) VALUES (?)");
    $stmt->bind_param("s", $product_category);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
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
                            <h6>Add New Product Category</h6>
                        </div>
                        <div class="card-body px-4 pt-0 pb-2">
                            <!--  -->

                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="product_name" class="form-label">Product Category</label>
                                    <input type="text" name="product_name" id="product_name" class="form-control" required>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">
                                    Add Category
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