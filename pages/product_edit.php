<!-- Content Wrapper. Contains page content --> -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><!-- Dashboard v2 --></h1>
          </div><!-- /.col -->
          <div class="col-sm-6 mt-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
              <li class="breadcrumb-item active">Edit Products</li>
            </ol>
            </div><!-- /.col -->
            </div><!-- /.row -->
            </div><!-- /.container-fluid -->
          </div>
          <!-- /.content-header -->
          <!-- Main content -->
          <section class="content">
            <div class="container-fluid">
              <!-- /.card-header -->
              <hr>
               <div class="row">
                 <div class="col-md-12 col-lg-12 mt-3">
                   <div class="card">
                     <div class="card-body">
      <?php 
        if (isset($_GET['edit_id'])) {
           $edit_id = $_GET['edit_id'];
           $data = $obj->find('products' , 'id' , $edit_id);

            if ($data) {
              ?>
      <form id="editProduct">
        <div class="row">
        <div class="col-md-4 col-lg-4">
          <div class="form-group">
          <label for="product_name">Product name * :</label>
          <input type="text" class="form-control" id="product_name" name="product_name" value="<?=$data->product_name;?>">
          <input type="text" hidden name="id" value="<?=$edit_id;?>">
        </div>
       </div>
        <div class="col-md-4 col-lg-4">
           <div class="form-group">
          <label for="brand">Brand Name * :</label>
          <input type="text" class="form-control" id="brand" value="<?=$data->brand_name;?>" name="brand">
        </div>
       </div>
       <div class="col-md-4 col-lg-4">
           <div class="form-group">
              <label for="p_catagory">Product catagory * :</label>
              <select name="p_catagory" id="p_catagory" class="form-control select2">
                <option disabled selected>Select a catagory</option>
                <?php 
                  $all_catgory = $obj->all('catagory');
                  $select_val = $data->catagory_id;

                    foreach ($all_catgory as $catagory) {
                    if ($select_val == $catagory->id) {
                      $selected = 'selected';
                    }else{
                       $selected = '';
                    }
                    ?>  
                      <option <?php echo $selected;?> value="<?=$catagory->id;?>"><?=$catagory->name;?></option>
                    <?php 
                  }
                
                 ?>
              </select>
           </div>
         </div>
       <div class="col-md-4 col-lg-4">
           <div class="form-group">
          <label for="product_source">Product source * :</label>
          <select name="product_source" id="product_source" class="form-control select2">
            <option <?php if ($data->product_source == 'factory'){echo "selected";}else{echo "";} ?> value="factory">Factory</option>
            <option <?php if ($data->product_source == 'buy'){echo "selected";}else{echo "";} ?> value="buy">Buying</option>s
          </select>
         </div>
       </div>
        <div class="col-md-4 col-lg-4">
           <div class="form-group">
          <label for="sku">SKU * :</label>
          <input type="text" class="form-control" readonly id="sku" value="<?=$data->sku;?>" name="sku">
         </div>
       </div>
        <div class="col-md-4 col-lg-4">
          <div class="form-group">
          <label for="quantity">Quantity * :</label>
          <input type="number" class="form-control" id="quantity" value="<?=$data->quantity;?>" name="quantity">
        </div>
       </div>
        <div class="col-md-4 col-lg-4">
          <div class="form-group">
          <label for="alert_quantity">Alert Quantity * :</label>
          <input type="number" class="form-control" id="alert_quantity" name="alert_quantity" value="<?=$data->alert_quanttity;?>">
        </div>
       </div>
       
       <div class="col-md-4 col-lg-4">
         <div class="form-group">
          <label for="buy_price">Buying Price * :</label>
          <input type="number" class="form-control" readonly id="buy_price" value="<?=$data->buy_price;?>" name="buy_price">
        </div>
       </div>
        <div class="col-md-4 col-lg-4">
         <div class="form-group">
          <label for="selling_price">Selling Price * :</label>
            <input type="number" class="form-control" id="selling_price" name="selling_price" value="<?=$data->sell_price;?>" min="0" max="10000" step="any" required>
        </div>
       </div>
       </div>
         <div class="row text-center mt-5">
          <div class="col-md-6 offset-md-3 col-lg-6 offset-lg-3">
            <button type="submit" title="update data" class="btn btn-primary pl-5 pr-5  rounded-0">update</button>
          </div>
        </div>
      </form>

              <?php 
            }else{
          header("location:index.php?page=error_page");
        }

        }
       ?>
         </div>
                  </div>
                 </div>
               </div>
            </div>
            <!-- /.card-body -->
            <!-- /.row -->
            </div><!--/. container-fluid -->
          </section>
          <!-- /.content -->
        </div>
        <!-- /.content-wrapper