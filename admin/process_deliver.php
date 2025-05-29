<?php
include 'auth_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connect/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    $order_id = $_POST['order_id'];
    $item_id = $_POST['item_id'];



    $new_status = 'completed';

    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        $stmt = $conn->prepare("UPDATE order_items SET status = ?, completed_date = NOW() WHERE item_id = ?");
        $stmt->bind_param("si", $new_status, $item_id);
        $stmt->execute();


        $_SESSION['success'] = "Order status updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating status: " . $conn->error;
    }

    $stmt->close();


    header("Location: process_deliver.php"); // Redirect back to orders page
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

        <!-- Button trigger modal -->


        <!-- Modal -->



        <div class="container-fluid py-4 ">
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

                        <div class="card-body p-4">












                            <table id="myTable" class="display">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice</th>
                                        <th>Customer</th>
                                        <th>Order date</th>
                                        <th>Total amount</th>
                                        <th>Payment type</th>

                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "
                                        SELECT 
                                            orders.*,
                                            order_items.*,
                                             order_items.item_id,
                                            products.product_name,
                                            products.image_path,
                                            users.first_name,
                                            users.last_name
                                        FROM orders
                                        INNER JOIN order_items ON orders.order_id = order_items.order_id
                                        INNER JOIN products ON order_items.product_id = products.id
                                        INNER JOIN users ON orders.user_id = users.id

                                        WHERE orders.order_status = 'shipped'
                                        ORDER BY orders.order_date DESC
                                    ";

                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    $grouped = [];
                                    while ($row = $result->fetch_assoc()) {
                                        $order_id = $row['order_id'];
                                        if (!isset($grouped[$order_id])) {
                                            $grouped[$order_id] = [
                                                'header' => [

                                                    'order_id' => $row['order_id'],

                                                    'invoice_id' => $row['invoice_id'],
                                                    'first_name' => $row['first_name'],
                                                    'last_name' => $row['last_name'],

                                                    'order_date' => $row['order_date'],
                                                    'total_amount' => $row['total_amount'],
                                                    'payment_method' => $row['payment_method'],
                                                    'order_status' => $row['order_status']
                                                ],
                                                'items' => []
                                            ];
                                        }
                                        $grouped[$order_id]['items'][] = [
                                            'item_id' => $row['item_id'],

                                            'product_name' => $row['product_name'],
                                            'image_path' => $row['image_path'],
                                            'quantity' => $row['quantity'],
                                            'cost_price' => $row['cost_price']
                                        ];
                                    }


                                    $count = 1;
                                    ?>

                                    <!-- Table header remains the same -->

                                    <?php foreach ($grouped as $order_id => $order_data): ?>
                                        <tr>
                                            <td><?= $count++ ?></td>
                                            <td><?= htmlspecialchars($order_data['header']['invoice_id']) ?></td>
                                            <td><?= htmlspecialchars($order_data['header']['first_name'] . ' ' . $order_data['header']['last_name']) ?></td>
                                            <td><?= date('M d, Y', strtotime($order_data['header']['order_date'])) ?></td>
                                            <td><?= htmlspecialchars($order_data['header']['total_amount']) ?></td>
                                            <td><?= htmlspecialchars($order_data['header']['payment_method']) ?></td>

                                            <td><?= htmlspecialchars($order_data['header']['order_status']) ?></td>
                                            <td>
                                                <!-- Corrected button with proper modal target -->
                                                <button type="button"
                                                    class="btn btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#orderModal<?= $order_id ?>">
                                                    View
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal for each order (inside the foreach loop) -->
                                        <div class="modal fade" id="orderModal<?= $order_id ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Order Details #<?= $order_id ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body ">
                                                        <?php foreach ($order_data['items'] as $item): ?>
                                                            <div class="row mb-3 ">
                                                                <div class="col-md-3">
                                                                    <img src="../admin/<?= htmlspecialchars($item['image_path']) ?>"
                                                                        class="img-fluid rounded"
                                                                        alt="<?= htmlspecialchars($item['product_name']) ?>">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h5><?= htmlspecialchars($item['product_name']) ?></h5>
                                                                    <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                                                                    <p>Price: ₱<?= number_format($item['cost_price'], 2) ?></p>
                                                                </div>
                                                                <div class="col-md-3 text-end">
                                                                    <p>Subtotal: ₱<?= number_format($item['cost_price'], 2) ?></p>
                                                                </div>




                                                            </div>
                                                        <?php endforeach; ?>

                                                        <div class="row mt-4">
                                                            <div class="col-md-12 text-end">
                                                                <h4>Total: ₱<?= number_format($order_data['header']['total_amount'], 2) ?></h4>
                                                                <p class="text-muted">
                                                                    Payment Method: <?= htmlspecialchars($order_data['header']['payment_method']) ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <!-- order_status Management Buttons -->

                                                        <form action="" method="POST">
                                                            <input type="hidden" name="order_id" value="<?= $order_id ?>">
                                                            <input type="hidden" name="item_id" value="<?= number_format($item['item_id'], 2) ?>">

                                                            <button type="submit" name="submit" value="process" class="btn btn-primary">
                                                                <i class="fas fa-cogs"></i> Complete
                                                            </button>
                                                        </form>



                                                        <!-- Close Button -->
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
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