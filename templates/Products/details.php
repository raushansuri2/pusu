<style>
  .appint-details {
    padding: 9px 0;
    border-top: 1px solid #E4E4E4;
  }

  .appint-details label {
    float: right;
  }

  .seller-info {
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #E1E1E1;
    background-color: #f9f9f9;
  }

  .addTOcart {
    background: #ffcc00 !important;
  }

  .product-main-image img {
    max-width: 100%;
    height: auto;
  }

  .star-rating .fa {
    font-size: 20px;
    color: #FFD700; /* Gold color for stars */
    cursor: pointer;
  }

  .star-rating .fa-star-o {
    color: #ccc; /* Gray color for unselected stars */
  }

  .theme-btn {
    background-color: #007bff;
    color: white;
  }

  .theme-btn:hover {
    background-color: #0056b3;
  }

  .review-list {
    list-style-type: none;
    padding: 0;
  }

  .reviews-box {
    margin-bottom: 15px;
    border: 1px solid #E1E1E1;
    padding: 10px;
    border-radius: 5px;
  }

  .review-avatar img {
    border-radius: 50%;
  }

  /* Ensure the container takes full width */
  .container-fluid {
    padding: 0; /* Remove padding to ensure full width */
  }

  .product-dimensions {
    margin-top: 10px;
  }
</style>

<section>
    <div class="container-fluid"> <!-- Use container-fluid for full width -->
        <div class="row">
            <aside class="col-md-3 col-sm-12">
                <div class="sidebar">
                    <div class="widget-boxed">
                        <header class="widget-boxed-header">
                            <h4>Select Categories</h4>
                        </header>
                        <div class="widget-boxed-body padd-top-10 padd-bot-0">
                            <div class="side-list">
                                <ul class="category-list list-unstyled">
                                  <?php foreach ($categories as $category) { ?>
                                    <li>
                                      <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'index', '?' => ['categoryid' => $category->id]]) ?>" target="_blank">
                                        <?php echo h($category->name); ?> <span class="badge bg-g"><?php echo count($category->products); ?></span>
                                      </a>
                                    </li>
                                  <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <main class="col-md-9 col-sm-12">
                <div class="product-page">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="product-main-image">
                              <?php $IMG = ($product->image != '') ? $this->Url->build('/') . 'img/uploads/products/' . $product->image : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                              <img src="<?php echo $IMG; ?>" alt="<?php echo h($product->productName); ?>" class="img-responsive">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <h1><?php echo h($product->productName); ?></h1>
                            <div class="rating-box">
                                <div class="review-comment-stars">
                                    <?php for($R=1; $R<=5; $R++){
                                        if($R <= @$product->AVGRating){?>
                                            <i class="fa fa-star"></i>
                                    <?php }else{ ?>
                                            <i class="fa fa-star empty"></i>
                                    <?php } }?>
                                    &nbsp;<span style="color:blue;"><?php echo $totalReviewCount; ?> Rating</span>
                                </div>
                            </div>
                            <div class="price-availability-block clearfix">
                                <div class="price">
                                    <?php if(!empty($product->specialPrice) || $product->specialPrice != 0) {?>
                                        <?php if ($product->unitPrice > $product->specialPrice) { ?>
                                          <strike style="color: #ff0000; font-weight: bold;"><span>$<?php echo h($product->unitPrice); ?></span></strike>
                                        <?php } ?>
                                        <span style="color: #0342a1; font-weight: bold;">$<?php echo h($product->specialPrice); ?></span>
                                    <?php } else { ?>
                                        <span style="color: #0342a1; font-weight: bold;"><?php echo ($product->unitPrice == 0) ? 'FREE' : '$'.h($product->unitPrice); ?></span>
                                    <?php } ?>    
                                </div>
                                <div class="availability">Availability: <strong><?php echo h($product->quantity); ?> In Stock</strong></div>
                            </div>
                            <div class="description">
                                <p><?php echo h($product->description); ?></p>
                            </div>
                            <div class="product-dimensions">
                                <p><strong>Weight:</strong> <?php echo h($product->weight); ?> pounds</p>
                                <p><strong>Dimensions:</strong> <?php echo h($product->length); ?> x <?php echo h($product->width); ?> x <?php echo h($product->height); ?> inches</p>
                            </div>
                            <div class="product-page-cart">
                                <div class="product-quantity">
                                    <input type="number" id="QUANT" value="<?php echo $product->quantity > 0 ? '1' : '0'; ?>" min="1" max="<?php echo h($product->quantity); ?>" class="form-control">
                                </div>
                                <?php if (in_array($product->id, $cartList) || $product->userId == $this->request->getSession()->read('RitevetUsers.id') || $product->quantity == 0) { ?>
                                    <button class="btn btn-primary" title="Oops! This item is either in your cart, owned by you, or currently out of stock." onclick="addtocart('<?php echo h($product->id); ?>');" id="ID_<?php echo h($product->id); ?>" disabled>Add to cart</button>
                                <?php } else { ?>
                                    <button class="btn btn-primary" onclick="addtocart('<?php echo h($product->id); ?>');" id="ID_<?php echo h($product->id); ?>">Add to cart</button>
                                <?php } ?>
                            </div>
                            <div class="seller-info">
                                <h3 class="seller-title">Seller Information</h3>
                                <div class="appint-details">
                                    <i class="fa fa-user"></i> Seller Name: <label><?php echo h(@$product->user->firstName); ?> <?php echo h(@$product->user->lastName); ?></label>
                                </div>
                            </div>
                            <!--ADD REVIEW START-->
                            <?php if($product->userId != $this->request->getSession()->read('RitevetUsers.id') && $orderCount > 0){?>
                            <div class="detail-wrapper" id="add-review">
                                <header class="detail-wrapper-header">
                                    <h4>Rate & Write Reviews</h4>
                                </header>
                                <div class="detail-wrapper-body">
                                    <?php echo $this->Form->create(null, ['class' => 'listar-formtheme listar-formaddlisting', 'onsubmit' => 'return validatereview();']) ?>
                                    <p><strong>Rate This</strong></p>
                                    <div class="star-rating">
                                        <span class="fa fa-star-o" data-rating="1"></span>
                                        <span class="fa fa-star-o" data-rating="2"></span>
                                        <span class="fa fa-star-o" data-rating="3"></span>
                                        <span class="fa fa-star-o" data-rating="4"></span>
                                        <span class="fa fa-star-o" data-rating="5"></span>
                                        <input type="hidden" name="star" class="rating-value" value="0">
                                    </div><br />
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <textarea name="message" id="message" class="form-control height-110" placeholder="Tell us your experience..."></textarea>
                                        </div>
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn theme-btn">Submit your review</button>
                                        </div>
                                    </div>
                                    <?php echo $this->Form->end(); ?>
                                </div>
                            </div>
                            <?php } ?>
                            <!--ADD REVIEW END-->
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="row">
            <!-- REVIEWS SECTION START-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-wrapper">
                            <header class="detail-wrapper-header">
                                <h4><?php echo $this->Paginator->counter(__('{{count}}')) ?> Review(s)</h4>
                            </header>
                            <div class="detail-wrapper-body">
                                <ul class="review-list">
                                    <?php foreach($reviews as $review){ ?>
                                    <li>
                                        <div class="reviews-box">
                                            <div class="review-body">
                                                <div class="review-avatar">
                                                    <?php $RIMG = (@$review->reviewfrom->profile_picture !='') ? $this->Url->build('/').'img/uploads/users/'.@$review->reviewfrom->profile_picture : $this->Url->build('/').'img/dummy.jpg'; ?>
                                                    <img alt="" src="<?php echo $RIMG;?>" class="avatar avatar-140 photo">
                                                </div>
                                                <div class="review-content">
                                                    <div class="review-info">
                                                        <div class="review-comment">
                                                            <div class="review-author"><?php echo (@$review->reviewfrom->firstName !='') ? @$review->reviewfrom->firstName." ".@$review->reviewfrom->lastName : '-';?></div>
                                                            <div class="review-comment-stars">
                                                                <?php for($R1=1; $R1<=5; $R1++){
                                                                    if($R1 <= $review->star){?>
                                                                        <i class="fa fa-star"></i>
                                                                <?php }else{ ?>
                                                                        <i class="fa fa-star empty"></i>
                                                                <?php } }?>
                                                            </div>
                                                        </div>
                                                        <div class="review-comment-date">
                                                            <div class="review-date">
                                                                <span><?php echo date("M jS, Y, g:i a", strtotime($review->created));?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p><?php echo h($review->message); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                                        
                                <div class="paging-container" id="add-review">
                                    <?php
                                    if($this->Paginator->counter(__('{{count}}')) != 0) {?> 
                                        <p><?php echo $this->Paginator->counter(__('Showing {{start}} - {{end}} of {{count}} Record(s)')); ?></p>
                                        <?php if($this->Paginator->counter(__('{{pages}} ')) > 1) {?>
                                        <ul class="pagination">
                                            <?php       
                                                echo $this->Paginator->prev(__('Previous'), ['tag' => 'li', 'escape' => false], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a', 'escape' => false]);
                                                echo $this->Paginator->numbers(['separator' => '', 'currentTag' => 'a', 'ellipsis' => '', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1]);
                                                echo $this->Paginator->next(__('Next'), ['tag' => 'li', 'escape' => false, 'currentClass' => 'disabled'], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a', 'escape' => false]);              
                                            ?>
                                        </ul>
                                        <?php } ?>
                                        <div class="cl"></div>  
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- REVIEWS SECTION END-->
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function addtocart(ID) {
        $("#newsAjaxLoader").css("display", "block");
        var QUA = $("#QUANT").val();
        jQuery.ajax({
          url: "<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'addtocart2']);?>/" + ID + '/' + QUA,
          dataType: 'json',
          success: function(response) {
                $("#newsAjaxLoader").css("display", "none");
                $("#ID_" + ID).addClass('addTOcart');
                $('#HeaderC').html(response.cartCount);
                $('#QUANT').val(response.quantity);
                
                // Disable the button to prevent repeated requests
                $("#ID_" + ID).text('Added').prop('disabled', true);
            }
        });
    }

    // Star rating functionality
    $(document).ready(function() {
        $('.star-rating .fa').on('click', function() {
            var rating = $(this).data('rating');
            $('.rating-value').val(rating);
            $('.star-rating .fa').removeClass('fa-star').addClass('fa-star-o');
            $(this ).prevAll().addBack().removeClass('fa-star-o').addClass('fa-star');
        });
    });

    function validatereview() {
        var rating = $('.rating-value').val();
        var message = $('#message').val();
        if (rating == 0) {
            alert('Please select a star rating for your review.');
            return false;
        }
        if (message.trim() === '') {
            alert('Please provide a message for your review.');
            return false;
        }
        return true;
    }
</script>