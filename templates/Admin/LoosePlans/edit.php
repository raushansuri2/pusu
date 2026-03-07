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
                        <li><?= $this->Html->link('Loose Plans', ['controller' => 'LoosePlans', 'action' => 'index']); ?></li>
                        <li>Edit Loose Plan</li>
                    </ul>
                    <h4>Edit Loose Plan</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->

        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Form->create($loosePlan, ['type' => 'file', 'novalidate' => 'novalidate']); ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Edit Loose Plan</h4>
                        <p>Update loose plan details below.</p>
                    </div><!-- panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Plan Name <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('plan_name', [
                                        'error' => ['Please enter plan name'],
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => 'Enter plan name',
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->
                        
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Programs</label>
                                <div class="col-sm-9">
                                    <?php 
                                    // Convert comma-separated program IDs to array for pre-selection
                                    $selectedPrograms = [];
                                    if (!empty($loosePlan->program_id)) {
                                        $selectedPrograms = explode(',', $loosePlan->program_id);
                                    }
                                    
                                    echo $this->Form->input('program_id', [
                                        'class' => 'form-control',
                                        'type' => 'select',
                                        'options' => $programs,
                                        'multiple' => 'multiple',
                                        'value' => $selectedPrograms,
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->
                        
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Spec Deductible</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('Spec_Deductible', [
                                        'class' => 'form-control',
                                        'type' => 'number',
                                        'placeholder' => '0',
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Spec Contract</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('Spec_Contract', [
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'placeholder' => 'Enter Spec Contract',
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Aggregating Spec Deductible</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('Aggregating_Spec_Deductible', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'placeholder' => '0',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Agg Contract</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('Agg_Contract', [
                                            'class' => 'form-control',
                                            'type' => 'text',
                                            'placeholder' => '12/12',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Agg Corridor</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('Agg_Corridor', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.1',
                                            'min' => '0',
                                            'max' => '100',
                                            'placeholder' => '0',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Commission(%)</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('Commission', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row -->

                       

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Status</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('status', [
                                            'class' => 'form-control',
                                            'type' => 'select',
                                            'options' => [1 => 'Active', 0 => 'Inactive'],
                                            'empty' => 'Select status',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row -->
                    </div><!-- panel-body -->

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-9 col-sm-offset-3">
                                <?php echo $this->Form->submit('Update Loose Plan', ['class' => 'btn btn-primary mr5', 'div' => false, 'label' => false]); ?>
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
