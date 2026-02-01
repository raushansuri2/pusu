<style>
    /* General Styles */
    .container {
        width: 100%;
        padding: 0 20px;
        margin: 0 auto;
        max-width: 1280px; /* Slightly wider for modern screens */
    }
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -20px;
    }
    [class*="col-"] {
        padding: 0 20px;
    }

    /* Add to Cart Button */
    .addTOcart { 
        background: linear-gradient(135deg, #ffcc00, #ff9900) !important;
        color: #fff;
        font-weight: 600;
        transition: transform 0.2s ease, background 0.3s ease;
        border-radius: 8px;
        padding: 10px 20px;
    }
    .addTOcart:hover:not(:disabled) {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #ff9900, #ff6600) !important;
    }
    .addTOcart:disabled {
        background: #d3d3d3 !important;
        cursor: not-allowed;
        opacity: 0.7;
    }

    /* Widget Boxed */
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
    .widget-boxed-body {
        padding: 25px;
    }

    /* Enhanced Search Section */
    .input-group {
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 1000px;
        margin: 20px auto;
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
    .input-group-btn .btn-search {
        background: linear-gradient(135deg, #ff9900, #ff6600);
        color: #fff;
    }
    .input-group-btn .btn-search:hover {
        background: linear-gradient(135deg, #ff6600, #ff4500);
    }
    .input-group-btn .reset-btn {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
        border-radius: 0 50px 50px 0;
        margin-left: 5px;
    }
    .input-group-btn .reset-btn:hover {
        background: linear-gradient(135deg, #c82333, #b31b2b);
    }

    /* Sidebar */
    .sidebar .widget-boxed {
        margin-bottom: 30px;
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

    /* Product Item */
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
    .btn.add2cart {
        background: linear-gradient(135deg, #ff9900, #ff6600);
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        width: 100%;
        transition: all 0.3s ease;
    }
    .btn.add2cart:hover:not(:disabled) {
        background: linear-gradient(135deg, #ff6600, #ff4500);
        transform: translateY(-2px);
    }
    .rating-box {
        margin: 15px 0;
    }
    .review-comment-stars i {
        color: #ffcc00;
        font-size: 16px;
    }
    .review-comment-stars .empty {
        color: #e0e0e0;
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

    /* Pagination */
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

    /* Responsive Design */
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
        .input-group-btn .reset-btn {
            margin-left: 0;
        }
        .product-item {
            margin: 0 auto 30px;
            max-width: 350px;
        }
        .pagination ul {
            flex-direction: row;
            justify-content: center;
        }
        .pagination li {
            margin: 5px 2px;
        }
    }
</style>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="widget-boxed padd-10">
                    <div class="widget-boxed-body">
                        <div class="input-group">
                            <?= $this->Form->create(null, ['type' => 'get', 'id' => 'searchForm', 'url' => ['controller' => 'Freestaffs', 'action' => 'index']]) ?>
                            <?= $this->Form->control('keyword', [
                                'type' => 'text',
                                'class' => 'form-control',
                                'placeholder' => 'Search free stuff…',
                                'label' => false,
                                'value' => $this->request->getQuery('keyword'),
                                'id' => 'searchInput'
                            ]) ?>
                            <span class="input-group-btn">
                                <?= $this->Form->button('Search', [
                                    'type' => 'submit',
                                    'class' => 'btn btn-search height-50'
                                ]) ?>
                                <?= $this->Html->link('Reset', ['controller' => 'Freestaffs', 'action' => 'index'], [
                                    'class' => 'btn height-50 reset-btn'
                                ]) ?>
                            </span>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="sidebar">
                    <div class="widget-boxed">
                        <div class="widget-boxed-header">
                            <h4>Categories</h4>
                        </div>
                        <div class="widget-boxed-body padd-top-10 padd-bot-0">
                            <div class="side-list">
                                <ul class="category-list">
                                    <?php foreach ($categories as $category): ?>
                                        <li>
                                            <?php
                                            $url = ['controller' => 'Freestaffs', 'action' => 'index', '?' => []];
                                            if ($this->request->getQuery('keyword')) {
                                                $url['?']['keyword'] = $this->request->getQuery('keyword');
                                            }
                                            $url['?']['categoryid'] = $category->id;
                                            ?>
                                            <?= $this->Html->link(
                                                h($category->name) . ' <span class="badge bg-g">' . count($category->products) . '</span>',
                                                $url,
                                                ['escape' => false]
                                            ) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="row product-list">
                    <?php if (!empty($freestaffs)): ?>
                        <?php foreach ($freestaffs as $freestaff): ?>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="product-item">
                                    <div class="pi-img-wrapper">
                                        <?php 
                                        $imgPath = $freestaff->image ? '/img/uploads/products/' . $freestaff->image : '/img/dummy.jpg';
                                        ?>
                                        <?= $this->Html->link(
                                            $this->Html->image($imgPath, [
                                                'class' => 'img-responsive',
                                                'alt' => h($freestaff->productName)
                                            ]),
                                            ['controller' => 'Freestaffs', 'action' => 'details', base64_encode($freestaff->id)],
                                            ['escape' => false, 'target' => '_blank']
                                        ) ?>
                                    </div>
                                    <h3>
                                        <?= $this->Html->link(
                                            h($freestaff->productName),
                                            ['controller' => 'Freestaffs', 'action' => 'details', base64_encode($freestaff->id)],
                                            ['target' => '_blank']
                                        ) ?>
                                    </h3>
                                    <div class="rating-box">
                                        <div class="review-comment-stars">
                                            <?php for ($r = 1; $r <= 5; $r++): ?>
                                                <?php if ($r <= ($freestaff->AVGRating ?? 0)): ?>
                                                    <i class="fa fa-star"></i>
                                                <?php else: ?>
                                                    <i class="fa fa-star empty"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <span style="color:blue;">
                                                (<?= count($freestaff->reviews ?? []) ?>) Rating
                                            </span>
                                        </div>
                                    </div>
                                    <?php if ($freestaff->unitPrice == 0): ?>
                                        <div class="pi-price">FREE</div>
                                    <?php endif; ?>
                                    <?php if (in_array($freestaff->id, $cartList) || $freestaff->userId == $this->request->getSession()->read('RitevetUsers.id') || $freestaff->quantity == 0): ?>
                                        <button title="Oops! This item is either in your cart, owned by you, or currently out of stock." 
                                                class="btn btn-default add2cart addTOcart" disabled>Unavailable</button>
                                    <?php else: ?>
                                        <button onclick="addtocart('<?= h($freestaff->id) ?>');" 
                                                id="ID_<?= h($freestaff->id) ?>" 
                                                class="btn btn-default add2cart">Add to cart</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <p>No Product found</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="pagination">
                    <ul class="pagination pagination-sm">
                        <?= $this->Paginator->prev('Previous', ['tag' => 'li']) ?>
                        <?= $this->Paginator->numbers(['tag' => 'li', 'currentClass' => 'active']) ?>
                        <?= $this->Paginator->next('Next', ['tag' => 'li']) ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        let timeout;
        $('#searchInput').on('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                const keyword = $('#searchInput').val();
                const url = new URL(window.location.origin + "<?= $this->Url->build(['controller' => 'Freestaffs', 'action' => 'index']) ?>");
                if (keyword) url.searchParams.set('keyword', keyword);
                window.location.href = url.toString();
            }, 500); // 500ms debounce
        });

        $('.category-list a').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const url = new URL(window.location.origin + href);
            const keyword = $('#searchInput').val();
            if (keyword) url.searchParams.set('keyword', keyword);
            window.location.href = url.toString();
        });

        $('.reset-btn').on('click', function(e) {
            e.preventDefault();
            $('#searchInput').val(''); // Clear the input
            window.location.href = "<?= $this->Url->build(['controller' => 'Freestaffs', 'action' => 'index']) ?>";
        });
    });
</script>