<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit User
            <small>Update user information</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="#">Users</a></li>
            <li class="active">Edit User</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit User Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form id="editUserForm">
                                <input type="hidden" id="userId" name="userId" value="<?php echo htmlspecialchars($_GET['edit_id']); ?>">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="userRole">User Role</label>
                                    <select class="form-control" id="userRole" name="userRole">
                                        <option value="admin">Admin</option>
                                        <option value="seller">Seller</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="newPassword">New Password</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Leave blank to keep current password">
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Update User</button>
                            </form>
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
<!-- /.content-wrapper -->

<style>
    @media (max-width: 767px) {
        .content-wrapper {
            padding-top: 20px;
        }
        .card-primary {
            border-top: 3px solid #007bff;
            box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 1px 2px rgba(0,0,0,.24);
        }
        .card-header {
            padding: 15px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            padding: 10px;
        }
    }
</style>
