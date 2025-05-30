<?php
include 'auth_admin.php';
include '../connect/connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);







?>




<?php
include 'admin_header.php';

?>


<body class="g-sidenav-show bg-gray-100">
    <?php include 'sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <?php include 'navbar.php'; ?>


        <!-- End Navbar -->

        <!-- Container for the Table -->

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <!-- <h6>Add New Product</h6> -->
                        </div>
                        <div class="card-body px-4 pt-0 pb-2">

                            <?php
                            $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                            $product_stmt = $conn->prepare("SELECT product_name, image_path FROM products WHERE id = ?");
                            $product_stmt->bind_param("i", $product_id);
                            $product_stmt->execute();
                            $product_result = $product_stmt->get_result();
                            $product = $product_result->fetch_assoc();
                            $product_stmt->close();
                            ?>

                            <?php if ($product): ?>
                                <div class="d-flex align-items-center mb-4">
                                    <?php if (!empty($product['image_path'])): ?>
                                        <img src="<?= htmlspecialchars($product['image_path']) ?>"
                                            alt="Product Image"
                                            class="img-thumbnail me-3"
                                            style="width: 200px; cursor: pointer;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#imageModal">
                                    <?php endif; ?>
                                    <h5 class="mb-0"><?= htmlspecialchars($product['product_name']) ?></h5>
                                </div>
                            <?php endif; ?>

                            <!-- Image Preview Modal -->
                            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="imageModalLabel">Product Image Preview</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="Full Product Image" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <table id="myTable" class="display">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Quantity</th>
                                        <th>Movement Type</th>
                                        <th>Date Created</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

                                    $stmt = $conn->prepare("SELECT qty, movement_type, movement_date FROM stock_movements WHERE product_id = ?");
                                    $stmt->bind_param("i", $product_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    $counter = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $counter++ . "</td>";
                                        echo "<td>" . htmlspecialchars($row['qty']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['movement_type']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['movement_date']) . "</td>";
                                        echo "</tr>";
                                    }

                                    $stmt->close();
                                    ?>

                                </tbody>
                            </table>

                            <?php
                            // Reset the statement to fetch totals
                            $total_stock = 0;
                            $total_sold = 0;

                            $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

                            $stmt = $conn->prepare("SELECT qty, movement_type FROM stock_movements WHERE product_id = ?");
                            $stmt->bind_param("i", $product_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                $movement_type = strtolower($row['movement_type']);
                                $qty = (int)$row['qty'];

                                if ($movement_type === 'initial_stock' || $movement_type === 'restock') {
                                    $total_stock += $qty;
                                } elseif ($movement_type === 'sold') {
                                    $total_sold += $qty;
                                }
                            }

                            $stmt->close();
                            ?>

                            <div class="mt-4">
                                <h6><strong>Total Stock:</strong> <?= $total_stock ?></h6>
                                <h6><strong>Total Sold:</strong> <?= $total_sold ?></h6>
                                <h6><strong>Current Stock:</strong> <?= $total_stock - $total_sold ?></h6>
                            </div>






                        </div>

                    </div>
                </div>
            </div>
        </div>



    </main>

    <!-- Core JS Files -->
    <!-- Load jQuery (via CDN) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Load DataTables JS (via CDN) -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({

                searching: false // Disable the search box
            });
        });
    </script>

</body>

<?php
include 'admin_footer.php';

?>