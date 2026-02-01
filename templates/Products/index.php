<!-- Dependencies -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.1/nouislider.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.1/nouislider.min.js"></script>

<style>
    /* All other styles remain unchanged until Rating Stars section */

    /* Rating Stars */
    .rating-box {
        margin: 15px 0;
        min-height: 20px;
    }
    .review-comment-stars {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .review-comment-stars i {
        font-size: 16px;
        transition: color 0.3s ease;
    }
    .review-comment-stars i.zero {
        color: #e0e0e0; /* Gray for zero rating */
    }
    .review-comment-stars i.filled {
        color: #ffcc00; /* Yellow for rated stars */
    }
    .review-comment-stars i.unfilled {
        color: #e0e0e0; /* Gray for unfilled stars when rating > 0 */
    }
    .review-comment-stars span {
        font-size: 14px;
        color: #4169E1;
        margin-left: 8px;
    }
    
    /* Rest of the styles remain unchanged */
    .container {
        width: 100%;
        padding: 0 20px;
        margin: 0 auto;
        max-width: 1280px;
    }
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -20px;
    }
    [class*="col-"] {
        padding: 0 20px;
    }
    .addTOcart, .btn.add2cart { 
        background: linear-gradient(135deg, #ffcc00, #ff9900);
        color: #fff;
        font-weight: 600;
        transition: transform 0.2s ease, background 0.3s ease;
        border-radius: 8px;
        padding: 12px 20px;
        border: none;
        font-size: 16px;
        width: 100%;
        cursor: pointer;
        text-align: center;
    }
    .addTOcart:hover:not(:disabled), .btn.add2cart:hover:not(:disabled) {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #ff9900, #ff6600);
    }
    .addTOcart:disabled, .btn.add2cart:disabled {
        background: #d3d3d3 !important;
        cursor: not-allowed;
        opacity: 0.7;
    }
    .widget-boxed {
        border: none;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.2s ease;
    }
    .widget-boxed:hover {
        transform: translateY(-5px);
    }
    .widget-boxed-header {
        background: linear-gradient(90deg, #ff9900, #ffcc00);
        color: #fff;
        padding: 15px 20px;
    }
    .widget-boxed-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .widget-boxed-body {
        padding: 25px;
    }
    .input-group {
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        background: #fff;
        border-radius: 50px;
        padding: 8px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease;
    }
    .input-group:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .form-control {
        flex-grow: 1;
        border: none;
        border-radius: 50px 0 0 50px;
        padding: 15px 25px;
        background: transparent;
        font-size: 16px;
        font-family: 'Arial', sans-serif;
        color: #333;
        transition: background 0.3s ease;
    }
    .form-control::placeholder {
        color: #aaa;
        font-style: italic;
    }
    .form-control:focus {
        outline: none;
        background: #fff;
        box-shadow: inset 0 0 5px rgba(255, 153, 0, 0.3);
    }
    .input-group-btn {
        display: flex;
    }
    .input-group-btn .btn {
        padding: 15px 30px;
        border: none;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-search {
        background: linear-gradient(135deg, #ff9900, #ff6600);
        color: #fff;
    }
    .btn-search:hover {
        background: linear-gradient(135deg, #ff6600, #ff4500);
    }
    .reset-btn {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
        border-radius: 0 50px 50px 0;
        margin-left: 5px;
    }
    .reset-btn:hover {
        background: linear-gradient(135deg, #c82333, #b31b2b);
    }
    .sidebar .widget-boxed {
        margin-bottom: 30px;
    }
    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .category-list li {
        margin-bottom: 12px;
    }
    .category-list a {
        color: #444;
        text-decoration: none;
        display: flex;
        justify-content: space-between;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f9f9f9;
    }
    .category-list a:hover {
        background: #ff9900;
        color: #fff;
        transform: translateX(5px);
    }
    .badge.bg-g {
        background: #28a745;
        color: #fff;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .category-list a:hover .badge.bg-g {
        background: #fff;
        color: #28a745;
    }
    .shop-by-price {
        padding: 25px;
    }
    .filter-button {
        background: linear-gradient(135deg, #ff9900, #ff6600);
        color: #fff;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }
    .filter-button:hover {
        background: linear-gradient(135deg, #ff6600, #ff4500);
        transform: translateY(-2px);
    }
    .price-range {
        font-size: 16px;
        color: #666;
        margin-top: 15px;
        text-align: center;
    }
    .range-value {
        font-weight: 700;
        color: #333;
    }
    .noUi-target {
        background: #f9f9f9;
        border: none;
        box-shadow: none;
        border-radius: 50px;
    }
    .noUi-connect {
        background: linear-gradient(90deg, #ffcc00, #ff9900);
    }
    .noUi-handle {
        background: #fff;
        border: 2px solid #ff9900;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .product-item {
        border: none;
        border-radius: 12px;
        padding: 20px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        margin-bottom: 30px;
    }
    .product-item:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transform: translateY(-5px);
    }
    .pi-img-wrapper img {
        border-radius: 10px;
        max-height: 220px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.3s ease;
    }
    .product-item:hover .pi-img-wrapper img {
        transform: scale(1.05);
    }
    .pi-price {
        color: #28a745;
        font-weight: 700;
        font-size: 1.4em;
        margin: 15px 0;
        letter-spacing: 0.5px;
    }
    .pi-price strike {
        color: #dc3545;
        font-size: 0.9em;
        margin-right: 10px;
    }
    h3 a {
        color: #333;
        font-size: 18px;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    h3 a:hover {
        color: #ff9900;
    }
    .pagination ul {
        display: flex;
        list-style: none;
        padding: 0;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    .pagination li {
        margin: 5px;
    }
    .pagination a {
        padding: 10px 15px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #333;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .pagination .active a {
        background: #ff9900;
        color: #fff;
        border-color: #ff9900;
    }
    .pagination a:hover:not(.active) {
        background: #ff9900;
        color: #fff;
        border-color: #ff9900;
    }
    @media (min-width: 1200px) {
        .col-md-3 { width: 25%; }
        .col-md-4 { width: 33.33%; }
        .col-md-9 { width: 75%; }
        .col-md-12 { width: 100%; }
    }
    @media (min-width: 992px) and (max-width: 1199px) {
        .col-md-3 { width: 25%; }
        .col-md-4 { width: 33.33%; }
        .col-md-9 { width: 75%; }
        .col-md-12 { width: 100%; }
    }
    @media (min-width: 768px) and (max-width: 991px) {
        .col-sm-6 { width: 50%; }
        .col-sm-12 { width: 100%; }
        .col-md-3, .col-md-9 { width: 100%; }
        .sidebar { margin-bottom: 30px; }
    }
    @media (max-width: 767px) {
        .col-xs-12 { width: 100%; }
        .col-md-3, .col-md-4, .col-md-9, .col-md-12, .col-sm-6, .col-sm-12 { width: 100%; }
        .input-group {
            flex-direction: column;
            max-width: 100%;
            padding: 15px;
        }
        .form-control {
            border-radius: 50px;
            margin-bottom: 15px;
            width: 100%;
            text-align: center;
        }
        .input-group-btn {
            width: 100%;
            justify-content: space-between;
        }
        .input-group-btn .btn {
            flex-grow: 1;
            border-radius: 50px !important;
            margin: 0 5px;
            padding: 12px;
        }
        .reset-btn {
            margin-left: 0;
        }
        .product-item {
            margin: 0 auto 30px;
            max-width: 350px;
        }
    }
</style>

<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="widget-boxed p-3">
                    <div class="widget-boxed-body">
                        <div class="input-group">
                            <?php echo $this->Form->create(null, ['type' => 'get', 'novalidate' => true, 'class' => 'w-100']); ?>
                            <input type="text" name="keyword" class="form-control" placeholder="Search products..." value="<?php echo h($this->request->getQuery('keyword')); ?>" id="search-input">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-search">Search</button>
                                <button type="button" class="btn reset-btn" id="reset-button">Reset</button>
                            </span>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="sidebar">
                    <div class="widget-boxed">
                        <div class="widget-boxed-header">
                            <h4>Categories</h4>
                        </div>
                        <div class="widget-boxed-body">
                            <ul class="category-list">
                                <?php foreach($categories as $category): ?>
                                <li>
                                    <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'index', '?' => ['categoryid' => $category->id]]);?>">
                                        <?php echo h($category->name); ?> 
                                        <span class="badge bg-g"><?php echo count($category->products);?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="widget-boxed shop-by-price">
                        <div class="widget-boxed-header">
                            <h4>Price Filter</h4>
                        </div>
                        <div class="widget-boxed-body">
                            <div id="price-filter-range"></div>
                            <span id="price-filter-range-value" class="price-range d-block mt-3"></span>
                            <button class="filter-button mt-4" onclick="filterProducts()">Apply Filter</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="row product-list">
                    <?php if(!empty($products)): ?>
                        <?php foreach($products as $product): ?>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="product-item">
                                <div class="pi-img-wrapper">
                                    <?php 
                                    $imgPath = !empty($product->image) ? '/img/uploads/products/' . $product->image : '/img/dummy.jpg';
                                    $imgUrl = $this->Url->build($imgPath, ['fullBase' => true]);
                                    ?>
                                    <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'details', base64_encode($product->id)]);?>" target="_blank">
                                        <img src="<?php echo h($imgUrl); ?>" class="img-fluid" alt="<?php echo h($product->productName); ?>">
                                    </a>
                                </div>
                                <h3>
                                    <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'details', base64_encode($product->id)]);?>" target="_blank">
                                        <?php echo h($product->productName); ?>
                                    </a>
                                </h3>
                                <div class="rating-box">
                                    <div class="review-comment-stars">
                                        <?php 
                                        $rating = $product->AVGRating ?? 0;
                                        for($r = 1; $r <= 5; $r++): ?>
                                            <i class="fa fa-star<?php 
                                                if ($rating == 0) echo ' zero';
                                                elseif ($r <= $rating) echo ' filled';
                                                else echo ' unfilled';
                                            ?>"></i>
                                        <?php endfor; ?>
                                        <span>(<?php echo count($product->reviews ?? []); ?>) Rating</span>
                                    </div>
                                </div>
                                <div class="price-container">
                                    <?php if(!empty($product->specialPrice) && $product->specialPrice != 0): ?>
                                        <?php if($product->unitPrice > $product->specialPrice): ?>
                                            <div class="pi-price"><strike>$<?php echo number_format($product->unitPrice, 2); ?></strike> $<?php echo number_format($product->specialPrice, 2); ?></div>
                                        <?php else: ?>
                                            <div class="pi-price">$<?php echo number_format($product->unitPrice, 2); ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="pi-price">$<?php echo number_format($product->unitPrice, 2); ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php 
                                $isDisabled = in_array($product->id, $cartList ?? []) || 
                                            $product->userId == $this->request->getSession()->read('RitevetUsers.id') || 
                                            $product->quantity == 0;
                                ?>
                                <button 
                                    <?php if($isDisabled): ?>
                                        title="This item is either in your cart, owned by you, or out of stock"
                                        class="btn btn-default add2cart addTOcart" 
                                        disabled
                                    <?php else: ?>
                                        onclick="addtocart('<?php echo $product->id; ?>');" 
                                        id="ID_<?php echo $product->id; ?>" 
                                        class="btn btn-default add2cart"
                                    <?php endif; ?>>
                                    <?php echo $isDisabled ? 'Unavailable' : 'Add to Cart'; ?>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <p>No products found matching your criteria</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if(!empty($products)): ?>
                <div class="pagination">
                    <ul class="pagination pagination-sm">
                        <?php
                        echo $this->Paginator->prev('Previous', ['tag' => 'li']);
                        echo $this->Paginator->numbers(['tag' => 'li', 'currentClass' => 'active']);
                        echo $this->Paginator->next('Next', ['tag' => 'li']);
                        ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
function addtocart(ID) {
    $("#newsAjaxLoader").css("display", "block");
    jQuery.ajax({
        url: "<?= $this->Url->build(['controller' => 'Products', 'action' => 'addtocart']) ?>/" + ID,
        dataType: 'json',
        method: 'POST',
        headers: {
            'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
        },
        success: function(response) {
            $("#newsAjaxLoader").css("display", "none");
            $("#ID_" + ID).addClass('addTOcart').text('Added').prop('disabled', true);
            $('#HeaderC').html(response.cartCount);
            $('#QUANT').val(response.quantity);
        },
        error: function() {
            $("#newsAjaxLoader").css("display", "none");
            alert('Failed to add to cart');
        }
    });
}

$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const minPrice = parseInt(urlParams.get('min')) || 0;
    const maxPriceValue = parseInt(urlParams.get('max')) || <?php echo $maxPrice ?? 1000; ?>;
    const maxPrice = <?php echo $maxPrice ?? 1000; ?>;

    const rangeSlider = document.getElementById("price-filter-range");
    
    noUiSlider.create(rangeSlider, {
        start: [minPrice, maxPriceValue],
        connect: true,
        range: {
            'min': 1,
            'max': Math.max(maxPrice, maxPriceValue)
        },
        step: 1,
        format: {
            to: value => Math.round(value),
            from: value => Number(value)
        }
    });

    rangeSlider.noUiSlider.on('update', function(values) {
        const formattedValues = values.map(val => '$' + val.toLocaleString());
        document.getElementById("price-filter-range-value").innerText = formattedValues.join(" - ");
    });

    let timeout;
    $('#search-input').on('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            const keyword = $('#search-input').val();
            const url = new URL(window.location.origin + "<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'index']); ?>");
            if (keyword) url.searchParams.set('keyword', keyword);
            window.location.href = url.toString();
        }, 500);
    });

    $('#reset-button').on('click', function() {
        $('#search-input').val('');
        window.location.href = "<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'index']); ?>";
    });
});

function filterProducts() {
    const rangeSlider = document.getElementById("price-filter-range");
    const values = rangeSlider.noUiSlider.get();
    const minPrice = Math.round(values[0]);
    const maxPrice = Math.round(values[1]);
    
    const currentUrl = new URL(window.location.href);
    const params = new URLSearchParams(currentUrl.search);
    
    params.set('min', minPrice);
    params.set('max', maxPrice);
    
    currentUrl.search = params.toString();
    window.location.href = currentUrl.href;
}
</script>