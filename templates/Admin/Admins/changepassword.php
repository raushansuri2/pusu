<?php use Cake\View\Helper\FormHelper; ?>
<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>                
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-lock"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']); ?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li>Admin Password</li>
                    </ul>
                    <h4>Admin Password</h4>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <?php
                echo $this->Flash->render();
                echo $this->Form->create($user, [
                    'url' => ['controller' => 'Admins', 'action' => 'changepassword'],
                    'novalidate' => 'novalidate',
                    'id' => 'changePasswordFrm'
                ]); 
                ?>

                <div class="panel panel-default">
                    <div class="panel-heading">  
                        <h4 class="panel-title">Change Admin Password</h4>
                        <p>Please provide admin old password, new password, and confirm password.</p>
                    </div><!-- panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Old Password <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php 
                                    echo $this->Form->control('old_password', [
                                        'type' => 'password',
                                        'id' => 'old_password',
                                        'class' => 'form-control',
                                        'div' => false,
                                        'label' => false,
                                        'placeholder' => 'Enter your old password', // Placeholder added
                                        'error' => ['custom' => __('You can submit up to')]
                                    ]); 
                                    ?>                      
                                </div>                                               
                            </div><!-- form-group -->
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">New Password <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php 
                                    echo $this->Form->control('password1', [
                                        'type' => 'password',
                                        'id' => 'new_password',
                                        'class' => 'form-control',
                                        'div' => false,
                                        'label' => false,
                                        'placeholder' => 'Enter your new password', // Placeholder added
                                        'error' => ['length' => __('You can submit up to')]
                                    ]); 
                                    ?>                         
                                </div>                                               
                            </div><!-- form-group -->
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Confirm Password <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php 
                                    echo $this->Form->control('password2', [
                                        'type' => 'password',
                                        'id' => 'confirm_password',
                                        'class' => 'form-control',
                                        'div' => false,
                                        'label' => false,
                                        'placeholder' => 'Re-enter your new password', // Placeholder added
                                        'error' => ['length' => __('This is not long enough')]
                                    ]); 
                                    ?>
                                </div>                                               
                            </div><!-- form-group -->
                        </div><!-- row -->
                    </div><!-- panel-body -->
                            
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-9 col-sm-offset-3">
                                <?php 
                                echo $this->Form->button('Submit', [
                                    'type' => 'submit', // Change to submit
                                    'id' => 'changePassword',
                                    'class' => 'btn btn-primary mr5',
                                    'div' => false
                                ]); 
                                ?>
                            </div>
                        </div>
                    </div><!-- panel-footer -->  
                </div><!-- panel -->
                <?php echo $this->Form->end(); ?>                                
            </div><!-- contentpanel -->
        </div><!-- contentpanel </div> mainpanel -->
</div><!-- mainwrapper -->
<?php echo $this->Html->script('admin/script/password'); ?>