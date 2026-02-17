<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>
    </div><!-- leftpanel -->

    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-shield"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'Admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li><?= $this->Html->link('Settings', ['controller' => 'Settings', 'action' => 'index']); ?></li>
                        <li>Quoting Partner Permissions</li>
                    </ul>
                    <h4>Manage Quoting Partner Permissions</h4>
                </div>
                <?php echo $this->Flash->render(); ?>
            </div><!-- media -->
        </div><!-- pageheader -->

        <div class="contentpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Quoting Partner Permissions</h4>
                            <p>Manage all quoting partner permissions in the system</p>
                        </div><!-- panel-heading -->
                        <div class="panel-body">
                            <?php if ($permissions->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User ID</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($permissions as $permission): ?>
                                                <tr>
                                                    <td><?= $permission->id ?></td>
                                                    <td><?= $permission->user_id ?></td>
                                                    <td><?= h($permission->user->email ?? 'N/A') ?></td>
                                                    <td>
                                                        <span class="label label-<?= $permission->status ? 'success' : 'danger' ?>">
                                                            <?= $permission->status ? 'Active' : 'Inactive' ?>
                                                        </span>
                                                    </td>
                                                    <td><?= $permission->created ? $permission->created->format('Y-m-d H:i') : 'N/A' ?></td>
                                                    <td>
                                                        <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $permission->id], ['class' => 'btn btn-sm btn-primary mr5', 'escape' => false]) ?>
                                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $permission->id], [
                                                            'confirm' => __('Are you sure you want to delete this permission?'),
                                                            'class' => 'btn btn-sm btn-danger',
                                                            'escape' => false
                                                        ]) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Enhanced Pagination -->
                                <div class="pagination-wrapper">
                                    <div class="pagination-info">
                                        <span class="info-text">
                                            <?= $this->Paginator->counter('Showing {{start}} to {{end}} of {{count}} entries') ?>
                                        </span>
                                    </div>
                                    <div class="pagination-controls">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                <li class="<?= $this->Paginator->hasPrev() ? '' : 'disabled' ?>">
                                                    <?= $this->Paginator->first('<i class="fa fa-angle-double-left"></i>', ['escape' => false]) ?>
                                                </li>
                                                <li class="<?= $this->Paginator->hasPrev() ? '' : 'disabled' ?>">
                                                    <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i> Previous', ['escape' => false]) ?>
                                                </li>
                                                <li class="page-numbers">
                                                    <?= $this->Paginator->numbers(['modulus' => 5, 'separator' => '', 'currentClass' => 'active']) ?>
                                                </li>
                                                <li class="<?= $this->Paginator->hasNext() ? '' : 'disabled' ?>">
                                                    <?= $this->Paginator->next('Next <i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
                                                </li>
                                                <li class="<?= $this->Paginator->hasNext() ? '' : 'disabled' ?>">
                                                    <?= $this->Paginator->last('<i class="fa fa-angle-double-right"></i>', ['escape' => false]) ?>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>

                                <style>
                                .pagination-wrapper {
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    padding: 20px 0;
                                    border-top: 1px solid #e5e5e5;
                                    margin-top: 20px;
                                }

                                .pagination-info {
                                    flex: 1;
                                }

                                .info-text {
                                    color: #666;
                                    font-size: 14px;
                                    font-weight: 500;
                                }

                                .pagination-controls {
                                    flex: 2;
                                    text-align: right;
                                }

                                .pagination {
                                    display: inline-flex;
                                    margin: 0;
                                    padding: 0;
                                    list-style: none;
                                    border-radius: 4px;
                                    background: #f8f9fa;
                                    border: 1px solid #dee2e6;
                                }

                                .pagination li {
                                    display: inline-flex;
                                    align-items: center;
                                }

                                .pagination li a {
                                    display: inline-flex;
                                    align-items: center;
                                    padding: 10px 15px;
                                    color: #007bff;
                                    text-decoration: none;
                                    border-right: 1px solid #dee2e6;
                                    font-size: 13px;
                                    font-weight: 500;
                                    transition: all 0.3s ease;
                                    min-width: 40px;
                                    justify-content: center;
                                }

                                .pagination li:last-child a {
                                    border-right: none;
                                }

                                .pagination li a:hover {
                                    background: #e9ecef;
                                    color: #0056b3;
                                }

                                .pagination li.disabled a {
                                    color: #6c757d;
                                    cursor: not-allowed;
                                    background: #f8f9fa;
                                }

                                .pagination li.active a {
                                    background: #007bff;
                                    color: white;
                                    border-color: #007bff;
                                    box-shadow: 0 2px 4px rgba(0,123,255,0.3);
                                }

                                .page-numbers {
                                    display: inline-flex;
                                }

                                .page-numbers li {
                                    border-right: 1px solid #dee2e6;
                                }

                                @media (max-width: 768px) {
                                    .pagination-wrapper {
                                        flex-direction: column;
                                        gap: 15px;
                                    }

                                    .pagination-info,
                                    .pagination-controls {
                                        text-align: center;
                                    }

                                    .pagination {
                                        flex-wrap: wrap;
                                        justify-content: center;
                                    }

                                    .pagination li a {
                                        padding: 8px 12px;
                                        font-size: 12px;
                                    }
                                }
                                </style>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <strong>No Permissions Found!</strong>
                                    <p>There are currently no quoting partner permissions configured.</p>
                                </div>
                            <?php endif; ?>
                        </div><!-- panel-body -->
                    </div><!-- panel -->
                </div>
            </div><!-- row -->
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->
