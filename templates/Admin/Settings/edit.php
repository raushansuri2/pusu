<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>        
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-cog"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'Admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li><?= $this->Html->link('Settings', ['controller' => 'Settings', 'action' => 'index']); ?></li>
                        <li>Edit Settings</li>
                    </ul>
                    <h4>Edit Settings</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <div class="col-md-12">
                    <?php echo $this->Form->create($setting, ['type' => 'file', 'novalidate' => 'novalidate']); ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Edit General Settings</h4>
                            <p>Update general application settings</p>
                        </div><!-- panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Default PPO Network Discount (%) <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('default_ppo_network_discount', [
                                            'error' => ['Please enter a valid discount'],
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'max' => '100',
                                            'required' => false,
                                            'placeholder' => 'Enter default PPO network discount',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                        <small class="help-block">Default discount applied to PPO network services</small>
                                    </div>
                                </div><!-- form-group -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Manual Discretion: Specific Rates (%) <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('manual_discretion_specific_rates', [
                                            'error' => ['Please enter a valid rate'],
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'max' => '100',
                                            'required' => false,
                                            'placeholder' => 'Enter manual discretion specific rates',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                        <small class="help-block">Rates applied for manual discretion on specific services</small>
                                    </div>
                                </div><!-- form-group -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Manual Discretion: Aggregate Rates (%) <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('manual_discretion_aggregate_rates', [
                                            'error' => ['Please enter a valid rate'],
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'max' => '100',
                                            'required' => false,
                                            'placeholder' => 'Enter manual discretion aggregate rates',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                        <small class="help-block">Rates applied for manual discretion on aggregate services</small>
                                    </div>
                                </div><!-- form-group -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Minimum Experience RTM: Specific Rates (%) <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('minimum_experience_rtm_specific_rates', [
                                            'error' => ['Please enter a valid rate'],
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'max' => '100',
                                            'required' => false,
                                            'placeholder' => 'Enter minimum experience RTM specific rates',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                        <small class="help-block">Rates for minimum experience RTM specific services</small>
                                    </div>
                                </div><!-- form-group -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Minimum Experience RTM: Aggregate Factors (%) <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('minimum_experience_rtm_aggregate_factors', [
                                            'error' => ['Please enter a valid factor'],
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'max' => '100',
                                            'required' => false,
                                            'placeholder' => 'Enter minimum experience RTM aggregate factors',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                        <small class="help-block">Factors for minimum experience RTM aggregate services</small>
                                    </div>
                                </div><!-- form-group -->
                            </div><!-- row -->
                        </div><!-- panel-body -->
                        
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <?php echo $this->Form->submit('Update Settings', ['class' => 'btn btn-primary mr5', 'div' => false, 'label' => false]); ?>
                                    <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-default']); ?>
                                </div>
                            </div>
                        </div><!-- panel-footer -->  
                    </div><!-- panel -->
                    <?php echo $this->Form->end(); ?>                                
                </div><!-- row -->    
            </div><!-- contentpanel -->
        </div><!-- mainpanel -->
    </div><!-- mainwrapper -->
