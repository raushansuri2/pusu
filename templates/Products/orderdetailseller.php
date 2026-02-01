
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="goods-page">
              <div class="goods-data clearfix">
								<?php echo $this->Flash->render(); ?>
                <div class="table-wrapper-responsive">
                <table summary="Shopping cart">
                  <tbody><tr>
				  <th class="goods-page-image" style="text-align: center">#OrderID</th>
					<th class="goods-page-image" style="text-align: center">Status</th>
                    <th class="goods-page-image">Image</th>
                    <th class="goods-page-description">Product</th>
                    <th class="goods-page-ref-no">#SKU</th>
                    <th class="goods-page-quantity">Quantity</th>
                    <th class="goods-page-price">Unit price</th>
					<th class="goods-page-price">Shipping Amt</th>
                    <th class="goods-page-total" style="text-align: center">Total</th>
										 
                  </tr>
									<?php $SUBTOTAL = 0;$ShippingAm=0;
									foreach($orderList as $cart){ ?>
                  <tr>
										<td class="goods-page-ref-no" style="text-align: center">
                      <?php echo @$cart->orderID;?>
                    </td>
										<td class="goods-page-total" style="text-align: center">
                      <?php if($cart->userId == $this->request->session()->read('RitevetUsers.id')){
												if($cart->orderStatus == 3){
													echo '<span style="padding: 4px;border: 2px solid #09d20f;color: #09d20f;border-radius: 10%;">Delivered</spam>';
												}else{
													echo '<span style="padding: 4px;border: 2px solid #FED019;color: #FED019;border-radius: 10%;">Precess</spam>';
												}
											}else{
												if($cart->orderStatus == 3){
													echo '<span style="padding: 4px;border: 2px solid #09d20f;color: #09d20f;border-radius: 10%;">Delivered</spam>';
												}else{ ?>
													<a style="padding: 4px;border: 2px solid #FED019;color: #FED019;border-radius: 10%;" href="<?php echo $this->Url->build(['controller'=>'products','action'=>'delivered',$cart->id]);?>" onclick="return confirm('Are you sure this product has been deliverd?');">Precess</a>
												<?php
												}
											}?>
                    </td>
                    <td class="goods-page-image">
											<?php $IMG = (@$cart->product->image !='') ? $this->Url->build('/').'img/uploads/products/'.@$cart->product->image : $this->Url->build('/').'img/dummy.jpg'; ?>
                      <a href="<?php echo $this->Url->build(['controller'=>'Products','action'=>'details', base64_encode($cart->productId)]);?>" target="_blank">
						<img src="<?php echo $IMG;?>" alt="Berry Lace Dress"></a>
                    </td>
                    <td class="goods-page-description">
                      <h3><a href="<?php echo $this->Url->build(['controller'=>'Products','action'=>'details', base64_encode($cart->productId)]);?>"  target="_blank"><?php echo @$cart->product->productName;?></a></h3>
											<!--<p><?php //echo @$cart->product->description;?></p>-->
                      <!--<p><strong>Item 1</strong> - Color: Green; Size: S</p>
                      <em>More info is here</em>-->
                    </td>
                    <td class="goods-page-ref-no">
                      <?php echo @$cart->product->SKU;?>
                    </td>
                    <td class="goods-page-quantity">
                      <div class="product-quantity">
                          <div class="bootstrap-touchspin input-group-sm">
							<p style="text-align: center"><?php echo $cart->quantity;?></p>
						  </div>
                      </div>
                    </td>
                    <td class="goods-page-price">
                      <strong><span>$</span><?php echo @$cart->price;?></strong>
                    </td>
					<td class="goods-page-price">
                      <strong><span>$</span><?php echo (@$cart->shippingAmount) ? number_format($cart->shippingAmount,2) : '0.00';?></strong>
                    </td>
                    <td class="goods-page-total" style="text-align: right">
                      <strong><span>$</span><span><?php echo (($cart->quantity*@$cart->price) + @$cart->shippingAmount);?></span></strong>
                    </td>
										
                    
					<?php $SUBTOTAL += ($cart->quantity*@$cart->price); ?>
					<?php $ShippingAm += (@$cart->shippingAmount); ?>
                  </tr>
				<?php } ?>
                  
                </tbody></table>
                </div>
				
				<div class="">
					<div class="col-md-8 col-sm-12">
						<div class="detail-wrapper-header">
								<h4><i class="ti-files theme-cl mrg-r-10"></i>Delivery Address</h4>
							</div>
						<div class="col-md-12 col-sm-12">
                        
                        <strong><?php echo $cart->shippingName;?></strong><br><br>
                        
<?php echo $cart->shippingAddress;?>,<br><?php echo $cart->ShippingCity;?> <br><?php echo $cart->ShippingState;?> - <?php echo $cart->shippingZipcode;?>
<br><br>
<strong>Order Status : <?php echo $cart->orderStatus;?></strong><br>
<strong>Transaction ID : <?php echo $cart->transactionID;?></strong><br>
<strong>Payment Mode : <?php echo $cart->paymentTrouugh;?></strong>

<br>
<!--<strong>Seller Name : <?php echo @$cart->product->sellerName;?></strong><br>
<strong>Seller Email : <?php echo @$cart->product->sellerEmail;?></strong><br>
<strong>Seller Phone : <?php echo @$cart->product->sellerPhone;?></strong>
<strong>Seller Company Name : <?php echo @$cart->product->SellerCompanyName;?></strong>-->
</div>
						
						
						
					</div>
					<div class="col-md-4 col-sm-12">
						<div class="shopping-total">
						  <ul>
							<li>
							  <em>Sub total</em>
							  <strong class="price"><span>$</span><span id="Subtotal"><?php echo number_format($SUBTOTAL,2);?></span></strong>
							</li>
							<li>
							  <em>Shipping cost</em>
							  <strong class="price"><span>$</span><?php echo number_format($ShippingAm,2);?></strong>
							</li>
							<li class="shopping-total-price">
							  <em><strong>Total</strong></em>
							  <strong class="price"><span></span><span class="theme-cl pull-right">$<?php echo number_format(($SUBTOTAL+$ShippingAm),2);?></span></strong>
							</li>
						  </ul>
						</div>
					</div>
				</div>
				
              </div>
            </div>
        </div>
      </div>
    

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script>
	jQuery(document).ready(function(){
        $(".changeQTT").on('change', function(){
			var QUA = $(this).val();
			var IDD = $(this).attr('id');
			//alert(IDD);
			//alert(QUA);
			$("#newsAjaxLoader").css("display", "block");
			jQuery.ajax({
						url : "<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'addtocart2']);?>/"+IDD+'/'+QUA,
						success: function(response) {
										$("#newsAjaxLoader").css("display", "none");
										var obj = jQuery.parseJSON(response);
										//alert(obj.finalTotal);
										$("#total_"+IDD).html(obj.mainTotal);
										$("#Subtotal").html(obj.total);
										$("#Finaltotal").html(obj.finalTotal);
										//$('#HeaderC').html(response);
										//var obj = jQuery.parseJSON(response);
										//alert(JSON.stringify(response));
										//$('select#lepId').html(obj.contactperson);
										//$("#servicerequestName").after(obj.contactperson);
									//alert( obj.contactperson);
									//alert()           
									return false;
						}
			});
		});
    });
</script>