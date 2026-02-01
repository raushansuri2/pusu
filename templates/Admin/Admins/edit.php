<div class="mainwrapper">
    <div class="leftpanel">
        <?= $this->element('admin/sidebar'); ?>                
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-pencil"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']); ?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li>Admin Details</li>
                    </ul>
                    <h4>Admin Details</h4>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">                        
            <div class="row">
                <?= $this->Flash->render(); ?>
                <?= $this->Form->create($user, ['type' => 'file', 'novalidate' => 'novalidate', 'id' => 'editAdminFrm']); ?>
                <?= $this->Form->hidden('role_id', ['value' => 1]); ?>
                
                <div class="panel panel-default">
                    <div class="panel-heading">  
                        <h4 class="panel-title">Edit Admin Details</h4>
                        <p>Please provide admin username, email, and profile picture.</p>
                    </div><!-- panel-heading -->
                    <div class="panel-body">
                        <div class="row">                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Username <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?= $this->Form->control('username', [
                                        'class' => 'form-control', 
                                        'placeholder' => '', 
                                        'id' => 'username', 
                                        'label' => false
                                    ]); ?>
                                </div>                                               
                            </div><!-- form-group -->
                                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email <span class="asterisk">*</span>
                                    <br><small>(Note: Notifications email)</small>
                                </label>
                                <div class="col-sm-9">
                                    <?= $this->Form->control('email', [
                                        'class' => 'form-control', 
                                        'placeholder' => '', 
                                        'id' => 'email', 
                                        'label' => false
                                    ]); ?>
                                </div>                                               
                            </div><!-- form-group -->
                        
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Profile Picture </label>                          
                                <div class="col-sm-9">
                                    <?php
                                    $bulkImage = WWW_ROOT . 'img/uploads/users/admin/' . trim($user->profile_picture);
                                    $img_src = $this->Url->build('/') . 'img/uploads/users/admin/no-image-100x100.jpg';
                                    if (trim($user->profile_picture) != '' && file_exists($bulkImage)) {
                                        $img_src = $this->Url->build('/') . 'img/uploads/users/admin/' . trim($user->profile_picture); 
                                    }
                                    if ($img_src != '') { ?>
                                        <img src="<?= $img_src; ?>" height="100" width="100" /><br>
                                    <?php } ?><br>
                                    <?= $this->Form->control('profile_picture', [
                                        'type' => 'file', 
                                        'id' => 'profilePicture',
                                        'label' => false
                                    ]); ?>
                                    <small>(Note: Image type jpg, jpeg, png only)</small>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- form-group -->
                    </div><!-- row -->
                </div><!-- panel-body -->
                                    
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-9 col-sm-offset-3">
                            <?= $this->Form->button('Submit', [
                                'type' => 'submit', 
                                'id' => 'editAdmin', 
                                'class' => 'btn btn-primary mr5',  
                                'label' => false
                            ]); ?>
                        </div>
                    </div>
                </div><!-- panel-footer -->  
            </div><!-- panel -->
                <?= $this->Form->end(); ?>                                
            </div><!-- row-->    
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->
<?= $this->Html->script('admin/script/admin'); ?>