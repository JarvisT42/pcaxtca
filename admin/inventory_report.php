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
                            <h6>Add New Product</h6>
                        </div>
                        <div class="card-body px-4 pt-0 pb-2">

                            <table id="myTable" class="display">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Price</th>

                                        <th>Stock Quantity</th>
                                        <th>on sale</th>

                                        <th>Image</th> <!-- New column -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $sql = "
                                        SELECT 
                                            p.id, 
                                            p.product_name, 
                                            p.description, 
                                            p.sell_price, 
                                            pq.qty, 
                                            p.image_path,
                                            COALESCE(SUM(s.on_sale_quantity), 0) AS total_on_sale_quantity
                                        FROM products p
                                        LEFT JOIN product_on_sales s ON p.id = s.product_id
                                        LEFT JOIN product_qty pq ON p.id = pq.product_id

                                        GROUP BY p.id, p.product_name, p.description, p.sell_price, pq.qty, p.image_path
                                    ";


                                    $result = $conn->query($sql);


                                    // 3. Loop through results
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["sell_price"]) . "</td>";

                                            echo "<td>" . htmlspecialchars($row["qty"] ?? '') . "</td>";
                                            echo "<td>" . htmlspecialchars($row["total_on_sale_quantity"]) . "</td>"; // ✅ This line

                                            $imagePath = htmlspecialchars($row["image_path"]);
                                            echo "<td><img src='$imagePath' alt='Product Image' width='60' height='60' style='object-fit: cover;'></td>";

                                            echo "<td>
  

    <a href='view_product.php?id=" . $row['id'] . "' class='btn btn-primary view-btn'>
        View
    </a>
</td>";




                                            echo "</tr>";
                                        }
                                    }


                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                            <!-- Edit Modal -->


                            <!-- Sell Modal -->






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
            $('#myTable').DataTable(); // Initialize DataTable on the table
        });
    </script>
</body>

<?php
include 'admin_footer.php';

?>