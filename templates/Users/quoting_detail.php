<script>
function changestatus(status, statusText, quoteId) {
    $(this).html(statusText);

    // Show loader
    showLoader();

    // Make AJAX call to update status
    $.ajax({
        url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'updateStatus']);?>/' + quoteId + '/' + status,
        type: 'GET',
        success: function(response) {
            // Optionally refresh the page or update UI
            location.reload();
        },
        error: function(xhr, status, error) {
            hideLoader();
            alert('Error updating status: ' + error);
        }
    });
}

function showLoader() {
    // Create loader if it doesn't exist
    if ($('#statusLoader').length === 0) {
        $('body').append('<div id="statusLoader" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;"><div style="background: white; padding: 20px; border-radius: 5px; text-align: center;"><div style="border: 3px solid #f3f3f3; border-top: 3px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 10px;"></div><p>Updating status...</p></div></div>');

        // Add CSS animation
        if (!$('#loaderStyle').length) {
            $('head').append('<style id="loaderStyle">@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>');
        }
    }
    $('#statusLoader').fadeIn();
}

function hideLoader() {
    $('#statusLoader').fadeOut();
}

// Show loader when page is reloading
$(window).on('beforeunload', function() {
    showLoader();
});
</script>
<style>
.status-class{ color: #d6ff06;  font-weight: bold;}
</style>
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0"><i data-feather="message-square"></i> Quote Request </h4>
        </div>
    </div>
    <div class="row align-items-center mb-3">
        <!-- Left: Title -->
        <div class="col-md-6">
            <h4 class="mb-0 d-flex align-items-center flex-wrap">
                <span class="me-2">#<?php echo $RequestQuots->id ?>:</span>

                <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'quotingDetail',$RequestQuots->quotgroup->id]);?>" class="link-no-color fw-semibold me-3">
                    <?php echo $RequestQuots->quotgroup->group_name;?>
                </a>

                <?php
                $statusOptions = \Cake\Core\Configure::read('keyFeatures.STATUS');
                $currentStatus = $RequestQuots->status;
                $statusText = isset($statusOptions[$currentStatus]) ? $statusOptions[$currentStatus] : 'Unknown';

                // Define badge colors based on status
                $badgeColors = [
                    1 => 'bg-success',    // Active
                    2 => 'bg-warning',    // Pending Decision
                    3 => 'bg-primary',    // Sold
                    4 => 'bg-danger',     // Lose
                    5 => 'bg-secondary',  // Cancelled
                    6 => 'bg-info',       // Illustrative Quote Ready
                    7 => 'bg-warning',    // Waiting on Carriers
                    8 => 'bg-dark'        // Terminated
                ];

                $badgeColor = isset($badgeColors[$currentStatus]) ? $badgeColors[$currentStatus] : 'bg-secondary';
                ?>
                <span style="font-size: 12px;" class="badge <?php echo $badgeColor; ?>">
                    <?php echo $statusText; ?>
                </span>
            </h4>
        </div>

        <!-- Right: Actions -->
        <div class="col-md-6">

            <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">

                <!-- Status Dropdown -->



                <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'editquotingRequest',$RequestQuots->id]);?>" class="btn btn-xs btn-danger btn-rounded">
                    <i data-feather="edit-3" class="icon-sm"></i>
                    Edit & Resubmit
                </a>
                <!-- Quick Actions -->
                <!-- <a href="add-note.html" class="btn btn-xs btn-success btn-rounded">
                    <i data-feather="plus-square" class="icon-sm"></i>
                    Add Note
                </a> -->

                <div class="dropdown">
                    <button class="btn btn-xs btn-primary btn-rounded dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="link-icon icon-sm" data-feather="settings"></i> Status - <span class="status-class"><?php
                        $statusOptions = \Cake\Core\Configure::read('keyFeatures.STATUS');
                        echo isset($statusOptions[$RequestQuots->status]) ? $statusOptions[$RequestQuots->status] : 'Unknown';
                    ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php $statusOptions = \Cake\Core\Configure::read('keyFeatures.STATUS');
                        foreach ($statusOptions as $key => $value) {
                            if ($key == $RequestQuots->status) {
                                echo '<li><span class="dropdown-item active" style="cursor: default; pointer-events: none;">' . $value . ' ✓</span></li>';
                            } else {
                                echo '<li><button class="dropdown-item" onclick="changestatus('.$key.', \''.$value.'\', '.$RequestQuots->id.')">' . $value . '</button></li>';
                            }
                        }
                        ?>

                    </ul>
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <p style="font-size: 13px;margin-top: 7px;">
                <strong>Final Proposals Due</strong> <?php echo $RequestQuots->Final_Proposals_Due ? date('m/d/y', strtotime($RequestQuots->Final_Proposals_Due)) : ''; ?> <strong>Plan Effective</strong> <?php echo $RequestQuots->Policy_Effective_Date ? date('m/d/Y', strtotime($RequestQuots->Policy_Effective_Date)) : ''; ?> <strong>Census Used</strong> <?php echo !empty($file_name) ? $file_name : ''; ?> (<?php echo array_sum($file_counts); ?> members)
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">


                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i data-feather="share-2" class="icon-sm"></i> Quote Request Recipients</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i data-feather="sun" class="icon-sm"></i> Illustrative Quotes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><i data-feather="sun" class="icon-sm"></i> Underwritten Quotes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="disabled-tab" data-bs-toggle="tab" href="#disabled" role="tab" aria-controls="disabled" aria-selected="false"><i data-feather="calendar" class=" icon-sm"></i> Timeline & Notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="quotes-tab" data-bs-toggle="tab" href="#quotes" role="tab" aria-controls="quotes" aria-selected="false"><i data-feather="file-text" class=" icon-sm"></i> Quote Request Details</a>
                        </li>
                    </ul>
                    <div class="tab-content border border-top-0 p-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="panel panel-default panel-table">
                                <div class="panel-heading panel-heading-divider panel-heading-full-width d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="panel-title">
                                            <i data-feather="sun" class="icon-sm"></i>
                                            Quote Request Recipients
                                        </span>
                                    </div>
                                </div>

                                <div class="panel-body">
                                    <form action="#" class="form-inline">
                                        <table class="table table-bordered align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Quoting Partner</th>
                                                    <th>Status</th>
                                                    <th class="text-right">Action</th>
                                                    <!-- <th class="text-right w-25">
                                                        <button type="submit" class="btn btn-sm btn-primary btn-rounded align-items-center">
                                                            <i data-feather="archive" class="icon-sm"></i> Compare
                                                        </button>
                                                    </th> -->
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="mgu-logo">
                                                        <img src="<?php echo $this->Url->build('/');?>img/admin/logo.png" style="width: 130px;border-radius: 0;" alt="Prodigy Health Insurance" class="mr-2 d-inline-block align-middle">
                                                        <!-- Prodigy Health Insurance -->
                                                    </td>

                                                    <td>
                                                        <span class="status text-success">
                                                            Success! An illustrative quote is ready to view.
                                                        </span>
                                                    </td>

                                                    <td class="text-right">
                                                        <a href="#" class="btn btn-xs btn-secondary btn-rounded d-inline-flex align-items-center">
                                                            <i data-feather="sun" class="icon-sm"></i> View Illustrative Quote
                                                        </a>
                                                    </td>

                                                    <!-- <td class="text-right">
                                                        <div class="custom-control custom-checkbox d-inline-flex align-items-center">
                                                            <input type="checkbox" class="custom-control-input" id="compare-1" name="compare[]">
                                                            <label class="custom-control-label mb-0 ml-1" for="compare-1"></label>
                                                        </div>
                                                    </td> -->
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <?php echo $this->element('illustrative_quotes'); ?>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <?php echo $this->element('underwritten_quotes'); ?>
                        </div>
                        <div class="tab-pane fade" id="disabled" role="tabpanel" aria-labelledby="disabled-tab">
                            <?php echo $this->element('timeline_notes'); ?>
                        </div>
                        <div class="tab-pane fade" id="quotes" role="tabpanel" aria-labelledby="quotes-tab">
                            <?php echo $this->element('quote_request_details'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>