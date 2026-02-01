<div class="mainwrapper">
	<div class="leftpanel">
		 <?php echo $this->element('admin/sidebar');?>                 
	</div><!-- leftpanel -->
	
	<div class="mainpanel">
		<div class="pageheader">
			<div class="media">
				<div class="pageicon pull-left">
					<i class="fa fa-gears"></i>
				</div>
				<div class="media-body">
					<ul class="breadcrumb">
						<li><a href="<?php echo $this->Url->build(['controller' => 'admins', 'action' => 'dashboard']);?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
						
						<li>Global Parameters</li>
					</ul>
					<h4>Global Parameters</h4>
				</div>
			</div><!-- media -->
		</div><!-- pageheader -->
		
		<div class="contentpanel">
                        
				<div class="row">
						<?php
                        echo $this->Flash->render();
						echo $this->Form->create($globalparameters,['novalidate' => 'novalidate', 'id' => 'globalParmForm']);
						 ?>
						<div class="panel panel-default">
							<div class="panel-heading">  
								<h4 class="panel-title">Global Parameters</h4>
								<p></p>
							</div><!-- panel-heading -->
							<div class="panel-body">
								<div class="row">
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Facebook link</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('facebook',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small>(Ex: https://facebook.com)</small>
											
										</div>										 
									</div><!-- form-group -->
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Twitter link</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('twitter',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small>(Ex: https://twitter.com)</small>
											
										</div>										 
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Instagram link</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('instagram',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small>(Ex: https://instagram.com)</small>
											
										</div>										 
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Youtube link</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('youtube',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small>(Ex: https://youtube.com)</small>
											
										</div>										 
									</div>
                                    
									<div class="form-group">
										<label class="col-sm-3 control-label">Pinterest link</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('pinterest',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small>(Ex: https://pinterest.com)</small>
											
										</div>										 
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">G Pluse</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('skype',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
										</div>										 
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Phone Number</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('phoneNo',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small></small>
										</div>										 
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Email Address</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('emailAddress',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small></small>
										</div>										 
									</div>
									
									<!--<div class="form-group">
										<label class="col-sm-3 control-label">Video Url</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('videoUrl',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small></small>
										</div>										 
									</div>-->

									<div class="form-group">
										<label class="col-sm-3 control-label">Address</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('address',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small></small>
										</div>										 
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Awards Winning</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('AwardsWinning',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small></small>
										</div>										 
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Veterinarian</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('Veterinarian',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small></small>
										</div>										 
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Happy Clients</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('HappyClients',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small></small>
										</div>										 
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Service Provides</label>
										<div class="col-sm-9">
											<?php echo $this->Form->input('ServiceProvides',['templates' => ['inputContainer' => '{{content}}'], 'class'=>'form-control','placeholder'=>'','div'=>false,'label'=>false, 'type'=>'text']); ?>
											<small></small>
										</div>										 
									</div>
									
								</div><!-- row -->
							</div><!-- panel-body -->
							<div class="panel-footer">
							  <div class="row">
								<div class="col-sm-9 col-sm-offset-3">
									 <?php echo $this->Form->submit('Submit', array('class' => 'btn btn-primary mr5', 'div' => false, 'label' =>false, 'id' => 'globalParmSubmit', 'type' => 'submit')); ?>
									
								</div>
							  </div>
							</div><!-- panel-footer -->  
						</div><!-- panel -->
						<?php echo $this->Form->end();?>
					<!-- col-md-6 -->			   
				</div><!-- row-->

			</div><!-- contentpanel -->
		</div><!-- contentpanel -->
		
	</div><!-- mainpanel -->
</div><!-- mainwrapper -->