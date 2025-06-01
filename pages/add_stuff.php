<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Registration</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User Registration</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Register New User</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="registrationForm">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                            <!-- Other form fields here -->
                            <div class="form-group">
                                <label class="mb-2 tag"><strong>Username</strong></label>
                                <input type="text" name="username" id="username" placeholder="Enter your username"
                                    class="form-control input" required />
                            </div>
                            <div class="form-group">
                                <label class="mb-2 tag"><strong>Password</strong></label>
                                <input type="password" name="password" id="password" placeholder="Enter your password"
                                    class="form-control input" required />
                            </div>
                            <div class="form-group">
                                <label class="mb-2 tag"><strong>Confirm Password</strong></label>
                                <input type="password" name="confirmPassword" id="confirmPassword"
                                    placeholder="Confirm your password" class="form-control input" required />
                            </div>
                            <div class="form-group">
                                <label class="mb-2 tag"><strong>User Role</strong></label>
                                <select name="user_role" id="user_role" class="form-control input" required>
                                    <option value="seller">Seller</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" id="submitButton"
                                    class="btn btn-primary btn-block">Register</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->