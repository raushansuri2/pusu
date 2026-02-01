<section class="bg-white-new">

      <div class="container pt-0">

        <div class="section-content">

          <div class="row">

            <div class="col-md-12">

              <h4 class="text-theme-colored"><?php echo $pages->page_title ;?></h4>

              <?php echo $pages->content ;?>
	
            <?php if($this->request->params['controller'] == 'Pages' && $this->request->params['action'] == 'detail' && $this->request->params['pass'][0] == 'about-us'){ ?> 	
              <a class="btn btn-theme-colored btn-flat btn-lg mt-10 mb-sm-30" href="#">Register Now!</a> </div>
            <?php } ?>
            

          </div>

        </div>

      </div>

    </section>