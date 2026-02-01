<?php
use Cake\Utility\Text;

$this->Html->script('jquery', ['block' => true]); // Ensure jQuery for tooltips
?>

<div class="mainwrapper">
    <div class="leftpanel">
        <?= $this->element('admin/sidebar') ?>
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-video-camera"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li>
                            <?= $this->Html->link(
                                '<i class="glyphicon glyphicon-home"></i> Dashboard',
                                ['controller' => 'Admins', 'action' => 'dashboard'],
                                ['escape' => false]
                            ) ?>
                        </li>
                        <li>Manage Videos</li>
                    </ul>
                    <h4>Manage Videos</h4>
                </div>
                <div class="search-body" style="width: 39%;">
                    <?= $this->Html->link(
                        'Add Video',
                        ['controller' => 'Elearning', 'action' => 'addVideo'],
                        ['class' => 'btn btn-primary mr5 ml10', 'style' => 'float: right;']
                    ) ?>
                    <?= $this->Form->create(null, [
                        'type' => 'get',
                        'novalidate' => true,
                        'class' => 'form-inline'
                    ]) ?>
                    <?= $this->Form->control('keyword', [
                        'value' => $this->request->getQuery('keyword'),
                        'class' => 'form-control width200',
                        'placeholder' => 'Enter Keyword to Search',
                        'label' => false,
                        'style' => 'float:left',
                        'autocomplete' => 'off'
                    ]) ?>
                    <?= $this->Form->button('Search', [
                        'type' => 'submit',
                        'class' => 'btn btn-primary mr5 ml10'
                    ]) ?>
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
                            <th>
                                <?= $this->Paginator->sort('title', 'Title') ?>
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('title', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false, 'direction' => 'asc']) ?>
                                    <?= $this->Paginator->sort('title', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc']) ?>
                                </span>
                            </th>
                            <th>
                                <?= $this->Paginator->sort('category_id', 'Category') ?>
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('category_id', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false, 'direction' => 'asc']) ?>
                                    <?= $this->Paginator->sort('category_id', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc']) ?>
                                </span>
                            </th>
                            <th>
                                <?= $this->Paginator->sort('created', 'Created') ?>
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('created', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false, 'direction' => 'asc']) ?>
                                    <?= $this->Paginator->sort('created', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc']) ?>
                                </span>
                            </th>
                            <th>
                                <?= $this->Paginator->sort('modified', 'Modified') ?>
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('modified', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false, 'direction' => 'asc']) ?>
                                    <?= $this->Paginator->sort('modified', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc']) ?>
                                </span>
                            </th>
                            <th>
                                <?= $this->Paginator->sort('status', 'Status') ?>
                                <span class="sort-link">
                                    <?= $this->Paginator->sort('status', $this->Html->image('sort-arrow-top.png', ['alt' => 'Ascending', 'title' => 'Ascending']), ['escape' => false, 'direction' => 'asc']) ?>
                                    <?= $this->Paginator->sort('status', $this->Html->image('sort-arrow-bottom.png', ['alt' => 'Descending', 'title' => 'Descending']), ['escape' => false, 'direction' => 'desc']) ?>
                                </span>
                            </th>
                            <th class="table-action" style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $page = $this->request->getQuery('page', 1);
                        $limit = $this->request->getQuery('limit', 10);
                        $i = ($page - 1) * $limit + 1;

                        if (!empty($videos)) {
                            foreach ($videos as $video):
                        ?>
                        <tr>
                            <td><?= $this->Number->format($video->id) ?>.</td>
                            <td>
                                <?= $this->Html->link(
                                    h(Text::wordWrap($video->title, 50)),
                                    ['controller' => 'Elearning', 'action' => 'videoDetails', $video->id],
                                    [
                                        'target' => '_blank',
                                        'title' => 'Video Details',
                                        'escape' => false
                                    ]
                                ) ?>
                            </td>
                            <td><?= h($video->elearning_category->name) ?></td>
                            <td><?= $video->created->format('M jS, Y H:i') ?></td>
                            <td><?= $video->modified->format('M jS, Y H:i') ?></td>
                            <td style="text-align: center;">
                                <?php if ($video->status == '1'): ?>
                                    <?= $this->Html->link(
                                        '<span>Active</span><br>' . $this->Html->image('admin/green.png', ['style' => 'width: 40px; margin-top: 5px;']),
                                        ['controller' => 'Elearning', 'action' => 'videoStatus', $video->id],
                                        [
                                            'escape' => false,
                                            'onclick' => "return confirm('Are you sure you want to deactivate this video?')",
                                            'style' => 'display: inline-block; text-decoration: none;'
                                        ]
                                    ) ?>
                                <?php else: ?>
                                    <?= $this->Html->link(
                                        '<span>Inactive</span><br>' . $this->Html->image('admin/red.png', ['style' => 'width: 40px; margin-top: 5px;']),
                                        ['controller' => 'Elearning', 'action' => 'videoStatus', $video->id],
                                        [
                                            'escape' => false,
                                            'onclick' => "return confirm('Are you sure you want to activate this video?')",
                                            'style' => 'display: inline-block; text-decoration: none;'
                                        ]
                                    ) ?>
                                <?php endif; ?>
                            </td>
                            <td class="table-action" style="width: 10%;">
                                <?= $this->Html->link(
                                    '<i class="fa fa-pencil"></i>',
                                    ['controller' => 'Elearning', 'action' => 'editVideo', $video->id],
                                    ['escape' => false, 'class' => 'tooltips', 'data-toggle' => 'tooltip', 'title' => 'Edit']
                                ) ?>
                                <?= $this->Html->link(
                                    '<i class="fa fa-trash-o"></i>',
                                    ['controller' => 'Elearning', 'action' => 'deleteVideo', $video->id],
                                    [
                                        'escape' => false,
                                        'class' => 'delete-row tooltips',
                                        'data-toggle' => 'tooltip',
                                        'title' => 'Delete',
                                        'onclick' => "return confirm('Are you sure you want to delete this video?')"
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php
                            $i++;
                        endforeach;
                        } else {
                            echo "<tr><td colspan='7' class='error text-center'>No Record Found...</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div><!-- panel -->
            <div class="paging-container">
                <?php if ($this->Paginator->counter('count') > 0): ?>
                    <p class="records-showing">
                        <?= $this->Paginator->counter('Showing {{start}} - {{end}} of {{count}} Videos') ?>
                    </p>
                    <?php if ($this->Paginator->counter('pages') > 1): ?>
                        <ul class="pagination">
                            <?= $this->Paginator->prev('Previous', ['tag' => 'li'], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a']) ?>
                            <?= $this->Paginator->numbers(['separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1]) ?>
                            <?= $this->Paginator->next('Next', ['tag' => 'li'], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a']) ?>
                        </ul>
                    <?php endif; ?>
                    <div class="cl"></div>
                <?php endif; ?>
            </div>
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->