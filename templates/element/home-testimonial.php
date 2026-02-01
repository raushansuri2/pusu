      <div class="owl-carousel-1col" data-dots="true">
	<?php
	foreach($testimonials as $testimonial){ ?>
                 <div class="item">
	      <div class="testimonial-wrapper text-center">
	           <div class="thumb">
		<?php
		      $bulkImage = WWW_ROOT . 'img/uploads/testimonials/thumb/'.trim($testimonial->testimonialsimage);
                                    $img_src = $this->Url->build('/', true).'img/uploads/no-image-100x100.jpg';
                                    if(trim($testimonial->testimonialsimage)!='' && file_exists($bulkImage)) {
                                        $img_src = $this->Url->build('/') . 'img/uploads/testimonials/thumb/' . trim($testimonial->testimonialsimage); 
                                    }
		?>
		<img class="img-circle" alt="" src="<?php echo $img_src;?>" />
	           </div>

                          <div class="content pt-10">
                              <p class="font-15 text-white"><em><?php echo $testimonial->text;?></em></p>
                              <i class="fa fa-quote-right font-36 mt-10 text-gray-lightgray"></i>
                              <h4 class="author text-theme-colored-new mb-0"><?php echo $testimonial->name;?></h4>
                              <h6 class="title text-gray mt-0 mb-15"><?php echo $testimonial->designation;?></h6>
                          </div>
                    </div>

                 </div>
	<?php
	
	}
	
	?>
                       
      </div>