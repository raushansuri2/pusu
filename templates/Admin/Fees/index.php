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
                        <li>Manage Fees</li>
                    </ul>
                    <h4>Manage Fees</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->

        <div class="contentpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Fees List</h4>
                            <p>Manage all fees in the system</p>
                            <div class="panel-btn">
                                <?= $this->Html->link('<i class="fa fa-plus"></i> Add Fee', ['action' => 'add'], ['class' => 'btn btn-primary', 'escape' => false]) ?>
                            </div>
                        </div><!-- panel-heading -->
                        <div class="panel-body">
                            <?php if ($fees->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Value</th>
                                                <th>Value Type</th>
                                                <th>Program</th>
                                                <th>Editable</th>
                                                <th>Premium Applied</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($fees as $fee): ?>
                                                <tr>
                                                    <td><?= $fee->id ?></td>
                                                    <td><?= h($fee->name) ?></td>
                                                    <td><?= $fee->value ?></td>
                                                    <td><?= h($fee->value_type) ?></td>
                                                    <td><?= h($fee->program->name ?? 'No Program') ?></td>
                                                    <td>
                                                        <?php if ($fee->is_editable): ?>
                                                            <span class="label label-success">Yes</span>
                                                        <?php else: ?>
                                                            <span class="label label-default">No</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($fee->is_applied_to_premium): ?>
                                                            <span class="label label-warning">Yes</span>
                                                        <?php else: ?>
                                                            <span class="label label-default">No</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $fee->id], ['class' => 'btn btn-sm btn-primary mr5', 'escape' => false]) ?>
                                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $fee->id], [
                                                            'confirm' => __('Are you sure you want to delete this fee?'),
                                                            'class' => 'btn btn-sm btn-danger',
                                                            'escape' => false
                                                        ]) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <strong>No Fees Found!</strong>
                                    <p>There are currently no fees configured.
                                    <?= $this->Html->link('Add Fee', ['action' => 'add'], ['class' => 'btn btn-primary']); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div><!-- panel-body -->
                    </div><!-- panel -->
                </div>
            </div><!-- row -->
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->
