<?php
$home = $about = $contact = $learn = '';
$request = $this->getRequest(); // Get the request object

if ($request->getParam('controller') === 'Pages' && $request->getParam('action') === 'home') {
    $home = 'active';
}
if ($request->getParam('controller') === 'Pages' && $request->getParam('action') === 'aboutus') {
    $about = 'active';
}
if ($request->getParam('controller') === 'Pages' && $request->getParam('action') === 'contactus') {
    $contact = 'active';
}
if ($request->getParam('controller') === 'Pages' && $request->getParam('action') === 'elearn') {
    $learn = 'active';
}
?>


<!-- partial:partials/_navbar.html -->
			<nav class="navbar">
				<a href="#" class="sidebar-toggler">
					<i data-feather="menu"></i>
				</a>
				<div class="navbar-content">

					<ul class="navbar-nav">


						<li class="nav-item dropdown">
							<a class="nav-link" href="#">
								<i data-feather="help-circle"></i>
								<div class="indicator">
									<div class="circle"></div>
								</div>
							</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<img class="wd-30 ht-30 rounded-circle" src="<?php echo $this->Url->build('/');?>img/user.png" alt="profile"> <?php echo $this->request->getSession()->read('ERISAQuoteProSession.Users.firstName');?> <?php echo $this->request->getSession()->read('ERISAQuoteProSession.Users.lastName');?>
							</a>
							<div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
								<div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
									<div class="mb-3">
										<img class="wd-80 ht-80 rounded-circle" src="<?php echo $this->Url->build('/');?>img/user.png" alt="">
									</div>
									<div class="text-center">
										<p class="tx-16 fw-bolder"><?php echo $this->request->getSession()->read('ERISAQuoteProSession.Users.firstName');?> </p>
										<p class="tx-12 text-muted"><?php echo $this->request->getSession()->read('ERISAQuoteProSession.Users.email');?></p>
									</div>
								</div>
                <ul class="list-unstyled p-1">
                  <li class="dropdown-item py-2">
                    <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'editprofile']); ?>" class="text-body ms-0">
                      <i class="me-2 icon-md" data-feather="user"></i>
                      <span>Profile</span>
                    </a>
                  </li>

                  <li class="dropdown-item py-2">
                    <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'logout']); ?>" class="text-body ms-0">
                      <i class="me-2 icon-md" data-feather="log-out"></i>
                      <span>Log Out</span>
                    </a>
                  </li>
                </ul>
							</div>
						</li>
					</ul>
				</div>
			</nav>
			<!-- partial -->
