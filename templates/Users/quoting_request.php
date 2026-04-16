<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
// Helper function to convert status number to text
function getStatusText($status) {
    $statusMap = [
        1 => "Active",
        2 => "Pending Decison",
        3 => "Sold",
        4 => "Lose",
        5 => "Cancelled",
        6 => "Illustrative Quote Ready",
        7 => "Waiting on Carriers",
        8 => "Terminated"
    ];
    return $statusMap[$status] ?? 'Unknown';
}

// Helper function to get badge color class
function getStatusBadgeClass($status) {
    return in_array($status, [2, 3, 4]) ? 'warning' : (in_array($status, [1,6]) ? 'success' : (in_array($status, [7,8]) ? 'danger' : 'secondary'));
}
?>

<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Quote Requests</h4>
            <p class="small-text">To limit the number of quote requests shown in this view, use the filters below or search on the group name.</p>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">


            <a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'program-choose']);?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="plus-square"></i>
                New Quote Request
            </a>
        </div>
    </div>
    <div class="row">

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="col-md-12">
                        <div class="d-flex">
                            <form method="get" action="" class="w-100" id="search-form">
    <?php
    // Preserve all current query parameters except page (which will be reset on form submit)
    $queryParams = $this->request->getQuery();
    $preserveParams = ['keyword', 'hide_expired', 'status'];
    foreach ($preserveParams as $param) {
        if (isset($queryParams[$param])) {
            $value = $queryParams[$param];
            // Handle array values (like status from old implementation)
            if (is_array($value)) {
                $value = reset($value); // Take first value
            }
            if (!empty($value)) {
                echo '<input type="hidden" name="' . htmlspecialchars($param) . '" value="' . htmlspecialchars($value) . '">';
            }
        }
    }
    ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <select name="status" id="qr-filter-status" class="form-control">
                                            <option value="">All</option>
                                            <?php
                                            $statusOptions = \Cake\Core\Configure::read('keyFeatures.STATUS');
                                            $selectedStatus = $this->request->getQuery('status') ?? '';
                                            // Handle array values from old implementation
                                            if (is_array($selectedStatus)) {
                                                $selectedStatus = reset($selectedStatus);
                                            }
                                            foreach ($statusOptions as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>" <?php echo ($key == $selectedStatus) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <?php $hideExpired = $this->request->getQuery('hide_expired') ?? 1; ?>
                                        <input type="hidden" id="hide-expired-hidden" name="hide_expired" value="0">
                                        <input
                                            type="checkbox"
                                            name="hide_expired"
                                            value="1"
                                            class="mt-2"
                                            id="hide-expired-checkbox"
                                            <?= ($hideExpired == 1) ? 'checked' : '' ?>
                                        >
                                        Hide groups past effective date</div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" name="keyword" placeholder="Search by group..." value="<?php echo $this->request->getQuery('keyword') ? $this->request->getQuery('keyword') : ''; ?>" class="form-control">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'quotingRequest']); ?>" class="btn btn-secondary">Clear</a>
                                        </div>
                                    </div>
                                </div>



                            </form>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="" class="table table-bordered">
                            <thead>
                                <tr class="table-light">
                                    <th>Quote Request #</th>
                                    <th>Group</th>
                                    <th>Created</th>
                                    <th>Final Proposals Due</th>
                                    <th>Effective Date </th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php //pr($request_quote_list); die;
                                if(!empty($request_quote_list)) {
                                    foreach($request_quote_list as $request_quote) {
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'quotingDetail', $request_quote->id]);?>" class="btn btn-xs btn-primary btn-rounded"><i class="icon icon-left s7-ticket"></i><i class="icon-md" data-feather="file"></i> <?php echo '#'.$request_quote->id;?></a>
                                    </td>
                                    <td><a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'groupDetails', $request_quote->quotgroup->id]);?>" class="link-no-color"><?php echo $request_quote->quotgroup->group_name;?></a></td>
                                    <td><?php echo $request_quote->created_at; ?></td>
                                    <td><?php echo $request_quote->Final_Proposals_Due; ?></td>
                                    <td><?php echo $request_quote->Policy_Effective_Date; ?></td>
                                    <td>
                                        <span class="badge bg-<?= getStatusBadgeClass($request_quote->status) ?>">
                                            <?= getStatusText($request_quote->status) ?>
                                        </span>

                                </td>

                                </tr>
                                <?php }
                                } else { ?>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="py-4">
                                            <i class="icon-md" data-feather="search" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                                            <p class="text-muted mt-2">No quote requests found matching your criteria.</p>
                                            <a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'quotingRequest']); ?>" class="btn btn-sm btn-outline-secondary">Clear Filters</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>






                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <?php if (!empty($request_quote_list) && $this->Paginator->total() > 1): ?>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted small">
                            <i class="icon-md" data-feather="list" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                            Showing <?php echo $this->Paginator->counter('{{start}} - {{end}} of {{count}}'); ?> records
                        </div>
                        <nav aria-label="Quote requests pagination">
                            <ul class="pagination mb-0">
                                <?php
                                echo $this->Paginator->first('<<', [
                                    'templates' => [
                                        'first' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                                        'firstDisabled' => '<li class="page-item disabled"><span class="page-link">{{text}}</span></li>'
                                    ]
                                ]);

                                echo $this->Paginator->prev('<', [
                                    'templates' => [
                                        'prev' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                                        'prevDisabled' => '<li class="page-item disabled"><span class="page-link">{{text}}</span></li>'
                                    ]
                                ]);

                                echo $this->Paginator->numbers([
                                    'templates' => [
                                        'current' => '<li class="page-item active"><span class="page-link">{{text}}</span></li>',
                                        'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>'
                                    ]
                                ]);

                                echo $this->Paginator->next('>', [
                                    'templates' => [
                                        'next' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                                        'nextDisabled' => '<li class="page-item disabled"><span class="page-link">{{text}}</span></li>'
                                    ]
                                ]);

                                echo $this->Paginator->last('>>', [
                                    'templates' => [
                                        'last' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                                        'lastDisabled' => '<li class="page-item disabled"><span class="page-link">{{text}}</span></li>'
                                    ]
                                ]);
                                ?>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<script>

$(document).ready(function() {
    console.log('Document ready');
    $('#hide-expired-checkbox').on('click', function() {
        $('#search-form').submit();
    });
});
</script>
