<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>
    </div><!-- leftpanel -->

    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-list-alt"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'Admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li><?= $this->Html->link('Settings', ['controller' => 'Settings', 'action' => 'index']); ?></li>
                        <li><?= $this->Html->link('Programs', ['controller' => 'Programs', 'action' => 'index']); ?></li>
                        <li>Add Program</li>
                    </ul>
                    <h4>Add Program</h4>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->

        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Form->create($program, ['type' => 'file', 'novalidate' => 'novalidate']); ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Add Program</h4>
                        <p>Please provide all below details to add program.</p>
                    </div><!-- panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Program Name <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('name', [
                                        'error' => ['Please enter program name'],
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => 'Enter program name',
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Program Type</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('p_type', [
                                        'class' => 'form-control',
                                        'type' => 'select',
                                        'options' => ['default' => 'Default', 'agent' => 'Agent'],
                                        'empty' => 'Select program type',
                                        'div' => false,
                                        'label' => false
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Network</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('network_id', [
                                        'class' => 'form-control',
                                        'type' => 'select',
                                        'options' => $networks,
                                        'empty' => 'Select network',
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
                                <?php echo $this->Form->submit('Add Program', ['class' => 'btn btn-primary mr5', 'div' => false, 'label' => false]); ?>
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
