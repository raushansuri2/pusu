<?php
$states = [
    'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
    'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
    'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
    'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
    'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
    'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
    'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
    'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
    'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
    'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
    'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
    'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
    'WI' => 'Wisconsin', 'WY' => 'Wyoming'
];
?>
<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>                
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-users"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'Admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li><?= $this->Html->link('Manage Quoting Groups', ['controller' => 'Quotgroups', 'action' => 'index']); ?></li>
                        <li>Edit Quoting Group</li>
                    </ul>
                    <h4>Edit Quoting Group</h4>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Form->create($quotgroup, ['type' => 'file', 'novalidate' => 'novalidate']); ?>
                
                <div class="panel panel-default">
                    <div class="panel-heading">  
                        <h4 class="panel-title">Edit Quoting Group</h4>
                        <p>Please provide all below details to edit a quoting group.</p>
                    </div><!-- panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Group Name <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('group_name', [
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => 'Group Name',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 255
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->

                            <div class="form-group">
                                <label class="col-sm-3 control-label">SIC or NAICS Code <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('SIC_Code', [
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => 'SIC or NAICS Code',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 50
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address Line 1 <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('address1', [
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => 'Address Line 1',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 255
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address Line 2</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('address2', [
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => 'Address Line 2',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 255
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->

                            <div class="form-group">
                                <label class="col-sm-3 control-label">City <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('city', [
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => 'City',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 100
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->

                            <div class="form-group">
                                <label class="col-sm-3 control-label">State <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('state_name', [
                                        'type' => 'select',
                                        'options' => $states,
                                        'empty' => 'Select State',
                                        'class' => 'form-control',
                                        'label' => false,
                                        'div' => false,
                                        'required' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Zip <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('zip', [
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => 'Zip',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 20
                                    ]); ?>
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
