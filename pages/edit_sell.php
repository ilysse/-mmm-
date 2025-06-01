<?php
// Ensure $obj is defined and is an object
if (!isset($obj) || !is_object($obj)) {
    die('Database object is not properly initialized.');
}
?>
<!-- Content Wrapper. Contains page content  -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 mt-3">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Edit Sale</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit Sale</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Edit Sale Details</h5>
        </div>
        <?php 
        if (isset($_GET['edit_id'])) {
          $edit_id = intval($_GET['edit_id']); // Ensure it's an integer
          $sell_data = $obj->find('invoice', 'id', $edit_id);
          $all_invoice_detils_res = $obj->findWhere('invoice_details', 'invoice_no', $edit_id);
          
          if ($sell_data && is_object($sell_data)) {
        ?>
        <div class="card-header">
          <p>Invoice number: <?php echo htmlspecialchars($sell_data->invoice_number ?? 'N/A'); ?></p>
        </div>
        <div class="card-body">
          <form id="editSellForm" onsubmit="return false">
            <div class="order-header">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="customer-name">Customer name</label>
                    <select name="customer_name" id="customer_name" class="form-control select2">
                      <?php 
                      $all_customer = $obj->all('member');
                      $select_val = $sell_data->customer_id ?? '';
                      if (is_array($all_customer)) {
                        foreach ($all_customer as $customer) {
                          if (is_object($customer)) {
                            $selected = ($select_val == $customer->id) ? 'selected' : '';
                            echo "<option $selected value='" . htmlspecialchars($customer->id) . "'>" . htmlspecialchars($customer->name) . "</option>";
                          }
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="orderdate">Order date</label>
                    <input type="text" class="form-control datepicker" name="orderdate" id="orderdate" autocomplete="off" value="<?php echo htmlspecialchars($sell_data->order_date ?? ''); ?>">
                    <input type="hidden" name="invoice_id" value="<?php echo htmlspecialchars($sell_data->id ?? ''); ?>">
                  </div>
                </div>
              </div>
            </div>

            <div class="card mt-4" style="background: #f1eaea40">
              <div class="table-responsive">
                <table class="table table-bordered text-center mt-4">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Product</th>
                      <th>Previous Order Qty</th>
                      <th>Price</th>
                      <th>Order Quantity</th>
                      <th>Total Price</th>
                      <th>Product Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="editInvoiceItem">
                    <?php 
                    if (is_array($all_invoice_detils_res)) {
                      foreach ($all_invoice_detils_res as $index => $all_invoice_res) {
                        if (is_object($all_invoice_res)) {
                    ?>
                    <tr>
                      <td><b class="si_number"><?php echo $index + 1; ?></b></td>
                      <td>
                        <input type="text" class="form-control form-control-sm pid" readonly name="pid[]" value="<?php echo htmlspecialchars($all_invoice_res->pid ?? ''); ?>">
                        <input type="hidden" name="up_pid[]" value="<?php echo htmlspecialchars($all_invoice_res->pid ?? ''); ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control form-control-sm qaty" readonly name="prev_order_qty[]" value="50">
                      </td>
                      <td>
                        <input type="number" class="form-control form-control-sm price" name="price[]" value="<?php echo htmlspecialchars(($all_invoice_res->price ?? 0) / ($all_invoice_res->quantity ?? 1)); ?>">
                      </td>
                      <td>
                        <input type="number" class="form-control form-control-sm oqty" name="orderQuantity[]" value="<?php echo htmlspecialchars($all_invoice_res->quantity ?? ''); ?>">
                        <input type="hidden" name="up_quantity[]" value="<?php echo htmlspecialchars($all_invoice_res->quantity ?? ''); ?>">
                      </td>
                      <td>
                        <input type="number" class="form-control form-control-sm tprice" readonly name="totalPrice[]" value="<?php echo htmlspecialchars($all_invoice_res->price ?? ''); ?>">
                      </td>
                      <td>
                        <input type="text" readonly class="form-control form-control-sm pro_name" name="pro_name[]" value="<?php echo htmlspecialchars($all_invoice_res->product_name ?? ''); ?>">
                      </td>
                      <td>
                        <button type="button" class="btn btn-danger btn-sm pl-3 pr-3 cancelThisItem"><i class="fas fa-times"></i></button>
                      </td>
                    </tr>
                    <?php 
                        }
                      }
                    } else {
                      echo '<tr><td colspan="8">No invoice details found.</td></tr>';
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="form-group text-right mt-3">
                <button type="button" class="btn btn-primary" id="EditaddNewRowBtn">Add</button>
              </div>
            </div>

            <div class="invoice-area card pt-3" style="background: #f1eaea40">
              <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">
                  <div class="form-group" style="display:none;">
                    <div class="row">
                      <div class="col-md-4 col-lg-3">
                        <label for="subtotal" >Subtotal</label>
                      </div> 
                      <div class="col-md-8 col-lg-9">
                        <input type="number" class="form-control form-control-sm" name="subtotal" id="subtotal" value="<?php echo htmlspecialchars($sell_data->sub_total ?? '0'); ?>">
                      </div>  
                    </div>
                  </div>
                  <input type="hidden" name="s_discount_amount" id="s_discount_amount" value="0">
                  <input type="hidden" name="discount" id="discount" value="0">
                  
                  <div class="form-group" style="display:none;">
                    <div class="row">
                      <div class="col-md-4 col-lg-3">
                        <label for="prev_due">Previous Total Due</label>
                      </div>
                      <div class="col-md-8 col-lg-9">
                        <input type="number" class="form-control form-control-sm" name="prev_due" id="prev_due" value="<?php echo htmlspecialchars($sell_data->pre_cus_due ?? '0'); ?>">
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-lg-3">
                        <label for="netTotal">Net Total</label>
                      </div>
                      <div class="col-md-8 col-lg-9">
                        <input type="number" class="form-control form-control-sm" name="netTotal" id="netTotal" value="<?php echo htmlspecialchars($sell_data->net_total ?? '0'); ?>">
                      </div>
                    </div>
                  </div>

                  <div class="form-group" style="display:none;">
                    <div class="row">
                      <div class="col-md-4 col-lg-3">
                        <label for="paidBill">Paid Bill</label>
                      </div>
                      <div class="col-md-8 col-lg-9">
                        <input type="number" class="form-control form-control-sm" name="paidBill" id="paidBill" value="0">
                      </div>
                    </div>
                  </div>

                  <div class="form-group" style="display:none;">
                    <div class="row">
                      <div class="col-md-4 col-lg-3">
                        <label for="dueBill">Due Bill</label>
                      </div>
                      <div class="col-md-8 col-lg-9">
                        <input type="number" class="form-control form-control-sm" name="dueBill" id="dueBill" value="0">
                      </div>
                    </div>
                  </div>

                  <div class="form-group" style="display:none;">
                    <div class="row">
                      <div class="col-md-4 col-lg-3">
                        <label for="payMethode">Payment Method</label>
                      </div>
                      <div class="col-md-8 col-lg-9">
                        <select name="payMethode" id="payMethode" class="form-control form-control-sm select2">
                          <option selected disabled>Select a payment method</option>
                          <?php 
                          $all_methode = $obj->all('paymethode');
                          if (is_array($all_methode)) {
                            foreach ($all_methode as $payMethode) {
                              if (is_object($payMethode)) {
                                echo "<option value='" . htmlspecialchars($payMethode->name) . "'selected>" . htmlspecialchars($payMethode->name) . "</option>";
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group text-center">
                    <button type="submit" class="btn btn-success btn-block" id="editSellBtn">Update Sale</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div> 
        <?php 
          } else {
            echo '<div class="alert alert-danger">No data found for edit</div>';
          }
        } else {
          echo '<div class="alert alert-warning">No edit ID provided</div>';
        }
        ?>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->