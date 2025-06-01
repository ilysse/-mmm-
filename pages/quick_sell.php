<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>New Sell</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">New Sell</li>
          </ol>
        </div>
      </div>    
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Make a sell here</h3>
          <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target=".myModal">
            <i class="fas fa-plus"></i> Add Customer
          </button>
        </div>
        <div class="card-body">
          <form id="sellForm" onsubmit="return false">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="customer_name" style="background-color:red;" >Customer name</label>
                  <select name="customer_name" id="customer_name" class="form-control select2" style="width: 100%;">
                    <option selected disabled>Select a customer</option>
                    <?php
                    $all_customer = $obj->all('member');
                    foreach ($all_customer as $customer) {
                      echo "<option value='{$customer->id}'>{$customer->name}</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="col-md-6 col-lg-6">
                  <label for="orderdate">Order date</label>
                  <?php
                  $today = date('Y-m-d'); // Get today's date in YYYY-MM-DD format
                  ?>
                  <input type="date" class="form-control" name="orderdate" id="orderdate" value="<?php echo $today; ?>" autocomplete="off">
                </div>
              </div>
            </div>

            <div class="card mt-4">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Product</th>
                      <th>Total quantity</th>
                      <th>Price</th>
                      <th>Order quantity</th>
                      <th>Total Price</th>
                      <th>Product name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="invoiceItem" >
                    <!-- invoice items will be added here dynamically -->
                  </tbody>
                </table>
              </div>
            </div>

            <div class="text-right mt-3">
              <button type="button" class="btn btn-primary" id="addNewRowBtn">Add Item</button>
            </div>

            <div class="card mt-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 offset-md-3">
                    <div class="form-group" style="display:none;">
                      <label for="subtotal">Subtotal</label>
                      <input type="number" class="form-control" name="subtotal" id="subtotal" readonly>
                    </div>
                    <div class="form-group" style="display:none;">
                      <label for="prev_due"></label>
                      <input type="number" class="form-control" name="prev_due" id="prev_due" readonly>
                    </div>
                    <div class="form-group" style="display:none;">
                      <div class="row">
                        <div class="col-md-3">
                          <label for="discount"></label>
                        </div>
                        <div class="col-md-8">
                          <input type="number" class="form-control form-control-sm" name="discount" id="discount"
                            value="0" min="0" max="100" hidden>
                        </div>
                      </div>
                    </div>
                    <div class="form-group" style="display:none;">
                      <div class="row">
                        <div class="col-md-3">
                          <label for="s_discount_amount">discount </label>
                        </div>
                        <div class="col-md-8">
                          <input type="number" class="form-control form-control-sm" name="s_discount_amount"
                            id="s_discount_amount" value="0" min="0">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="netTotal">Net Total</label>
                      <input type="number" class="form-control" name="netTotal" id="netTotal" readonly>
                    </div>
                    <div class="form-group" style="display:none;">
                      <label for="paidBill" style="display:none;">Paid bill</label>
                      <input type="number" class="form-control" name="paidBill" id="paidBill" value="0">
                    </div>
                    <div class="form-group" style="display:none;">
                      <label for="dueBill">Due bill</label>
                      <input type="text" class="form-control" name="dueBill" id="dueBill" readonly>
                    </div>
                    <div class="form-group">
                      <label for="payMethode">Payment Method</label>
                      <select name="payMethode" id="payMethode" class="form-control select2" style="width: 100%;">
                        <option disabled>Select a payment method</option>
                        <?php
                        $all_methode = $obj->all('paymethode');
                        foreach ($all_methode as $payMethode) {
                          echo "<option value='{$payMethode->name}'>{$payMethode->name}</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-success btn-block" id="sellBtn">Make sell</button>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>