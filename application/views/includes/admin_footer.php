<!-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> -->
<script src="<?php echo base_url('assets/js/quill.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="<?php echo base_url('assets/js/bootstrap-material-design.min.js') ?>" type="text/javascript"></script>

<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script> -->
<script src="<?php echo base_url('assets/js/perfect-scrollbar.jquery.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/perfect-scrollbar.min.js') ?>"></script>


<!--  Plugin for Sweet Alert -->
<script src="<?php echo base_url('assets/js/sweet_alert2.js'); ?>"></script>


<!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>


<!-- Chartist JS -->
 <script src="<?php echo base_url('assets/js/chartist.min.js') ?>"></script>
 <script src="<?php echo base_url('assets/js/pagination.min.js'); ?>"></script>

<!--  Notifications Plugin    -->
<script src="<?php echo base_url('assets/js/bootstrap-notify.js')?> "></script>
<script src="<?php echo base_url('assets/js/jquery.bootstrap-wizard.js')?> "></script>
<script src="<?php echo base_url('assets/js/moment.js')?> "></script>
<script src="<?php echo base_url('assets/js/bootstrap-datetimepicker.min.js')?> "></script>
<script src="<?php echo base_url('assets/js/letter_avatar.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.custom-file-input.js') ?>"></script>
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/emojify.js/1.1.0/js/emojify.min.js"></script> -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script> -->
<script src="<?php echo base_url('assets/js/jspdf.min.js')?> "></script>
<script src="<?php echo base_url('assets/js/jspdf.plugin.autotable.js')?> "></script>
<script src="<?php echo base_url('assets/js/jsPdf_Plugins.js')?> "></script>
<script src="<?php echo base_url('assets/js/index (2).js')?> "></script>
<script src="<?php echo base_url('assets/js/index.js')?> "></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<!-- <script src="<?php echo base_url('assets/js/material-dashboard.min.js?v=2.1.0')?>" type="text/javascript"></script> -->
<script src="<?php echo base_url('assets/js/fileinput.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap-selectpicker.js') ?>"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="<?php echo base_url('assets/js/material-dashboard.min.js?v=2.0.2') ?>" type="text/javascript"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<!-- test -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
<!-- <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
<script src="<?php echo base_url('assets/js/functions.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/owl.carousel.js'); ?>"></script>
<!-- <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script> -->
<!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-7834185622507344",
    enable_page_level_ads: true
  });
</script> -->
<script>
	
	$('.main-panel').perfectScrollbar('destroy');

	$(document).ready( function () {
	    $('#myTable').DataTable();
	} );

	$('.datetimepicker').datetimepicker({
		// minView: 2,
  		// format: 'YYYY-MM-DD',
	    icons: {
	        time: "fa fa-clock-o",
	        date: "fa fa-calendar",
	        up: "fa fa-chevron-up",
	        down: "fa fa-chevron-down",
	        previous: 'fa fa-chevron-left',
	        next: 'fa fa-chevron-right',
	        today: 'fa fa-screenshot',
	        clear: 'fa fa-trash',
	        close: 'fa fa-remove'
	    }
	});
</script>
</html>