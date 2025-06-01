<style>
  @media print {
    /* Hide unnecessary elements */
    body * {
      visibility: hidden;
    }

    /* Show only the table and its container */
    #sales_report_table, #sales_report_table * {
      visibility: visible;
    }

    /* Position the table at the top of the page */
    #sales_report_table {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
    }

    /* Add some padding and borders for better readability */
    #sales_report_table th,
    #sales_report_table td {
      border: 1px solid #000;
      padding: 8px;
    }

    #sales_report_table th {
      background-color: #f2f2f2;
    }
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Sales Report</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Sales Report</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Search Form -->
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label for="reportrange">Date Range</label>
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 6px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="fa fa-calendar"></i>&nbsp;
                  <span></span> <i class="fa fa-caret-down"></i>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label for="seller_id">Seller</label>
                <select id="seller_id" class="form-control">
                  <option value="">Select Seller</option>
                  <!-- Seller options will be populated dynamically -->
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group" style="margin-top: 30px;">
                <button id="search_sales_report" class="btn btn-primary">Show</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Results Table -->
      <div class="card">
        <div class="card-body">
        <button id="print_table" class="btn btn-primary">Print Table</button>

          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Total Quantity</th>
              </tr>
            </thead>
            <tbody id="search_sales_report_res">
              <!-- Results will be populated here -->
            </tbody>
            <tfoot>
              <tr>
                <th colspan="2" class="text-right">Overall Total Quantity:</th>
                <th id="overall_total_quantity">0</th>
              </tr>
            </tfoot>
          </table>
          <iframe id="print-iframe" style="display: none;"></iframe>

        </div>
      </div>
    </div><!-- /.container-fluid -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript">
// Initialize date range picker
var start = moment().subtract(29, 'days');
var end = moment();

function cb(start, end) {
  $('#reportrange span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
}

$('#reportrange').daterangepicker({
  startDate: start,
  endDate: end,
  ranges: {
    'Today': [moment(), moment()],
    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    'This Month': [moment().startOf('month'), moment().endOf('month')],
    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
  }
}, cb);

cb(start, end);

// Handle print table button click
$('#print_table').on('click', function() {
  window.print();
});
</script>