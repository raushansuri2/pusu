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
            <h4 class="mb-3 mb-md-0"><i data-feather="users"></i> <?php echo htmlspecialchars($group->group_name); ?></h4>

			   </div>
        </div>
		<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
               <h6 class="card-title mb-2"><i data-feather="tag"></i> Quote Requests for <?php echo htmlspecialchars($group->group_name); ?></h6>
			   <p class="text-muted tx-13 mb-3 mb-md-0">Revenue is the income that a business has from its normal business activities, usually from the sale of goods and services to customers.</p>
			   <hr>
			   <div class="table-responsive1">
  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th>Quote Request #</th>
        <th>Effective</th>
        <th>Submitted</th>
        <!-- <th>Status</th> -->
        <th class="text-end"></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($quoteRequests)): ?>
        <?php foreach ($quoteRequests as $quote): ?>
          <tr>
            <td>
              <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'quotingDetail', $quote->id]); ?>"
                 class="btn btn-sm btn-primary btn-rounded">
                <i class="link-icon icon-sm" data-feather="file-text"></i> #<?php echo $quote->id; ?>
              </a>
            </td>
            <td><?php echo $quote->Policy_Effective_Date ? date('m/d/Y', strtotime($quote->Policy_Effective_Date)) : '—'; ?></td>
            <td><?php echo $quote->created ? date('m/d/Y', strtotime($quote->created)) : '—'; ?></td>
            <!-- <td>
              <span class="badge <?php echo $quote->status == 1 ? 'bg-warning' : 'bg-danger'; ?>">
                <?php echo $quote->status == 1 ? 'Illustrative Quote Ready' : 'Draft'; ?>
              </span>
              <?php if ($quote->status != 1): ?>
                <a href="#" class="ms-2 text-danger small">Delete Draft</a>
              <?php endif; ?>
            </td> -->
            <td class="text-end">
                <div class="dropdown">
                    <button class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="link-icon icon-sm" data-feather="settings"></i> Status - <span class="status-class"><?php
                        $statusOptions = \Cake\Core\Configure::read('keyFeatures.STATUS');
                        echo isset($statusOptions[$quote->status]) ? $statusOptions[$quote->status] : 'Unknown';
                    ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php $statusOptions = \Cake\Core\Configure::read('keyFeatures.STATUS');
                        foreach ($statusOptions as $key => $value) {
                            if ($key == $quote->status) {
                                echo '<li><span class="dropdown-item active" style="cursor: default; pointer-events: none;">' . $value . ' ✓</span></li>';
                            } else {
                                echo '<li><button class="dropdown-item" onclick="changestatus('.$key.', \''.$value.'\', '.$quote->id.')">' . $value . '</button></li>';
                            }
                        }
                        ?>

                    </ul>
                </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">No quote requests found for this group.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>



              </div>
            </div>
					</div>
				</div>

<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">

			   <div class="panel panel-default">
			   <div class="panel-heading panel-heading-divider">
    <div class="row align-items-center">
      <div class="col-md-9">
        <h5 class="panel-title mb-0">
        <i data-feather="users"></i>
          Group Information
        </h5>
      </div>

      <div class="col-md-3 text-end d-print-none">

        <a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'groupedit', $group->id]); ?>"
           class="btn btn-sm btn-danger btn-rounded">
           Edit Group
        </a>
      </div>
    </div>
  </div>
            <div class="panel-body">
                <dl class="row">
                    <dt class="col-sm-3">Group Name</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($group->group_name ?? '');?></dd>
                    <dt class="col-sm-3">Address</dt>
                    <dd class="col-sm-9"><address class="address-summary "><?php echo htmlspecialchars($group->address1 ?? '');?><br><?php echo htmlspecialchars($group->address2 ?? '');?><br><?php echo htmlspecialchars($group->city ?? '');?>, <?php echo htmlspecialchars($group->state_name ?? '');?> <?php echo htmlspecialchars($group->zip ?? '');?></address></dd>
                    <dt class="col-sm-3">Business Classification</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($group->SIC_Code ?? '');?></dd>
                </dl>
            </div>
        </div>



              </div>
            </div>
					</div>
				</div>

<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">

			   <div class="panel panel-default">
  <!-- Panel Header -->
  <div class="panel-heading panel-heading-divider">
    <div class="row align-items-center">
      <div class="col-md-9">
        <h5 class="panel-title mb-0">
        <i data-feather="file-text"></i>
          Attachments
        </h5>
      </div>

      <!-- <div class="col-md-3 text-end d-print-none">
        <a href="#"
           class="btn btn-sm btn-success btn-rounded me-1"> <i data-feather="upload" class="icon-sm"></i>
          Upload Files
        </a>
        <a href="#"
           class="btn btn-sm btn-primary btn-rounded"> <i data-feather="download" class="icon-sm"></i>
          Download All
        </a>
      </div> -->
    </div>
  </div>

  <!-- Panel Body -->
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>File Added</th>
            <th>File Name</th>
            <th>Type</th>
            <th class="text-end"></th>
          </tr>
        </thead>

        <tbody>
          <?php if (!empty($censusData)): ?>
            <?php foreach ($censusData as $census): ?>
              <tr>
                <td><?php echo $census->created ? date('M d, Y · g:i A', strtotime($census->created)) : '—'; ?></td>

                <td>
                  <a href="<?php echo $this->Url->build('/img/uploads/census/' . $census->xl_file); ?>"
                     class="fw-semibold" target="_blank">
                    <?php echo htmlspecialchars($census->xl_file); ?>
                  </a>
                  <div class="text-muted small">
                    <?php
                    $filePath = WWW_ROOT . 'img/uploads/census/' . $census->xl_file;
                    $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                    echo $fileSize ? number_format($fileSize / 1024, 2) . ' KB' : 'Unknown size';
                    ?>
                    ·
                    <?php
                    // Try to get member count from Excel file if possible
                    $memberCount = 0;
                    if (!empty($census->xl_file) && file_exists($filePath)) {
                        try {
                            if ($xlsx = \Shuchkin\SimpleXLSX::parse($filePath)) {
                                $rows = $xlsx->rows();
                                $memberCount = max(0, count($rows) - 4); // Assuming 4 header rows
                            }
                        } catch (Exception $e) {
                            // If parsing fails, show unknown
                            $memberCount = 0;
                        }
                    }
                    echo $memberCount . ' members';
                    ?>
                  </div>
                </td>

                <td>
                  <span class="badge bg-info"><?php echo htmlspecialchars($census->type ?? 'Census'); ?></span>
                </td>

                <td class="text-end text-nowrap d-print-none">
                  <a href="<?php echo $this->Url->build('/img/uploads/census/' . $census->xl_file); ?>"
                     class="btn btn-xs btn-warning btn-rounded me-1" target="_blank">
                    <i data-feather="download" class="icon-sm"></i> Download
                  </a>
                  <button
                    class="btn btn-xs btn-danger btn-rounded"
                    data-confirm-message="Are you sure you want to delete this file?">
                    <i data-feather="delete" class="icon-sm"></i> Delete
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center">No census files found for this group.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>




              </div>
            </div>
					</div>
				</div>

			</div>
