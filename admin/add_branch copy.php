<?php
include 'auth_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connect/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $store_branch = htmlspecialchars($_POST['store_branch']);

    $stmt = $conn->prepare("INSERT INTO pm_pickup_store (storebranch) VALUES (?)");
    if ($stmt) {
        $stmt->bind_param("s", $store_branch);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF'] . "?added_success=1");
            exit();
        } else {
            $error = "Failed to insert category.";
        }
        $stmt->close();
    } else {
        $error = "Failed to prepare statement.";
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
                    <?php if (isset($_GET['added_success']) && $_GET['added_success'] == 1): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                            Product category added successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <script src="timeoutMessage.js"></script>



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


                    <div class="card mb-4">

                        <div class="card-body p-4">

                            <table id="myTable" class="display">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>branch</th>
                                        <th>date created</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // 1. Create SQL query
                                    $sql = "SELECT * FROM pm_pickup_store";

                                    // 2. Execute the query
                                    $result = $conn->query($sql);

                                    // 3. Check and loop through results
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["storebranch"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No categories found.</td></tr>";
                                    }

                                    // 4. Close the connection
                                    $conn->close();
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!--   Core JS Files   -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Load DataTables JS (via CDN) -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable(); // Initialize DataTable on the table
        });
    </script>
</body>

<?php include 'admin_footer.php'; ?>