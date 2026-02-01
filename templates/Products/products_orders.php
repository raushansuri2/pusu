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
                                    <?php if ($order->cart->productOwner == $this->request->getSession()->read('RitevetUsers.id')) { ?>
                                        <select id="status-select-<?= $order->id ?>" onchange="checkShippingCode(this.value, <?= $order->id; ?>)" <?php if ($order->orderStatus == 3 && $order->deliveryConfirm == 1) echo 'disabled'; ?>>
                                            <option value="1" <?php if ($order->orderStatus == 1) echo 'selected'; ?>>In Review</option>
                                            <option value="2" <?php if ($order->orderStatus == 2) echo 'selected'; ?>>Shipped</option>
                                            <option value="3" <?php if ($order->orderStatus == 3) echo 'selected'; ?>>Delivered</option>
                                        </select>
                                        <div id="shipping-code-container-<?= $order->id ?>" style="<?php if ($order->orderStatus != 2) echo 'display: none;'; ?>">
                                            <input type="text" id="shipping-code-<?= $order->id ?>" value="<?= h($order->shippingCode ?? '') ?>" placeholder="Enter shipping code">
                                            <button id="update-status-btn" onclick="updateStatus(2, <?= $order->id; ?>)">Update Status</button>
                                        </div>
                                    <?php } ?>
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
    function checkShippingCode(statusId, orderId) {
        if (statusId == 1 || statusId == 3) {
            updateStatus(statusId, orderId);
        } else if (statusId == 2) {
            document.getElementById('shipping-code-container-' + orderId).style.display = 'block';
        }
    }
    
    function updateStatus(statusId, orderId) {
        var shippingCode = document.getElementById('shipping-code-' + orderId).value;
        if (statusId == 2) {
            if (shippingCode == '') {
                alert('Please enter shipping code before changing status to Shipped');
                return;
            } else if (shippingCode.length < 5) {
                alert('Shipping code must be at least 5 characters long');
                return;
            }
        }
        $('.ajaxloader').fadeIn();
        $.ajax({
            type: 'POST',
            url: "<?= $this->Url->build(['controller' => 'Products', 'action' => 'updateOrderStatus']); ?>",
            headers: {
                'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
            },
            data: { statusId: statusId, orderId: orderId, shippingCode: shippingCode },
            success: function(response) {
                $('.ajaxloader').fadeOut();
                if (response.success) {
                    alert(response.message);
                }
                if (statusId == 2) {
                    document.getElementById('shipping-code-container-' + orderId).style.display = 'block';
                } else {
                    document.getElementById('shipping-code-container-' + orderId).style.display = 'none';
                }
                window.location.reload();
            }
        });
    }
</script>