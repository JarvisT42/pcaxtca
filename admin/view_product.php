<?php
include 'auth_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connect/connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Inventory Report</title>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- Soft UI CSS -->
    <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />

</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include 'sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include 'navbar.php'; ?>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body p-4">
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

                            <form id="filter-form" class="mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="start-date" class="form-label">Start Date</label>
                                        <input type="date" id="start-date" name="start-date" class="form-control" />
                                    </div>
                                    <div class="col-md-3">
                                        <label for="end-date" class="form-label">End Date</label>
                                        <input type="date" id="end-date" name="end-date" class="form-control" />
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                </div>
                            </form>

                            <table id="myTable" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Quantity</th>
                                        <th>Movement Type</th>
                                        <th>Date Created</th>
                                        <th>Action</th> <!-- Add this if you want to keep the button -->
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" id="totals-cell" style="text-align:left;"></th>
                                    </tr>
                                </tfoot>
                            </table>


                            <div class="mt-4">
                                <h6 id="total-stock"><strong>Total Stock:</strong> </h6>
                                <h6 id="total-sold"><strong>Total Sold:</strong> </h6>
                                <h6 id="current-stock"><strong>Current Stock:</strong> </h6>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        let table;

        $(document).ready(function() {
            // Initialize DataTable with print button and entries dropdown
            table = $('#myTable').DataTable({
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'print',
                    text: 'Print',
                    customize: function(win) {
                        const totalStock = $('#total-stock').text();
                        const totalSold = $('#total-sold').text();
                        const currentStock = $('#current-stock').text();

                        const startDate = $('#start-date').val();
                        const endDate = $('#end-date').val();

                        $(win.document.body)
                            .prepend(`
                        <div style="margin-bottom: 20px;">
                            <h4>Inventory Report</h4>
                            <p><strong>Start Date:</strong> ${startDate}</p>
                            <p><strong>End Date:</strong> ${endDate}</p>
                            <p><strong>${totalStock}</strong></p>
                            <p><strong>${totalSold}</strong></p>
                            <p><strong>${currentStock}</strong></p>
                        </div>
                    `);
                    }
                }],
                lengthMenu: [5, 10, 25, 50, 100]
            });



            // Set default date range to current month
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

            $('#start-date').val(firstDay.toISOString().split('T')[0]);
            $('#end-date').val(lastDay.toISOString().split('T')[0]);

            // Load initial data
            fetchAndDisplayOrders(firstDay.toISOString().split('T')[0], lastDay.toISOString().split('T')[0]);

            // Filter form submit event
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                const startDate = $('#start-date').val();
                const endDate = $('#end-date').val();

                fetchAndDisplayOrders(startDate, endDate);
            });
        });

        function fetchAndDisplayOrders(startDate, endDate) {
            $.ajax({
                url: 'vp_fetch_table_data.php?id=<?= $product_id ?>',
                method: 'POST',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    product_id: <?= $product_id ?> // Echo from PHP to JS
                },
                dataType: 'json',
                success: function(response) {
                    table.clear();
                    let count = 1;

                    response.orders.forEach(function(item) {
                        table.row.add([
                            count++,
                            item.qty,
                            item.movement_type,
                            item.movement_date,
                            `<button class="btn btn-sm btn-info">View</button>`
                        ]);
                    });

                    table.draw();

                    // Display totals
                    const totalStock = response.total_stock || 0;
                    const totalSold = response.total_sold || 0;
                    const currentStock = totalStock - totalSold;

                    $('h6:contains("Total Stock")').html(`<strong>Total Stock:</strong> ${totalStock}`);
                    $('h6:contains("Total Sold")').html(`<strong>Total Sold:</strong> ${totalSold}`);
                    $('h6:contains("Current Stock")').html(`<strong>Current Stock:</strong> ${currentStock}`);
                },

                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                    alert('Failed to load order data.');
                }
            });
        }
    </script>

    <?php include 'admin_footer.php'; ?>
</body>

</html>