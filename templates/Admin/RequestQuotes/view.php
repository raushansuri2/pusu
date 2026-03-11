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
                        <li><?= $this->Html->link('Request Quotes', ['action' => 'index']); ?></li>
                        <li>View Quote Request</li>
                    </ul>
                    <h4>View Quote Request Details</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->

        <div class="contentpanel">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Quote Request Details</h4>
                        <p>Complete information about this quote request.</p>
                    </div><!-- panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><strong>Request Information</strong></h5>
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td width="40%"><strong>User ID:</strong></td>
                                        <td><?= h($requestQuote->user->firstName ?? $requestQuote->user_id) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Program ID:</strong></td>
                                        <td><?= h($requestQuote->program->name ?? $requestQuote->program_id) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Group ID:</strong></td>
                                        <td><?= h($requestQuote->quotgroup->group_name ?? $requestQuote->group_id) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Policy Effective Date:</strong></td>
                                        <td><?= h($requestQuote->Policy_Effective_Date) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Policy Termination Date:</strong></td>
                                        <td><?= h($requestQuote->Policy_Termination_Date) ?></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <h5><strong>Plan & Coverage</strong></h5>
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td width="40%"><strong>Final Proposals Due:</strong></td>
                                        <td><?= h($requestQuote->Final_Proposals_Due) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Networking ID:</strong></td>
                                        <td><?= h($requestQuote->network->name ?? $requestQuote->networking_id) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Loss Plan:</strong></td>
                                        <td><?= h($requestQuote->loosePlan->plan_name ?? $requestQuote->loss_plan) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Benefit Plan:</strong></td>
                                        <td><?= h($requestQuote->benifitPlan->plan_name ?? $requestQuote->benifit_plan) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stop Loss Coverage Type:</strong></td>
                                        <td><?= h($requestQuote->Stop_Loss_Coverage_Type) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <?php if ($requestQuote->status == 1): ?>
                                                <span class="badge badge-success">Processed</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h5><strong>Notes</strong></h5>
                                <div class="well">
                                    <?= !empty($requestQuote->notes) ? nl2br(h($requestQuote->notes)) : 'No notes provided.' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-actions">
                                    <?= $this->Html->link('Back to List', ['action' => 'index'], ['class' => 'btn btn-default']); ?>
                                    <?= $this->Form->postLink('Delete Request', ['action' => 'delete', $requestQuote->id], ['class' => 'btn btn-danger pull-right', 'confirm' => 'Are you sure you want to delete this quote request?']); ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- panel-body -->
                </div><!-- panel -->
            </div><!-- row -->
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->
