<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>
    </div><!-- leftpanel -->

    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-file-text"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'Admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li><?= $this->Html->link('Settings', ['controller' => 'Settings', 'action' => 'index']); ?></li>
                        <li>Request Quotes</li>
                    </ul>
                    <h4>Request Quotes</h4>
                </div>


                <div class="search-body" style="width: 39%;">
                    <?= $this->Form->create(null, ['type' => 'get', 'valueSources' => ['query']]) ?>
                    <?= $this->Form->control('keyword', [
                        'class' => 'form-control width200',
                        'placeholder' => 'Enter Keyword to Search',
                        'style' => 'float:left',
                        'label' => false,
                        'autocomplete' => 'off'
                    ]) ?>
                    <?= $this->Form->button('Search', ['class' => 'btn btn-primary mr5 ml10']) ?>
                    <?= $this->Form->end() ?>
                </div>

            </div><!-- media -->
        </div><!-- pageheader -->

        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Flash->render(); ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Request Quotes List</h4>
                        <p>View and manage all quote requests submitted by users.</p>



                    </div><!-- panel-heading -->

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                                        <th><?= $this->Paginator->sort('user_id', 'User') ?></th>
                                        <th><?= $this->Paginator->sort('program_id', 'Program') ?></th>
                                        <th><?= $this->Paginator->sort('group_id', 'Group') ?></th>
                                        <th><?= $this->Paginator->sort('Policy_Effective_Date', 'Effective Date') ?></th>
                                        <th><?= $this->Paginator->sort('Policy_Termination_Date', 'Termination Date') ?></th>
                                        <th><?= $this->Paginator->sort('Final_Proposals_Due', 'Final Proposals Due') ?></th>
                                        <th><?= $this->Paginator->sort('networking_id', 'Networking') ?></th>
                                        <th><?= $this->Paginator->sort('loss_plan', 'Loss Plan') ?></th>
                                        <th><?= $this->Paginator->sort('benifit_plan', 'Benefit Plan') ?></th>
                                        <th><?= $this->Paginator->sort('Stop_Loss_Coverage_Type', 'Stop Loss Coverage Type') ?></th>
                                        <th><?= $this->Paginator->sort('status', 'Status') ?></th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($requestQuotes)): ?>
                                        <?php foreach ($requestQuotes as $quote): ?>
                                            <tr>
                                                <td><?= $quote->id ?></td>
                                                <td><?= h($quote->user->firstName ?? $quote->user_id) ?></td>
                                                <td><?= h($quote->program->name ?? $quote->program_id) ?></td>
                                                <td><?= h($quote->quotgroup->group_name ?? $quote->group_id) ?></td>
                                                <td><?= h($quote->Policy_Effective_Date) ?></td>
                                                <td><?= h($quote->Policy_Termination_Date) ?></td>
                                                <td><?= h($quote->Final_Proposals_Due) ?></td>
                                                <td>
                                                    <?php
                                                    $networkNames = [];
                                                    if (!empty($quote->networking_id)) {
                                                        $ids = array_filter(array_map('trim', explode(',', (string)$quote->networking_id)));
                                                        foreach ($ids as $nid) {
                                                            $nid = (int)$nid;
                                                            if (!empty($networkNamesById[$nid])) {
                                                                $networkNames[] = $networkNamesById[$nid];
                                                            }
                                                        }
                                                    }
                                                    echo h(!empty($networkNames) ? implode(', ', $networkNames) : ($quote->networking_id ?? ''));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $lossPlanNames = [];
                                                    if (!empty($quote->loss_plan)) {
                                                        $ids = array_filter(array_map('trim', explode(',', (string)$quote->loss_plan)));
                                                        foreach ($ids as $pid) {
                                                            $pid = (int)$pid;
                                                            if (!empty($lossPlanNamesById[$pid])) {
                                                                $lossPlanNames[] = $lossPlanNamesById[$pid];
                                                            }
                                                        }
                                                    }
                                                    echo h(!empty($lossPlanNames) ? implode(', ', $lossPlanNames) : ($quote->loss_plan ?? ''));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $benifitPlanNames = [];
                                                    if (!empty($quote->benifit_plan)) {
                                                        $ids = array_filter(array_map('trim', explode(',', (string)$quote->benifit_plan)));
                                                        foreach ($ids as $pid) {
                                                            $pid = (int)$pid;
                                                            if (!empty($benifitPlanNamesById[$pid])) {
                                                                $benifitPlanNames[] = $benifitPlanNamesById[$pid];
                                                            }
                                                        }
                                                    }
                                                    echo h(!empty($benifitPlanNames) ? implode(', ', $benifitPlanNames) : ($quote->benifit_plan ?? ''));
                                                    ?>
                                                </td>
                                                <td><?= h($quote->Stop_Loss_Coverage_Type) ?></td>
                                                <td>
                                                    <?php if ($quote->status == 1): ?>
                                                        <span class="badge badge-success">Processed</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $quote->id], ['class' => 'btn btn-xs btn-info', 'escape' => false, 'title' => 'View Details']); ?>
                                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $quote->id], ['class' => 'btn btn-xs btn-danger', 'escape' => false, 'confirm' => 'Are you sure you want to delete this quote request?', 'title' => 'Delete']); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="13" class="text-center">No request quotes found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div><!-- table-responsive -->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="pagination-wrapper">
                                    <?= $this->Paginator->numbers(['prev' => '«', 'next' => '»']); ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- panel-body -->
                </div><!-- panel -->
            </div><!-- row -->
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->
