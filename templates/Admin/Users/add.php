<?php echo $this->Html->script('ckeditor/ckeditor'); ?>
<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>                
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-question-circle"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'Admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li><?= $this->Html->link('Manage Member', ['controller' => 'Users', 'action' => 'index']); ?></li>
                        <li>Add Member</li>
                    </ul>
                    <h4>Add Member</h4>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Form->create($users, ['type' => 'file', 'novalidate' => 'novalidate']); ?>
                
                <div class="panel panel-default">
                    <div class="panel-heading">  
                        <h4 class="panel-title">Add Member</h4>
                        <p>Please provide all below details to add Member.</p>
                    </div><!-- panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Member Name <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->control('firstName', [
                                        'error' => ['minLength' => __('You can submit up to ')],
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => '',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 200
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Member Email <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->control('email', [
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => '',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 200,
                                        'data-placeholder' => 'Choose One',
                                    ]);?>
                                </div>
                            </div><!-- form-group -->

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Member Password <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->control('password', [
                                        'error' => ['minLength' => __('You can submit up to ')],
                                        'class' => 'form-control',
                                        'type' => 'password',
                                        'required' => false,
                                        'placeholder' => '',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 200
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Member Password <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->control('confirm_password', [
                                        'error' => ['minLength' => __('You can submit up to ')],
                                        'class' => 'form-control',
                                        'type' => 'password',
                                        'required' => false,
                                        'placeholder' => '',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 200
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Member Phone <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('contactNumber', [
                                        'error' => ['minLength' => __('You can submit up to ')],
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => '',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 200
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Member Date Of Birth</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('dob', [
                                        'error' => ['minLength' => __('You can submit up to ')],
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => '',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 200
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->


                            <div class="form-group">
                                <label class="col-sm-3 control-label">Member Address <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('address', [
                                        'error' => ['minLength' => __('You can submit up to ')],
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => '',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 500
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Gender</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->control('gender', [
                                        'type' => 'select',
                                        'options' => ['Male' => 'Male', 'Female' => 'Female'],
                                        'class' => 'form-control',
                                        'empty'=>'Select Gender',
                                        'label' => false,
                                        'div' => false,
                                        'required' => false
                                    ]); ?>
                                    <label class="error" for="status"></label> 
                                </div>                                                 
                            </div><!-- form-group --> 
                            
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('status', [
                                        'type' => 'select',
                                        'options' => [1 => 'Active', 0 => 'Inactive'],
                                        'class' => 'form-control',
                                        'label' => false,
                                        'div' => false,
                                        'required' => true
                                    ]); ?>
                                    <label class="error" for="status"></label> 
                                </div>                                                 
                            </div><!-- form-group -->               
                        </div><!-- row -->
                    </div><!-- panel-body -->
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-9 col-sm-offset-3">
                                <?php echo $this->Form->submit('Submit', ['class' => 'btn btn-primary mr5', 'div' => false, 'label' => false]); ?>
                            </div>
                        </div>
                    </div><!-- panel-footer -->  
                </div><!-- panel -->
                <?php echo $this->Form->end(); ?>                                
            </div><!-- row-->    
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->

<script>
jQuery(document).ready(function(){
    CKEDITOR.replace('answer');
});
</script>