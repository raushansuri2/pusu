<div class="dashboard-left">
		<div class="thumb">
				<img src="images/photos/volunteer.jpg" alt="">
		</div>
		<h3 class="name text-theme-colored text-center mt-10 mb-0"> <?php echo $users->firstName;?> </h3>
		<h6 class="mt-5 text-center"><i class="fa fa-envelope"></i> <?php echo $users->email;?></h6>
		<h5 class="mt-5 text-center"><i class="fa fa-user"></i> 200 Members</h5>
		<a class="btn btn-theme-colored btn-flat mt-5 mb-20 text-center" style="width:100%;" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'editprofile']);?>">Edit Profile</a>
		<nav id="tg-dashboardnav" class="tg-dashboardnav">
				<ul class="dashboard-link">
						<li class="tg-active"> <a href="dashboard.html"> <i class="fa fa-line-chart"></i> <span>Dashboard</span> </a> </li>
						<li class="tg-warningmessage"> <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'editprofile']);?>"> <i class="fa fa-user"></i> <span>Edit Profile</span> </a> </li>
						<li> <a href="donate.html"> <i class="fa fa-envelope-o"></i> <span>Make Donation</span> </a> </li>
						<li> <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'changepassword']);?>"> <i class="fa fa-arrow-up"></i> <span>Change Password</span> </a> </li>
						<li> <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'logout']);?>"> <i class="fa fa-sign-out"></i> <span>Logout</span> </a> </li>
				</ul>
		</nav>
</div>