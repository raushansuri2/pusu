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
                        <li>Manage Benefit Plans</li>
                    </ul>
                    <h4>Manage Benefit Plans</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Benefit Plans List</h4>
                            <p>Manage all benefit plans in the system</p>
                            <div class="panel-btn">
                                <?= $this->Html->link('<i class="fa fa-plus"></i> Add Benefit Plan', ['action' => 'add'], ['class' => 'btn btn-primary', 'escape' => false]) ?>
                            </div>
                        </div><!-- panel-heading -->
                        <div class="panel-body">
                            <?php if ($benifitPlans->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Plan Name</th>
                                                <th>Programs</th>
                                                <th>Deductible In/Out</th>
                                                <th>Coin In/Out</th>
                                                <th>OOP Max In/Out</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($benifitPlans as $benifitPlan): ?>
                                                <tr>
                                                    <td><?= $benifitPlan->id ?></td>
                                                    <td><?= h($benifitPlan->plan_name) ?></td>
                                                    <td>
                                                        <?php 
                                                        if (!empty($benifitPlan->program_id)) {
                                                            // Handle both string and array types
                                                            if (is_array($benifitPlan->program_id)) {
                                                                $programIds = $benifitPlan->program_id;
                                                            } else {
                                                                $programIds = explode(',', $benifitPlan->program_id);
                                                            }
                                                            $programsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Programs');
                                                            $programNames = [];
                                                            foreach ($programIds as $programId) {
                                                                if (!empty($programId)) {
                                                                    $program = $programsTable->find()->where(['id' => $programId])->first();
                                                                    if ($program) {
                                                                        $programNames[] = h($program->name);
                                                                    }
                                                                }
                                                            }
                                                            echo !empty($programNames) ? implode(', ', $programNames) : 'No Programs';
                                                        } else {
                                                            echo 'No Programs';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>$<?= number_format($benifitPlan->deductible_in, 2) ?> / $<?= number_format($benifitPlan->deductible_out, 2) ?></td>
                                                    <td><?= $benifitPlan->coinsurance_in ?>% / <?= $benifitPlan->coinsurance_out ?>%</td>
                                                    <td>$<?= number_format($benifitPlan->oop_maximum_in, 2) ?> / $<?= number_format($benifitPlan->oop_maximum_out, 2) ?></td>
                                                    <td>
                                                        <?php if ($benifitPlan->status == 1): ?>
                                                            <span class="badge badge-success">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-danger">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $benifitPlan->id], ['class' => 'btn btn-sm btn-primary mr5', 'escape' => false]) ?>
                                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $benifitPlan->id], [
                                                            'confirm' => __('Are you sure you want to delete this benefit plan?'),
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
                                    <strong>No Benefit Plans Found!</strong>
                                    <p>There are currently no benefit plans configured. 
                                    <?= $this->Html->link('Add Benefit Plan', ['action' => 'add'], ['class' => 'btn btn-primary']); ?>
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
