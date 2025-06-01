
<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar ">

    <!-- Brand Logo -->
    <a src="" class="brand-link">
      <img src="dist/img/log.jpg" alt="logo" class="brand-image ">
     
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            <?php 
               $login_user = $_SESSION['user_id'];
               $login_user = $obj->find('user','id',$login_user);
               echo $login_user->username;
             ?>
          </a>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

    

         

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php 
              if ($actual_link == 'quick_sell' || $actual_link =='sell_list' || $actual_link =='sell_return_list') {echo "active";
          }else{
            echo "";
          }
            ?>">
              <i class="material-symbols-outlined">sell</i>
              <p>
                Sells
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="index.php?page=quick_sell" class="nav-link <?php echo $actual_link=='quick_sell'?'active':'';?>">
                  <!-- <i class="far fa-circle nav-icon"></i> -->
                  <p>New sell</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=sell_list" class="nav-link <?php echo $actual_link=='sell_list'?'active':'';?>">
                  <!-- <i class="fas fa-align-justify nav-icon"></i> -->
                  <p>Sell list</p>
                </a>
              </li>
             
            </ul>
          </li>

          <!-- expense sidebar menu -->

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

    </div>
    <?php require_once 'inc/member_add_modal.php'; ?>
    <?php require_once 'inc/catagory_modal.php'; ?>
    <?php require_once 'inc/suppliar_modal.php'; ?>
    <?php require_once 'inc/expense_catagory_modal.php'; ?>