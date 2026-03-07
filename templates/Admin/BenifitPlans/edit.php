<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>
    </div><!-- leftpanel -->

    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-heartbeat"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'Admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li><?= $this->Html->link('Settings', ['controller' => 'Settings', 'action' => 'index']); ?></li>
                        <li><?= $this->Html->link('Benefit Plans', ['controller' => 'BenifitPlans', 'action' => 'index']); ?></li>
                        <li>Edit Benefit Plan</li>
                    </ul>
                    <h4>Edit Benefit Plan</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->

        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Form->create($benifitPlan, ['type' => 'file', 'novalidate' => 'novalidate']); ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Edit Benefit Plan</h4>
                        <p>Update benefit plan details below.</p>
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
                                    <?php echo $this->Form->input('program_id', [
                                        'class' => 'form-control',
                                        'type' => 'select',
                                        'options' => $programs,
                                        'multiple' => 'multiple',
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Deductible In <span class="asterisk">*</span></label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('deductible_in', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'placeholder' => '0.00',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Deductible Out <span class="asterisk">*</span></label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('deductible_out', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'placeholder' => '0.00',
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
                                    <label class="col-sm-6 control-label">Coinsurance In(%) <span class="asterisk">*</span></label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('coinsurance_in', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.1',
                                            'min' => '0',
                                            'max' => '100',
                                            'placeholder' => '80',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Coinsurance Out(%) <span class="asterisk">*</span></label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('coinsurance_out', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.1',
                                            'min' => '0',
                                            'max' => '100',
                                            'placeholder' => '60',
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
                                    <label class="col-sm-6 control-label">OOP Max In <span class="asterisk">*</span></label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('oop_maximum_in', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'placeholder' => '2000.00',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">OOP Max Out <span class="asterisk">*</span></label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('oop_maximum_out', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'placeholder' => '4000.00',
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
                                    <label class="col-sm-6 control-label">RX Copay Generic</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('rx_copay_generic', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'placeholder' => '10.00',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">RX Copay Formulary</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('rx_copay_formulary', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'placeholder' => '25.00',
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
                                    <label class="col-sm-6 control-label">RX Copay Non-Formulary</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('rx_copay_non_formulary', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'placeholder' => '50.00',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">RX Copay Specialty</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('rx_copay_specialty', [
                                            'class' => 'form-control',
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'min' => '0',
                                            'placeholder' => '200.00',
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
                                    <label class="col-sm-6 control-label">OOP Includes Deductible In</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('oop_includes_deductible_in', [
                                            'class' => 'form-control',
                                            'type' => 'select',
                                            'options' => [1 => 'Yes', 0 => 'No'],
                                            'empty' => 'Select',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">OOP Includes Deductible Out</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('oop_includes_deductible_out', [
                                            'class' => 'form-control',
                                            'type' => 'select',
                                            'options' => [1 => 'Yes', 0 => 'No'],
                                            'empty' => 'Select',
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
                                    <label class="col-sm-6 control-label">RX Covers Specialty</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('rx_covers_specialty', [
                                            'class' => 'form-control',
                                            'type' => 'select',
                                            'options' => [1 => 'Yes', 0 => 'No'],
                                            'empty' => 'Select',
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
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
                                <?php echo $this->Form->submit('Update Benefit Plan', ['class' => 'btn btn-primary mr5', 'div' => false, 'label' => false]); ?>
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
