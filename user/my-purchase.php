<?php
include '../connect/connection.php';




if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel'])) {
    $item_id = $_POST['item_id'];
    $order_id = $_POST['order_id'];
    $new_status = 'cancelled';

    // Count how many items are in this order
    $stmt = $conn->prepare("SELECT COUNT(*) FROM order_items WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($item_count);
    $stmt->fetch();
    $stmt->close();

    // Update the individual item status
    $stmt = $conn->prepare("UPDATE order_items SET status = ? WHERE item_id = ?, cancelled_at = NOW()");
    $stmt->bind_param("si", $new_status, $item_id);
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        // If there's only one item, update the main orders table
        if ($item_count == 1) {
            $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
            $stmt->bind_param("si", $new_status, $order_id);
            $stmt->execute();
            $stmt->close();
        }

        $_SESSION['success'] = "Order status updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating status: " . $conn->error;
    }

    header("Location: my-purchase.php");
    exit();
}











?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>

<body>
    <?php include 'header.php'; ?>

    <div class="breadcumb_area bg-img mt-custom" style="background-image: url(img/bg-img/breadcumb.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="page-title text-center">
                        <h2>My Purchases</h2>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .mt-custom {
                margin-top: 80px;
            }
        </style>
    </div>

    <section class="shop_grid_area pb-5">
        <div class="container">
            <div class="row">
                <?php include 'account-sidebar.php'; ?>

                <div class="col-md-9 mt-5">
                    <div class="account-content p-4 bg-white shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0">My Orders</h3>
                            <div class="text-muted">Showing 1-5 of 15 orders</div>
                        </div>

                        <ul class="nav nav-tabs purchase-tabs mb-4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#all">All Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pending">Pending</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#to-ship">To Ship</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#to-receive">To Receive</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#completed">Completed</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#cancelled">Cancelled</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- All Orders Tab -->
                            <div class="tab-pane fade show active" id="all" role="tabpanel">
                                <div class="order-card mb-4">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <div class="order-info">
                                            <span class="text-muted">Order ID: #123456</span>
                                            <span class="mx-2">|</span>
                                            <span class="order-status text-success">Completed</span>
                                        </div>
                                        <small class="text-muted">Placed on: 2023-08-15</small>
                                    </div>
                                    <div class="card-body ">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <img src="product-image.jpg" class="img-fluid rounded" alt="Product">
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="mb-1">24 Hours Mechanical Electrical Plug Program Timer</h5>
                                                <p class="mb-1 text-muted">x1 • Pre-Order</p>
                                                <div class="price-container">
                                                    <span class="text-muted text-decoration-line-through">₱499</span>
                                                    <span class="text-danger h5 ml-2">₱162</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <div class="mb-3">
                                                    <h5>Total: ₱207</h5>
                                                </div>
                                                <div class="btn-group">
                                                    <button class="btn essence-btn btn-sm">View Details</button>
                                                    <button class="btn essence-btn btn-sm">Buy Again</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Repeat order cards as needed -->
                            </div>

                            <!-- Other Tabs -->


                            <?php
                            $user_id = $_SESSION['user_id'];

                            // Get all orders with their items
                            $query = "
                                SELECT 
                                    orders.*,
                                    order_items.*,
                                    products.product_name,
                                    products.image_path,
                                     order_items.item_id
                                FROM orders
                                INNER JOIN order_items ON orders.order_id = order_items.order_id
                                INNER JOIN products ON order_items.product_id = products.id
                                WHERE orders.user_id = ? 
                                AND orders.order_status = 'processing'
                                AND order_items.status = 'pending'
                                ORDER BY orders.order_date DESC
                            ";

                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // Group orders by order_id
                            $grouped_orders = [];
                            while ($row = $result->fetch_assoc()) {
                                $order_id = $row['order_id'];

                                if (!isset($grouped_orders[$order_id])) {
                                    $grouped_orders[$order_id] = [
                                        'header' => [
                                            'invoice_id' => $row['invoice_id'],
                                            'order_date' => $row['order_date'],
                                            'total_amount' => $row['total_amount'],
                                            'payment_method' => $row['payment_method'],
                                            'order_status' => $row['order_status'],

                                        ],
                                        'items' => []
                                    ];
                                }

                                $grouped_orders[$order_id]['items'][] = [
                                    'item_id' => $row['item_id'], // Add item_id here

                                    'product_name' => $row['product_name'],
                                    'image_path' => $row['image_path'],
                                    'quantity' => $row['quantity'],
                                    'total_amount' => $row['total_amount']
                                ];
                            }
                            ?>

                            <div class="tab-pane fade" id="pending" role="tabpanel">

                                <?php if (empty($grouped_orders)): ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-shipping-fast fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Orders To Ship</h5>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($grouped_orders as $order_id => $order_data): ?>
                                        <div class="order-card mb-4">
                                            <!-- Order Header -->
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <div class="order-info">
                                                    <span class="text-muted">Order #<?= $order_id ?></span>
                                                    <span class="mx-2">|</span>
                                                    <span class="order-status status-processing">
                                                        <!-- <?= strtoupper($order_data['header']['order_status']) ?> -->
                                                        Pending
                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($order_data['header']['order_date'])) ?>
                                                </small>
                                            </div>

                                            <!-- Order Summary -->
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h5><?= count($order_data['items']) ?> Item(s) in this order</h5>
                                                        <p class="text-muted">Total Amount: ₱<?= number_format($order_data['header']['total_amount'], 2) ?></p>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <button class="btn essence-btn btn-sm" data-toggle="modal" data-target="#orderModal<?= $order_id ?>">
                                                            View Details
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Order Details Modal -->
                                        <div class="modal fade" id="orderModal<?= $order_id ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Order Desstails #<?= $order_id ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php foreach ($order_data['items'] as $item): ?>
                                                            <div class="row mb-3 border-bottom pb-3">
                                                                <div class="col-md-3">
                                                                    <img src="../admin/<?= $item['image_path'] ?>" class="img-fluid rounded">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h5><?= $item['product_name'] ?></h5>
                                                                    <p>Quantity: <?= $item['quantity'] ?></p>
                                                                    <p>Price: ₱<?= number_format($item['total_amount'], 2) ?></p>
                                                                </div>
                                                                <div class="col-md-3 text-right">
                                                                    <p>Subtotal: ₱<?= number_format($item['quantity'] * $item['total_amount'], 2) ?></p>
                                                                    <form action="" method="POST" class="mt-2">
                                                                        <!-- <input type="hidden" name="order_id" value="<?= $order_id ?>"> -->
                                                                        <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                                                                        <input type="hidden" name="order_id" value="<?= $order_id ?>">

                                                                        <button type="submit" name="cancel" class="btn btn-sm btn-danger">
                                                                            <i class="fas fa-times"></i> Cancel Item
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>

                                                        <div class="row mt-4">
                                                            <div class="col-md-12 text-right">
                                                                <h4>Total: ₱<?= number_format($order_data['header']['total_amount'], 2) ?></h4>
                                                                <p class="text-muted">Payment Method: <?= $order_data['header']['payment_method'] ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>











                            <?php
                            $user_id = $_SESSION['user_id'];

                            // Get all orders with their items
                            $query = "
                                SELECT 
                                    orders.*,
                                    order_items.*,
                                    products.product_name,
                                    products.image_path,
                                     order_items.item_id,
                                     order_items.total_amount
                                FROM orders
                                INNER JOIN order_items ON orders.order_id = order_items.order_id
                                INNER JOIN products ON order_items.product_id = products.id
                                WHERE orders.user_id = ? 
                                AND orders.order_status = 'completed'
                                AND order_items.status = 'pending'
                                ORDER BY orders.order_date DESC
                            ";

                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // Group orders by order_id
                            $grouped_orders = [];
                            while ($row = $result->fetch_assoc()) {
                                $order_id = $row['order_id'];

                                if (!isset($grouped_orders[$order_id])) {
                                    $grouped_orders[$order_id] = [
                                        'header' => [
                                            'invoice_id' => $row['invoice_id'],
                                            'order_date' => $row['order_date'],
                                            'total_amount' => $row['total_amount'],
                                            'payment_method' => $row['payment_method'],
                                            'order_status' => $row['order_status'],

                                        ],
                                        'items' => []
                                    ];
                                }

                                $grouped_orders[$order_id]['items'][] = [
                                    'item_id' => $row['item_id'], // Add item_id here
                                    'total_amount' => $row['total_amount'], // Add item_id here



                                    'product_name' => $row['product_name'],
                                    'image_path' => $row['image_path'],
                                    'quantity' => $row['quantity'],
                                    'total_amount' => $row['total_amount']
                                ];
                            }
                            ?>

                            <div class="tab-pane fade" id="completed" role="tabpanel">

                                <?php if (empty($grouped_orders)): ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-shipping-fast fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Orders To Ship</h5>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($grouped_orders as $order_id => $order_data): ?>
                                        <div class="order-card mb-4">
                                            <!-- Order Header -->
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <div class="order-info">
                                                    <span class="text-muted">Order #<?= $order_id ?></span>
                                                    <span class="mx-2">|</span>
                                                    <span class="order-status text-success">
                                                        <!-- <?= strtoupper($order_data['header']['order_status']) ?> -->
                                                        Completed
                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($order_data['header']['order_date'])) ?>
                                                </small>
                                            </div>

                                            <!-- Order Summary -->
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h5><?= count($order_data['items']) ?> Item(s) in this order</h5>

                                                        <p class="text-muted">Total Amount: ₱<?= number_format($order_data['header']['total_amount'], 2) ?></p>

                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <button class="btn essence-btn btn-sm" data-toggle="modal" data-target="#orderModal<?= $order_id ?>">
                                                            View Details
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Order Details Modal -->
                                        <div class="modal fade" id="orderModal<?= $order_id ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Order Details #<?= $order_id ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php foreach ($order_data['items'] as $item): ?>
                                                            <div class="row mb-3 border-bottom pb-3">
                                                                <div class="col-md-3">
                                                                    <img src="../admin/<?= $item['image_path'] ?>" class="img-fluid rounded">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h5><?= $item['product_name'] ?></h5>
                                                                    <p>Quantity: <?= $item['quantity'] ?></p>
                                                                    <p>Price: ₱<?= number_format($item['total_amount'] / 2, 2) ?></p>
                                                                </div>
                                                                <div class="col-md-3 text-right">
                                                                    <p>Subtotal: ₱<?= number_format($item['quantity'] * $item['total_amount'], 2) ?></p>

                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>

                                                        <div class="row mt-4">
                                                            <div class="col-md-12 text-right">
                                                                <?php
                                                                $totalAmount = 0;
                                                                foreach ($order_data['items'] as $item) {
                                                                    $totalAmount += $item['quantity'] * $item['total_amount'];
                                                                }
                                                                ?>
                                                                <h4>Total: ₱<?= number_format($totalAmount, 2) ?></h4>
                                                                <p class="text-muted">Payment Method: <?= $order_data['header']['payment_method'] ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>


                            <div class="tab-pane fade" id="to-receive" role="tabpanel">
                                <?php
                                // New query for to-receive status
                                $query_to_ship = "
                                    SELECT 
                                        orders.*,
                                        order_items.*,
                                        products.product_name,
                                        products.image_path 
                                    FROM orders
                                    INNER JOIN order_items ON orders.order_id = order_items.order_id
                                    INNER JOIN products ON order_items.product_id = products.id
                                    WHERE orders.user_id = ? 
                                    AND orders.order_status = 'shipped' /* Change this to your shipping status */
                                    ORDER BY orders.order_date DESC
                                ";

                                $stmt_to_ship = $conn->prepare($query_to_ship);
                                $stmt_to_ship->bind_param("i", $user_id);
                                $stmt_to_ship->execute();
                                $result_to_ship = $stmt_to_ship->get_result();

                                // Group shipping orders
                                $grouped_ship_orders = [];
                                while ($row = $result_to_ship->fetch_assoc()) {
                                    $order_id = $row['order_id'];
                                    if (!isset($grouped_ship_orders[$order_id])) {
                                        $grouped_ship_orders[$order_id] = [
                                            'header' => [
                                                'invoice_id' => $row['invoice_id'],
                                                'order_date' => $row['order_date'],
                                                'total_amount' => $row['total_amount'],
                                                'payment_method' => $row['payment_method'],
                                                'order_status' => $row['order_status']
                                            ],
                                            'items' => []
                                        ];
                                    }
                                    $grouped_ship_orders[$order_id]['items'][] = [
                                        'product_name' => $row['product_name'],
                                        'image_path' => $row['image_path'],
                                        'quantity' => $row['quantity'],
                                        'total_amount' => $row['total_amount']
                                    ];
                                }
                                ?>

                                <?php if (empty($grouped_ship_orders)): ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-shipping-fast fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Orders To Ship</h5>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($grouped_ship_orders as $order_id => $order_data): ?>
                                        <div class="order-card mb-4">
                                            <!-- Same card structure as to-pay tab -->
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <div class="order-info">
                                                    <span class="text-muted">Order #<?= $order_id ?></span>
                                                    <span class="mx-2">|</span>
                                                    <span class="order-status status-shipping">
                                                        Pending </span>
                                                </div>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($order_data['header']['order_date'])) ?>
                                                </small>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h5><?= count($order_data['items']) ?> Item(s) Ready for Shipping</h5>
                                                        <p class="text-muted">Total Amount: ₱<?= number_format($order_data['header']['total_amount'], 2) ?></p>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <button class="btn essence-btn btn-sm" data-toggle="modal" data-target="#shipModal<?= $order_id ?>">
                                                            Track Order
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Shipping Modal -->
                                        <div class="modal fade" id="shipModal<?= $order_id ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Shipping Details #<?= $order_id ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Add shipping-specific info -->
                                                        <div class="shipping-timeline mb-4">
                                                            <!-- Shipping progress steps -->
                                                        </div>

                                                        <?php foreach ($order_data['items'] as $item): ?>
                                                            <!-- Same item display as before -->
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>









                            <div class="tab-pane fade" id="cancelled" role="tabpanel">
                                <?php
                                // Query for cancelled items
                                $query_cancelled = "
                                    SELECT 
                                        order_items.item_id,
                                        order_items.order_id,
                                        products.product_name,
                                        products.image_path,
                                        order_items.quantity,
                                        order_items.total_amount,
                                        order_items.status,
                                        order_items.cancelled_at
                                    FROM order_items
                                    INNER JOIN orders ON order_items.order_id = orders.order_id
                                    INNER JOIN products ON order_items.product_id = products.id
                                    WHERE order_items.status = 'cancelled'
                                    AND orders.user_id = ?
                                    ORDER BY order_items.item_id DESC
                                ";

                                $stmt_cancelled = $conn->prepare($query_cancelled);
                                $stmt_cancelled->bind_param("i", $user_id);
                                $stmt_cancelled->execute();
                                $result_cancelled = $stmt_cancelled->get_result();
                                ?>

                                <?php if ($result_cancelled->num_rows === 0): ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-ban fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Cancelled Items</h5>
                                    </div>
                                <?php else: ?>
                                    <?php while ($item = $result_cancelled->fetch_assoc()): ?>

                                        <div class="order-card mb-4">
                                            <!-- Same card structure as to-pay tab -->
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <div class="order-info">
                                                    <span class="text-muted">Order #<?= $item['order_id'] ?></span>
                                                    <span class="mx-2">|</span>
                                                    <span class="order-status status-cancelled">Cancelled</span>
                                                </div>
                                                <small class="text-muted">
                                                    <?= $item['cancelled_at'] ?>
                                                </small>
                                            </div>

                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-md-2">
                                                        <img src="../admin/<?= htmlspecialchars($item['image_path']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($item['product_name']) ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5 class="mb-1"><?= htmlspecialchars($item['product_name']) ?></h5>
                                                        <p class="mb-1 text-muted">x<?= number_format($item['quantity']) ?>• Order</p>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <div class="mb-3">
                                                            <h5>Total: ₱<?= number_format($item['total_amount'], 2) ?></h5>
                                                        </div>
                                                        <div class="btn-group">
                                                            <button class="btn essence-btn btn-sm">View Details</button>
                                                            <button class="btn essence-btn btn-sm">Buy Again</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endwhile; ?>



                                <?php endif; ?>
                            </div>

                            <!-- Add remaining tabs content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>

</html>
<style>
    .order-card {
        border: 1px solid #eee;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .modal-content {
        padding: 20px;
    }

    .modal-body img {
        max-height: 150px;
        object-fit: cover;
    }
</style>
<style>
    /* Additional status color */
    .status-shipping {
        background-color: #cce5ff;
        color: #004085;
        border: 1px solid #b8daff;
    }

    .shipping-timeline {
        border-left: 3px solid #eee;
        padding-left: 1.5rem;
        margin-left: 1rem;
    }
</style>
<style>
    .status-shipping {
        background-color: #cce5ff;
        color: #004085;
        border: 1px solid #b8daff;
    }

    .status-processing {
        background-color: #F7FF79;
        color: #004085;
        border: 1px solid #B9C608;
    }

    .status-cancelled {
        background-color: #FFA9A9;
        color: #A90000;
        border: 1px solid #CA0000;
    }



    .purchase-tabs .nav-link {
        font-size: 0.9rem;
        padding: 1rem 1.5rem;
        color: #666;
        border: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .purchase-tabs .nav-link.active {
        color: #0315ff;
        border-bottom: 3px solid #dc0345;
        font-weight: 600;
    }

    .purchase-tabs .nav-link:hover {
        color: #dc0345;
        background: #fff5f7;
    }

    .order-card {
        border: 1px solid #eee;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        transition: box-shadow 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .order-status {
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .order-status.text-success {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .price-container {
        background: #f8f9fa;
        padding: 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .col-md-4.text-right {
        padding-left: 15px;
        padding-right: 15px;
    }

    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-end;
    }



    /* Ensure card-body has enough padding */
    .card-body {
        padding: 1.5rem;
        min-height: 150px;
    }

    @media (max-width: 768px) {
        .col-md-4 {
            margin-top: 1rem;
            text-align: left !important;
        }

        .btn-group {
            justify-content: flex-start;
            width: 100%;
        }

        .essence-btn.btn-sm {
            width: 100%;
            margin: 4px 0;
        }

        .col-md-2 {
            text-align: center;
        }

        .price-container {
            margin-top: 1rem;
        }
    }

    /* Add minimum height for mobile alignment */
    @media (max-width: 576px) {
        .card-body .row {
            min-height: 300px;
        }

        .col-md-6 {
            margin-top: 1rem;
        }
    }
</style>