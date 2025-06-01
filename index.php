<?php require_once 'inc/header.php'; ?>
<?php if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    require_once 'user/sidebar.php';
    ?>
<?php } else { ?>
    <?php require_once 'inc/sidebar.php'; ?>
<?php } ?>


<!-- Content Wrapper. Contains page content -->
<?php 
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
    // Non-admin users navigate based on 'page' or default to profile.php
    $page = isset($_GET['page']) ? 'pages/' . $_GET['page'] . '.php' : 'pages/profil.php';
} else {
    // Admin users navigate based on 'page' or default to dashboard.php
    $page = isset($_GET['page']) ? 'pages/' . $_GET['page'] . '.php' : 'pages/dashboard.php';
}

// Check if the page file exists before including
if (file_exists($page)) {
    require_once $page; 
} else {
    require_once 'pages/error_page.php';
}
?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<?php require_once 'inc/footer.php'; ?>



