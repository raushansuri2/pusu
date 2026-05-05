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
                            <div class="panel panel-default sticky-panel">
                                <div class="panel-heading panel-heading-divider panel-heading-full-width">
                                    <div class="row">
                                        <div class="col-9"> <span class="panel-title"> <i data-feather="sun" class="icon-sm"></i> Illustrative Quotes </span> <span class="panel-subtitle">#<?php echo $RequestQuots->id;?> : <?php echo $RequestQuots->quotgroup->group_name;?></span> </div>
                                        <div class="col-3"> <a href="#" target="_blank" class="float-right ml-1 btn btn-primary btn-rounded btn-sm"><i class="s7-expand1"></i> View full sized</a> <a href="#" class="float-right ml-1 btn btn-secondary btn-rounded btn-sm">Print All</a> </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <table class="table table-hover table-sm table-bordered table-illustrative-quote">
                                                <thead>
                                                    <tr class="table-light">
                                                        <th colspan="2 "><strong>Enrollment</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="fixed-left">Tier 1: Employee Only (EE)</td>
                                                        <td><?php echo $file_counts['EE'] ?? 0; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fixed-left">Tier 2: Employee + Spouse (ES)</td>
                                                        <td><?php echo $file_counts['ES'] ?? 0; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fixed-left">Tier 3: Employee + Child(ren) (EC)</td>
                                                        <td><?php echo $file_counts['EC'] ?? 0; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fixed-left">Tier 4: Employee + Family (EF)</td>
                                                        <td><?php echo $file_counts['EF'] ?? 0; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fixed-left"><strong>Total Enrollment</strong></td>
                                                        <td><?php echo $total_EMP = (@$file_counts['EE'] + @$file_counts['ES'] + @$file_counts['EC']+ @$file_counts['EF']) ?? 0; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-sm-6">
                                            <table class="table table-hover table-sm table-bordered table-illustrative-quote">
                                                <thead>
                                                    <tr class="table-light">
                                                        <th colspan="2"><strong>Fees</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($feesData)): ?>
                                                        <?php foreach ($feesData as $key=>$fee): ?>
                                                            <tr>
                                                                <td class="fixed-left"><?php echo htmlspecialchars(ucwords(strtolower(str_replace('_', ' ', $key)))); ?></td>
                                                                <td>$<?php echo htmlspecialchars($fee ?? ''); ?> <?php echo htmlspecialchars($fee['value_type'] ?? 'PEPM'); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                    <?php if (!empty($feesData)): ?>
                                                        <?php foreach ($feesData as $key=>$fee): ?>
                                                            <tr>
                                                                <td class="fixed-left"><?php echo htmlspecialchars(ucwords(strtolower(str_replace('_', ' ', $key)))); ?></td>
                                                                <td>$<?php echo htmlspecialchars($fee ?? ''); ?> <?php echo htmlspecialchars($fee['value_type'] ?? 'PEPM'); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
                                                            <td>$70.00 PEPM</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fixed-left">Broker Fee</td>
                                                            <td>$35.00 PEPM</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel-table ">
                                        <table class="table table-hover table-sm table-bordered table-illustrative-quote">
                                            <thead>
                                                <tr class="table-light">
                                                    <th><strong>Employee Benefit Plan</strong></th>
                                                    <th colspan="1" class="plan-name">
                                                        <?php
                                                        if (!empty($benefitPlansDetails)) {
                                                            $planNames = [];
                                                            foreach ($benefitPlansDetails as $planss) {
                                                                $planNames[] = htmlspecialchars($planss->plan_name);
                                                            }
                                                            echo implode(', ', $planNames);
                                                        } else {
                                                            echo 'No benefit plans selected';
                                                        }
                                                        ?>
                                                    </th>
                                                </tr>
                                                <tr class="table-light">
                                                    <th><strong>Network or Repricing</strong></th>
                                                    <th colspan="1" class="network-name">
                                                        <?php
                                                        if (!empty($networksDetails)) {
                                                            $networkNames = [];
                                                            foreach ($networksDetails as $network) {
                                                                $networkNames[] = htmlspecialchars($network->name);
                                                            }
                                                            echo implode(', ', $networkNames);
                                                        } else {
                                                            echo 'No networks selected';
                                                        }
                                                        ?>
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th></th>
                                                    <!-- <th class="text-center"><a href="view-quote.html" class="btn btn-primary btn-sm btn-rounded">Print Quote</a></th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $T_ANNUAL = 0;
                                                     $AG_CORRIDOR;
                                                    if($lossPlansDetails){
                                                    foreach($lossPlansDetails as $lossPlan){
                                                        $T_ANNUAL = $lossPlan->Spec_Deductible;
                                                        $AG_CORRIDOR = $lossPlan->Agg_Corridor;?>
                                                        <tr class="table-light">
                                                            <td colspan="2 table-light">Stop Loss Plan:- <strong><?php echo $lossPlan->plan_name; ?></strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fixed-left font-weight-bold">Specific Deductible</td>
                                                            <td class="text-center">$<?php echo $lossPlan->Spec_Deductible; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fixed-left font-weight-bold">Specific Contract</td>
                                                            <td class="text-center"><?php echo $lossPlan->Spec_Contract; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fixed-left font-weight-bold">Aggregate Contract</td>
                                                            <td class="text-center"><?php echo $lossPlan->Agg_Contract; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fixed-left font-weight-bold">Aggregate Corridor</td>
                                                            <td class="text-center"><?php echo $lossPlan->Agg_Corridor; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fixed-left font-weight-bold">Aggregating Specific Deductible</td>
                                                            <td class="text-center">$<?php echo $lossPlan->Aggregating_Spec_Deductible; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fixed-left font-weight-bold">Commission</td>
                                                            <td class="text-center"><?php echo $lossPlan->Commission; ?>%</td>
                                                        </tr>
                                                    <?php }
                                                } ?>


                                                <?php
                                                // Ensure T_ANNUAL is set (from Specific Deductible)
                                                $T_ANNUAL = isset($T_ANNUAL) && $T_ANNUAL > 0 ? $T_ANNUAL : 75000;

                                                // Calculate Expected Claims (C) - Specific Deductible annual divided by 12 months, then per employee
                                                $C = 0;
                                                if ($total_EMP > 0 && $T_ANNUAL > 0) {
                                                    $C = ($T_ANNUAL / 12) / $total_EMP;
                                                }

                                                // Define specific rates for each tier (can be made dynamic later)
                                                $specificRate_EE = 284.88; // Employee Only
                                                $specificRate_ES = 468.36; // Employee + Spouse
                                                $specificRate_EC = 443.70; // Employee + Child(ren)
                                                $specificRate_EF = 739.52; // Employee + Family

                                                // Ensure AG_CORRIDOR is set and valid
                                                $AG_CORRIDOR = isset($AG_CORRIDOR) && $AG_CORRIDOR > 0 ? $AG_CORRIDOR : 1.25;

                                                // Calculate aggregate rates using the formula: Aggregate Rate = Expected Claims × Aggregate Factor
                                                $aggregateRate_EE = $C * $AG_CORRIDOR;
                                                $aggregateRate_ES = $C * $AG_CORRIDOR * 1.9; // ES multiplier
                                                $aggregateRate_EC = $C * $AG_CORRIDOR * 1.8; // EC multiplier
                                                $aggregateRate_EF = $C * $AG_CORRIDOR * 2.6; // EF multiplier

                                                // Calculate aggregate accommodation: Aggregate Accommodation = Aggregate Rate – Expected Claims
                                                $aggregateAccommodation_EE = $aggregateRate_EE - $C;
                                                $aggregateAccommodation_ES = $aggregateRate_ES - ($C * 1.9);
                                                $aggregateAccommodation_EC = $aggregateRate_EC - ($C * 1.8);
                                                $aggregateAccommodation_EF = $aggregateRate_EF - ($C * 2.6);

                                                // Calculate fees (default values if not provided)
                                                $totalFees = 105; // $70 TPA + $35 Broker default
                                                if (!empty($feesData)) {
                                                    $totalFees = 0;
                                                    foreach ($feesData as $fee) {
                                                        if (is_array($fee) && isset($fee['value'])) {
                                                            $totalFees += $fee['value'];
                                                        }
                                                    }
                                                }

                                                // Calculate Total PEPM: Total PEPM = Specific Rate + Aggregate Rate + Fees
                                                $totalPEPM_EE = $specificRate_EE + $aggregateRate_EE + $totalFees;
                                                $totalPEPM_ES = $specificRate_ES + $aggregateRate_ES + $totalFees;
                                                $totalPEPM_EC = $specificRate_EC + $aggregateRate_EC + $totalFees;
                                                $totalPEPM_EF = $specificRate_EF + $aggregateRate_EF + $totalFees;

                                                // Calculate monthly totals by multiplying PEPM rates by enrollment counts
                                                $monthlyTotal_EE_Specific = $specificRate_EE * ($file_counts['EE'] ?? 0);
                                                $monthlyTotal_EE_Aggregate = $aggregateRate_EE * ($file_counts['EE'] ?? 0);
                                                $monthlyTotal_EE_Factor = ($AG_CORRIDOR * $C) * ($file_counts['EE'] ?? 0);
                                                $monthlyTotal_EE_Accommodation = $aggregateAccommodation_EE * ($file_counts['EE'] ?? 0);

                                                $monthlyTotal_ES_Specific = $specificRate_ES * ($file_counts['ES'] ?? 0);
                                                $monthlyTotal_ES_Aggregate = $aggregateRate_ES * ($file_counts['ES'] ?? 0);
                                                $monthlyTotal_ES_Factor = ($AG_CORRIDOR * 1.9 * $C) * ($file_counts['ES'] ?? 0);
                                                $monthlyTotal_ES_Accommodation = $aggregateAccommodation_ES * ($file_counts['ES'] ?? 0);

                                                $monthlyTotal_EC_Specific = $specificRate_EC * ($file_counts['EC'] ?? 0);
                                                $monthlyTotal_EC_Aggregate = $aggregateRate_EC * ($file_counts['EC'] ?? 0);
                                                $monthlyTotal_EC_Factor = ($AG_CORRIDOR * 1.8 * $C) * ($file_counts['EC'] ?? 0);
                                                $monthlyTotal_EC_Accommodation = $aggregateAccommodation_EC * ($file_counts['EC'] ?? 0);

                                                $monthlyTotal_EF_Specific = $specificRate_EF * ($file_counts['EF'] ?? 0);
                                                $monthlyTotal_EF_Aggregate = $aggregateRate_EF * ($file_counts['EF'] ?? 0);
                                                $monthlyTotal_EF_Factor = ($AG_CORRIDOR * 2.6 * $C) * ($file_counts['EF'] ?? 0);
                                                $monthlyTotal_EF_Accommodation = $aggregateAccommodation_EF * ($file_counts['EF'] ?? 0);

                                                // Calculate fee totals
                                                $tpaFeeMonthly = 70 * $total_EMP; // Default TPA fee
                                                $brokerFeeMonthly = 35 * $total_EMP; // Default Broker fee
                                                if (!empty($feesData)) {
                                                    $tpaFeeMonthly = 0;
                                                    $brokerFeeMonthly = 0;
                                                    foreach ($feesData as $feeType => $fee) {
                                                        if (is_array($fee) && isset($fee['value'])) {
                                                            if (strpos(strtolower($feeType), 'broker') !== false) {
                                                                $brokerFeeMonthly = $fee['value'] * $total_EMP;
                                                            } else {
                                                                $tpaFeeMonthly += $fee['value'] * $total_EMP;
                                                            }
                                                        }
                                                    }
                                                }

                                                // Calculate monthly totals for each tier
                                                $monthlyTotal_EE = $monthlyTotal_EE_Specific + $monthlyTotal_EE_Aggregate + $tpaFeeMonthly + $brokerFeeMonthly;
                                                $monthlyTotal_ES = $monthlyTotal_ES_Specific + $monthlyTotal_ES_Aggregate + ($tpaFeeMonthly * 0.1) + ($brokerFeeMonthly * 0.1);
                                                $monthlyTotal_EC = $monthlyTotal_EC_Specific + $monthlyTotal_EC_Aggregate;
                                                $monthlyTotal_EF = $monthlyTotal_EF_Specific + $monthlyTotal_EF_Aggregate + ($tpaFeeMonthly * 0.55) + ($brokerFeeMonthly * 0.55);

                                                // Calculate composite aggregate rate
                                                $totalAggregatePremium = $monthlyTotal_EE_Aggregate + $monthlyTotal_ES_Aggregate + $monthlyTotal_EC_Aggregate + $monthlyTotal_EF_Aggregate;
                                                $compositeAggregateRate = $total_EMP > 0 ? $totalAggregatePremium / $total_EMP : 0;

                                                // Calculate estimated specific premium annual
                                                $estimatedSpecPremium = ($monthlyTotal_EE_Specific + $monthlyTotal_ES_Specific + $monthlyTotal_EC_Specific + $monthlyTotal_EF_Specific) * 12;
                                                ?>
                                                <tr>
                                                    <td colspan="2" class="table-light">Tier 1: Employee Only (EE)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$<?php echo number_format($specificRate_EE, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$<?php echo number_format($aggregateRate_EE, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center"><?php echo number_format($AG_CORRIDOR, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$<?php echo number_format($aggregateAccommodation_EE, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
                                                    <td class="text-center">$<?php echo number_format($totalPEPM_EE, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="table-light">Tier 2: Employee + Spouse (ES)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$<?php echo number_format($specificRate_ES, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$<?php echo number_format($aggregateRate_ES, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center"><?php echo number_format($AG_CORRIDOR * 1.9, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$<?php echo number_format($aggregateAccommodation_ES, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
                                                    <td class="text-center">$<?php echo number_format($totalPEPM_ES, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="table-light">Tier 3: Employee + Child(ren) (EC)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$<?php echo number_format($specificRate_EC, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$<?php echo number_format($aggregateRate_EC, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center"><?php echo number_format($AG_CORRIDOR * 1.8, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$<?php echo number_format($aggregateAccommodation_EC, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
                                                    <td class="text-center">$<?php echo number_format($totalPEPM_EC, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Tier 4: Employee + Family (EF)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$<?php echo number_format($specificRate_EF, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$<?php echo number_format($aggregateRate_EF, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center"><?php echo number_format($AG_CORRIDOR * 2.6, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$<?php echo number_format($aggregateAccommodation_EF, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
                                                    <td class="text-center">$<?php echo number_format($totalPEPM_EF, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width dark font-weight-bold">Monthly Totals</td>
                                                    <td colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Tier 1: Employee Only (EE)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EE_Specific, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EE_Aggregate, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EE_Factor, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EE_Accommodation, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
                                                    <td class="text-center">$<?php echo number_format($tpaFeeMonthly, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Broker Fee</td>
                                                    <td class="text-center">$<?php echo number_format($brokerFeeMonthly, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EE, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width" colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Tier 2: Employee + Spouse (ES)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_ES_Specific, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_ES_Aggregate, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_ES_Factor, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_ES_Accommodation, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
                                                    <td class="text-center">$<?php echo number_format($tpaFeeMonthly * 0.1, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Broker Fee</td>
                                                    <td class="text-center">$<?php echo number_format($brokerFeeMonthly * 0.1, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_ES, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width" colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Tier 3: Employee + Child(ren) (EC)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EC_Specific, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EC_Aggregate, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EC_Factor, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EC_Accommodation, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Broker Fee</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EC, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width" colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Tier 4: Employee + Family (EF)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EF_Specific, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EF_Aggregate, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EF_Factor, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EF_Accommodation, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
                                                    <td class="text-center">$<?php echo number_format($tpaFeeMonthly * 0.55, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Broker Fee</td>
                                                    <td class="text-center">$<?php echo number_format($brokerFeeMonthly * 0.55, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total</td>
                                                    <td class="text-center">$<?php echo number_format($monthlyTotal_EF, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width" colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Composite Aggregate Rate</td>
                                                    <td class="text-center">$<?php echo number_format($compositeAggregateRate, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width" colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Quote Summary</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Estimated Spec Premium</td>
                                                    <td class="text-center">$<?php echo number_format($estimatedSpecPremium, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Estimated Aggregate Premium</td>
                                                    <td class="text-center">$<?php echo number_format($totalAggregatePremium * 12, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total Attachment Point</td>
                                                    <td class="text-center">$<?php echo number_format($T_ANNUAL * $total_EMP, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Estimated Total Premium</td>
                                                    <td class="text-center">$<?php echo number_format($estimatedSpecPremium + ($totalAggregatePremium * 12), 2); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="panel-table p-0 mt-3" style="width: 100%;overflow: scroll; max-height:700px;">
  <table class="table table-hover table-sm table-bordered table-illustrative-quote">
    <thead>
      <tr>
        <th class="fixed-left"><strong>Employee Benefit Plan</strong></th> 
        <th colspan="8" class="plan-name">Plan 1</th>
        <th colspan="8" class="plan-name">Plan 4</th>
      </tr>
      <tr>
        <th class="fixed-left"><strong>Network or Repricing</strong></th>
        <th colspan="8" class="network-name">Blue Cross/Blue Shield</th> 
        <th colspan="8" class="network-name">Blue Cross/Blue Shield</th>
      </tr>
      <tr>
        <th class="fixed-left"></th>
        <th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
<th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="fixed-left full-width dark font-weight-bold">Stop Loss Plan</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Specific Deductible</td>
        <td class="text-center">$50,000</td>
        <td class="text-center">$75,000</td>
        <td class="text-center">$25,000</td>
        <td class="text-center">$25,000</td>
        <td class="text-center">$35,000</td>
        <td class="text-center">$35,000</td>
        <td class="text-center">$50,000</td>
        <td class="text-center">$75,000</td>
        <td class="text-center">$75,000</td>
        <td class="text-center">$50,000</td>
        <td class="text-center">$75,000</td>
        <td class="text-center">$50,000</td>
        <td class="text-center">$35,000</td>
        <td class="text-center">$25,000</td>
        <td class="text-center">$35,000</td>
        <td class="text-center">$25,000</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Specific Contract</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/12</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Aggregate Contract</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/18</td>
        <td class="text-center">12/12</td>
        <td class="text-center">12/12</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Aggregate Corridor</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
        <td class="text-center">1.25</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Aggregating Specific Deductible</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
        <td class="text-center">$0</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Commission</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
        <td class="text-center">0.00%</td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width dark font-weight-bold">Rates</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width font-weight-bold">Tier 1: Employee Only (EE)</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left">Specific Rate</td>
        <td class="text-center">$418.80</td>
        <td class="text-center">$284.88</td>
        <td class="text-center">$583.11</td>
        <td class="text-center">$491.08</td>
        <td class="text-center">$519.45</td>
        <td class="text-center">$437.57</td>
        <td class="text-center">$355.14</td>
        <td class="text-center">$335.71</td>
        <td class="text-center">$338.30</td>
        <td class="text-center">$422.18</td>
        <td class="text-center">$287.07</td>
        <td class="text-center">$358.01</td>
        <td class="text-center">$523.93</td>
        <td class="text-center">$588.46</td>
        <td class="text-center">$441.34</td>
        <td class="text-center">$495.57</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Rate</td>
        <td class="text-center">$128.48</td>
        <td class="text-center">$160.03</td>
        <td class="text-center">$76.92</td>
        <td class="text-center">$70.44</td>
        <td class="text-center">$98.88</td>
        <td class="text-center">$91.64</td>
        <td class="text-center">$121.18</td>
        <td class="text-center">$167.40</td>
        <td class="text-center">$170.34</td>
        <td class="text-center">$130.79</td>
        <td class="text-center">$162.93</td>
        <td class="text-center">$123.42</td>
        <td class="text-center">$100.64</td>
        <td class="text-center">$78.29</td>
        <td class="text-center">$93.34</td>
        <td class="text-center">$71.76</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Factor</td>
        <td class="text-center">$1,019.25</td>
        <td class="text-center">$1,024.25</td>
        <td class="text-center">$745.35</td>
        <td class="text-center">$639.94</td>
        <td class="text-center">$872.95</td>
        <td class="text-center">$749.50</td>
        <td class="text-center">$875.10</td>
        <td class="text-center">$1,192.95</td>
        <td class="text-center">$1,218.64</td>
        <td class="text-center">$1,043.24</td>
        <td class="text-center">$1,046.30</td>
        <td class="text-center">$895.71</td>
        <td class="text-center">$895.23</td>
        <td class="text-center">$765.87</td>
        <td class="text-center">$768.63</td>
        <td class="text-center">$657.56</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
        <td class="text-center">$1,764.53</td>
        <td class="text-center">$1,667.16</td>
        <td class="text-center">$1,603.38</td>
        <td class="text-center">$1,399.46</td>
        <td class="text-center">$1,689.27</td>
        <td class="text-center">$1,476.71</td>
        <td class="text-center">$1,549.43</td>
        <td class="text-center">$1,894.06</td>
        <td class="text-center">$1,925.29</td>
        <td class="text-center">$1,794.21</td>
        <td class="text-center">$1,694.30</td>
        <td class="text-center">$1,575.14</td>
        <td class="text-center">$1,717.80</td>
        <td class="text-center">$1,630.62</td>
        <td class="text-center">$1,501.30</td>
        <td class="text-center">$1,422.89</td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width font-weight-bold">Tier 2: Employee + Spouse (ES)</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left">Specific Rate</td>
        <td class="text-center">$795.72</td>
        <td class="text-center">$541.27</td>
        <td class="text-center">$1,107.92</td>
        <td class="text-center">$933.05</td>
        <td class="text-center">$986.95</td>
        <td class="text-center">$831.38</td>
        <td class="text-center">$674.78</td>
        <td class="text-center">$637.85</td>
        <td class="text-center">$642.78</td>
        <td class="text-center">$802.15</td>
        <td class="text-center">$545.43</td>
        <td class="text-center">$680.22</td>
        <td class="text-center">$995.47</td>
        <td class="text-center">$1,118.08</td>
        <td class="text-center">$838.54</td>
        <td class="text-center">$941.59</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Rate</td>
        <td class="text-center">$244.12</td>
        <td class="text-center">$304.07</td>
        <td class="text-center">$146.14</td>
        <td class="text-center">$133.84</td>
        <td class="text-center">$187.87</td>
        <td class="text-center">$174.11</td>
        <td class="text-center">$230.24</td>
        <td class="text-center">$318.05</td>
        <td class="text-center">$323.66</td>
        <td class="text-center">$248.50</td>
        <td class="text-center">$309.57</td>
        <td class="text-center">$234.50</td>
        <td class="text-center">$191.21</td>
        <td class="text-center">$148.75</td>
        <td class="text-center">$177.34</td>
        <td class="text-center">$136.34</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Factor</td>
        <td class="text-center">$1,936.56</td>
        <td class="text-center">$1,946.07</td>
        <td class="text-center">$1,416.16</td>
        <td class="text-center">$1,215.89</td>
        <td class="text-center">$1,658.61</td>
        <td class="text-center">$1,424.04</td>
        <td class="text-center">$1,662.69</td>
        <td class="text-center">$2,266.61</td>
        <td class="text-center">$2,315.41</td>
        <td class="text-center">$1,982.16</td>
        <td class="text-center">$1,987.98</td>
        <td class="text-center">$1,701.86</td>
        <td class="text-center">$1,700.92</td>
        <td class="text-center">$1,455.16</td>
        <td class="text-center">$1,460.39</td>
        <td class="text-center">$1,249.37</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
        <td class="text-center">$3,174.40</td>
        <td class="text-center">$2,989.40</td>
        <td class="text-center">$2,868.22</td>
        <td class="text-center">$2,480.78</td>
        <td class="text-center">$3,031.43</td>
        <td class="text-center">$2,627.53</td>
        <td class="text-center">$2,765.71</td>
        <td class="text-center">$3,420.52</td>
        <td class="text-center">$3,479.85</td>
        <td class="text-center">$3,230.81</td>
        <td class="text-center">$3,040.98</td>
        <td class="text-center">$2,814.58</td>
        <td class="text-center">$3,085.60</td>
        <td class="text-center">$2,919.99</td>
        <td class="text-center">$2,674.27</td>
        <td class="text-center">$2,525.30</td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width font-weight-bold">Tier 3: Employee + Child(ren) (EC)</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left">Specific Rate</td>
        <td class="text-center">$753.84</td>
        <td class="text-center">$512.78</td>
        <td class="text-center">$1,049.61</td>
        <td class="text-center">$883.95</td>
        <td class="text-center">$935.01</td>
        <td class="text-center">$787.62</td>
        <td class="text-center">$639.27</td>
        <td class="text-center">$604.28</td>
        <td class="text-center">$608.95</td>
        <td class="text-center">$759.93</td>
        <td class="text-center">$516.73</td>
        <td class="text-center">$644.42</td>
        <td class="text-center">$943.08</td>
        <td class="text-center">$1,059.24</td>
        <td class="text-center">$794.40</td>
        <td class="text-center">$892.03</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Rate</td>
        <td class="text-center">$231.28</td>
        <td class="text-center">$288.06</td>
        <td class="text-center">$138.45</td>
        <td class="text-center">$126.79</td>
        <td class="text-center">$177.98</td>
        <td class="text-center">$164.95</td>
        <td class="text-center">$218.13</td>
        <td class="text-center">$301.31</td>
        <td class="text-center">$306.63</td>
        <td class="text-center">$235.42</td>
        <td class="text-center">$293.28</td>
        <td class="text-center">$222.16</td>
        <td class="text-center">$181.15</td>
        <td class="text-center">$140.92</td>
        <td class="text-center">$168.01</td>
        <td class="text-center">$129.16</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Factor</td>
        <td class="text-center">$1,834.63</td>
        <td class="text-center">$1,843.64</td>
        <td class="text-center">$1,341.62</td>
        <td class="text-center">$1,151.90</td>
        <td class="text-center">$1,571.32</td>
        <td class="text-center">$1,349.08</td>
        <td class="text-center">$1,575.18</td>
        <td class="text-center">$2,147.32</td>
        <td class="text-center">$2,193.56</td>
        <td class="text-center">$1,877.83</td>
        <td class="text-center">$1,883.36</td>
        <td class="text-center">$1,612.30</td>
        <td class="text-center">$1,611.40</td>
        <td class="text-center">$1,378.57</td>
        <td class="text-center">$1,383.52</td>
        <td class="text-center">$1,183.61</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
        <td class="text-center">$3,017.75</td>
        <td class="text-center">$2,842.49</td>
        <td class="text-center">$2,727.67</td>
        <td class="text-center">$2,360.64</td>
        <td class="text-center">$2,882.31</td>
        <td class="text-center">$2,499.65</td>
        <td class="text-center">$2,630.58</td>
        <td class="text-center">$3,250.91</td>
        <td class="text-center">$3,307.14</td>
        <td class="text-center">$3,071.18</td>
        <td class="text-center">$2,891.36</td>
        <td class="text-center">$2,676.88</td>
        <td class="text-center">$2,933.64</td>
        <td class="text-center">$2,776.73</td>
        <td class="text-center">$2,543.93</td>
        <td class="text-center">$2,402.80</td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width font-weight-bold">Tier 4: Employee + Family (EF)</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left">Specific Rate</td>
        <td class="text-center">$1,256.40</td>
        <td class="text-center">$854.64</td>
        <td class="text-center">$1,749.35</td>
        <td class="text-center">$1,473.25</td>
        <td class="text-center">$1,558.35</td>
        <td class="text-center">$1,312.71</td>
        <td class="text-center">$1,065.45</td>
        <td class="text-center">$1,007.15</td>
        <td class="text-center">$1,014.92</td>
        <td class="text-center">$1,266.56</td>
        <td class="text-center">$861.21</td>
        <td class="text-center">$1,074.04</td>
        <td class="text-center">$1,571.80</td>
        <td class="text-center">$1,765.40</td>
        <td class="text-center">$1,324.02</td>
        <td class="text-center">$1,486.73</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Rate</td>
        <td class="text-center">$385.46</td>
        <td class="text-center">$480.11</td>
        <td class="text-center">$230.74</td>
        <td class="text-center">$211.32</td>
        <td class="text-center">$296.63</td>
        <td class="text-center">$274.92</td>
        <td class="text-center">$363.54</td>
        <td class="text-center">$502.19</td>
        <td class="text-center">$511.04</td>
        <td class="text-center">$392.36</td>
        <td class="text-center">$488.80</td>
        <td class="text-center">$370.26</td>
        <td class="text-center">$301.92</td>
        <td class="text-center">$234.86</td>
        <td class="text-center">$280.01</td>
        <td class="text-center">$215.28</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Factor</td>
        <td class="text-center">$3,057.73</td>
        <td class="text-center">$3,072.74</td>
        <td class="text-center">$2,236.04</td>
        <td class="text-center">$1,919.82</td>
        <td class="text-center">$2,618.85</td>
        <td class="text-center">$2,248.48</td>
        <td class="text-center">$2,625.30</td>
        <td class="text-center">$3,578.86</td>
        <td class="text-center">$3,655.92</td>
        <td class="text-center">$3,129.73</td>
        <td class="text-center">$3,138.92</td>
        <td class="text-center">$2,687.15</td>
        <td class="text-center">$2,685.67</td>
        <td class="text-center">$2,297.61</td>
        <td class="text-center">$2,305.87</td>
        <td class="text-center">$1,972.69</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
        <td class="text-center">$4,897.59</td>
        <td class="text-center">$4,605.49</td>
        <td class="text-center">$4,414.13</td>
        <td class="text-center">$3,802.39</td>
        <td class="text-center">$4,671.84</td>
        <td class="text-center">$4,034.11</td>
        <td class="text-center">$4,252.29</td>
        <td class="text-center">$5,286.19</td>
        <td class="text-center">$5,379.88</td>
        <td class="text-center">$4,986.65</td>
        <td class="text-center">$4,686.93</td>
        <td class="text-center">$4,329.45</td>
        <td class="text-center">$4,757.39</td>
        <td class="text-center">$4,495.87</td>
        <td class="text-center">$4,107.90</td>
        <td class="text-center">$3,872.70</td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width dark font-weight-bold">Monthly Totals</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width font-weight-bold">Tier 1: Employee Only (EE)</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left">Specific Rate</td>
        <td class="text-center">$8,794.72</td>
        <td class="text-center">$5,982.46</td>
        <td class="text-center">$12,245.39</td>
        <td class="text-center">$10,312.64</td>
        <td class="text-center">$10,908.43</td>
        <td class="text-center">$9,188.97</td>
        <td class="text-center">$7,458.04</td>
        <td class="text-center">$7,049.97</td>
        <td class="text-center">$7,104.36</td>
        <td class="text-center">$8,865.86</td>
        <td class="text-center">$6,028.47</td>
        <td class="text-center">$7,518.17</td>
        <td class="text-center">$11,002.51</td>
        <td class="text-center">$12,357.70</td>
        <td class="text-center">$9,268.06</td>
        <td class="text-center">$10,407.01</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Rate</td>
        <td class="text-center">$2,698.19</td>
        <td class="text-center">$3,360.71</td>
        <td class="text-center">$1,615.24</td>
        <td class="text-center">$1,479.26</td>
        <td class="text-center">$2,076.38</td>
        <td class="text-center">$1,924.38</td>
        <td class="text-center">$2,544.86</td>
        <td class="text-center">$3,515.36</td>
        <td class="text-center">$3,577.24</td>
        <td class="text-center">$2,746.55</td>
        <td class="text-center">$3,421.57</td>
        <td class="text-center">$2,591.90</td>
        <td class="text-center">$2,113.42</td>
        <td class="text-center">$1,644.05</td>
        <td class="text-center">$1,960.10</td>
        <td class="text-center">$1,506.90</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Factor</td>
        <td class="text-center">$21,404.25</td>
        <td class="text-center">$21,509.25</td>
        <td class="text-center">$15,652.35</td>
        <td class="text-center">$13,438.74</td>
        <td class="text-center">$18,331.95</td>
        <td class="text-center">$15,739.50</td>
        <td class="text-center">$18,377.10</td>
        <td class="text-center">$25,051.95</td>
        <td class="text-center">$25,591.44</td>
        <td class="text-center">$21,908.04</td>
        <td class="text-center">$21,972.30</td>
        <td class="text-center">$18,809.91</td>
        <td class="text-center">$18,799.83</td>
        <td class="text-center">$16,083.27</td>
        <td class="text-center">$16,141.23</td>
        <td class="text-center">$13,808.76</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left">Broker Fee</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
        <td class="text-center">$735.00</td>
      </tr>
      <tr>
        <td class="fixed-left">TPA, RBP, PBM, Service Providers</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
        <td class="text-center">$1,953.00</td>
      </tr>
      <tr>
        <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
        <td class="text-center">$1,470.00</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total</td>
        <td class="text-center">$37,055.15</td>
        <td class="text-center">$35,010.42</td>
        <td class="text-center">$33,670.98</td>
        <td class="text-center">$29,388.64</td>
        <td class="text-center">$35,474.75</td>
        <td class="text-center">$31,010.85</td>
        <td class="text-center">$32,538.01</td>
        <td class="text-center">$39,775.28</td>
        <td class="text-center">$40,431.05</td>
        <td class="text-center">$37,678.45</td>
        <td class="text-center">$35,580.34</td>
        <td class="text-center">$33,077.98</td>
        <td class="text-center">$36,073.76</td>
        <td class="text-center">$34,243.02</td>
        <td class="text-center">$31,527.38</td>
        <td class="text-center">$29,880.67</td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width font-weight-bold">Tier 2: Employee + Spouse (ES)</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left">Specific Rate</td>
        <td class="text-center">$1,591.44</td>
        <td class="text-center">$1,082.54</td>
        <td class="text-center">$2,215.84</td>
        <td class="text-center">$1,866.10</td>
        <td class="text-center">$1,973.90</td>
        <td class="text-center">$1,662.75</td>
        <td class="text-center">$1,349.56</td>
        <td class="text-center">$1,275.71</td>
        <td class="text-center">$1,285.56</td>
        <td class="text-center">$1,604.30</td>
        <td class="text-center">$1,090.87</td>
        <td class="text-center">$1,360.44</td>
        <td class="text-center">$1,990.94</td>
        <td class="text-center">$2,236.16</td>
        <td class="text-center">$1,677.07</td>
        <td class="text-center">$1,883.18</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Rate</td>
        <td class="text-center">$488.25</td>
        <td class="text-center">$608.13</td>
        <td class="text-center">$292.28</td>
        <td class="text-center">$267.68</td>
        <td class="text-center">$375.73</td>
        <td class="text-center">$348.22</td>
        <td class="text-center">$460.49</td>
        <td class="text-center">$636.10</td>
        <td class="text-center">$647.32</td>
        <td class="text-center">$497.00</td>
        <td class="text-center">$619.14</td>
        <td class="text-center">$469.00</td>
        <td class="text-center">$382.42</td>
        <td class="text-center">$297.50</td>
        <td class="text-center">$354.69</td>
        <td class="text-center">$272.68</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Factor</td>
        <td class="text-center">$3,873.12</td>
        <td class="text-center">$3,892.14</td>
        <td class="text-center">$2,832.32</td>
        <td class="text-center">$2,431.78</td>
        <td class="text-center">$3,317.22</td>
        <td class="text-center">$2,848.08</td>
        <td class="text-center">$3,325.38</td>
        <td class="text-center">$4,533.22</td>
        <td class="text-center">$4,630.82</td>
        <td class="text-center">$3,964.32</td>
        <td class="text-center">$3,975.96</td>
        <td class="text-center">$3,403.72</td>
        <td class="text-center">$3,401.84</td>
        <td class="text-center">$2,910.32</td>
        <td class="text-center">$2,920.78</td>
        <td class="text-center">$2,498.74</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left">Broker Fee</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
        <td class="text-center">$70.00</td>
      </tr>
      <tr>
        <td class="fixed-left">TPA, RBP, PBM, Service Providers</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
        <td class="text-center">$186.00</td>
      </tr>
      <tr>
        <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
        <td class="text-center">$140.00</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total</td>
        <td class="text-center">$6,348.81</td>
        <td class="text-center">$5,978.81</td>
        <td class="text-center">$5,736.43</td>
        <td class="text-center">$4,961.56</td>
        <td class="text-center">$6,062.85</td>
        <td class="text-center">$5,255.05</td>
        <td class="text-center">$5,531.43</td>
        <td class="text-center">$6,841.03</td>
        <td class="text-center">$6,959.70</td>
        <td class="text-center">$6,461.62</td>
        <td class="text-center">$6,081.96</td>
        <td class="text-center">$5,629.16</td>
        <td class="text-center">$6,171.20</td>
        <td class="text-center">$5,839.98</td>
        <td class="text-center">$5,348.54</td>
        <td class="text-center">$5,050.60</td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width font-weight-bold">Tier 3: Employee + Child(ren) (EC)</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left">Specific Rate</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Rate</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Factor</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left">Broker Fee</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left">TPA, RBP, PBM, Service Providers</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width font-weight-bold">Tier 4: Employee + Family (EF)</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left">Specific Rate</td>
        <td class="text-center">$13,820.42</td>
        <td class="text-center">$9,401.08</td>
        <td class="text-center">$19,242.84</td>
        <td class="text-center">$16,205.73</td>
        <td class="text-center">$17,141.89</td>
        <td class="text-center">$14,439.81</td>
        <td class="text-center">$11,719.94</td>
        <td class="text-center">$11,078.61</td>
        <td class="text-center">$11,164.15</td>
        <td class="text-center">$13,932.15</td>
        <td class="text-center">$9,473.31</td>
        <td class="text-center">$11,814.42</td>
        <td class="text-center">$17,289.81</td>
        <td class="text-center">$19,419.40</td>
        <td class="text-center">$14,564.16</td>
        <td class="text-center">$16,354.03</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Rate</td>
        <td class="text-center">$4,240.08</td>
        <td class="text-center">$5,281.20</td>
        <td class="text-center">$2,538.15</td>
        <td class="text-center">$2,324.55</td>
        <td class="text-center">$3,262.95</td>
        <td class="text-center">$3,024.10</td>
        <td class="text-center">$3,999.00</td>
        <td class="text-center">$5,524.06</td>
        <td class="text-center">$5,621.46</td>
        <td class="text-center">$4,316.00</td>
        <td class="text-center">$5,376.76</td>
        <td class="text-center">$4,072.92</td>
        <td class="text-center">$3,321.09</td>
        <td class="text-center">$2,583.50</td>
        <td class="text-center">$3,080.15</td>
        <td class="text-center">$2,368.06</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Factor</td>
        <td class="text-center">$33,635.03</td>
        <td class="text-center">$33,800.14</td>
        <td class="text-center">$24,596.44</td>
        <td class="text-center">$21,118.02</td>
        <td class="text-center">$28,807.35</td>
        <td class="text-center">$24,733.28</td>
        <td class="text-center">$28,878.30</td>
        <td class="text-center">$39,367.46</td>
        <td class="text-center">$40,215.12</td>
        <td class="text-center">$34,427.03</td>
        <td class="text-center">$34,528.12</td>
        <td class="text-center">$29,558.65</td>
        <td class="text-center">$29,542.37</td>
        <td class="text-center">$25,273.71</td>
        <td class="text-center">$25,364.57</td>
        <td class="text-center">$21,699.59</td>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
        <td class="text-center">$0.00</td>
      </tr>
      <tr>
        <td class="fixed-left">Broker Fee</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
        <td class="text-center">$385.00</td>
      </tr>
      <tr>
        <td class="fixed-left">TPA, RBP, PBM, Service Providers</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
        <td class="text-center">$1,023.00</td>
      </tr>
      <tr>
        <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
        <td class="text-center">$770.00</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total</td>
        <td class="text-center">$53,873.53</td>
        <td class="text-center">$50,660.42</td>
        <td class="text-center">$48,555.43</td>
        <td class="text-center">$41,826.30</td>
        <td class="text-center">$51,390.20</td>
        <td class="text-center">$44,375.19</td>
        <td class="text-center">$46,775.23</td>
        <td class="text-center">$58,148.12</td>
        <td class="text-center">$59,178.74</td>
        <td class="text-center">$54,853.18</td>
        <td class="text-center">$51,556.19</td>
        <td class="text-center">$47,623.98</td>
        <td class="text-center">$52,331.27</td>
        <td class="text-center">$49,454.61</td>
        <td class="text-center">$45,186.89</td>
        <td class="text-center">$42,599.68</td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Composite Aggregate Rate</td>
        <td class="text-center">$218.43</td>
        <td class="text-center">$272.06</td>
        <td class="text-center">$130.75</td>
        <td class="text-center">$119.75</td>
        <td class="text-center">$168.09</td>
        <td class="text-center">$155.79</td>
        <td class="text-center">$206.01</td>
        <td class="text-center">$284.57</td>
        <td class="text-center">$289.59</td>
        <td class="text-center">$222.34</td>
        <td class="text-center">$276.98</td>
        <td class="text-center">$209.82</td>
        <td class="text-center">$171.09</td>
        <td class="text-center">$133.09</td>
        <td class="text-center">$158.67</td>
        <td class="text-center">$121.99</td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width dark font-weight-bold">Quote Summary</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Estimated Spec Premium</td>
        <td class="text-center">$290,478.89</td>
        <td class="text-center">$197,592.95</td>
        <td class="text-center">$404,448.83</td>
        <td class="text-center">$340,613.62</td>
        <td class="text-center">$360,290.70</td>
        <td class="text-center">$303,498.38</td>
        <td class="text-center">$246,330.50</td>
        <td class="text-center">$232,851.44</td>
        <td class="text-center">$234,648.96</td>
        <td class="text-center">$292,827.78</td>
        <td class="text-center">$199,111.75</td>
        <td class="text-center">$248,316.26</td>
        <td class="text-center">$363,399.12</td>
        <td class="text-center">$408,159.19</td>
        <td class="text-center">$306,111.54</td>
        <td class="text-center">$343,730.69</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Estimated Aggregate Premium</td>
        <td class="text-center">$89,118.20</td>
        <td class="text-center">$111,000.54</td>
        <td class="text-center">$53,347.98</td>
        <td class="text-center">$48,857.93</td>
        <td class="text-center">$68,580.71</td>
        <td class="text-center">$63,560.36</td>
        <td class="text-center">$84,052.16</td>
        <td class="text-center">$116,106.23</td>
        <td class="text-center">$118,152.30</td>
        <td class="text-center">$90,714.62</td>
        <td class="text-center">$113,009.57</td>
        <td class="text-center">$85,605.83</td>
        <td class="text-center">$69,803.16</td>
        <td class="text-center">$54,300.62</td>
        <td class="text-center">$64,739.30</td>
        <td class="text-center">$49,771.60</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total Attachment Point</td>
        <td class="text-center">$706,948.80</td>
        <td class="text-center">$710,418.36</td>
        <td class="text-center">$516,973.32</td>
        <td class="text-center">$443,862.48</td>
        <td class="text-center">$605,478.24</td>
        <td class="text-center">$519,850.32</td>
        <td class="text-center">$606,969.36</td>
        <td class="text-center">$827,431.56</td>
        <td class="text-center">$845,248.56</td>
        <td class="text-center">$723,592.68</td>
        <td class="text-center">$725,716.56</td>
        <td class="text-center">$621,267.36</td>
        <td class="text-center">$620,928.48</td>
        <td class="text-center">$531,207.60</td>
        <td class="text-center">$533,118.96</td>
        <td class="text-center">$456,085.08</td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Estimated Total Premium</td>
        <td class="text-center">$379,597.09</td>
        <td class="text-center">$308,593.49</td>
        <td class="text-center">$457,796.81</td>
        <td class="text-center">$389,471.54</td>
        <td class="text-center">$428,871.41</td>
        <td class="text-center">$367,058.75</td>
        <td class="text-center">$330,382.67</td>
        <td class="text-center">$348,957.67</td>
        <td class="text-center">$352,801.26</td>
        <td class="text-center">$383,542.40</td>
        <td class="text-center">$312,121.32</td>
        <td class="text-center">$333,922.09</td>
        <td class="text-center">$433,202.28</td>
        <td class="text-center">$462,459.82</td>
        <td class="text-center">$370,850.84</td>
        <td class="text-center">$393,502.28</td>
      </tr>
    </tbody>
  </table>
</div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="panel panel-default sticky-panel">
                                <div class="panel-heading panel-heading-divider panel-heading-full-width d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="panel-title">
                                            <i data-feather="sun" class="icon-sm"></i>
                                            Quote Request Recipients
                                        </span>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="alert alert-info alert-simple">
                                        <div class="icon"><span class="s7-info"></span></div>
                                        <div class="message"> <strong>No Underwritten quotes available: </strong> There are no current active quotes available. </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="disabled" role="tabpanel" aria-labelledby="disabled-tab">
                            <ul class="timeline">
                                <?php if (!empty($timelineData)): ?>
                                    <?php foreach ($timelineData as $timelineItem): ?>
                                        <li class="timeline-item">
                                            <div class="timeline-content timeline-type file">
                                                <div class="timeline-icon">
                                                    <i class="link-icon icon-md" data-feather="file-text"></i>
                                                </div>
                                                <div class="timeline-header">
                                                    <span class="timeline-author">
                                                        <?php echo htmlspecialchars($timelineItem->user->firstName ?? 'System') . ' ' . htmlspecialchars($timelineItem->user->lastName ?? 'User'); ?>
                                                    </span>
                                                    <p class="timeline-activity mr-1">
                                                        updated Quote Request #<?php echo htmlspecialchars($RequestQuots->id); ?>
                                                    </p>
                                                    <span class="timeline-time">
                                                        <?php echo $timelineItem->created ? $timelineItem->created->format('M d, Y (g:ia)') : ''; ?>
                                                    </span>
                                                    <div class="timeline-summary">
                                                        <table class="table table-bordered table-sm font-size-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <div class="diff">
                                                                            <span class="new">
                                                                                <span class="new badge badge-secondary">
                                                                                    <?php
                                                                                    $statusOptions = \Cake\Core\Configure::read('keyFeatures.STATUS');
                                                                                    echo isset($statusOptions[$timelineItem->status]) ? $statusOptions[$timelineItem->status] : 'Unknown';
                                                                                    ?>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php if (!empty($timelineItem->message)): ?>
                                                                <tr>
                                                                    <td width="100px">Note</td>
                                                                    <td><?php echo htmlspecialchars($timelineItem->message); ?></td>
                                                                </tr>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="timeline-item">
                                        <div class="timeline-content timeline-type file">
                                            <div class="timeline-icon">
                                                <i class="link-icon icon-md" data-feather="info"></i>
                                            </div>
                                            <div class="timeline-header">
                                                <span class="timeline-author">System</span>
                                                <p class="timeline-activity mr-1">No timeline activity available</p>
                                                <span class="timeline-time">
                                                    <?php echo date('M d, Y (g:ia)'); ?>
                                                </span>
                                                <div class="timeline-summary">
                                                    <table class="table table-bordered table-sm font-size-sm">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <em>No status updates have been recorded for this quote request yet.</em>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="quotes" role="tabpanel" aria-labelledby="quotes-tab">
                            <div class="row">
                                <div class="col-md-6 d-flex align-items-stretch">
                                    <div class="panel">
                                        <div class="panel-heading panel-heading-divider">  <span class="panel-title"><i class="link-icon icon-md" data-feather="users"></i> Group Information</span> </div>
                                        <div class="panel-body">
                                            <dl class="row">
                                                <dt class="col-sm-4 text-right">Group Information</dt>
                                                <dd class="col-sm-8"> <strong> <a href="#"><?php echo htmlspecialchars($RequestQuots->quotgroup->group_name ?? ''); ?></a> </strong>
                                                    <address class="address-summary ">
                                                        <?php echo htmlspecialchars($RequestQuots->quotgroup->address ?? ''); ?><br>
                                                        <?php echo htmlspecialchars(($RequestQuots->quotgroup->city ?? '') . ', ' . ($RequestQuots->quotgroup->state ?? '') . ' ' . ($RequestQuots->quotgroup->zip_code ?? '')); ?>
                                                    </address>
                                                </dd>
                                                <dt class="col-sm-4 text-right">Business Classification</dt>
                                                <dd class="col-sm-8"><?php echo htmlspecialchars($RequestQuots->quotgroup->SIC_Code ?? ''); ?></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-stretch">
                                    <div class="panel">
                                        <div class="panel-heading panel-heading-divider"> <span class="panel-title"><i class="link-icon icon-md" data-feather="user"></i> Submitted By</span> </div>
                                        <div class="panel-body">
                                            <dl class="row">
                                                <dt class="col-sm-4 text-right">Organization Name</dt>
                                                <dd class="col-sm-8"><?php echo htmlspecialchars($RequestQuots->user->organization_name ?? ''); ?></dd>
                                                <dt class="col-sm-4 text-right">Organization Type</dt>
                                                <dd class="col-sm-8 text-capitalize"><?php echo htmlspecialchars($RequestQuots->user->organization_type ?? ''); ?></dd>
                                                <dt class="col-sm-4 text-right">Contact Person</dt>
                                                <dd class="col-sm-8"><?php echo htmlspecialchars(($RequestQuots->user->firstName ?? '') . ' ' . ($RequestQuots->user->lastName ?? '')); ?></dd>
                                                <dt class="col-sm-4 text-right">Contact Phone</dt>
                                                <dd class="col-sm-8"> <?php echo htmlspecialchars($RequestQuots->user->contactNumber ?? ''); ?></dd>
                                                <dt class="col-sm-4 text-right">Contact Email</dt>
                                                <dd class="col-sm-8"><a href="mailto:<?php echo htmlspecialchars($RequestQuots->user->email ?? ''); ?>"><?php echo htmlspecialchars($RequestQuots->user->email ?? ''); ?></a></dd>
                                                <dt class="col-sm-4 text-right">Request Created</dt>
                                                <dd class="col-sm-8"><?php echo $RequestQuots->created ? $RequestQuots->created->format('M d, Y (g:ia)') : ''; ?> by <?php echo htmlspecialchars(($RequestQuots->user->firstName ?? '') . ' ' . ($RequestQuots->user->lastName ?? '')); ?></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 d-flex align-items-stretch">
                                    <div class="panel panel-default">
                                        <div class="panel-heading panel-heading-divider"> <span class="panel-title"><i class="link-icon icon-md" data-feather="calendar"></i> Networks &amp; Dates</span> </div>
                                        <div class="panel-body">
                                            <dl class="row">
                                                <dt class="col-sm-4 text-right">Networks or Repricing Requested</dt>
                                                <dd class="col-sm-7">
                                                    <ul class="pl-3">
                                                        <?php
                                                        if (!empty($networksDetails)) {
                                                            foreach ($networksDetails as $network) {
                                                                echo '<li>' . htmlspecialchars($network->name) . '</li>';
                                                            }
                                                        } else {
                                                            // Fallback: show IDs if no network details found
                                                            $networkIds = explode(',', $RequestQuots->networking_id ?? '');
                                                            foreach ($networkIds as $networkId) {
                                                                if (!empty(trim($networkId))) {
                                                                    echo '<li>' . htmlspecialchars(trim($networkId)) . '</li>';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </dd>
                                                <div class="mt-3">&nbsp;</div>
                                                <dt class="col-sm-4 text-right">Policy Effective Date</dt>
                                                <dd class="col-sm-7">
                                                    <p><?php echo $RequestQuots->Policy_Effective_Date ? $RequestQuots->Policy_Effective_Date->format('M d, Y') : ''; ?></p>
                                                </dd>
                                                <dt class="col-sm-4 text-right">Policy Termination Date</dt>
                                                <dd class="col-sm-7">
                                                    <p><?php echo $RequestQuots->Policy_Termination_Date ? $RequestQuots->Policy_Termination_Date->format('M d, Y') : ''; ?></p>
                                                </dd>
                                                <dt class="col-sm-4 text-right">Carrier Responses Due</dt>
                                                <dd class="col-sm-7">
                                                    <p><?php echo $RequestQuots->Final_Proposals_Due ? $RequestQuots->Final_Proposals_Due->format('M d, Y') : ''; ?></p>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-stretch">
                                    <div class="panel panel-default">
                                        <div class="panel-heading panel-heading-divider"> <span class="panel-title"><i class="link-icon icon-md" data-feather="file-text"></i> Notes</span> </div>
                                        <div class="panel-body">
                                            <div class="alert alert-secondary">
                                                <div class="icon"><span class="s7-info"></span></div>
                                                <div class="message"> <strong><?php echo !empty($RequestQuots->notes) ? 'Notes:' : 'No Notes:'; ?></strong> <?php echo htmlspecialchars($RequestQuots->notes ?? 'There were no notes included in this quote request.'); ?> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading panel-heading-divider"> <span class="panel-title"> <i class="link-icon icon-md" data-feather="x"></i> Requested Stop Loss </span> </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="stop-loss-plans" class="row">
                                                <div class="col-12">
                                                    <div class="panel panel-table">
                                                        <div class="card-header">
                                                            <div class="panel-title">
                                                                <div class="row">
                                                                    <div class="col-9"> <i class="link-icon icon-md" data-feather="x"></i> Requested Stop Loss Plan Designs </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h5>Prescription Drug Coverage Options</h5>
                                                            <hr>
                                                            <dl class="row">
                                                                <dt class="col-4">Specific Includes Rx Coverage?</dt>
                                                                <dd class="col-8"><?php echo htmlspecialchars($RequestQuots->specific_includes_rx ?? 'Yes'); ?></dd>
                                                                <dt class="col-4">Aggregate Includes Rx Coverage?</dt>
                                                                <dd class="col-8"><?php echo htmlspecialchars($RequestQuots->aggregate_includes_rx ?? 'Yes'); ?></dd>
                                                            </dl>
                                                            <table class="table table-bordered table-sm">
                                                                <thead>
                                                                    <tr class="table-light">
                                                                        <th>Plan Name</th>
                                                                        <th>Spec Deductible</th>
                                                                        <th>Spec Contract</th>
                                                                        <th>Aggregating Spec Deductible</th>
                                                                        <th>Agg Contract</th>
                                                                        <th>Agg Corridor</th>
                                                                        <th>Commission</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if (!empty($lossPlansDetails)) {
                                                                        foreach ($lossPlansDetails as $lossPlan) {
                                                                            echo '<tr>';
                                                                            echo '<td><strong>' . htmlspecialchars($lossPlan->plan_name) . '</strong></td>';
                                                                            echo '<td>$' . htmlspecialchars($lossPlan->Spec_Deductible ?? '') . '</td>';
                                                                            echo '<td>' . htmlspecialchars($lossPlan->Spec_Contract ?? '') . '</td>';
                                                                            echo '<td>$' . htmlspecialchars($lossPlan->Aggregating_Spec_Deductible ?? '') . '</td>';
                                                                            echo '<td>' . htmlspecialchars($lossPlan->Agg_Contract ?? '') . '</td>';
                                                                            echo '<td>' . htmlspecialchars($lossPlan->Agg_Corridor ?? '') . '</td>';
                                                                            echo '<td>' . htmlspecialchars($lossPlan->Commission ?? '') . '</td>';
                                                                            echo '</tr>';
                                                                        }
                                                                    } else {
                                                                        // Fallback: show IDs if no loss plan details found
                                                                        $lossPlanIds = explode(',', $RequestQuots->loss_plan ?? '');
                                                                        foreach ($lossPlanIds as $lossPlanId) {
                                                                            if (!empty(trim($lossPlanId))) {
                                                                                echo '<tr>';
                                                                                echo '<td><strong>' . htmlspecialchars(trim($lossPlanId)) . '</strong></td>';
                                                                                echo '<td>' . htmlspecialchars($RequestQuots->spec_deductible ?? '$35,000') . '</td>';
                                                                                echo '<td>' . htmlspecialchars($RequestQuots->spec_contract ?? '12/18') . '</td>';
                                                                                echo '<td>' . htmlspecialchars($RequestQuots->aggregating_spec_deductible ?? '$0') . '</td>';
                                                                                echo '<td>' . htmlspecialchars($RequestQuots->agg_contract ?? '12/18') . '</td>';
                                                                                echo '<td>' . htmlspecialchars($RequestQuots->agg_corridor ?? '1.25') . '</td>';
                                                                                echo '<td>' . htmlspecialchars($RequestQuots->commission ?? '0.00%') . '</td>';
                                                                                echo '</tr>';
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
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
                            <div class="panel panel-default">
                                <div class="panel-heading panel-heading-divider"> <span class="panel-title"> <i class="link-icon icon-md" data-feather="umbrella"></i> Employee Benefit Plan Designs </span> </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="employee-benefit-plans" class="row">
                                                <div class="col-12">
                                                    <div class="panel panel-table">
                                                        <div class="card-header">
                                                            <div class="panel-title">
                                                                <div class="row">
                                                                    <div class="col-12"> <i class="link-icon icon-md" data-feather="umbrella"></i> Requested Employee Benefit Plans </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-bordered table-responsive table-sm">
                                                                <thead>
                                                                    <tr class="table-light">
                                                                        <th class="w-10">Plan Name</th>
                                                                        <th class="w-10">Deductible</th>
                                                                        <th>Coinsurance</th>
                                                                        <th class="w-10">OOP<br>Maximum</th>
                                                                        <th>OOP Includes<br>Deductible?</th>
                                                                        <th>Rx Copay<br>Generic</th>
                                                                        <th>Rx Copay<br>Formulary</th>
                                                                        <th>Rx Copay<br>Non-Formulary</th>
                                                                        <th>Rx covers<br>specialty?</th>
                                                                        <th>Rx copay<br>specialty</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if (!empty($benefitPlansDetails)) {
                                                                        foreach ($benefitPlansDetails as $benefitPlan) {
                                                                            echo '<tr>';
                                                                            echo '<td><strong>' . htmlspecialchars($benefitPlan->plan_name) . ':</strong></td>';
                                                                            echo '<td> In: $' . htmlspecialchars($benefitPlan->deductible_in ?? '') . ' <br> Out: $' . htmlspecialchars($benefitPlan->deductible_out ?? '') . ' </td>';
                                                                            echo '<td> In: ' . htmlspecialchars($benefitPlan->coinsurance_in ?? '') . '% <br> Out: ' . htmlspecialchars($benefitPlan->coinsurance_out ?? '') . '% </td>';
                                                                            echo '<td> In: $' . htmlspecialchars($benefitPlan->oop_maximum_in ?? '') . ' <br> Out: $' . htmlspecialchars($benefitPlan->oop_maximum_out ?? '') . ' </td>';
                                                                            echo '<td> In: ' . (($benefitPlan->oop_includes_deductible_in ?? 0) == 1 ? 'Yes' : 'No') . ' <br> Out: ' . (($benefitPlan->oop_includes_deductible_out ?? 0) == 1 ? 'Yes' : 'No') . ' </td>';
                                                                            echo '<td> $' . htmlspecialchars($benefitPlan->rx_copay_generic ?? '') . ' </td>';
                                                                            echo '<td> $' . htmlspecialchars($benefitPlan->rx_copay_formulary ?? '') . ' </td>';
                                                                            echo '<td> $' . htmlspecialchars($benefitPlan->rx_copay_non_formulary ?? '') . ' </td>';
                                                                            echo '<td> ' . (($benefitPlan->rx_covers_specialty ?? 0) == 1 ? 'Yes' : 'No') . ' </td>';
                                                                            echo '<td> $' . htmlspecialchars($benefitPlan->rx_copay_specialty ?? '') . ' </td>';
                                                                            echo '</tr>';
                                                                        }
                                                                    } else {
                                                                        // Fallback: show IDs if no benefit plan details found
                                                                        $benefitPlanIds = explode(',', $RequestQuots->benifit_plan ?? '');
                                                                        foreach ($benefitPlanIds as $benefitPlanId) {
                                                                            if (!empty(trim($benefitPlanId))) {
                                                                                echo '<tr>';
                                                                                echo '<td><strong>' . htmlspecialchars(trim($benefitPlanId)) . ':</strong></td>';
                                                                                echo '<td> In: ' . htmlspecialchars($RequestQuots->in_network_deductible ?? '$3,500') . ' <br> Out: ' . htmlspecialchars($RequestQuots->out_network_deductible ?? '$7,000') . ' </td>';
                                                                                echo '<td> In: ' . htmlspecialchars($RequestQuots->in_network_coinsurance ?? '80%') . ' <br> Out: ' . htmlspecialchars($RequestQuots->out_network_coinsurance ?? '60%') . ' </td>';
                                                                                echo '<td> In: ' . htmlspecialchars($RequestQuots->in_network_oop_max ?? '$5,000') . ' <br> Out: ' . htmlspecialchars($RequestQuots->out_network_oop_max ?? '$10,000') . ' </td>';
                                                                                echo '<td> In: ' . htmlspecialchars($RequestQuots->in_network_oop_includes_deductible ?? 'Yes') . ' <br> Out: ' . htmlspecialchars($RequestQuots->out_network_oop_includes_deductible ?? 'Yes') . ' </td>';
                                                                                echo '<td> ' . htmlspecialchars($RequestQuots->rx_copay_generic ?? '$0') . ' </td>';
                                                                                echo '<td> ' . htmlspecialchars($RequestQuots->rx_copay_formulary ?? '$0') . ' </td>';
                                                                                echo '<td> ' . htmlspecialchars($RequestQuots->rx_copay_non_formulary ?? '$0') . ' </td>';
                                                                                echo '<td> ' . htmlspecialchars($RequestQuots->rx_covers_specialty ?? 'Yes') . ' </td>';
                                                                                echo '<td> ' . htmlspecialchars($RequestQuots->rx_copay_specialty ?? '$300') . ' </td>';
                                                                                echo '</tr>';
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
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
                            <div class="panel panel-default">
                                <div class="panel-heading panel-heading-divider panel-heading-full-width"> <span class="panel-title"> <i class="link-icon icon-md" data-feather="dollar-sign"></i> Claims Experience </span> </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <div class="message"> <i class="link-icon icon-md" data-feather="dollar-sign"></i> <strong><?php echo !empty($RequestQuots->claims_experience) ? 'Claims Experience:' : 'None entered:'; ?></strong> <?php echo htmlspecialchars($RequestQuots->claims_experience ?? 'No claims experience was entered.'); ?> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading panel-heading-divider">
                                                <div class="row">
                                                    <div class="col-md-9"> <span class="panel-title"> <i class="link-icon icon-md" data-feather="link"></i> Attachments </span> <span class="panel-subtitle"> Upload any attachment such as claims experience (if available), case management history or anything else. </span> </div>
                                                    <div class="col-md-3 text-right">
                                                        <!--<a href="#" class="btn btn-rounded btn-secondary mr-1 d-print-none">Upload Files</a> -->
                                                        <!-- <a href="#" class="btn btn-rounded btn-primary d-print-none" data-no-ajax="1">Download All</a>  -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="table-light">
                                                            <th scope="col">File Added</th>
                                                            <th scope="col">File Name</th>
                                                            <th scope="col">Type</th>
                                                            <th scope="col" class="actions">&nbsp;</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($censusData)): ?>
                                                            <?php foreach ($censusData as $census): ?>
                                                                <tr>
                                                                    <td><?php echo $census->created ? $census->created->format('M d, Y (g:ia)') : ''; ?></td>
                                                                    <td><a href="<?php echo $this->Url->build('/');?>img/uploads/census/<?php echo htmlspecialchars($census->xl_file ?? ''); ?>"><?php echo htmlspecialchars($census->xl_file ?? ''); ?></a>
                                                                        <?php if (!empty($census->member_count)): echo htmlspecialchars($census->member_count) . ' Member(s)'; endif; ?>
                                                                    </td>
                                                                    <td class=""><?php echo $census->type;?></td>
                                                                    <td class="text-right text-nowrap">
                                                                        <a href="<?php echo $this->Url->build('/');?>img/uploads/census/<?php echo htmlspecialchars($census->xl_file ?? ''); ?>" data-no-ajax="1" class="btn btn-xs btn-secondary btn-rounded mr-2 d-print-none"><i class="link-icon icon-md" data-feather="download"></i> Download</a>
                                                                        <a href="#" class="btn btn-xs btn-danger btn-rounded"><i class="link-icon icon-md" data-feather="delete"></i> Delete</a>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center">No attachments found.</td>
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
            </div>
        </div>





    </div>


</div>
<style>
  .panel.sticky-panel .panel-table table th.fixed-left, .panel.sticky-panel .panel-table table td.fixed-left {
	z-index: 998;
	position: sticky;
	left: 0;
}

.panel.sticky-panel .panel-table table thead {
	z-index: 999;
	position: sticky;
	top: 0;
	background: #fff;
	box-shadow: 0 3px 0 #eceeef;
}
  </style>