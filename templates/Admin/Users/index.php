<?php
use Cake\I18n\DateTime;

echo $this->Html->css(['admin/jquery.fancybox'], ['block' => true]);
echo $this->Html->script(['admin/jquery.fancybox.pack'], ['block' => true]);
?>

<div class="mainwrapper">
    <div class="leftpanel">
        <?= $this->element('admin/sidebar') ?>             
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-user"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']) ?>">
                                <i class="glyphicon glyphicon-home"></i> Dashboard
                            </a>
                        </li>
                        <li>Manage Member</li>
                    </ul>
                    <h4>Manage Member</h4>
                </div>
                <div class="search-body" style="width: 39%;">
                    <!-- <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'add']) ?>" 
                       class="btn btn-primary mr5 ml10" style="float: right;">Add</a> -->
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
            <?= $this->Flash->render() ?>
            <div class="panel panel-primary-head"> 
                <table id="basicTable" class="table table-striped table-bordered responsive">
                    <thead class="table-heading">
                        <tr>
                            <th>ID</th>
                            <th>Full Name
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('firstname', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false]) ?>
                                    <?= $this->Paginator->sort('firstname', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc']) ?>
                                </span>
                            </th>
                            <th>Email
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('email', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false]) ?>
                                    <?= $this->Paginator->sort('email', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc']) ?>
                                </span>
                            </th>
                            <th>Registered On
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('created', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false]) ?>
                                    <?= $this->Paginator->sort('created', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc']) ?>
                                </span>
                            </th>
                            <th>Contact Number</th>
                            <th>Status
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('status', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false]) ?>
                                    <?= $this->Paginator->sort('status', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc']) ?>
                                </span>
                            </th>
                            <th class="table-action" style="width: 10%">Action</th>
                        </tr>
                    </thead>                         
                    <tbody>									
                        <?php
                        $page = $this->Paginator->param('page') ?: 1;
                        $limit = $this->Paginator->param('perPage');
                        $i = $page > 1 ? (($page - 1) * $limit) + 1 : 1;

                        if (!empty($users)):
                            foreach ($users as $user):
                                ?>
                                <tr>
                                    <td><?= h($user->id) ?>.</td>
                                    <td><?php echo $user->firstName; ?></td>
                                    <td><?= wordwrap(h($user->email), 35, "<br>", true) ?></td>
                                    <td><?= DateTime::parse($user->created)->format('Y-m-d H:i') ?></td>
                                    <td><?= wordwrap(h($user->contactNumber), 35, "<br>", true) ?></td>
                                    <td style="text-align: center;">
                                        <?php if ($user->status == '1'): ?>
                                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'status', $user->id]) ?>" 
                                               onclick="return confirm('Are you sure want to inactivate this employee?')">
                                                Active<br>
                                                <img style="width: 40px;" src="<?= $this->Url->build('/img/admin/green.png') ?>" />
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'status', $user->id]) ?>" 
                                               onclick="return confirm('Are you sure want to activate this employee?')">
                                                Inactive<br>
                                                <img style="width: 40px;" src="<?= $this->Url->build('/img/admin/red.png') ?>" />
                                            </a>
                                        <?php endif; ?>
                                    </td>                                    
                                    <td class="table-action" style="width: 10%;">
                                        <a target="_blank" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'userdetails', $user->id]) ?>" 
                                           data-toggle=" tooltip" title="Details" class="tooltips"><i class="fa fa-eye"></i></a>
                                        <!-- <a target="_blank" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'edit', $user->id]) ?>" 
                                           data-toggle="tooltip" title="Edit" class="tooltips"><i class="fa fa-pencil"></i></a> -->
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            endforeach;
                        else:
                            echo "<tr><td colspan='8' class='error'>No Record Found...</td></tr>";
                        endif;
                        ?> 
                    </tbody>
                </table>
            </div><!-- panel -->                  
            <div class="paging-container">
                <?php if ($this->Paginator->param('count') > 0): ?>
                    <p>
                        <?= $this->Paginator->counter('<p class="records-showing">Showing {{start}} - {{end}} of {{count}} Employee</p>') ?>
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