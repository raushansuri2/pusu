<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="goods-page">
            <div class="goods-data clearfix">
                <?php echo $this->Flash->render(); ?>
                <div class="table-wrapper-responsive">
                    <table summary="Shopping cart">
                        <tbody>
                            <tr>
                                <th class="goods-page-image">Image</th>
                                <th class="goods-page-description">Description</th>
                                <th class="goods-page-ref-no">#SKU</th>
                                <th class="goods-page-quantity">Quantity</th>
                                <th class="goods-page-price">Unit price</th>
                                <th class="goods-page-total" colspan="2">Total Price</th>
                            </tr>
                        <?php
                        $SUBTOTAL = 0;
                        foreach ($carts as $cart) {
                        ?>
                            <tr>
                                <td class="goods-page-image">
                                    <?php 
                                    $IMG = ($cart->product->image != '') ? $this->Url->build('/') . 'img/uploads/products/' . $cart->product->image : $this->Url->build('/') . 'img/dummy.jpg'; 
                                    ?>
                                    <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'details', base64_encode($cart->product->id)]); ?>" target="_blank">
                                        <img src="<?php echo $IMG; ?>" alt="Berry Lace Dress">
                                    </a>
                                </td>
                                <td class="goods-page-description">
                                    <h3>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'details', base64_encode($cart->product->id)]); ?>" target="_blank">
                                            <?php echo $cart->product->productName; ?>
                                        </a>
                                    </h3>
                                    <p><?php echo $cart->product->description; ?></p>
                                </td>
                                <td class="goods-page-ref-no">
                                    <?php echo $cart->product->SKU; ?>
                                </td>
                                <td class="goods-page-quantity">
                                    <div class="product-quantity">
                                        <div class="bootstrap-touchspin input-group-sm">
                                            <input type="number" class="changeQTT form-control input-sm" id="<?php echo $cart->productId; ?>" min="1" max="<?php echo $cart->product->quantity; ?>" value="<?php echo $cart->quantity ? $cart->quantity : '1' ; ?>" style="display: block;text-align:center">
                                        </div>
                                    </div>
                                </td>
                                <td class="goods-page-price">
                                    <strong><span>$</span><?php echo $cart->unitPrice; ?></strong>
                                </td>
                                <td class="goods-page-total">
                                    <strong><span>$</span><span id="total_<?php echo $cart->productId; ?>"><?php echo ($cart->quantity * $cart->unitPrice); ?></span></strong>
                                </td>
                                <td class="del-goods-col">
                                    <a class="" onclick="return confirm('Are you sure want to delete?')" href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'cart', $cart->id]); ?>"><img src="<?php echo $this->Url->build('/'); ?>assets/img/close.png"></a>
                                </td>
                                <?php
                                  $SUBTOTAL += ($cart->quantity * $cart->unitPrice);
                                ?>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>

                <div class="shopping-total">
                    <ul>
                        <li class="shopping-total-price">
                            <em>Total</em>
                            <strong class="price"><span>$</span><span id="Finaltotal"><?php echo number_format($SUBTOTAL, 2); ?></span></strong>
                        </li>
                    </ul>
                </div>
            </div>
            <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'index']); ?>"><button class="btn btn-default" type="submit">Continue shopping <i class="fa fa-shopping-cart"></i></button></a>
            <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'payment']); ?>" target="_blank"><button class="btn btn-primary" type="submit">Checkout <i class="fa fa-check"></i></button></a>
        </div>
    </div>
</div>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script>
    jQuery(document).ready(function(){
        $(".changeQTT").on('change', function(){
            var QUA = $(this).val();
            var IDD = $(this).attr('id');
            $("#newsAjaxLoader").css("display", "block");
            jQuery.ajax({
                url : "<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'addtocart2']);?>/"+IDD+'/'+QUA,
                success: function(response) {
                    $("#newsAjaxLoader").css("display", "none");
                    
                    $("#total_"+IDD).html(response.mainTotal);
                    $("#Finaltotal").html(response.finalTotal);
                    $("#"+IDD).val(response.quantity);
                              
                    return false;
                }
            });
        });
    });
</script>