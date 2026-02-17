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
                        <li>Manage Programs</li>
                    </ul>
                    <h4>Manage Programs</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Programs List</h4>
                            <p>Manage all programs in the system</p>
                            <div class="panel-btn">
                                <?= $this->Html->link('<i class="fa fa-plus"></i> Add Program', ['action' => 'add'], ['class' => 'btn btn-primary', 'escape' => false]) ?>
                            </div>
                        </div><!-- panel-heading -->
                        <div class="panel-body">
                            <?php if ($programs->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Network</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($programs as $program): ?>
                                                <tr>
                                                    <td><?= $program->id ?></td>
                                                    <td><?= h($program->name) ?></td>
                                                    <td><?= h($program->p_type) ?></td>
                                                    <td><?= h($program->networks_repricing->name ?? 'No Network') ?></td>
                                                    <td>
                                                        <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $program->id], ['class' => 'btn btn-sm btn-primary mr5', 'escape' => false]) ?>
                                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $program->id], [
                                                            'confirm' => __('Are you sure you want to delete this program?'),
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
                                    <strong>No Programs Found!</strong>
                                    <p>There are currently no programs configured. 
                                    <?= $this->Html->link('Add Program', ['action' => 'add'], ['class' => 'btn btn-primary']); ?>
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
