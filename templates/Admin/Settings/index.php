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
                        <li>View Settings</li>
                    </ul>
                    <h4>Settings</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">General Settings</h4>
                            <p>Manage general application settings</p>
                        </div><!-- panel-heading -->
                        <div class="panel-body">
                            <?php if ($settings): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Setting</th>
                                                <th>Value</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Default PPO Network Discount</td>
                                                <td><?= $settings->default_ppo_network_discount ?>%</td>
                                                <td>
                                                    <?= $this->Html->link('<i class="fa fa-edit"></i> Edit', ['action' => 'edit'], ['class' => 'btn btn-sm btn-primary', 'escape' => false]) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Manual Discretion: Specific Rates</td>
                                                <td><?= $settings->manual_discretion_specific_rates ?>%</td>
                                                <td>
                                                    <?= $this->Html->link('<i class="fa fa-edit"></i> Edit', ['action' => 'edit'], ['class' => 'btn btn-sm btn-primary', 'escape' => false]) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Manual Discretion: Aggregate Rates</td>
                                                <td><?= $settings->manual_discretion_aggregate_rates ?>%</td>
                                                <td>
                                                    <?= $this->Html->link('<i class="fa fa-edit"></i> Edit', ['action' => 'edit'], ['class' => 'btn btn-sm btn-primary', 'escape' => false]) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Minimum Experience RTM: Specific Rates</td>
                                                <td><?= $settings->minimum_experience_rtm_specific_rates ?>%</td>
                                                <td>
                                                    <?= $this->Html->link('<i class="fa fa-edit"></i> Edit', ['action' => 'edit'], ['class' => 'btn btn-sm btn-primary', 'escape' => false]) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Minimum Experience RTM: Aggregate Factors</td>
                                                <td><?= $settings->minimum_experience_rtm_aggregate_factors ?>%</td>
                                                <td>
                                                    <?= $this->Html->link('<i class="fa fa-edit"></i> Edit', ['action' => 'edit'], ['class' => 'btn btn-sm btn-primary', 'escape' => false]) ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <strong>No Settings Found!</strong>
                                    <p>There are currently no settings configured. 
                                    <?= $this->Html->link('Create Settings', ['action' => 'edit'], ['class' => 'btn btn-primary']); ?>
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
