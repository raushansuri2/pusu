<!DOCTYPE html>
<html lang="en">
<?php echo $this->element('head');?>
<body>
	<div class="main-wrapper">
		<div class="page-wrapper full-page" style="background:#182e81">
			<div class="page-content d-flex align-items-center justify-content-center">

				<div class="row w-100 mx-0 auth-page">
					<div class="col-md-8 col-xl-4 mx-auto">
						<div class="card">
							<div class="row">
                
                <div class="col-md-12">
                  <div class="auth-form-wrapper px-4 py-5" style="">
                  <p class="text-center"><img src="<?php echo $this->Url->build('/');?>img/logo.png" style="width:60%; margin:0 auto"></p>
                    <?php echo $this->Flash->render(); ?>
                    <form method="post" action="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'login']); ?>" enctype="multipart/form-data" accept-charset="utf-8"  class="forms-sample" style="padding: 30px 15px 15px 15px;">
					<div class="mb-3"><p class="text-center">Sign in your ERISAQuote Pro account!</p></div>
                      <div class="mb-3">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" style="padding: 17px;border-radius: 9px;">
                      </div>
                      <div class="mb-3">
                        <input type="password" class="form-control" name="password" id="userPassword" autocomplete="current-password" placeholder="Password" style="padding: 17px;border-radius: 9px;">
                      </div>
                      
                      <div>
                        <input type="submit" value="SIGN IN" class="btn" style="width: 100%;padding: 15px;font-weight: bold;text-transform: uppercase;font-size: 23px;background: #41b42b;border-color: #41b42b;border-radius: 9px;color: #fff;">
                        
                      </div> 
					
					  
                      <p class="text-center mt-4"> <a href="forgot-password.html"><span class="reset-password-link show-reset-password-form cursor" style="cursor: pointer">Forgot
                    Password? - Click Here</span></a></p>
                    </form>
                  </div>
                </div>
              </div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- core:js -->
	<script src="assets/vendors/core/core.js"></script>
	<!-- endinject -->

	<!-- Plugin js for this page -->
	<!-- End plugin js for this page -->

	<!-- inject:js -->
	<script src="assets/vendors/feather-icons/feather.min.js"></script>
	<script src="assets/js/template.js"></script>
	<!-- endinject -->

	<!-- Custom js for this page -->
	<!-- End custom js for this page -->

</body>
</html>