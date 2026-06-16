<div class="mainwrapper">
    <div class="leftpanel">
        <?= $this->element('admin/sidebar'); ?>             
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-users"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li>Manage Quoting Groups</li>
                    </ul>
                    <h4>Manage Quoting Groups</h4>
                </div>
                <div class="search-body" style="width: 39%;">
                    <?//= $this->Html->link('Add', ['controller' => 'Quotgroups', 'action' => 'add'], ['class' => 'btn btn-primary mr5 ml10', 'style' => 'float: right;']); ?>
                    <?= $this->Form->create(null, ['type' => 'get', 'novalidate' => 'novalidate']); ?>
                    <?= $this->Form->input('keyword', [
                        'templates' => ['inputContainer' => '{{content}}'],
                        'value' => $this->request->getQuery('keyword'),
                        'class' => 'form-control width200',
                        'placeholder' => 'Enter Keyword to Search',
                        'style' => 'float:left',
                        'div' => false,
                        'label' => false,
                        'autocomplete' => 'off'
                    ]); ?>
                    <?= $this->Form->submit('Search', ['class' => 'btn btn-primary mr5 ml10', 'div' => false, 'label' => false]); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">      
            <?= $this->Flash->render(); ?>              
            <div class="panel panel-primary-head"> 
                <table id="basicTable" class="table table-striped table-bordered responsive">
                    <thead class="table-heading">
                        <tr>
                            <th>ID</th>
                            <th>
                                Group Name
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('group_name', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false, 'direction' => 'asc', 'lock' => true]); ?>
                                    <?= $this->Paginator->sort('group_name', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc', 'lock' => true]); ?>
                                </span>
                            </th>
                            <th>SIC/NAICS Code</th>
                            <th>City</th>
                            <th>
                                State
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('state_name', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false, 'direction' => 'asc', 'lock' => true]); ?>
                                    <?= $this->Paginator->sort('state_name', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc', 'lock' => true]); ?>
                                </span>
                            </th>
                            <th>Zip</th>
                            <th>Status</th>
                            <th class="table-action" style="width: 10%">Action</th>
                        </tr>
                    </thead>                         
                    <tbody>									
                        <?php if (count($quotgroups)): ?>
                            <?php foreach ($quotgroups as $quotgroup): ?>
                                <tr>
                                    <td><?= $quotgroup->id; ?>.</td>
                                    <td><?= h($quotgroup->group_name); ?></td>
                                    <td><?= h($quotgroup->SIC_Code); ?></td>
                                    <td><?= h($quotgroup->city); ?></td>
                                    <td><?= h($quotgroup->state_name); ?></td>
                                    <td><?= h($quotgroup->zip); ?></td>
                                    <td>
                                        <?= $quotgroup->status == '1' ? 
                                            $this->Html->link('Active', ['controller' => 'Quotgroups', 'action' => 'status', $quotgroup->id], ['onclick' => "return confirm('Are you sure want to deactivate this quoting group?')"]) : 
                                            $this->Html->link('Inactive', ['controller' => 'Quotgroups', 'action' => 'status', $quotgroup->id], ['onclick' => "return confirm('Are you sure want to activate this quoting group?')"]); ?>
                                    </td>
                                    <td class="table-action" style="width: 10%;">
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Quotgroups', 'action' => 'edit', $quotgroup->id], ['data-toggle' => 'tooltip', 'title' => 'Edit', 'class' => 'tooltips', 'escape' => false]); ?>
                                        <?= $this->Html->link('<i class="fa fa-trash-o"></i>', ['controller' => 'Quotgroups', 'action' => 'delete', $quotgroup->id], ['data-toggle' => 'tooltip', 'onclick' => "return confirm('Are you sure you want to delete this quoting group?')", 'title' => 'Delete', 'class' => 'delete-row tooltips', 'escape' => false]); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan='8' class='error'>No Record Found...</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div><!-- panel -->                  
            <div class="paging-container">
                <?php if ($this->Paginator->param('count') > 0): ?>
                    <p>
                        <?= $this->Paginator->counter('<p class="records-showing">Showing {{start}} - {{end}} of {{count}} Groups</p>') ?>
                    </p>
                    <?php if ($this->Paginator->param('pageCount') > 1): ?>
                        <ul>
                            <?= $this->Paginator->prev(__('Previous'), ['tag' => 'li', 'escape' => false], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a', 'escape' => false]) ?>
                            <?= $this->Paginator->numbers(['separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1]) ?>
                            <?= $this->Paginator->next(__('Next'), ['tag' => 'li', 'escape' => false], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a', 'escape' => false]) ?>
                        </ul>
                    <?php endif; ?>
                    <div class="cl"></div>	
                <?php endif; ?>
            </div>
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->
