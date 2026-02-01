<div class="mainwrapper">
    <div class="leftpanel">
        <?= $this->element('admin/sidebar'); ?>             
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-question-circle"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li>Manage Like</li>
                    </ul>
                    <h4>Manage Like</h4>
                </div>
                <div class="search-body" style="width: 39%;">
                    <?//= //$this->Html->link('Add', ['controller' => 'Likes', 'action' => 'add'], ['class' => 'btn btn-primary mr5 ml10', 'style' => 'float: right;']); ?>
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
                            <th>#ID</th>
                            <th>Post Title</th>
                            <th>Post By</th>
                            <th>Liked By</th>
                            <th>Status</th>
                            <th class="table-action" style="width: 10%">Action</th>
                        </tr>
                    </thead>                         
                    <tbody>									
                        <?php if (count($likes)): ?>
                            <?php foreach ($likes as $like): ?>
                                <tr>
                                    <td><?= $like->id; ?>.</td>
                                    <td><a href="<?php echo $this->Url->build(['controller'=>'Posts','action'=>'detail',$like->like]);?>" title="click to view post details"><?= h($like->post->postTitle); ?></a></td>
                                    <td><a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'userdetails',$like->post->userId]);?>" title="click to view user details"><?= h($like->post->user->firstName); ?></a></td>
                                    <td><a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'userdetails',$like->userId]);?>" title="click to view user details"><?= h($like->user->firstName); ?></a></td>

                                    <!-- <td><?= h($like->created); ?></td> -->
                                    <td>
                                        <?= $like->status == '1' ? 
                                            $this->Html->link('Active', ['controller' => 'Likes', 'action' => 'status', $like->id], ['onclick' => "return confirm('Are you sure want to deactivate this like?')"]) : 
                                            $this->Html->link('Inactive', ['controller' => 'Likes', 'action' => 'status', $like->id], ['onclick' => "return confirm('Are you sure want to activate this like?')"]); ?>
                                    </td>
                                    <td class="table-action" style="width: 10%;">
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Likes', 'action' => 'edit', $like->id], ['data-toggle' => 'tooltip', 'title' => 'Edit', 'class' => 'tooltips', 'escape' => false]); ?>
                                        <?= $this->Html->link('<i class="fa fa-trash-o"></i>', ['controller' => 'Likes', 'action' => 'delete', $like->id], ['data-toggle' => 'tooltip', 'onclick' => "return confirm('Are you sure you want to delete this like?')", 'title' => 'Delete', 'class' => 'delete-row tooltips', 'escape' => false]); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan='5' class='error'>No Record Found...</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div><!-- panel -->                  
            <div class="paging-container">
                <?php if ($this->Paginator->param('count') > 0): ?>
                    <p>
                        <?= $this->Paginator->counter('<p class="records-showing">Showing {{start}} - {{end}} of {{count}} Members</p>') ?>
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