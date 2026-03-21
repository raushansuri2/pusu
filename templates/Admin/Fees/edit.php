<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>
    </div><!-- leftpanel -->

    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-dollar"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'Admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li><?= $this->Html->link('Settings', ['controller' => 'Settings', 'action' => 'index']); ?></li>
                        <li><?= $this->Html->link('Fees', ['controller' => 'Fees', 'action' => 'index']); ?></li>
                        <li>Edit Fee</li>
                    </ul>
                    <h4>Edit Fee</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->

        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Form->create($fee, ['type' => 'file', 'novalidate' => 'novalidate']); ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Edit Fee</h4>
                        <p>Update fee details below.</p>
                    </div><!-- panel-heading -->
                    <div class="panel-body">
                        <?php $selectedPrograms = !empty($fee->program_id) ? array_filter(array_map('trim', explode(',', (string)$fee->program_id))) : []; ?>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Fee Name <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('name', [
                                        'error' => ['Please enter fee name'],
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => 'Enter fee name',
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Value <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('value', [
                                        'error' => ['Please enter fee value'],
                                        'class' => 'form-control',
                                        'type' => 'number',
                                        'step' => '0.01',
                                        'required' => false,
                                        'placeholder' => 'Enter fee value',
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Value Type</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('value_type', [
                                        'class' => 'form-control',
                                        'type' => 'select',
                                        'options' => ['flat' => 'Flat', 'percentage' => 'Percentage'],
                                        'empty' => 'Select value type',
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Program</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('program_id', [
                                        'class' => 'form-control',
                                        'type' => 'select',
                                        'options' => $programs,
                                        'multiple' => true,
                                        'name' => 'program_id[]',
                                        'value' => $selectedPrograms,
                                        //'empty' => 'Select program',
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('status', [
                                        'class' => 'form-control',
                                        'type' => 'select',
                                        'options' => $statusOptions,
                                        'empty' => false,
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Is Editable</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('is_editable', [
                                        'type' => 'checkbox',
                                        'hiddenField' => true,
                                        'value' => 1,
                                        'uncheckValue' => 0,
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Applied to Premium</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('is_applied_to_premium', [
                                        'type' => 'checkbox',
                                        'hiddenField' => true,
                                        'value' => 1,
                                        'uncheckValue' => 0,
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->
                    </div><!-- panel-body -->

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-9 col-sm-offset-3">
                                <?php echo $this->Form->submit('Update Fee', ['class' => 'btn btn-primary mr5', 'div' => false, 'label' => false]); ?>
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
