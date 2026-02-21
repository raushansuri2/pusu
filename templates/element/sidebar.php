	<nav class="sidebar">
      <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
         <img src="<?php echo $this->Url->build('/');?>img/logo.png">
        </a>
        <div class="sidebar-toggler not-active">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <div class="sidebar-body">
        <ul class="nav">
          <li class="nav-item nav-category">Home</li>
          <li class="nav-item">
            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']); ?>" class="nav-link">
              <i class="link-icon" data-feather="box"></i>
              <span class="link-title">Dashboard</span>
            </a>
          </li>

          <li class="nav-item nav-category">Quotes Manager</li>

          <li class="nav-item">
            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'quotingRequest']); ?>" class="nav-link">
              <i class="link-icon" data-feather="message-square"></i>
              <span class="link-title">Quote Requests</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'programChoose']); ?>" class="nav-link">
              <i class="link-icon" data-feather="plus-square"></i>
              <span class="link-title">Add Quote Requests</span>
            </a>
          </li>

		  <li class="nav-item nav-category">Groups</li>

          <li class="nav-item">
            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'group']); ?>" class="nav-link">
              <i class="link-icon" data-feather="users"></i>
              <span class="link-title">Groups</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupAdd']); ?>" class="nav-link">
              <i class="link-icon" data-feather="plus-square"></i>
              <span class="link-title">Add New Groups</span>
            </a>
          </li>

		   <li class="nav-item nav-category">Account</li>

          <li class="nav-item">
            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'editprofile']); ?>" class="nav-link">
              <i class="link-icon" data-feather="user"></i>
              <span class="link-title">My Account</span>
            </a>
          </li>
		  <li class="nav-item">
            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'changepassword']); ?>" class="nav-link">
              <i class="link-icon" data-feather="unlock"></i>
              <span class="link-title">Change Password</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'logout']); ?>" class="nav-link">
              <i class="link-icon" data-feather="log-out"></i>
              <span class="link-title">Logout</span>
            </a>
          </li>



        </ul>
      </div>
    </nav>
