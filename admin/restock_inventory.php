<?php
include 'auth_admin.php';
include '../connect/connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['restockProduct'])) {
    // Sanitize and validate the inputs
    $product_id = intval($_POST['product_id']);
    $current_quantity = intval($_POST['currentQuantity']);
    $quantity_to_add = intval($_POST['quantity']);


    // Ensure both current quantity and quantity to add are valid
    if ($product_id > 0 && $quantity_to_add > 0) {
        // Calculate the new quantity
        $new_quantity = $current_quantity + $quantity_to_add;

        // Prepare the SQL update query
        $update = $conn->prepare("UPDATE product_qty SET qty = ? WHERE product_id = ?");
        $update->bind_param("ii", $new_quantity, $product_id);

        // Execute the update and check if rows were affected
        if ($update->execute()) {
            if ($update->affected_rows > 0) {
                // If update is successful, redirect back to the same page or another page
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();  // Don't forget to exit to prevent further code execution
            } else {
                $error = "No changes were made. Please check the product ID or quantity.";
            }
        } else {
            $error = "Failed to update stock. Please try again.";
        }

        // Close the prepared statement
        $update->close();
    } else {
        // If product_id or quantity is invalid
        $error = "Invalid product ID or quantity.";
    }
}




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
        p.price, 
        pq.qty, 
        p.image_path,
        COALESCE(SUM(s.on_sale_quantity), 0) AS total_on_sale_quantity
    FROM products p
    LEFT JOIN product_on_sales s ON p.id = s.product_id
    LEFT JOIN product_qty pq ON p.id = pq.product_id

    GROUP BY p.id, p.product_name, p.description, p.price, pq.qty, p.image_path
";


                                    $result = $conn->query($sql);


                                    // 3. Loop through results
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["price"]) . "</td>";

                                            echo "<td>" . htmlspecialchars($row["qty"] ?? '') . "</td>";
                                            echo "<td>" . htmlspecialchars($row["total_on_sale_quantity"]) . "</td>"; // ✅ This line

                                            $imagePath = htmlspecialchars($row["image_path"]);
                                            echo "<td><img src='$imagePath' alt='Product Image' width='60' height='60' style='object-fit: cover;'></td>";

                                            echo "<td>
                                            <button type='button' class='btn btn-success restock-btn' 
                                                data-bs-toggle='modal' 
                                                data-bs-target='#restockModal'
                                                data-id='" . $row['id'] . "'
                                                data-name='" . htmlspecialchars($row['product_name']) . "'
                                                data-quantity='" . $row['qty'] . "'>
                                                Restock
                                            </button>
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
                            <div class="modal fade" id="restockModal" tabindex="-1" aria-labelledby="restockModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="restockModalLabel">Restock Product: <span id="restockProductName"></span></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="" method="POST" id="restockForm">
                                            <div class="modal-body">

                                                <input type="hidden" name="product_id" id="restockProductId">
                                                <input type="hidden" name="currentQuantity" id="currentQuantityInput">

                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Current Stock: <span id="currentQuantityDisplay"></span>
                                                    </label>
                                                    <input type="number" class="form-control" id="restockQuantity" name="quantity" min="1" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="restockProduct" class="btn btn-success">Confirm Restock</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    document.querySelectorAll('.restock-btn').forEach(button => {
                                        button.addEventListener('click', function() {
                                            const productId = this.dataset.id;
                                            const productName = this.dataset.name;
                                            const currentQuantity = this.dataset.quantity;

                                            // Populate modal fields
                                            document.getElementById('restockProductId').value = productId;
                                            document.getElementById('currentQuantityInput').value = currentQuantity;
                                            document.getElementById('currentQuantityDisplay').textContent = currentQuantity;

                                            const nameElem = document.getElementById('restockProductName');
                                            if (nameElem) {
                                                nameElem.textContent = productName;
                                            }
                                        });
                                    });
                                });
                            </script>


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