<!DOCTYPE html>
<html class="no-js" lang="en">
<?php echo $this->element('head');?>

	<body class="home-2">
		<div class="wrapper">  
        
			<!-- Start Navigation -->
			<?php echo $this->element('header');?>
			<!-- End Navigation -->
			<div class="clearfix"></div>
			
			<!-- Main Banner Section Start -->
			
			<?php echo $this->element('breadcrum'); ?>
			<div class="clearfix"></div>
			<!-- Main Banner Section End -->
            
            
			

            
       
      <section>
				<div class="container">
      
				<?php echo $this->fetch('content'); ?>        
				</div>
			</section>
            
            
			
			<!-- ================ Start Footer ======================= -->
			<?php echo $this->element('footer');?>		
			
			<!-- ================ End Footer Section ======================= -->
			
			<!-- ================== Login & Sign Up Window ================== -->
			
            
			<a id="back2Top" class="theme-bg" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>

			
			<!-- START JAVASCRIPT -->
			<?php echo $this->element('footer_bottom');?>
			
			<script>
				function openRightMenu() {
					document.getElementById("rightMenu").style.display = "block";
				}
				function closeRightMenu() {
					document.getElementById("rightMenu").style.display = "none";
				}
			</script>
			
			<script>
				$(document).ready(function(){
					$('[data-toggle="tooltip"]').tooltip();   
				});
			</script>
			
			<script type="text/javascript">
				  $(document).ready(function() {
				  $('select').niceSelect();
				});	
			</script>
			
		</div>
	</body>
</html>