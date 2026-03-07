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
                        <li>Manage Loose Plans</li>
                    </ul>
                    <h4>Manage Loose Plans</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Loose Plans List</h4>
                            <p>Manage all loose plans in the system</p>
                            <div class="panel-btn">
                                <?= $this->Html->link('<i class="fa fa-plus"></i> Add Loose Plan', ['action' => 'add'], ['class' => 'btn btn-primary', 'escape' => false]) ?>
                            </div>
                        </div><!-- panel-heading -->
                        <div class="panel-body">
                            <?php if ($loosePlans->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Deductible In/Out</th>
                                                <th>Coinsurance In/Out</th>
                                                <th>OOP Max In/Out</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($loosePlans as $plan): ?>
                                                <tr>
                                                    <td><?= $plan->id ?></td>
                                                    <td><?= h($plan->name) ?></td>
                                                    <td><?= h($plan->type) ?></td>
                                                    <td>$<?= number_format($plan->deductible_in ?? 0, 2) ?> / $<?= number_format($plan->deductible_out ?? 0, 2) ?></td>
                                                    <td><?= number_format($plan->deductible_co_insurance ?? 0, 1) ?>% / <?= number_format($plan->deductible_co_insurance_out ?? 0, 1) ?>%</td>
                                                    <td>$<?= number_format($plan->deductible_oop_in ?? 0, 2) ?> / $<?= number_format($plan->deductible_oop_out ?? 0, 2) ?></td>
                                                    <td>
                                                        <?php if ($plan->status == 1): ?>
                                                            <span class="badge badge-success">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-danger">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $plan->id], ['class' => 'btn btn-sm btn-primary mr5', 'escape' => false]) ?>
                                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $plan->id], [
                                                            'confirm' => __('Are you sure you want to delete this loose plan?'),
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
                                    <strong>No Loose Plans Found!</strong>
                                    <p>There are currently no loose plans configured. 
                                    <?= $this->Html->link('Add Loose Plan', ['action' => 'add'], ['class' => 'btn btn-primary']); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div><!-- panel-body -->
                        
                        <?php if ($this->Paginator->hasPage()): ?>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="text-muted">
                                        Showing <?= $this->Paginator->counter() ?>
                                    </p>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <ul class="pagination pagination-sm m-0">
                                        <?= $this->Paginator->first('<< First') ?>
                                        <?= $this->Paginator->prev('< Previous') ?>
                                        <?= $this->Paginator->numbers() ?>
                                        <?= $this->Paginator->next('Next >') ?>
                                        <?= $this->Paginator->last('Last >>') ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div><!-- panel -->
                </div>
            </div><!-- row -->
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->
