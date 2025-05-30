<?php
include 'auth_admin.php';
include '../connect/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sellProduct'])) {
    $product_id = intval($_POST['product_id']);
    $quantity_to_sell = intval($_POST['quantity']);
    $available_quantity = intval($_POST['availableQuantity']); // from hidden input


    // Check if a record already exists for this product_id
    $checkStmt = $conn->prepare("SELECT id, on_sale_quantity FROM product_on_sales WHERE product_id = ?");
    $checkStmt->bind_param("i", $product_id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows == 0) {
        // No existing record, proceed to insert
        $insertStmt = $conn->prepare("INSERT INTO product_on_sales (product_id, on_sale_quantity, sale_date) VALUES (?, ?, NOW())");
        $insertStmt->bind_param("ii", $product_id, $quantity_to_sell);

        if ($insertStmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?sell_success=1");
            exit();
        } else {
            $error = "Failed to record product sale.";
        }
        $insertStmt->close();
    } else {
        // Existing record found, proceed to update the on_sale_quantity
        $checkStmt->bind_result($existing_id, $existing_quantity);
        $checkStmt->fetch();

        // Update the quantity in the database
        $new_quantity = $existing_quantity + $quantity_to_sell;
        $updateStmt = $conn->prepare("UPDATE product_on_sales SET on_sale_quantity = ? WHERE product_id = ?");
        $updateStmt->bind_param("ii", $new_quantity, $product_id);

        if ($updateStmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?sell_updated=1");
            exit();
        } else {
            $error = "Failed to update product sale.";
        }
        $updateStmt->close();
    }

    $checkStmt->close();
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

                    <?php if (isset($_GET['sell_success']) && $_GET['sell_success'] == 1): ?>
                        <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
                            Product sell successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>



                    <?php if (isset($_GET['sell_updated']) && $_GET['sell_updated'] == 1): ?>
                        <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
                            Product sell updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>


                    <script src="timeoutMessage.js"></script>


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
                                        s.on_sale_quantity
                                        
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

                                            echo "<td>" . htmlspecialchars($row["qty"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["on_sale_quantity"]) . "</td>"; // ✅ This line

                                            $imagePath = htmlspecialchars($row["image_path"]);
                                            echo "<td><img src='$imagePath' alt='Product Image' width='60' height='60' style='object-fit: cover;'></td>";

                                            echo "<td>
                                                        <button type='button' class='btn btn-primary edit-btn' 
                                                            data-bs-toggle='modal' 
                                                            data-bs-target='#editModal'
                                                            data-id='" . $row['id'] . "'
                                                            data-name='" . htmlspecialchars($row['product_name']) . "'
                                                            data-description='" . htmlspecialchars($row['description']) . "'
                                                            data-sell_price='" . $row['sell_price'] . "'
                                                            data-quantity='" . $row['qty'] . "'>
                                                            Edit
                                                        </button>
                                                        
                                                    <button type='button' class='btn btn-primary sell-btn' 
                                                data-bs-toggle='modal' 
                                                data-bs-target='#sellModal'
                                                data-id='" . $row['id'] . "'
                                                data-name='" . htmlspecialchars($row['product_name']) . "'

                                                data-quantity='" . $row['qty'] . "' 
                                                data-on-sale-quantity='" . $row['on_sale_quantity'] . "'> 
                                                Sell 
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
                            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="editModalLabel">Edit Product: <span id="productNameTitle"></span></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="update_product.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="product_id" id="editProductId">
                                                <div class="mb-3">
                                                    <label for="editProductName" class="form-label">Product Name</label>
                                                    <input type="text" class="form-control" id="editProductName" name="product_name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editDescription" class="form-label">Description</label>
                                                    <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editPrice" class="form-label">Price</label>
                                                    <input type="number" class="form-control" id="editPrice" name="sell_price" step="0.01" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editQuantity" class="form-label">Quantity</label>
                                                    <input type="number" class="form-control" id="editQuantity" name="quantity" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Sell Modal -->
                            <div class="modal fade" id="sellModal" tabindex="-1" aria-labelledby="sellModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="sellModalLabel">Sell Product: <span id="sellProductName"></span></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="" method="POST" id="sellForm">
                                            <div class="modal-body">
                                                <input type="hidden" name="product_id" id="sellProductId">
                                                <!-- Hidden inputs -->
                                                <input type="hidden" name="availableQuantity" id="availableQuantityInput">
                                                <input type="hidden" id="availableOnSaleQuantityInput">


                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Available Quantity: <span id="availableQuantityDisplay"></span>
                                                    </label>
                                                    <input type="number" class="form-control" id="sellQuantity" name="quantity" min="1" required>
                                                    <div class="invalid-feedback" id="quantityError">
                                                        Quantity cannot exceed available stock!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="sellProduct" class="btn btn-primary">Confirm Sale</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>




                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script>
            // Handle Edit button click
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const description = button.getAttribute('data-description');
                    const sell_price = button.getAttribute('data-sell_price');
                    const quantity = button.getAttribute('data-quantity');

                    // Set modal fields
                    document.getElementById('editProductId').value = id;
                    document.getElementById('editProductName').value = name;
                    document.getElementById('productNameTitle').textContent = name;
                    document.getElementById('editDescription').value = description;
                    document.getElementById('editsell_price').value = sell_price;
                    document.getElementById('editQuantity').value = quantity;
                });
            });

            // Handle Sell button click
            document.querySelectorAll('.sell-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const quantity = parseInt(button.getAttribute('data-quantity')) || 0;
                    const onsalequantity = parseInt(button.getAttribute('data-on-sale-quantity')) || 0;

                    const available = quantity - onsalequantity;

                    // Set values in the modal
                    document.getElementById('sellProductId').value = id;
                    document.getElementById('sellProductName').textContent = name;
                    document.getElementById('availableQuantityInput').value = available;
                    document.getElementById('availableOnSaleQuantityInput').value = onsalequantity;
                    document.getElementById('availableQuantityDisplay').textContent = available;

                    // Set max attribute for validation
                    const sellInput = document.getElementById('sellQuantity');
                    sellInput.max = available;
                    sellInput.value = ''; // reset previous input
                    sellInput.classList.remove('is-invalid');
                });
            });

            // Optional real-time validation
            document.getElementById('sellQuantity').addEventListener('input', function() {
                const max = parseInt(this.max);
                const value = parseInt(this.value);
                const error = document.getElementById('quantityError');

                if (value > max) {
                    this.classList.add('is-invalid');
                    error.style.display = 'block';
                } else {
                    this.classList.remove('is-invalid');
                    error.style.display = 'none';
                }
            });
        </script>


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