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
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Company</th>
                                        <th>Product Type</th>
                                        <th>Coverage Amount</th>
                                        <th>State</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($requestQuotes as $quote): ?>
                                        <tr>
                                            <td><?= $quote->id ?></td>
                                            <td><?= h($quote->name) ?></td>
                                            <td><?= h($quote->email) ?></td>
                                            <td><?= h($quote->phone) ?></td>
                                            <td><?= h($quote->company_name) ?></td>
                                            <td><?= h($quote->product_type) ?></td>
                                            <td><?= h($quote->coverage_amount) ?></td>
                                            <td><?= h($quote->state) ?></td>
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
