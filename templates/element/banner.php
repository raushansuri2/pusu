	<?php
	echo $this->Html->css(array('admin/jquery.fancybox'));
	echo $this->Html->script(array('admin/jquery.fancybox.pack'));
?>
	<div class="banner-box width2">
                <a href="#" class="next-arrow" style="display: block;">&nbsp;</a>
                <a href="#" class="prev-arrow" style="display: none;">&nbsp;</a>
                <div class="slider-inner" style="width: 2698px; margin-left: 0px;">
                    <div class="slide-div1" style="width: 1349px;">            
                        <div class="slider-wrapper">
                        <div class="responisve-container">
                        <div class="slider" style="max-width: 1000px;"><div class="fraction-slider" style="overflow: visible; width: auto; height: auto;">
                        
                        <div class="slide active-slide">
                           
																
																<img src="http://localhost/gunjanjain/img/banner-book.png" data-position="120,705" width="349" height="341" data-in="bottomLeft" data-delay="500" data-out="right">

                                        <img src="<?php echo $this->Url->build('/');?>img/top-logo.png" data-position="30,265" width="521" height="145" data-in="left" data-delay="200" data-out="right">

                                        <img src="<?php echo $this->Url->build('/');?>img/gunjan-jain-banner-pic.png" width="444" height="491" data-position="0,-122" data-in="bottomRight" data-delay="200">

                                        <img src="<?php echo $this->Url->build('/');?>img/meet-gunjan-tag.png" data-position="303,180" width="202" height="146" data-in="bottom" data-delay="800" data-out="fade">
<!--
                                        <img src="images/banner-quote-text.png" data-position="241,322" width="428" height="126" data-in="fade" data-delay="800" data-out="fade">-->

                                   <img src="<?php echo $this->Url->build('/');?>img/banner-arundhati.png" data-position="376,442" width="228" height="32" data-in="bottom" data-delay="800" data-out="fade">    
		
																
																
                        </div>
                        <a href="#" class="prev"></a><a href="#" class="next"></a><div class="fs-pager-wrapper"><a rel="0" href="#" class="active"></a></div><div class="fs-stretcher" style="width:1000px; height:400px"></div></div></div>
                        </div>
                        </div>
                        
                        <div id="bxslider" style="opacity: 1;">
                        <div class="bx-wrapper" style="max-width: 100%;"><div class="bx-viewport" style="width: 100%; overflow: hidden; position: relative; height: 216px;">
	         <ul class="bxslider" style="width: 815%; position: relative; transition-duration: 0.5s; transform: translate3d(-1520px, 0px, 0px);">
		<?php foreach($testimonials as $key=>$testimonial){
		    if($key==0 || $key == count($testimonials)-1){?>
		<li style="float: left; list-style: none; position: relative; width: 380px;" class="bx-clone">		
                            <p><span class="quote-top">&nbsp;</span><?php echo $testimonial->text;?><span class="quote-bottom">&nbsp;</span></p>
                            <h3><?php echo $testimonial->name;?><span><?php echo $testimonial->designation;?></span></h3>	
                            </li>
		<?php }else{ ?>
                            <li style="float: left; list-style: none; position: relative; width: 380px;">		
                            <p><span class="quote-top">&nbsp;</span><?php echo $testimonial->text;?><span class="quote-bottom">&nbsp;</span></p>
                            <h3><?php echo $testimonial->name;?><span><?php echo $testimonial->designation;?></span></h3>	
                            </li>
                            <?php }
	             } ?>
                            
	         </ul>
	         </div>
                            <div class="bx-controls bx-has-pager bx-has-controls-direction">
                         
                            </div></div>
                        </div>
                    </div>
                        
                    <div class="slide-div2" style="width: 1349px;">
                    <a class='readmore' data-fancybox-type='iframe' href="<?php echo $this->Url->build(['controller' => 'Pages', 'action' => 'video']);?>" data-toggle="tooltip" class="delete-row tooltips">
		<?php echo $this->Html->image('traillerVideo.jpg');?>
                    </a>
                    </div>
                </div>
                </div>

<script type="text/javascript">
$(document).ready(function(){	
	$(".readmore").fancybox({
		maxWidth	: 600,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});	
	
});
</script>
