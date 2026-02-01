<div class="headerwrapper">
	<div class="header-left">
		<a href="<?php echo $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']); ?>" class="logo">
			<?php echo $this->Html->image('admin/logo.png', array('style' => 'width:100px;height: 30px;'));?>
		</a>
		<div class="pull-right">
			<a href="" class="menu-collapse">
				<i class="fa fa-bars"></i>
			</a>
		</div>
	</div><!-- header-left -->
	
	<div class="header-right">
		
		<div class="pull-right">
			
			<div class="btn-group btn-group-option">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				  <i class="fa fa-caret-down"></i>
				</button>
				<ul class="dropdown-menu pull-right" role="menu">
					<li><a href="<?php echo $this->Url->build(['controller' => 'Admins', 'action' => 'edit']); ?>"><i class="glyphicon glyphicon-user"></i> Change Admin details</a></li>
					<li><a href="<?php echo $this->Url->build(array('controller' => 'admins', 'action' => 'changepassword')); ?>"><i class="glyphicon glyphicon-star"></i> Change password</a></li>
				  <li><a href="<?php echo $this->Url->build(array('controller' => 'admins', 'action' => 'logout')); ?>"><i class="glyphicon glyphicon-log-out"></i>Sign Out</a></li>
				</ul>
			</div><!-- btn-group -->
			
		</div><!-- pull-right -->
		
	</div><!-- header-right -->
	
</div><!-- headerwrapper -->
