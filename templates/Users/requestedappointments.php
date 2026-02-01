<style>
/* Form Styles */
.form-verticle {
    margin-bottom: 20px;
}

.form-box {
    margin-top: -50px;
    margin-bottom: 10px;
}

.min-pad {
    padding: 10px;
}

/* Button Styles */
.btn {
    border-radius: 5px;
    padding: 10px 15px;
    font-size: 16px;
    transition: background-color 0.3s, transform 0.2s;
}

.btn:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Card Styles */
.card {
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    margin-bottom: 20px;
    transition: transform 0.2s;
}

.card:hover {
    transform: scale(1.05);
}

.card-img-top {
    border-radius: 50%;
    margin: 0 auto;
}

.card-body {
    text-align: center;
}

.card-footer {
    background-color: #f8f9fa;
}

/* Status Badge Styles */
.status-badge {
    padding: 5px;
    border: 1px solid;
    border-radius: 5px;
    writing-mode: vertical-rl;
    float: right;
    margin-top: -200px;
    margin-right: 5px;
}

.status-pending {
    color: #B2A7A7;
    border-color: #B2A7A7;
}

.status-pending-payment {
    color: #ebef0c;
    border-color: #ebef0c;
}

.status-rejected {
    color: #ff0000;
    border-color: #ff0000;
}

.status-cancelled {
    color: #004eff;
    border-color: #004eff;
}

.status-upcoming {
    color: #d1b912;
    border-color: #d1b912;
}

.status-completed {
    color: #12D119;
    border-color: #12D119;
}

.status-noshow {
    color: #a220ab;
    border-color: #a220ab;
}

/* Pagination Styles */
.paging-container {
    margin-top: 20px;
}

.pagination {
    list-style: none;
    padding: 0;
}

.pagination li {
    display: inline;
    margin: 0 5px;
}

.records-showing {
    font-weight: bold;
}
</style>

<?= $this->Form->create(null, [
    'type' => 'get',
    'class' => 'form-verticle',
    'inputDefaults' => ['novalidate' => true]
]) ?>
<div class="row">
    <div class="col-md-5 col-sm-5 min-pad">
        <div class="form-box">
            <?= $this->Form->control('status', [
                'type' => 'select',
                'options' => [
                    '' => 'All',
                    0 => 'Pending',
                    1 => 'Payment Pending',
                    2 => 'Rejected',
                    3 => 'Cancelled',
                    4 => 'Upcoming',
                    5 => 'Completed',
                    6 => 'Noshow'
                ],
                'value' => $this->request->getQuery('status'),
                'class' => 'form-control',
                'id' => 'status-filter',
                'label' => false
            ]) ?>
        </div>
    </div>
    <div class="col-md-2 col-sm-2 min-pad">
        <div class="form-box">
            <?= $this->Form->button('Search', [
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'style' => 'width:100%;'
            ]) ?>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<div id="doctors-list">
    <div class="row match-height">
        <?php if (!empty($requests)) : ?>
            <?php foreach ($requests as $request) : ?>
                <?php if ($this->request->getSession()->read('RitevetUsers.id') == $request->service_provider_user->id) : ?>
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <div class="card" style="height: 314.617px;">
                            <?php 
                            $UIMG = !empty($request->sender_user->profile_picture) 
                                ? $this->Url->build('/img/uploads/users/' . $request->sender_user->profile_picture) 
                                : $this->Url->build('/img/dummy.jpg'); 
                            ?>
                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'requestedappointmentsdetail', $request->id]) ?>" target="_blank">
                                <img src="<?= $UIMG ?>" alt="" class="card-img-top img-fluid rounded-circle w-25 mx-auto mt-1" style="width:120px; height:120px;">
                            </a>
                            <div class="card-body">
                                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'requestedappointmentsdetail', $request->id]) ?>" target="_blank">
                                    <h6 class="card-title font-large-1 mb-0 text-center"><?= h($request->sender_user->firstName) ?> <?= h($request->sender_user->lastName) ?></h6>
                                </a>
                                <?php if (!empty($request->requested_service_date)) : ?>
                                    <p class="card-text font-medium-1 text-center mb-0"><?= date("F jS, Y", strtotime($request->requested_service_date)) ?></p>
                                <?php else : ?>
                                    <p class="card-text font-medium-1 text-center mb-0">
                                        <?php 
                                        if (!empty($request->multi_date)) {
                                            $dates = explode(',', $request->multi_date);
                                            $formattedDates = array_map('trim', array_map(function($date) {
                                                return date("F jS, Y", strtotime($date));
                                            }, $dates));
                                            echo implode(' | ', $formattedDates);
                                        }
                                        ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php 
                                $statusClass = '';
                                $statusText = '';
                                switch ($request->status) {
                                    case 0:
                                        $statusClass = 'status-badge status-pending';
                                        $statusText = 'Pending';
                                        break;
                                    case 1:
                                        $statusClass = 'status-badge status-pending-payment';
                                        $statusText = 'Payment Pending';
                                        break;
                                    case 2:
                                        $statusClass = 'status-badge status-rejected';
                                        $statusText = 'Rejected';
                                        break;
                                    case 3:
                                        $statusClass = 'status-badge status-cancelled';
                                        $statusText = 'Cancelled';
                                        break;
                                    case 4:
                                        $statusClass = 'status-badge status-upcoming';
                                        $statusText = 'Upcoming';
                                        break;
                                    case 5:
                                        $statusClass = 'status-badge status-completed';
                                        $statusText = 'Completed';
                                        break;
                                    case 6:
                                        $statusClass = 'status-badge status-noshow';
                                        $statusText = 'Noshow';
                                        break;
                                }
                                ?>
                                <span class="<?= $statusClass ?>" title="<?= $statusText ?>"><?= $statusText ?></span>
                            </div>
                            <div class="card-footer mx-auto text-center">
                                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'requestedappointmentsdetail', $request->id]) ?>" target="_blank" class="btn btn-outline-danger btn-sm">See details -->>></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-xl-12 col-lg-12 col-md-12">No Data found!</div>
        <?php endif; ?>
    </div>
    
    <div class="paging-container">
        <?php if ($this->Paginator->hasPage()) : ?>
            <p class="records-showing">
                <?= $this->Paginator->counter('Showing {{start}} - {{end}} of {{count}} Records') ?>
            </p>
            <?php if ($this->Paginator->hasNext() || $this->Paginator->hasPrev()) : ?>
                <ul class="pagination">
                    <?= $this->Paginator->prev('Prev', ['escape' => false], null, ['class' => 'disabled']) ?>
                    <?= $this->Paginator->numbers(['separator' => '', 'currentTag' => 'a', 'ellipsis' => '', 'currentClass' => 'active']) ?>
                    <?= $this->Paginator->next('Next', ['escape' => false], null, ['class' => 'disabled']) ?>
                </ul>
            <?php endif; ?>
            <div class="cl"></div>
        <?php endif; ?>
    </div>
</div>