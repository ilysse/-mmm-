<div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid mt-5">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="m-0 text-dark">User Management</h1>
                    </div>
                    <div class="col-md-6 mt-3">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><b>All Users</b></h3>
                        <a href="index.php?page=add_user" class="btn btn-primary rounded-0 float-right">Add User</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="userdata" class="display dataTable text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>User Role</th>
                                        <th>Added Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Assuming you have a function to populate your table with user data
    // You might be using DataTables or another library, ensure this runs after the table is populated

    const table = document.getElementById('userdata');

    // Listen for double-click events on table rows
    table.addEventListener('dblclick', function(event) {
        const target = event.target.closest('tr'); // Get the closest <tr> to the clicked element
        if (target) {
            const userId = target.cells[0].textContent; // Assuming ID is in the first cell
            // Redirect to another page based on user ID
            window.location.href = `index.php?page=profil&user_id=${userId}`;
        }
    });
});

</script>