<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Groups</h4>
            <p class="small-text">All the groups you currently manage in ERISAQuote Pro.</p>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">


            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupAdd']); ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="plus-square"></i>
                Add New Groups
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
<?php echo $this->Flash->render(); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 48px;"></th>
                                    <th>Group Name</th>
                                    <th width="40%">Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Zip</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($groups as $group): ?>
                                    <tr>
                                        <td>
                                            <div class="circle"><?= h(substr($group->group_name, 0, 1)) ?></div>
                                        </td>
                                        <td>
                                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'groupDetails', $group->id]) ?>">
                                                <?= h($group->group_name) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?= h($group->address1) ?>
                                            <?php if (!empty($group->address2)): ?>
                                                <br><?= h($group->address2) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= h($group->city) ?></td>
                                        <td><?= h($group->state_name) ?></td>
                                        <td><?= h($group->zip) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>

                        </table>
                    </div>

                    <!-- pegination start -->
                   <div class="d-flex justify-content-center mt-3">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <?= $this->Paginator->prev('&laquo; Previous', [
                                    'class' => 'page-item', 
                                    'escape' => false, 
                                    'disabledClass' => 'disabled'
                                ]) ?>

                                <?= $this->Paginator->numbers([
                                    'modulus' => 5,
                                    'first' => 1,
                                    'last' => 1,
                                    'templates' => [
                                        'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                                        'current' => '<li class="page-item active" aria-current="page"><a class="page-link" href="javascript:void(0)">{{text}}</a></li>',
                                    ],
                                ]) ?>

                                <?= $this->Paginator->next('Next &raquo;', [
                                    'class' => 'page-item', 
                                    'escape' => false, 
                                    'disabledClass' => 'disabled'
                                ]) ?>
                            </ul>
                        </nav>
                    </div>
                    <!-- pegination end -->

                </div>
            </div>
        </div>
    </div>

</div>
