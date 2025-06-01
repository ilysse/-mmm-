<style>

rowi {
  background-color: aqua;
  margin-top:  150px;
  margin-bottom:   100px;
}

  @media print {
body{
  font-size: 12px;
  width: 90%;
  max-width: 1048px;
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
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row ">
        <div class="col-md-6">
          <h1 class="m-0 text-dark"><!-- Dashboard v2 --></h1>
        </div><!-- /.col -->
        <div class="col-md-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Catagory</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
    <div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title"><b>Total Sell List</b></h3>
            <div class="btn-group">
                <a href="index.php?page=quick_sell" target="_blank" class="btn btn-primary btn-sm rounded-0">
                    <i class="fas fa-plus"></i> New Sell
                </a>
                <a id="printSelected" target="_blank" class="btn btn-primary btn-sm rounded-0">
                    <i class="fas fa-print"></i> Print Selected
                </a>
                <a id="deleteSelected" target="_blank" class="btn btn-danger btn-sm rounded-0">
                    <i class="fas fa-trash"></i> Delete Selected
                </a>
            </div>
        </div>
        <div class="mt-3">
            <h5 class="mb-2">Seller ID</h5>
            <input type="text" id="seller_id_search" class="form-control" placeholder="Search by Seller ID" />
        </div>
    </div>
</div>


        <!-- /.card-header -->
        <div class="card-body">
          
          <div class="table-responsive">
            <table id="sellTable" class="display dataTable text-center table-clickable">
              <thead>
                <tr>
                  <th>Invoice no</th>
                  <th>customer</th>
                  <th>seller id</th>
                  <th>order-date</th>
                  <th>sub total</th>
                  <th>Previous due</th>
                  <th>Net total</th>
                  <th>paid </th>
                  <th>due</th>
                  <th>status</th>
                  <th>payment type</th>
                  <th>action</th>
                </tr>
              </thead>

            </table>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card-body -->
    </div>
  </section>
