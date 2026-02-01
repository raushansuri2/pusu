<section class="bg-white-new">

      <div class="container pt-0">

         <div class="row">
      <?php $i=0;
      foreach($testimonials as $key=>$testimonial){
            $i++; ?>
            <div class="col-md-6">

              <blockquote class="<?php echo ($i%2 ==0) ? 'gray theme-colored' : 'gray';?>">
               <?php
		      $bulkImage = WWW_ROOT . 'img/uploads/testimonials/thumb/'.trim($testimonial->testimonialsimage);
                  $testimonial_src = $this->Url->build('/', true).'img/uploads/no-image-100x100.jpg';
                  if(trim($testimonial->testimonialsimage)!='' && file_exists($bulkImage)) {
                      $testimonial_src = $this->Url->build('/') . 'img/uploads/testimonials/thumb/' . trim($testimonial->testimonialsimage); 
                  }
		   ?>
                <img src="<?php echo $testimonial_src;?>" width="75" align="left" class="img-circle-new">
                <p><?php echo $testimonial->text;?></p>

                <footer><?php echo $testimonial->name;?> <cite title="Source Title"><?php echo $testimonial->designation;?></cite></footer>

              </blockquote>

            </div>
      <?php
      }
      ?>
         </div>
      </div>
</section>