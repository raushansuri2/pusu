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
                                <h5><strong>Personal Information</strong></h5>
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td width="30%"><strong>Name:</strong></td>
                                        <td><?= h($requestQuote->name) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><?= h($requestQuote->email) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td><?= h($requestQuote->phone) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Company Name:</strong></td>
                                        <td><?= h($requestQuote->company_name) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Contact Person:</strong></td>
                                        <td><?= h($requestQuote->contact_person) ?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h5><strong>Request Information</strong></h5>
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td width="30%"><strong>Product Type:</strong></td>
                                        <td><?= h($requestQuote->product_type) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coverage Amount:</strong></td>
                                        <td><?= h($requestQuote->coverage_amount) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Zip Code:</strong></td>
                                        <td><?= h($requestQuote->zip_code) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>State:</strong></td>
                                        <td><?= h($requestQuote->state) ?></td>
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
                                <h5><strong>Message</strong></h5>
                                <div class="well">
                                    <?= !empty($requestQuote->message) ? nl2br(h($requestQuote->message)) : 'No message provided.' ?>
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
