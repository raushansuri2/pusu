<div class="col-md-12 col-sm-12">
    <div class="row">
        <?= $this->Flash->render() ?>
        <div class="search-body" style="width: 100%;">
            <?= $this->Form->create(null, ['type' => 'get', 'novalidate' => 'novalidate']) ?>
            <div class="row">
                <div class="col-md-8 col-sm-5 min-pad">
                    <div class="form-box">
                        <?= $this->Form->control('keyword', [
                            'templates' => ['inputContainer' => '{{content}}'],
                            'value' => $this->request->getQuery('keyword'),
                            'class' => 'form-control width200',
                            'placeholder' => 'Enter Keyword to Search',
                            'style' => 'width: 100%;',
                            'div' => false,
                            'label' => false,
                            'autocomplete' => 'off'
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-2 col-sm-5 min-pad">
                    <div class="form-box">
                        <?= $this->Form->button('Search', [
                            'style' => 'width: 100%;',
                            'class' => 'btn btn-primary mr5 ml10',
                            'div' => false,
                            'label' => false
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 min-pad">
                    <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'add']) ?>" style="width:100%;" class="btn btn-primary1 mr5 ml10">Add</a>
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
                        <th>Product Name</th>
                        <th>Unit Price</th>
                        <th>S. Price</th>
                        <th>Quantity</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th class="table-action" style="width: 10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)) { ?>
                        <?php $i = $this->Paginator->counter('{{start}}') - 1; ?>
                        <?php foreach ($products as $product) { ?>
                            <tr>
                                <td><?= $i++ ?></td> <!-- Increment the index here -->
                                <td><?= !empty($product->category) ? h($product->category->name) : 'N/A' ?></td>
                                <td><?= !empty($product->subcategory) ? h($product->subcategory->name) : 'N/A' ?></td>
                                <td><div class="text-data"><?= h($product->productName) ?></div></td>
                                <td>$<?= ($product->unitPrice) ? number_format($product->unitPrice, 2) : '0.00' ?></td>
                                <td>$<?= ($product->specialPrice) ? number_format($product->specialPrice, 2) : '0.00' ?></td>
                                <td><?= ($product->quantity) ? $product->quantity : '0' ?></td>
                                <?php $IMG = !empty($product->image) ? $this->Url->build('/').'img/uploads/products/'.$product->image : $this->Url->build('/').'img/dummy.jpg'; ?>
                                <td><img src="<?= h($IMG) ?>" class="inner img-responsive" alt="Product Image" width="75"></td>
                                <td><div class="text-data"><?= h($product->description) ?></div></td>
                                <td><?= h($product->created->format('Y-m-d H:i:s')) ?></td>
                                <td>
                                    <?php if ($product->status == '1') { ?>
                                        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'status', $product->id]) ?>" data-toggle="tooltip" title="Deactivate">Active</a>
                                    <?php } else { ?>
                                        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'status', $product->id]) ?>" data-toggle="tooltip" title="Activate">Inactive</a>
                                    <?php } ?>
                                </td>
                                <td class="table-action" style="width: 10%; text-align: center;">
                                    <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'edit', $product->id]) ?>" data-toggle="tooltip" title="Edit" class="tooltips"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else {
                        echo "<tr><td colspan='12' class='error'>No Record Found...</td></tr>";
                    } ?>
                </tbody>
            </table>
        </div>
        <!-- End All Listing List -->
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