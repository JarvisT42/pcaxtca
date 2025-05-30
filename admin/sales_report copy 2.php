<?php
include 'auth_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connect/connection.php';


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









                    <div class="card mb-4">

                        <div class="card-body p-4">


                            <form id="filter-form" class="mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="start-date" class="form-label">Start Date</label>
                                        <input type="date" id="start-date" name="start-date" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="end-date" class="form-label">End Date</label>
                                        <input type="date" id="end-date" name="end-date" class="form-control">
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                </div>
                            </form>


                            <table id="myTable" class="display">
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Load DataTables JS (via CDN) -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!-- Initialize DataTable -->
    <script>
        let table; // Declare globally

        $(document).ready(function() {
            // Initialize DataTable only once
            table = $('#myTable').DataTable();

            // Set default start and end date
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

            $('#start-date').val(firstDay.toISOString().split('T')[0]);
            $('#end-date').val(lastDay.toISOString().split('T')[0]);

            // Load initial filtered data
            fetchAndDisplayOrders(firstDay.toISOString().split('T')[0], lastDay.toISOString().split('T')[0]);

            // Filter on form submit
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();

                const startDate = $('#start-date').val();
                const endDate = $('#end-date').val();

                fetchAndDisplayOrders(startDate, endDate);
            });
        });

        function fetchAndDisplayOrders(startDate, endDate) {
            // Fetch filtered data from your server via AJAX
            $.ajax({
                url: 'fetch_table_data.php',
                method: 'POST',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                dataType: 'json',
                success: function(data) {
                    table.clear(); // Use global table variable
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


    <!--   Core JS Files   -->

</body>

<?php include 'admin_footer.php'; ?>