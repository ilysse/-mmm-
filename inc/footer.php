 <!-- Main Footer -->
 
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<!-- <script src="dist/js/demo.js"></script> -->

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- Datatable JS -->
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- datepicker js  -->
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Show the 'page' element and hide the 'loading' element
    document.getElementById('page').style.display = 'block';
    document.getElementById('loading').style.display = 'none';
});
</script>

<!-- select 2 js  -->
<script src="plugins/select2/js/select2.min.js"></script>
<!-- PAGE SCRIPTS -->
<script src="assets/js/custom.js"></script>
<script src="assets/js/ajax_req.js"></script>
<script src="assets/js/buy_product.js"></script>

<script>
  $(document).ready(function(){
    $('.dropdown-submenu a.test').on("click", function(e){
      $(this).next('ul').toggle();
      e.stopPropagation();
      e.preventDefault();
    });
  });
</script>
<script>
   $(document).ready(function($) {
     $('.select2').select2();
   });
</script>

<!-- datepicker -->
<script>
 $('.datepicker').datepicker();
</script>


<!-- google transelate -->



</body>
</html>
