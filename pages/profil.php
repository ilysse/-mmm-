<?php

$current_user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['user_role'] === 'admin';

// Determine the user_id to display (admin can specify any user, others only see their own)
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : $current_user_id;
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">User Profile</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Profile</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- Metric Cards -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <dv class="inner">
              <h3 id="totalSales">0</h3>
              <p>Total Sales</p>
              <div class="icon"><i class="fas fa-dollar-sign"></i></div>

            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 id="totalCustomers">0</h3>
              <p>Total Customers</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
          </div>
        </div>

        <!-- Monthly Sales Chart -->
        <div class="col-lg-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Monthly Sales</h3>
            </div>
            <div class="card-body">
              <canvas id="monthlySalesChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Top Products Table -->
        <div class="col-lg-6">
          <div class="card">
          <input id="csrf_tokn"  value="<?php echo htmlspecialchars($csrf_token); ?>" hidden></input>

            <div class="card-header">
              <h3 class="card-title">Top 5 Best-Selling Products</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>Quantity Sold</th>
                  </tr>
                </thead>
                <tbody id="topProductsTableBody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Top 5 Best Custmrs</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Customers Name</th>
                    <th>Sales Amout</th>
                  </tr>
                </thead>
                <tbody id="topCostumerTableBody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  function loadProfileMetrics(userId) {
    let csrf_token =  document.getElementById('csrf_tokn').value;
    fetch(`app/ajax/dddba.php?user_id=${userId}&token=${csrf_token}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const metrics = data.data;
          document.getElementById('totalSales').textContent = metrics.total_sales;
          document.getElementById('totalCustomers').textContent = metrics.total_customers;
          populateTopProducts(metrics.top_products);
          renderMonthlySalesChart(metrics.monthly_sales);
        } else {
          alert('Failed to load metrics');
        }
      })  
      .catch(error => console.error('Error:', error));
  }

  function populateTopProducts(products) {
    const tbody = document.getElementById('topProductsTableBody');
    tbody.innerHTML = '';
    products.forEach(product => {
      const row = `<tr><td>${product.product_name}</td><td>${product.total_quantity}</td></tr>`;
      tbody.innerHTML += row;
    });
  }

  function renderMonthlySalesChart(monthlySales) {
    const labels = monthlySales.map(sale => sale.date);
    const data = monthlySales.map(sale => sale.total_sales);

    new Chart(document.getElementById('monthlySalesChart'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'total_sales',
          data: data,
          borderColor: 'rgba(75, 192, 192, 1)',
          fill: false,
          tension: 0.1
        }]
      },
      options: {
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { title: { display: true, text: 'Sales Amount' } }
            }
        }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    const userId = <?= $user_id ?>;
    loadProfileMetrics(userId);
  });
</script>

