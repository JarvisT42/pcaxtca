<?php
include 'auth_admin.php';
include '../connect/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sellProduct'])) {
    $product_id = intval($_POST['product_id']);
    $quantity_to_sell = intval($_POST['quantity']);
    $available_quantity = intval($_POST['availableQuantity']); // from hidden input

    if ($quantity_to_sell <= 0) {
        $error = "Please enter a valid quantity to sell!";
    } elseif ($quantity_to_sell > $available_quantity) {
        $error = "Not enough stock available!";
    } else {
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
                header("Location: " . $_SERVER['PHP_SELF'] . "?sale_success=1");
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
                header("Location: " . $_SERVER['PHP_SELF'] . "?sale_updated=1");
                exit();
            } else {
                $error = "Failed to update product sale.";
            }
            $updateStmt->close();
        }

        $checkStmt->close();
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
        p.quantity, 
        p.image_path,
       s.on_sale_quantity
      
    FROM products p
    LEFT JOIN product_on_sales s ON p.id = s.product_id
    GROUP BY p.id, p.product_name, p.description, p.price, p.quantity, p.image_path
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

                                            echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
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
                data-price='" . $row['price'] . "'
                data-quantity='" . $row['quantity'] . "'>
                Edit
            </button>
            
          <button type='button' class='btn btn-primary sell-btn' 
    data-bs-toggle='modal' 
    data-bs-target='#sellModal'
    data-id='" . $row['id'] . "'
    data-name='" . htmlspecialchars($row['product_name']) . "'
    data-quantity='" . $row['quantity'] . "' 
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
                                                    <input type="number" class="form-control" id="editPrice" name="price" step="0.01" required>
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
                                                <input type="hidden" name="availableQuantity" id="availableQuantityInput">

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


                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Edit Modal Handler
                                    document.querySelectorAll('.edit-btn').forEach(button => {
                                        button.addEventListener('click', function() {
                                            const productId = this.dataset.id;
                                            const productName = this.dataset.name;
                                            const description = this.dataset.description;
                                            const price = this.dataset.price;
                                            const quantity = this.dataset.quantity;

                                            document.getElementById('productNameTitle').textContent = productName;
                                            document.getElementById('editProductId').value = productId;
                                            document.getElementById('editProductName').value = productName;
                                            document.getElementById('editDescription').value = description;
                                            document.getElementById('editPrice').value = price;
                                            document.getElementById('editQuantity').value = quantity;
                                        });
                                    });

                                    // Sell Modal Handler
                                    document.querySelectorAll('.sell-btn').forEach(button => {
                                        button.addEventListener('click', function() {
                                            const productId = this.dataset.id;
                                            const productName = this.dataset.name;
                                            const availableQuantity = parseInt(this.dataset.quantity);
                                            const onSaleQuantity = parseInt(this.dataset.onSaleQuantity);

                                            const sellQuantity = document.getElementById('sellQuantity');
                                            const quantityError = document.getElementById('quantityError');

                                            // Reset validation
                                            sellQuantity.classList.remove('is-invalid');
                                            quantityError.style.display = 'none';

                                            // Set values
                                            document.getElementById('sellProductId').value = productId;
                                            document.getElementById('availableQuantityInput').value = availableQuantity;
                                            document.getElementById('availableQuantityDisplay').textContent = availableQuantity;
                                            sellQuantity.max = availableQuantity - onSaleQuantity; // Adjust max to reflect stock minus already on sale
                                            sellQuantity.value = Math.min(1, availableQuantity); // Set to 1 or max available if less than 1

                                            // Update product name
                                            document.getElementById('sellProductName').textContent = productName;

                                            // Add real-time validation
                                            sellQuantity.addEventListener('input', function() {
                                                const enteredValue = parseInt(this.value) || 0;
                                                const maxAllowed = availableQuantity - onSaleQuantity;

                                                if (enteredValue > maxAllowed) {
                                                    this.value = maxAllowed;
                                                    this.classList.add('is-invalid');
                                                    quantityError.style.display = 'block';
                                                } else if (enteredValue < 1) {
                                                    this.value = 1;
                                                } else {
                                                    this.classList.remove('is-invalid');
                                                    quantityError.style.display = 'none';
                                                }
                                            });
                                        });
                                    });

                                    // Form submission validation
                                    document.getElementById('sellForm').addEventListener('submit', function(e) {
                                        const enteredQuantity = parseInt(document.getElementById('sellQuantity').value);
                                        const availableQuantity = parseInt(document.getElementById('availableQuantityInput').value);
                                        const onSaleQuantity = parseInt(document.getElementById('availableQuantityInput').dataset.onSaleQuantity);

                                        const maxAllowed = availableQuantity - onSaleQuantity;

                                        // Check if the entered quantity exceeds available stock
                                        if (enteredQuantity > maxAllowed) {
                                            e.preventDefault();
                                            document.getElementById('sellQuantity').classList.add('is-invalid');
                                            document.getElementById('quantityError').style.display = 'block';
                                        }
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