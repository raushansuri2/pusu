<div class="col-md-12 col-sm-12">
    <div class="row">
        <?= $this->Flash->render() ?>
        <div class="search-body" style="width: 100%;">
            <?= $this->Form->create(null, [
                'type' => 'get',
                'valueSources' => ['query'],
                'class' => 'bicky'
            ]) ?>
            <div class="row1">
                <div class="col-md-8 col-sm-5 min-pad">
                    <div class="form-box">
                        <?= $this->Form->control('keyword', [
                            'label' => false,
                            'class' => 'form-control width200',
                            'placeholder' => 'Enter Keyword to Search',
                            'autocomplete' => 'off'
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-2 col-sm-5 min-pad">
                    <div class="form-box">
                        <?= $this->Form->button('Search', [
                            'type' => 'submit',
                            'class' => 'btn btn-primary mr5 ml10',
                            'style' => 'width:100%'
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 min-pad">
                    <?= $this->Html->link('Add', ['controller' => 'Freestaffs', 'action' => 'add'], [
                        'class' => 'btn btn-primary1 mr5 ml10',
                        'style' => 'width:100%'
                    ]) ?>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
        <br style="clear:both">
        <div class="responsive-table">
            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="table-heading">
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Stuff Name</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Image</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th class="table-action" style="width: 10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($freestuffs)): ?>
                        <?php $i = $this->Paginator->counter('{{start}}') - 1; ?>
                        <?php foreach ($freestuffs as $freestuff): ?>
                            <tr>
                                <td><?= ++$i ?></td>
                                <td><?= h($freestuff->category->name) ?></td>
                                <td><?= h($freestuff->subcategory->name) ?></td>
                                <td><div class="text-data"><?= h($freestuff->productName) ?></div></td>
                                <td><div class="text-data"><?= h($freestuff->description) ?></div></td>
                                <td><?= $this->Number->format($freestuff->quantity) ?></td>
                                <td>
                                    <?= $freestuff->unitPrice == 0 ? 'FREE' : $this->Number->currency($freestuff->unitPrice) ?>
                                </td>
                                <td>
                                    <?php
                                    $imgPath = $freestuff->image ? '/img/uploads/products/' . $freestuff->image : '/img/dummy.jpg';
                                    echo $this->Html->image($imgPath, [
                                        'class' => 'inner img-responsive',
                                        'alt' => 'Product Image',
                                        'width' => '75'
                                    ]);
                                    ?>
                                </td>
                                <td><?= h($freestuff->created->format('Y-m-d H:i:s')) ?></td>
                                <td>
                                    <?= $this->Html->link(
                                        $freestuff->status == 1 ? 'Active' : 'Inactive',
                                        ['action' => 'status', $freestuff->id],
                                        [
                                            'onclick' => "return confirm('Are you sure want to " . ($freestuff->status == 1 ? 'deactivate' : 'activate') . " this freestuff?')"
                                        ]
                                    ) ?>
                                </td>
                                <td class="table-action" style="width: 10%; text-align: center;">
                                    <!--<?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'details', $freestuff->id], ['escape' => false, 'target' => '_blank', 'class' => 'tool tips', 'data-toggle' => 'tooltip', 'title' => 'View', 'style' => 'margin-right: 10px;']) ?>-->
                                    <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['action' => 'edit', $freestuff->id], ['escape' => false, 'class' => 'tooltips', 'data-toggle' => 'tooltip', 'title' => 'Edit', 'style' => 'margin-right: 10px;']) ?>
                                    <!-- Uncomment if you want to enable delete
                                    <?= $this->Html->link('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $freestuff->id], ['escape' => false, 'class' => 'delete-row tooltips', 'data-toggle' => 'tooltip', 'title' => 'Delete', 'onclick' => "return confirm('Are you sure you want to delete this stuff?')"]) ?>
                                    -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="11" class="error">No Record Found...</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="paging-container">
            <?php if ($this->Paginator->hasPage()): ?>
                <p class="records-showing">
                    <?= $this->Paginator->counter('<p class="records-showing">Showing {{start}} - {{end}} of {{count}} Records</p>') ?>
                </p>
                <?php if ($this->Paginator->total() > 1): ?>
                    <ul class="pagination">
                        <?= $this->Paginator->prev('Previous', ['tag' => 'li']) ?>
                        <?= $this->Paginator->numbers(['tag' => 'li', 'currentTag' => 'a', 'currentClass' => 'active']) ?>
                        <?= $this->Paginator->next('Next', ['tag' => 'li']) ?>
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>