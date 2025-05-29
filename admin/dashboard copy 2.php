<?php include 'admin_header.php'; ?>

<body class="g-sidenav-show bg-gray-100">
  <?php include 'sidebar.php'; ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-4">
      <!-- Dashboard Cards -->
      <div class="row mb-4">
        <!-- Orders Card -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Orders</p>
                    <h5 class="font-weight-bolder mb-0">
                      2,300
                      <span class="text-success text-sm font-weight-bolder">+55%</span>
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
        </div>

        <!-- Cancellations Card -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Cancellations</p>
                    <h5 class="font-weight-bolder mb-0">
                      86
                      <span class="text-danger text-sm font-weight-bolder">+3%</span>
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
        </div>

        <!-- Revenue Card -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Revenue</p>
                    <h5 class="font-weight-bolder mb-0">
                      $53,000
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
        <div class="col-xl-3 col-sm-6">
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
      </div>

      <!-- Additional Cards -->
      <div class="row mb-4">
        <!-- Customers Card -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Customers</p>
                    <h5 class="font-weight-bolder mb-0">
                      3,462
                      <span class="text-success text-sm font-weight-bolder">+12%</span>
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
        </div>

        <!-- Returns Card -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Returns</p>
                    <h5 class="font-weight-bolder mb-0">
                      47
                      <span class="text-danger text-sm font-weight-bolder">+8%</span>
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

        <!-- Average Order Value -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Avg. Order Value</p>
                    <h5 class="font-weight-bolder mb-0">
                      $142.30
                      <span class="text-success text-sm font-weight-bolder">+5.2%</span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                    <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Conversion Rate -->
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Conversion Rate</p>
                    <h5 class="font-weight-bolder mb-0">
                      4.23%
                      <span class="text-success text-sm font-weight-bolder">+1.2%</span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-chart-pie-35 text-lg opacity-10" aria-hidden="true"></i>
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
        <div class="col-lg-7 mb-lg-0 mb-4">
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
        <div class="col-lg-5">
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

      <!-- Additional Charts -->
      <div class="row">
        <!-- Revenue Bar Chart -->
        <div class="col-lg-5 mb-lg-0 mb-4">
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

        <!-- Stock Radar Chart -->
        <div class="col-lg-7">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Inventory Status</h6>
              <p class="text-sm mb-0">
                <i class="fa fa-exclamation-triangle text-warning"></i>
                <span class="font-weight-bold">12 items</span> need restocking
              </p>
            </div>
            <div class="card-body p-0 d-flex flex-column">
              <div class="chart-container flex-grow-1">
                <canvas id="radar-chart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- More Charts -->
      <div class="row mt-4">
        <!-- Monthly Performance -->
        <div class="col-lg-6 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Monthly Performance</h6>
            </div>
            <div class="card-body p-0 d-flex flex-column">
              <div class="chart-container flex-grow-1">
                <canvas id="mixed-chart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Regional Sales -->
        <div class="col-lg-6">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Regional Sales</h6>
            </div>
            <div class="card-body p-0 d-flex flex-column">
              <div class="chart-container flex-grow-1">
                <canvas id="doughnut-chart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
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
      const barCtx = document.getElementById('bar-chart').getContext('2d');
      const barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: ['Electronics', 'Clothing', 'Home & Kitchen', 'Beauty', 'Sports'],
          datasets: [{
            label: 'Revenue ($)',
            data: [125000, 82000, 78000, 56000, 45000],
            backgroundColor: [
              'rgba(94, 114, 228, 0.8)',
              'rgba(23, 201, 100, 0.8)',
              'rgba(255, 193, 7, 0.8)',
              'rgba(233, 30, 99, 0.8)',
              'rgba(33, 150, 243, 0.8)'
            ],
            borderColor: [
              'rgba(94, 114, 228, 1)',
              'rgba(23, 201, 100, 1)',
              'rgba(255, 193, 7, 1)',
              'rgba(233, 30, 99, 1)',
              'rgba(33, 150, 243, 1)'
            ],
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
                  return '$' + value.toLocaleString();
                }
              }
            }
          }
        }
      });

      // Radar Chart - Inventory Status
      const radarCtx = document.getElementById('radar-chart').getContext('2d');
      const radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
          labels: ['Electronics', 'Clothing', 'Home & Kitchen', 'Beauty', 'Sports', 'Books'],
          datasets: [{
            label: 'Current Stock',
            data: [35, 65, 85, 45, 25, 75],
            backgroundColor: 'rgba(253, 126, 20, 0.2)',
            borderColor: 'rgba(253, 126, 20, 1)',
            pointBackgroundColor: 'rgba(253, 126, 20, 1)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgba(253, 126, 20, 1)'
          }, {
            label: 'Ideal Stock',
            data: [70, 70, 70, 70, 70, 70],
            backgroundColor: 'rgba(23, 201, 100, 0.2)',
            borderColor: 'rgba(23, 201, 100, 1)',
            pointBackgroundColor: 'rgba(23, 201, 100, 1)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgba(23, 201, 100, 1)'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            r: {
              angleLines: {
                display: true
              },
              suggestedMin: 0,
              suggestedMax: 100
            }
          }
        }
      });

      // Mixed Chart - Monthly Performance
      const mixedCtx = document.getElementById('mixed-chart').getContext('2d');
      const mixedChart = new Chart(mixedCtx, {
        type: 'bar',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [{
            label: 'Revenue',
            data: [25000, 32000, 28000, 41000, 52000, 49000],
            backgroundColor: 'rgba(94, 114, 228, 0.7)',
            borderColor: 'rgba(94, 114, 228, 1)',
            borderWidth: 1,
            type: 'bar'
          }, {
            label: 'Target',
            data: [30000, 35000, 35000, 45000, 50000, 55000],
            borderColor: 'rgba(253, 126, 20, 1)',
            backgroundColor: 'transparent',
            type: 'line',
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
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

      // Doughnut Chart - Regional Sales
      const doughnutCtx = document.getElementById('doughnut-chart').getContext('2d');
      const doughnutChart = new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
          labels: ['North America', 'Europe', 'Asia', 'South America', 'Africa', 'Oceania'],
          datasets: [{
            data: [35, 25, 20, 10, 5, 5],
            backgroundColor: [
              'rgba(94, 114, 228, 0.8)',
              'rgba(23, 201, 100, 0.8)',
              'rgba(255, 193, 7, 0.8)',
              'rgba(233, 30, 99, 0.8)',
              'rgba(33, 150, 243, 0.8)',
              'rgba(156, 39, 176, 0.8)'
            ],
            borderColor: [
              'rgba(94, 114, 228, 1)',
              'rgba(23, 201, 100, 1)',
              'rgba(255, 193, 7, 1)',
              'rgba(233, 30, 99, 1)',
              'rgba(33, 150, 243, 1)',
              'rgba(156, 39, 176, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right',
            }
          }
        }
      });

      // Make charts responsive on window resize
      window.addEventListener('resize', function() {
        lineChart.resize();
        pieChart.resize();
        barChart.resize();
        radarChart.resize();
        mixedChart.resize();
        doughnutChart.resize();
      });
    });
  </script>
</body>