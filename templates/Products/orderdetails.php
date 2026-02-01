<?php
// Separate PHP code from HTML
$subtotal = 0;

foreach ($orderList as $order) {
    $cart = $order->cart;
    $product = $cart->product;
    
    $price = ($cart->finalPrice == 0) ? $cart->unitPrice : $cart->finalPrice;
    $subtotal += $cart->quantity * $price;
}
// pr($orderList);exit;
?>

<!-- HTML structure -->
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="goods-page">
            <div class="goods-data clearfix">
                <?php echo $this->Flash->render(); ?>
                <div class="table-wrapper-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>SKU</th>
								<th>Product</th>
								<th>Quantity</th>
								<th>Unit price</th>
								<th>Total Amt.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $k=1; ?>
                            <?php foreach ($orderList as $order) { ?>
                            <?php 
                                $cart = $order->cart;
                                $product = $cart->product;
                            ?>
                            <tr>
                                <td><?php echo $k; ?></td>
                                <td class="goods-page-image">
                                    <?php $img = ($product->image != '') ? $this->Url->build('/').'img/uploads/products/'.$product->image : $this->Url->build('/').'img/dummy.jpg'; ?>
                                    <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'details', base64_encode($product->id)]); ?>" target="_blank">
                                        <img src="<?php echo $img; ?>" alt="Berry Lace Dress">
                                    </a>
                                </td>
                                <td><?php echo $product->SKU; ?></td>
                                <td>
                                    <h3>
                                        <?php if($cart->unitPrice != 0) { ?>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'details', base64_encode($product->id)]); ?>" target="_blank">
                                            <?php echo $product->productName; ?>
                                        </a>
                                        <?php } else { ?>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Freestaffs', 'action' => 'details', base64_encode($product->id)]); ?>" target="_blank">
                                            <?php echo $product->productName; ?>
                                        </a>
                                        <?php } ?>
                                    </h3>
                                </td>
                                <td><?php echo $cart->quantity; ?></td>
                                <td>
                                    <strong>
                                        <span>$</span><?php echo ($cart->finalPrice != 0) ? $cart->finalPrice : $cart->unitPrice; ?>
                                    </strong>
                                </td>
                                <td>
                                    <strong>
                                        <?php $price = ($cart->finalPrice != 0) ? $cart->finalPrice : $cart->unitPrice; ?>
                                        <span>$</span><?php echo number_format(($cart->quantity * $price), 2); ?>
                                    </strong>
                                </td>
                            </tr>
                            <?php $k++;?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <div class="detail-wrapper-header">
                            <h4>
                                <i class="ti-map theme-cl mrg-r-10"></i>Delivery Address
                            </h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <strong>Name: </strong><?php echo $order->shippingName; ?>
                            <br>
                            <br>
                            <strong>Mobile: </strong><?php echo $order->ShippingMobile; ?>
                            <br>
                            <br>
                            <strong>Address: </strong><?php echo $order->shippingAddress; ?>, <?php echo $order->shippingZipcode; ?>
                            <br>
                            <br>
                        </div>
                        <div class="detail-wrapper-header">
                            <h4>
                                <i class="ti-credit-card theme-cl mrg-r-10"></i>Payment Details
                            </h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <strong>Transaction ID : <?php echo $order->transactionID; ?></strong>
                            <br>
                            <br>
                            <strong>Payment Status : <?php echo $order->paymentStatus; ?></strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="detail-wrapper-header">
                            <h4>
                                <i class="ti-calculator theme-cl mrg-r-10"></i>Order Summary
                            </h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Total :</td>
                                        <td>
                                            <strong>
                                                <span>$</span><?php echo number_format($subtotal, 2); ?>
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>