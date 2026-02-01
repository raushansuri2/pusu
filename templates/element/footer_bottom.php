			<script src="<?php echo $this->Url->build('/');?>assets/js/jquery.min.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/bootstrap.min.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/bootsnav.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/bootstrap-select.min.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/bootstrap-touch-slider-min.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/jquery.touchSwipe.min.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/chosen.jquery.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/datedropper.min.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/dropzone.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/jquery.counterup.min.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/jquery.fancybox.js"></script>
			<!--<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/jquery.nice-select.js"></script>-->
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/jqueryadd-count.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/jquery-rating.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/slick.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/timedropper.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/waypoints.min.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/js/jQuery.style.switcher.js"></script>
			<script src="<?php echo $this->Url->build('/');?>assets/js/custom.js"></script>
			
			
			<script>
				this.$('.TIme').timeDropper({
					setCurrentTime: false,
					meridians: true,
					primaryColor: "#e8212a",
					borderColor: "#e8212a",
					//minutesInterval: '15',
					//interval: '15 min',
					init_animation: "fadein",
					autoswitch:false
				});
			</script>
			<script>
				$('.droperDate').dateDropper({
						format: 'Y-m-d',
						Default: false
				});
				//$('.droperDate').dateDropper();
			</script>
<style>
	.ajaxloader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		opacity: 0.7;
		background: url('<?php echo $this->Url->build('/');?>img/admin/fancybox_loading@2x.gif') 50% 50% no-repeat rgb(249,249,249);
	}
</style>
			<div class="ajaxloader" style="display: none;"></div>