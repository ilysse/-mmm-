<style>
  @page {
    margin-top: 150px;
    margin-bottom: 100px;
    height: 100%;
  }

  @media print {
    body {
      height: 100%;
      font-size: 12px;
      width: 90%;
      max-width: 1048px;
    }

    html{
      height: 100%;
    }
    .view_sell_payment_info {
      display: none;
    }

    .view_sell_button-area {
      display: none;
    }

    footer.main-footer {
      display: none;
    }

    .card.view_sell_page_info {
      margin-top: 100px;
    }
  }
</style>
<!-- Content Wrapper. Contains page content -->
onsive Sell Page with Proper Icons

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Total Sell List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Sell List</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-12 col-sm-6 col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Sell</span>
              <span class="info-box-number">
                <?php
                $stmt = $pdo->prepare("SELECT SUM(`net_total`) FROM `invoice`");
                $stmt->execute();
                $res = $stmt->fetch(PDO::FETCH_NUM);
                echo number_format($res[0], 2);
                ?>
              </span>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Paid Amount</span>
              <span class="info-box-number">
                <?php
                $stmt = $pdo->prepare("SELECT SUM(`paid_amount`) FROM `invoice`");
                $stmt->execute();
                $res = $stmt->fetch(PDO::FETCH_NUM);
                echo number_format($res[0], 2);
                ?>
              </span>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-money-bill-alt"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Due Amount</span>
              <span class="info-box-number">
                <?php
                $stmt = $pdo->prepare("SELECT SUM(`due_amount`) FROM `invoice`");
                $stmt->execute();
                $res = $stmt->fetch(PDO::FETCH_NUM);
                echo number_format($res[0], 2);
                ?>
              </span>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Sell List</h3>
              <div class="card-tools">
                <div class="btn-group">
                  <a href="index.php?page=quick_sell" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New Sell
                  </a>
                  <button id="printSelected" class="btn btn-info btn-sm">
                    <i class="fas fa-print"></i> Print
                  </button>
                  <button id="deleteSelected" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="sellTable" class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Invoice no</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Sub Total</th>
                    <th>Previous Due</th>
                    <th>Net Total</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Payment Type</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Table body will be populated by DataTables -->
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>