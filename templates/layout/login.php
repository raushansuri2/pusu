<!DOCTYPE html>

<html lang="en">
<?php echo $this->element('head');?>
<body>
	<div class="main-wrapper">

	<?php echo $this->element('sidebar');?>

		<!-- partial -->

		<div class="page-wrapper">

			<!-- partial:partials/_navbar.html -->
			<?php echo $this->element('header');?>
			<!-- partial -->

        <?php echo $this->fetch('content'); ?>

			<!-- partial:partials/_footer.html -->
			<footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
				<p class="text-muted mb-1 mb-md-0">Copyright © 2026 <a href="<?php echo $this->Url->build('/');?>" target="_blank">ERISAQuote Pro</a>.</p>
				<p class="text-muted">Handcrafted With <i class="mb-1 text-primary ms-1 icon-sm" data-feather="heart"></i> Evirtual Services LLC</p>
			</footer>
			<!-- partial -->

		</div>
	</div>

	<!-- core:js -->
	<script src="<?php echo $this->Url->build('/');?>vendors/core/core.js"></script>
  <script src="<?php echo $this->Url->build('/');?>vendors/flatpickr/flatpickr.min.js"></script>
  <script src="<?php echo $this->Url->build('/');?>vendors/apexcharts/apexcharts.min.js"></script>
	<script src="<?php echo $this->Url->build('/');?>vendors/feather-icons/feather.min.js"></script>
	<script src="<?php echo $this->Url->build('/');?>js/template.js"></script>
  <script src="<?php echo $this->Url->build('/');?>js/dashboard-light.js"></script>

</body>
</html>
