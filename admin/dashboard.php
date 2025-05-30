<?php
include 'auth_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../connect/connection.php';

// Fetch monthly sales data
$sales_report = [];
$query = "SELECT 
            YEAR(Orders.order_date) AS year,
            MONTH(Orders.order_date) AS month,
            SUM(order_items.quantity * order_items.total_amount) AS revenue,
            SUM(order_items.quantity * order_items.cost_price) AS cost,
            SUM(order_items.quantity * (order_items.total_amount - order_items.cost_price)) AS profit,
            COUNT(DISTINCT Orders.order_id) AS total_orders
          FROM Orders
          JOIN Order_items ON Orders.order_id = Order_items.order_id
          WHERE Orders.order_status = 'completed'
          GROUP BY YEAR(Orders.order_date), MONTH(Orders.order_date)
          ORDER BY year DESC, month DESC";

$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
  $sales_report[] = $row;
}

// Prepare data for charts
$chart_labels = [];
$chart_revenue = [];
$chart_profit = [];

foreach ($sales_report as $report) {
  $chart_labels[] = date('M Y', mktime(0, 0, 0, $report['month'], 1, $report['year']));
  $chart_revenue[] = $report['revenue'];
  $chart_profit[] = $report['profit'];
}
?>



<?php include 'admin_header.php'; ?>

<body class="g-sidenav-show bg-gray-100">
  <?php include 'sidebar.php'; ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-4">
      <!-- Dashboard Cards -->
      <div class="row mb-4">
        <!-- Orders Card -->
        <?php
        $query = "SELECT COUNT(*) AS total_processing FROM orders WHERE order_status = 'processing'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $totalProcessing = $row['total_processing'];
        ?>



        <div class="col-lg-4 col-sm-6 mb-xl-0 mb-4">
          <a href="view_order.php" style="text-decoration: none; color: inherit;">
            <div class="card" style="font-family: 'Inter', sans-serif;">
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <p class="text-sm mb-0 text-capitalize font-weight-bold">Processing Orders</p>
                      <h5 class="font-weight-bolder mb-0 d-flex align-items-center gap-2">
                        <?= number_format($totalProcessing) ?>
                        <i class="fas fa-spinner text-primary"></i>
                      </h5>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                      <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>



        <?php
        $query = "SELECT COUNT(*) AS total_processing FROM order_items WHERE status = 'cancelled'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $totalProcessing = $row['total_processing'];
        ?>

        <!-- Cancellations Card -->
        <div class="col-lg-4 col-sm-6 mb-xl-0 mb-4">
          <a href="cancelled_order.php" style="text-decoration: none; color: inherit;">

            <div class="card" style="font-family: 'Inter', sans-serif;">
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <p class="text-sm mb-0 text-capitalize font-weight-bold">Cancellations</p>
                      <h5 class="font-weight-bolder mb-0 d-flex align-items-center gap-2">
                        <?= number_format($totalProcessing) ?>

                        <i class="fas fa-times-circle text-danger"></i>
                      </h5>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                      <i class="ni ni-fat-remove text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>

        <!-- Revenue Card -->
        <?php
        $categories = [];
        $revenues = [];
        $totalRevenue = 0;

        $sql = "SELECT pc.product_category, SUM(oi.total_amount) AS total_revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        JOIN product_categorys pc ON p.product_category_id = pc.id
        WHERE oi.status = 'completed'
        GROUP BY pc.product_category
        ORDER BY total_revenue DESC";

        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
          $categories[] = $row['product_category'];
          $revenues[] = $row['total_revenue'];
          $totalRevenue += $row['total_revenue'];
        }
        ?>

        <div class="col-lg-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Revenue</p>
                    <h5 class="font-weight-bolder mb-0">
                      $<?= number_format($totalRevenue, 2) ?>
                      <span class="text-success text-sm font-weight-bolder">+15%</span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- Stock Warning Card -->

      </div>

      <!-- Additional Cards -->
      <div class="row mb-4">
        <!-- Customers Card -->
        <div class="col-lg-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Stock Warning</p>
                    <h5 class="font-weight-bolder mb-0">
                      12 items
                      <span class="text-warning text-sm font-weight-bolder">Low stock</span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                    <i class="ni ni-notification-70 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php

        // Get total number of users
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total_users = $row['total'];
        ?>

        <div class="col-lg-4 col-sm-6 mb-xl-0 mb-4">
          <a href="manage_user.php" style="text-decoration: none; color: inherit;">

            <div class="card" style="font-family: 'Inter', sans-serif;">
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <p class="text-sm mb-0 text-capitalize font-weight-bold">Users</p>
                      <h5 class="font-weight-bolder mb-0 d-flex align-items-center gap-2">
                        <?= number_format($total_users) ?>
                        <i class="fas fa-users text-info"></i>
                      </h5>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                      <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>


        <!-- Returns Card -->
        <div class="col-lg-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card" style="font-family: 'Inter', sans-serif;">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Returns</p>
                    <h5 class="font-weight-bolder mb-0 d-flex align-items-center gap-2">
                      47
                      <i class="fas fa-undo text-secondary"></i>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-secondary shadow text-center border-radius-md">
                    <i class="ni ni-curved-next text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


      </div>

      <!-- Charts Section -->
      <div class="row mb-4">
        <!-- Sales Line Chart -->
        <div class="col-lg-8 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Sales Overview</h6>
              <p class="text-sm mb-0">
                <i class="fa fa-arrow-up text-success"></i>
                <span class="font-weight-bold">4% more</span> in 2024
              </p>
            </div>
            <div class="card-body p-0 d-flex flex-column">
              <div class="chart-container flex-grow-1">
                <canvas id="line-chart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Orders Pie Chart -->
        <div class="col-lg-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Orders Distribution</h6>
            </div>
            <div class="card-body p-0 d-flex flex-column">
              <div class="chart-container flex-grow-1">
                <canvas id="pie-chart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Charts and Stock Timeline -->
      <div class="row">
        <!-- Revenue Bar Chart -->
        <?php
        $categories = [];
        $revenues = [];

        $sql = "SELECT pc.product_category, SUM(oi.total_amount) AS total_revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        JOIN product_categorys pc ON p.product_category_id = pc.id
        WHERE oi.status = 'completed'
        GROUP BY pc.product_category
        ORDER BY total_revenue DESC";

        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
          $categories[] = $row['product_category'];
          $revenues[] = $row['total_revenue'];
        }
        ?>



        <div class="col-lg-8 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Revenue by Category</h6>
              <p class="text-sm mb-0">
                <i class="fa fa-arrow-up text-success"></i>
                <span class="font-weight-bold">Electronics up 24%</span> this month
              </p>
            </div>
            <div class="card-body p-0 d-flex flex-column">
              <div class="chart-container flex-grow-1">
                <canvas id="bar-chart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Stock Timeline -->


        <?php
        // Adjust your query to join product_categorys to get the category name
        $sql = "
    SELECT p.product_name, pc.product_category, pq.qty
    FROM product_qty pq
    JOIN products p ON pq.product_id = p.id
    JOIN product_categorys pc ON p.product_category_id = pc.id
    WHERE pq.qty <= 20
";

        $result = $conn->query($sql);
        $lowStockCount = $result->num_rows;
        ?>



        <div class="col-lg-4">
          <div class="card h-100">
            <div class="card-header pb-0">
              <h6>Low Stock Products</h6>
              <p class="text-sm">
                <i class="fa fa-exclamation-triangle text-warning" aria-hidden="true"></i>
                <span class="font-weight-bold"><?php echo $lowStockCount; ?> item<?php echo $lowStockCount !== 1 ? 's' : ''; ?></span> need restocking
              </p>
            </div>
            <div class="card-body p-3">
              <div class="timeline timeline-one-side">
                <?php while ($row = $result->fetch_assoc()) : ?>
                  <div class="timeline-block mb-3">
                    <span class="timeline-step">
                      <i class="ni ni-box-2 text-danger text-gradient"></i>
                    </span>
                    <div class="timeline-content">
                      <h6 class="text-dark text-sm font-weight-bold mb-0">
                        <?php echo htmlspecialchars($row['product_name']); ?> - Only <?php echo $row['qty']; ?> left
                      </h6>
                      <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                        Category: <?php echo htmlspecialchars($row['product_category']); ?>
                      </p>
                    </div>
                  </div>
                <?php endwhile; ?>
              </div>
            </div>
          </div>
        </div>


      </div>

      <!-- More Charts -->

    </div>
  </main>

  <!-- Core JS Files -->
  <?php include 'admin_footer.php'; ?>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    .chart-container {
      position: relative;
      width: 100%;
      height: 100%;
      min-height: 300px;
      padding: 0 15px;
      box-sizing: border-box;
    }

    .card-body.d-flex {
      padding: 0 !important;
    }

    .card {
      overflow: hidden;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .timeline {
      position: relative;
      padding-left: 4rem;
    }

    .timeline::before {
      content: '';
      position: absolute;
      top: 0;
      bottom: 0;
      left: 18px;
      width: 2px;
      background: linear-gradient(to bottom, #e9ecef, transparent);
    }

    .timeline-step {
      position: absolute;
      left: -10px;
      background: #fff;
      border-radius: 50%;
      height: 36px;
      width: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 0 3px #fff, 0 0 0 6px currentColor;
      z-index: 2;
    }

    .timeline-content {
      padding-left: 2rem;
      position: relative;
    }

    .timeline-block {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .timeline-block:last-child {
      margin-bottom: 0;
    }

    .text-gradient {
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      position: relative;
      z-index: 1;
    }

    .text-danger.text-gradient {
      background-image: linear-gradient(310deg, #ea0606, #ff667c);
    }

    .bg-gradient-primary {
      background-image: linear-gradient(310deg, #7928ca, #ff0080);
    }

    .bg-gradient-danger {
      background-image: linear-gradient(310deg, #ea0606, #ff667c);
    }

    .bg-gradient-success {
      background-image: linear-gradient(310deg, #17ad37, #98ec2d);
    }

    .bg-gradient-warning {
      background-image: linear-gradient(310deg, #f53939, #fbcf33);
    }

    .bg-gradient-info {
      background-image: linear-gradient(310deg, #2152ff, #21d4fd);
    }
  </style>

  <script>
    // Wait for DOM to load
    document.addEventListener('DOMContentLoaded', function() {
      // Line Chart - Sales Overview
      const lineCtx = document.getElementById('line-chart').getContext('2d');
      const lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          datasets: [{
            label: '2023',
            data: [45000, 52000, 48000, 61000, 72000, 69000, 85000, 78000, 82000, 91000, 95000, 110000],
            borderColor: 'rgba(94, 114, 228, 1)',
            backgroundColor: 'rgba(94, 114, 228, 0.1)',
            tension: 0.4,
            fill: true
          }, {
            label: '2024',
            data: [52000, 58000, 62000, 75000, 81000, 89000, 92000, 98000, 102000, 108000, 115000, 125000],
            borderColor: 'rgba(23, 201, 100, 1)',
            backgroundColor: 'rgba(23, 201, 100, 0.1)',
            tension: 0.4,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '$' + value.toLocaleString();
                }
              }
            }
          }
        }
      });

      // Pie Chart - Orders Distribution
      const pieCtx = document.getElementById('pie-chart').getContext('2d');
      const pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
          labels: ['Completed', 'Pending', 'Cancelled', 'Refunded'],
          datasets: [{
            data: [65, 15, 10, 10],
            backgroundColor: [
              'rgba(23, 201, 100, 0.8)',
              'rgba(255, 193, 7, 0.8)',
              'rgba(253, 126, 20, 0.8)',
              'rgba(233, 30, 99, 0.8)'
            ],
            borderColor: [
              'rgba(23, 201, 100, 1)',
              'rgba(255, 193, 7, 1)',
              'rgba(253, 126, 20, 1)',
              'rgba(233, 30, 99, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.label}: ${context.parsed}%`;
                }
              }
            }
          }
        }
      });

      // Bar Chart - Revenue by Category
      const labels = <?= json_encode($categories) ?>;
      const data = <?= json_encode($revenues) ?>;

      const barCtx = document.getElementById('bar-chart').getContext('2d');
      const barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Revenue (₱)',
            data: data,
            backgroundColor: labels.map(() => 'rgba(94, 114, 228, 0.8)'), // or generate colors dynamically
            borderColor: labels.map(() => 'rgba(94, 114, 228, 1)'),
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '₱' + value.toLocaleString();
                }
              }
            }
          }
        }
      });


      // Make charts responsive on window resize
      window.addEventListener('resize', function() {
        lineChart.resize();
        pieChart.resize();
        barChart.resize();

      });
    });
  </script>
</body>