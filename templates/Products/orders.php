<div class="col-md-12 col-sm-12">
    <div class="row">
        <?= $this->Flash->render() ?>
        <div class="search-body" style="width: 100%;">
            <?= $this->Form->create(null, [
                'type' => 'get',
                'novalidate' => 'novalidate',
                'style' => "float:left;width:70%;"
            ]) ?>
            <?= $this->Form->input('keyword', [
                'templates' => ['inputContainer' => '{{content}}'],
                'value' => $this->request->getQuery('keyword'),
                'class' => 'form-control width200',
                'placeholder' => 'Enter Keyword to Search',
                'style' => 'float:left; width:75%;',
                'div' => false,
                'label' => false,
                'autocomplete' => 'off'
            ]) ?>
            <?= $this->Form->button('Search', [
                'style' => "float:right;",
                'class' => 'btn btn-primary mr5 ml10',
                'div' => false,
                'label' => false
            ]) ?>
            <?= $this->Form->end() ?>
        </div>
        <br style="clear:both">
        <div class="responsive-table">
            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="table-heading">
                    <tr>
                        <th>#</th>
                        <th>Order No.</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Amt.</th>
                        <th>Order Date</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Ship Date</th>
                        <th>Delivery Confirm</th>
                        <th class="table-action" style="width: 10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php if (!empty($orders)) { ?>
                        <?php foreach ($orders as $order) { ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= h($order->order_number) ?></td>
                                <td><?= h($order->quantity ?? 0) ?></td>
                                <td>$<?= h($order->price) ?></td>
                                <td>$<?= h($order->TotalAmount) ?></td>
                                <td><?= h($order->created->format('Y-m-d H:i:s')) ?></td>
                                <td><?= h(strtoupper($order->paymentStatus)) ?></td>
                                <td>
                                    <?php if ($order->userId == $this->request->getSession()->read('RitevetUsers.id')) : ?>
                                        <?php switch ($order->orderStatus) : 
                                            case 1: ?>
                                                <span>In Review</span>
                                                <?php break; 
                                            case 2: ?>
                                                <span>Shipped</span>
                                                <?php break; 
                                            case 3: ?>
                                                <?php if ($order->deliveryConfirm == 0) : ?>
                                                    <a href="#" onclick="return confirmDelivery(<?= $order->id; ?>)">Delivered</a>
                                                <?php else : ?>
                                                    <span>Delivered</span>
                                                <?php endif; ?>
                                                <?php break; 
                                        endswitch; ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($order->shippingDate) ?></td>
                                <td><?= $order->deliveryConfirm == 1 ? 'Yes' : 'No' ?></td>
                                <td class="table-action" style="width: 10%; text-align:center;">
                                    <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'orderdetails', $order->id]) ?>" target="_blank" data-toggle="tooltip" title="View" class="tooltips"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="11" class="error">No Record Found...</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="paging-container">
        <?php if ($this->Paginator->counter('{{count}}') != 0) : ?>
            <p>
                <?= $this->Paginator->counter('<p class="records-showing">Showing {{start}} - {{end}} of {{count}} Records</p>') ?>
            </p>
            <?php if ($this->Paginator->counter('{{pages}}') > 1) : ?>
                <ul class="pagination">
                    <?= $this->Paginator->prev(__('Previous'), ['tag' => 'li', 'escape' => false], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a', 'escape' => false]) ?>
                    <?= $this->Paginator->numbers(['separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1]) ?>
                    <?= $this->Paginator->next(__('Next'), ['tag' => 'li', 'escape' => false], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a', 'escape' => false]) ?>
                </ul>
            <?php endif; ?>
            <div class="cl"></div>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmDelivery(orderId) {
        var response = prompt("Do you want to confirm this order as delivered? (yes/no)");
        if (response && response.toLowerCase() === 'yes') {
            var delivered = 1;
        } else if (response && response.toLowerCase() === 'no') {
            var delivered = 0;
        } else {
            alert("Invalid input. Please enter 'yes' or 'no'.");
            return false;
        }
        
        $.ajax({
            type: "POST",
            url: "<?= $this->Url->build(['controller' => 'Products', 'action' => 'confirmDelivery']); ?>",
            data: { orderId: orderId, delivered: delivered, _csrfToken: '<?= $this->request->getAttribute('csrfToken') ?>' },
            success: function() {
                window.location.reload();
            }
        });
    }
</script>