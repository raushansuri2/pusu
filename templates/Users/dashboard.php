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
            <h4 class="mb-3 mb-md-0">Welcome to ERISAQuote Pro Dashboard</h4>
          </div>
        </div>
		<div class="row">
					<div class="col-md-12">
            <div class="container1">
              <div class="row">
                <div class="col-md-3 stretch-card grid-margin grid-margin-md-0">
                  <div class="card" style="background: #0066bf;border-radius: 10px;border: none;color: #FFF;">
                    <div class="card-body">
                      <i data-feather="file-text" class="text-white icon-xxl d-block mx-auto my-3"></i>
                      <h1 class="text-center"><?= $instantQuotesCount ?></h1>
                      <p class="text-white text-center mb-0 fw-light">Instant Quotes Available</p>
                      <p class="home-text text-center" style="font-size: 13px;margin-top: 9px;">Quote requests with instant quotes available.</p>

                    </div>
                  </div>
                </div>
				<div class="col-md-3 stretch-card grid-margin grid-margin-md-0">
                  <div class="card" style="background: #fbbc06;border-radius: 10px;border: none;color: #FFF;">
                    <div class="card-body">
                      <i data-feather="check-circle" class="text-white icon-xxl d-block mx-auto my-3"></i>
                      <h1 class="text-center"><?= $pendingDecisionCount ?></h1>
                      <p class="text-white text-center mb-0 fw-light">Pending Decision</p>
                      <p class="home-text text-center" style="font-size: 13px;margin-top: 9px;">Waiting for the group to make a decision.</p>
                    </div>
                  </div>
                </div>
				<div class="col-md-3 stretch-card grid-margin grid-margin-md-0">
                  <div class="card" style="background: #41b42b;border-radius: 10px;border: none;color: #FFF;">
                    <div class="card-body">
                      <i data-feather="award" class="text-white icon-xxl d-block mx-auto my-3"></i>
                      <h1 class="text-center"><?= $soldCount ?></h1>
                      <p class="text-white text-center mb-0 fw-light">Sold</p>
                      <p class="home-text text-center" style="font-size: 13px;margin-top: 9px;">Quote requests marked sold.</p>
                    </div>
                  </div>
                </div>
				<div class="col-md-3 stretch-card grid-margin grid-margin-md-0">
                  <div class="card" style="background: #000;border-radius: 10px;border: none;color: #FFF;">
                    <div class="card-body">
                      <i data-feather="x" class="text-white icon-xxl d-block mx-auto my-3"></i>
                      <h1 class="text-center"><?= $lostCount ?></h1>
                      <p class="text-white text-center mb-0 fw-light">Lost</p>
                      <p class="home-text text-center" style="font-size: 13px;margin-top: 9px;">Quote requests marked lost.</p>
                    </div>
                  </div>
                </div>



              </div>
            </div>
					</div>
				</div>



        <div class="d-flex justify-content-between align-items-center flex-wrap mt-4">
          <div>
            <h4 class="mb-3 mb-md-0"> Groups with Recent Activity</h4>
			<p class="small-text">Groups and quote requests with recent activity are listed below.</p>
          </div>
        </div>
<div class="row">
<?php if (!empty($groupsWithRequests)): ?>
    <?php foreach ($groupsWithRequests as $groupData): ?>
        <div class="col-md-4 mt-3 grid-margin stretch-card">
            <div class="card" style="border-radius: 10px;">
                <div class="card-body">
                    <h4 class="card-title mb-2">
                        <i class="icon-md" data-feather="users"></i>
                        <?= h($groupData['group']->group_name) ?>
                    </h4>
                    <p class="text-muted mb-3">
                        <?= h($groupData['group']->city) ?>, <?= h($groupData['group']->state_name) ?>
                    </p>
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Quote Request #</th>
                                <th>Effective Date & Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groupData['requests'] as $request): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'quotingDetail', $request->id]) ?>"
                                           class="btn btn-xs btn-primary btn-rounded">
                                            <i class="icon-md" data-feather="file"></i> #<?= $request->id ?>
                                        </a>
                                    </td>
                                    <td>
                                        Date: <?= $request->Policy_Effective_Date ? date('m/d/Y', strtotime($request->Policy_Effective_Date)) : 'N/A' ?><br>
                                        <span class="badge bg-<?= getStatusBadgeClass($request->status) ?>">
                                            <?= getStatusText($request->status) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center">
                <p class="text-muted">No recent group activity found.</p>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>



		<!-- row -->

			</div>
