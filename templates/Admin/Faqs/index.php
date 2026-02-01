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
                        <li>Manage Faq</li>
                    </ul>
                    <h4>Manage Faq</h4>
                </div>
                <div class="search-body" style="width: 39%;">
                    <?= $this->Html->link('Add', ['controller' => 'Faqs', 'action' => 'add'], ['class' => 'btn btn-primary mr5 ml10', 'style' => 'float: right;']); ?>
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
                                Faq Question
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('question', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false, 'direction' => 'asc', 'lock' => true]); ?>
                                    <?= $this->Paginator->sort('question', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc', 'lock' => true]); ?>
                                </span>
                            </th>
                            <th>
                                Created
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('created', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false, 'direction' => 'asc', 'lock' => true]); ?>
                                    <?= $this->Paginator->sort('created', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc', 'lock' => true]); ?>
                                </span>
                            </th>
                            <th>Status</th>
                            <th class="table-action" style="width: 10%">Action</th>
                        </tr>
                    </thead>                         
                    <tbody>									
                        <?php if (count($faqs)): ?>
                            <?php foreach ($faqs as $faq): ?>
                                <tr>
                                    <td><?= $faq->id; ?>.</td>
                                    <td><?= h($faq->question); ?></td>
                                    <td><?= h($faq->created); ?></td>
                                    <td>
                                        <?= $faq->status == '1' ? 
                                            $this->Html->link('Active', ['controller' => 'Faqs', 'action' => 'status', $faq->id], ['onclick' => "return confirm('Are you sure want to deactivate this faq?')"]) : 
                                            $this->Html->link('Inactive', ['controller' => 'Faqs', 'action' => 'status', $faq->id], ['onclick' => "return confirm('Are you sure want to activate this faq?')"]); ?>
                                    </td>
                                    <td class="table-action" style="width: 10%;">
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Faqs', 'action' => 'edit', $faq->id], ['data-toggle' => 'tooltip', 'title' => 'Edit', 'class' => 'tooltips', 'escape' => false]); ?>
                                        <?= $this->Html->link('<i class="fa fa-trash-o"></i>', ['controller' => 'Faqs', 'action' => 'delete', $faq->id], ['data-toggle' => 'tooltip', 'onclick' => "return confirm('Are you sure you want to delete this faq?')", 'title' => 'Delete', 'class' => 'delete-row tooltips', 'escape' => false]); ?>
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