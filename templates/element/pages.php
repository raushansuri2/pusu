	<title><?php echo $layoutTitle;?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
			<?php echo $this->Html->css(['style', 'menu']);?>
        <!--[if IE]><?php echo $this->Html->script('html5');?><![endif]-->
       <?php echo $this->Html->script('jquery.min');?>
							<?php echo $this->Html->css('form-element');?>
							<?php echo $this->Html->script('form-element');?>
							
							<?php echo $this->Html->css(['flexslider']);?>
							<?php echo $this->Html->script(['jquery.flexslider']);?>
        
							<script type="text/javascript">
								$(document).ready(function() {
									$('.slider1').bxSlider({
										slideWidth:370,
										minSlides:2,
										maxSlides:10,
										slideMargin:30
									});
							   
									$(".menubtn").click(function(){
										$("#nav").slideToggle("slow");
									})
								})
							</script>
					
							<!-- color box starts -->
							<?php echo $this->Html->css('colorbox');?>
							<?php echo $this->Html->script('jquery.colorbox');?>
							<script>
								$(document).ready(function () {
									//Examples of how to assign the Colorbox event to elements
									$(".group1").colorbox({rel: 'group1'});
								  
								});
							</script>
							<!-- color box ends -->
       