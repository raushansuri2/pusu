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
                        <li>Manage Album</li>
                    </ul>
                    <h4>Manage Album</h4>
                </div>
                <div class="search-body" style="width: 39%;">
                    <?//= $this->Html->link('Add', ['controller' => 'Posts', 'action' => 'add'], ['class' => 'btn btn-primary mr5 ml10', 'style' => 'float: right;']); ?>
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
                           
                            <th>Image Added By</th>
                            <th>View Image</th>
                            <th>Type</th>
                            <th>
                                Created
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('created', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false, 'direction' => 'asc', 'lock' => true]); ?>
                                    <?= $this->Paginator->sort('created', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc', 'lock' => true]); ?>
                                </span>
                            </th>
                            <!-- <th>Status</th> -->
                            <!-- <th class="table-action" style="width: 10%">Action</th> -->
                        </tr>
                    </thead>                         
                    <tbody>									
                        <?php if (count($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?= $post->id; ?>.</td>
                                    <td><a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'userdetails',$post->userId]);?>" title="click to view user details"><?= h($post->user->firstName); ?></a></td>
                                    <td><a href="<?php echo $this->Url->build('/'); ?>img/uploads/multiimage/<?php echo $post->userId;?>/<?php echo $post->image;?>" target="_blank">View</a></td>
                                    <td>
                                        <?php if($post->ImageType == 1){
                                            echo "Public";
                                        }elseif($post->ImageType == 2){
                                            echo "Private";
                                        }else{
                                            echo "Only Friend";
                                        } ?>
                                    </td>
                                    <td><?= h($post->created); ?></td>
                                    <!-- <td>
                                        <?= $post->status == '1' ? 
                                            $this->Html->link('Active', ['controller' => 'Posts', 'action' => 'status', $post->id], ['onclick' => "return confirm('Are you sure want to deactivate this post?')"]) : 
                                            $this->Html->link('Inactive', ['controller' => 'Posts', 'action' => 'status', $post->id], ['onclick' => "return confirm('Are you sure want to activate this post?')"]); ?>
                                    </td> -->
                                    <!-- <td class="table-action" style="width: 10%;">
                                        <a href="<?= $this->Url->build(['controller' => 'Posts', 'action' => 'details', $post->id]) ?>" 
                                           data-toggle=" tooltip" title="Details" class="tooltips"><i class="fa fa-eye"></i></a>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Posts', 'action' => 'edit', $post->id], ['data-toggle' => 'tooltip', 'title' => 'Edit', 'class' => 'tooltips', 'escape' => false]); ?>
                                        <?= $this->Html->link('<i class="fa fa-trash-o"></i>', ['controller' => 'Posts', 'action' => 'delete', $post->id], ['data-toggle' => 'tooltip', 'onclick' => "return confirm('Are you sure you want to delete this post?')", 'title' => 'Delete', 'class' => 'delete-row tooltips', 'escape' => false]); ?>
                                    </td> -->
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