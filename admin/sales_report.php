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
    <title>Sales Report</title>

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
                                        <th>Product Name</th>
                                        <th>Total Cost</th>
                                        <th>Total Amount</th>
                                        <th>Total Profit</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

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
                buttons: ['print'],
                // You can also add lengthMenu to customize entries dropdown
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
                url: 'fetch_table_data.php',
                method: 'POST',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                dataType: 'json',
                success: function(data) {
                    table.clear();
                    let count = 1;

                    data.forEach(function(item) {
                        table.row.add([
                            count++,
                            item.product_name,
                            item.total_cost,
                            item.total_amount,
                            item.total_profit,
                            item.status,
                            `<button class="btn btn-sm btn-info">View</button>`
                        ]);
                    });

                    table.draw();
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