<div class="panel panel-signin">
	<div class="panel-body">
		<div class="logo text-center">
			<?php echo $this->Html->image('admin/logo-black.png', ['style' => 'width:250px']);?>
		</div>
		<br />
		<h4 class="text-center mb5">Reset password</h4>
		<!--<p class="text-center">Sign in to access admin panel</p>-->
		
		<div class="mb30"></div>
		   <?php echo $this->Flash->render(); ?>
			<?php echo $this->Form->create('Admins', ['novalidate' => 'novalidate', 'id' =>'basicForm']);?>
			<div class="input-group mb15 width315" style="width: 100%">
				<?php echo $this->Form->input("password1", array("type" => "password", "div" => false, "label" => false,'placeholder'=> "Password",'class'=>'form-control', 'validate'=>true, 'required' => true, 'minlength' =>'6', 'maxlength' => 16)); ?>
                
			</div><!-- input-group -->
			
            <div class="input-group mb15 width315" style="width: 100%">
				<?php echo $this->Form->input("password2", array("type" => "password", "div" => false, "label" => false,'placeholder'=> "Confirm Password",'class'=>'form-control', 'validate'=>true, 'required' => true, 'minlength' =>'6', 'maxlength' => 16)); ?>
                
			</div><!-- input-group -->
			<div class="clearfix">
				<div class="pull-left">
					<div class="ckbox ckbox-primary mt10">
						<?php echo $this->Html->link('Login', array('controller'=>'Admins', 'action' => 'login'));?>
					</div>
				</div>
				<div class="pull-right">
					<?php echo $this->Form->submit("Reset", array("class" => "btn btn-primary mr5",'div'=>false)); ?>
					
				</div>
			</div>                      
		<?php echo $this->Form->end(); ?>
		
	</div><!-- panel-body -->
	<div class="panel-footer">
		<!--{{ HTML::link('admin/user/signup', 'Not yet a Member? Create Account Now', array('class' => 'btn btn-primary btn-block'))}}-->
		
	</div><!-- panel-footer -->
</div><!-- panel -->
