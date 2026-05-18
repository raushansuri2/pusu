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
                <?php $TOTAL_FEE = 0 ;?>
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
                     <?php $TOTAL_FEE +=$fee;?>
                  </tr>
                  <?php endforeach; ?>
                  <?php else: ?>
                  
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

         </table>
      </div>






















      <?php
// Dynamic quote calculation system

// Function to calculate dynamic specific rates based on benefit and loss plans
function calculateDynamicSpecificRates($benefitPlansDetails, $lossPlansDetails, $total_EMP) {
    // Base rates for different plan combinations
    $baseRates = [
        'EE' => 284.88, // Employee Only base
        'ES' => 468.36, // Employee + Spouse base
        'EC' => 443.70, // Employee + Child(ren) base
        'EF' => 739.52  // Employee + Family base
    ];

    // Benefit plan multipliers
    $benefitMultipliers = [];
    if (!empty($benefitPlansDetails)) {
        foreach ($benefitPlansDetails as $plan) {
            // Different multipliers based on plan type or characteristics
            if (isset($plan->plan_type)) {
                switch (strtolower($plan->plan_type)) {
                    case 'ppo':
                        $benefitMultipliers[] = 1.15;
                        break;
                    case 'hmo':
                        $benefitMultipliers[] = 0.95;
                        break;
                    case 'epo':
                        $benefitMultipliers[] = 1.05;
                        break;
                    default:
                        $benefitMultipliers[] = 1.0;
                }
            } else {
                // Default multiplier based on plan name patterns
                $planName = strtolower($plan->plan_name ?? '');
                if (strpos($planName, 'ppo') !== false) {
                    $benefitMultipliers[] = 1.15;
                } elseif (strpos($planName, 'hmo') !== false) {
                    $benefitMultipliers[] = 0.95;
                } elseif (strpos($planName, 'epo') !== false) {
                    $benefitMultipliers[] = 1.05;
                } else {
                    $benefitMultipliers[] = 1.0;
                }
            }
        }
    }

    // Loss plan adjustments
    $lossAdjustments = [];
    if (!empty($lossPlansDetails)) {
        foreach ($lossPlansDetails as $lossPlan) {
            // Adjust based on specific deductible
            $deductible = $lossPlan->Spec_Deductible ?? 75000;
            if ($deductible <= 25000) {
                $lossAdjustments[] = 1.25; // Lower deductible = higher rate
            } elseif ($deductible <= 50000) {
                $lossAdjustments[] = 1.10;
            } elseif ($deductible <= 75000) {
                $lossAdjustments[] = 1.0;
            } else {
                $lossAdjustments[] = 0.90; // Higher deductible = lower rate
            }

            // Commission impact
            $commission = $lossPlan->Commission ?? 0;
            if ($commission > 10) {
                $lossAdjustments[] = 1.05;
            }
        }
    }

    // Calculate final rates
    $finalRates = [];
    $benefitMultiplier = !empty($benefitMultipliers) ? array_sum($benefitMultipliers) / count($benefitMultipliers) : 1.0;
    $lossAdjustment = !empty($lossAdjustments) ? array_sum($lossAdjustments) / count($lossAdjustments) : 1.0;

    // Employee count scaling
    $employeeScaling = 1.0;
    if ($total_EMP > 500) {
        $employeeScaling = 0.95; // Large group discount
    } elseif ($total_EMP > 100) {
        $employeeScaling = 0.98;
    } elseif ($total_EMP < 10) {
        $employeeScaling = 1.10; // Small group loading
    }

    foreach ($baseRates as $tier => $baseRate) {
        $finalRates[$tier] = $baseRate * $benefitMultiplier * $lossAdjustment * $employeeScaling;
    }

    return $finalRates;
}

// Function to generate comprehensive quote calculations for all plan combinations
function generateQuoteCalculations($benefitPlansDetails, $lossPlansDetails, $file_counts, $feesData) {
    $calculations = [];

    // Get employee counts and totals
    $ee_count = $file_counts['EE'] ?? 0;
    $es_count = $file_counts['ES'] ?? 0;
    $ec_count = $file_counts['EC'] ?? 0;
    $ef_count = $file_counts['EF'] ?? 0;
    $total_EMP = $ee_count + $es_count + $ec_count + $ef_count;

    // Calculate total fees
    $totalFees = 105; // Default $70 TPA + $35 Broker
    if (!empty($feesData)) {
        $totalFees = 0;
        foreach ($feesData as $fee) {
            if (is_array($fee) && isset($fee['value'])) {
                $totalFees += $fee['value'];
            }
        }
    }

    // Generate calculations for each loss plan
    foreach ($lossPlansDetails as $lossPlan) {
        $lossPlanName = $lossPlan->plan_name ?? 'Unknown Plan';
        $T_ANNUAL = $lossPlan->Spec_Deductible ?? 75000;
        $AG_CORRIDOR = $lossPlan->Agg_Corridor ?? 1.25;

        // Calculate Expected Claims (C)
        $C = 0;
        if ($total_EMP > 0 && $T_ANNUAL > 0) {
            $C = ($T_ANNUAL / 12) / $total_EMP;
        }

        // Get specific rates for this loss plan
        $specificRates = calculateDynamicSpecificRates($benefitPlansDetails, [$lossPlan], $total_EMP);

        // Calculate aggregate rates for each tier
        $aggregateRates = [
            'EE' => $C * $AG_CORRIDOR,
            'ES' => $C * $AG_CORRIDOR * 1.9,
            'EC' => $C * $AG_CORRIDOR * 1.8,
            'EF' => $C * $AG_CORRIDOR * 2.6
        ];

        // Calculate aggregate accommodations
        $aggregateAccommodations = [
            'EE' => $aggregateRates['EE'] - $C,
            'ES' => $aggregateRates['ES'] - ($C * 1.9),
            'EC' => $aggregateRates['EC'] - ($C * 1.8),
            'EF' => $aggregateRates['EF'] - ($C * 2.6)
        ];

        // Calculate total PEPM rates
        $totalPEPM = [];
        foreach (['EE', 'ES', 'EC', 'EF'] as $tier) {
            $totalPEPM[$tier] = $specificRates[$tier] + $aggregateRates[$tier] + $totalFees;
        }

        // Calculate monthly totals
        $monthlyTotals = [
            'EE' => [
                'specific' => $specificRates['EE'] * $ee_count,
                'aggregate' => $aggregateRates['EE'] * $ee_count,
                'accommodation' => $aggregateAccommodations['EE'] * $ee_count,
                'tpa_fee' => 70 * $ee_count,
                'broker_fee' => 35 * $ee_count,
                'total' => 0
            ],
            'ES' => [
                'specific' => $specificRates['ES'] * $es_count,
                'aggregate' => $aggregateRates['ES'] * $es_count,
                'accommodation' => $aggregateAccommodations['ES'] * $es_count,
                'tpa_fee' => 70 * $es_count * 0.1,
                'broker_fee' => 35 * $es_count * 0.1,
                'total' => 0
            ],
            'EC' => [
                'specific' => $specificRates['EC'] * $ec_count,
                'aggregate' => $aggregateRates['EC'] * $ec_count,
                'accommodation' => $aggregateAccommodations['EC'] * $ec_count,
                'tpa_fee' => 0,
                'broker_fee' => 0,
                'total' => 0
            ],
            'EF' => [
                'specific' => $specificRates['EF'] * $ef_count,
                'aggregate' => $aggregateRates['EF'] * $ef_count,
                'accommodation' => $aggregateAccommodations['EF'] * $ef_count,
                'tpa_fee' => 70 * $ef_count * 0.55,
                'broker_fee' => 35 * $ef_count * 0.55,
                'total' => 0
            ]
        ];

        // Calculate totals for each tier
        foreach (['EE', 'ES', 'EC', 'EF'] as $tier) {
            $monthlyTotals[$tier]['total'] = $monthlyTotals[$tier]['specific'] +
                                                $monthlyTotals[$tier]['aggregate'] +
                                                $monthlyTotals[$tier]['accommodation'] +
                                                $monthlyTotals[$tier]['tpa_fee'] +
                                                $monthlyTotals[$tier]['broker_fee'];
        }

        // Calculate summary values
        $totalAggregatePremium = $monthlyTotals['EE']['aggregate'] + $monthlyTotals['ES']['aggregate'] +
                             $monthlyTotals['EC']['aggregate'] + $monthlyTotals['EF']['aggregate'];
        $compositeAggregateRate = $total_EMP > 0 ? $totalAggregatePremium / $total_EMP : 0;

        $estimatedSpecPremium = ($monthlyTotals['EE']['specific'] + $monthlyTotals['ES']['specific'] +
                               $monthlyTotals['EC']['specific'] + $monthlyTotals['EF']['specific']) * 12;

        $estimatedAggregatePremium = $totalAggregatePremium * 12;
        $totalAttachmentPoint = $T_ANNUAL * $total_EMP;
        $estimatedTotalPremium = $estimatedSpecPremium + $estimatedAggregatePremium;

        $calculations[$lossPlanName] = [
            'loss_plan' => $lossPlan,
            'specific_rates' => $specificRates,
            'aggregate_rates' => $aggregateRates,
            'aggregate_accommodations' => $aggregateAccommodations,
            'total_pepm' => $totalPEPM,
            'monthly_totals' => $monthlyTotals,
            'summary' => [
                'composite_aggregate_rate' => $compositeAggregateRate,
                'estimated_spec_premium' => $estimatedSpecPremium,
                'estimated_aggregate_premium' => $estimatedAggregatePremium,
                'total_attachment_point' => $totalAttachmentPoint,
                'estimated_total_premium' => $estimatedTotalPremium
            ]
        ];
    }

    return $calculations;
}

// Testing function to validate calculations with different scenarios
function testCalculations() {
    echo "<div class='alert alert-info'>";
    echo "<h4>Calculation Testing Results</h4>";

    // Test scenario 1: Small group with basic plans
    $testFileCounts1 = ['EE' => 5, 'ES' => 2, 'EC' => 1, 'EF' => 1];
    $testBenefitPlans1 = [(object)['plan_type' => 'ppo', 'plan_name' => 'PPO Basic']];
    $testLossPlans1 = [(object)['plan_name' => 'Basic Loss', 'Spec_Deductible' => 50000, 'Agg_Corridor' => 1.25, 'Commission' => 8]];

    $calculations1 = generateQuoteCalculations($testBenefitPlans1, $testLossPlans1, $testFileCounts1, []);
    echo "<p><strong>Test 1 - Small Group (9 employees, PPO, $50K deductible):</strong></p>";
    echo "<pre>" . print_r($calculations1['Basic Loss']['summary'], true) . "</pre>";

    // Test scenario 2: Large group with multiple plans
    $testFileCounts2 = ['EE' => 50, 'ES' => 30, 'EC' => 15, 'EF' => 25];
    $testBenefitPlans2 = [(object)['plan_type' => 'hmo', 'plan_name' => 'HMO Premium']];
    $testLossPlans2 = [(object)['plan_name' => 'Premium Loss', 'Spec_Deductible' => 25000, 'Agg_Corridor' => 1.15, 'Commission' => 12]];

    $calculations2 = generateQuoteCalculations($testBenefitPlans2, $testLossPlans2, $testFileCounts2, []);
    echo "<p><strong>Test 2 - Large Group (120 employees, HMO, $25K deductible):</strong></p>";
    echo "<pre>" . print_r($calculations2['Premium Loss']['summary'], true) . "</pre>";

    // Test scenario 3: Mixed plans with different deductibles
    $testFileCounts3 = ['EE' => 15, 'ES' => 8, 'EC' => 5, 'EF' => 7];
    $testBenefitPlans3 = [(object)['plan_type' => 'epo', 'plan_name' => 'EPO Standard']];
    $testLossPlans3 = [
        (object)['plan_name' => 'Low Deductible', 'Spec_Deductible' => 25000, 'Agg_Corridor' => 1.20, 'Commission' => 15],
        (object)['plan_name' => 'High Deductible', 'Spec_Deductible' => 100000, 'Agg_Corridor' => 1.30, 'Commission' => 5]
    ];

    $calculations3 = generateQuoteCalculations($testBenefitPlans3, $testLossPlans3, $testFileCounts3, []);
    echo "<p><strong>Test 3 - Medium Group (35 employees, EPO, Multiple Deductibles):</strong></p>";
    echo "<p>Low Deductible Plan:</p><pre>" . print_r($calculations3['Low Deductible']['summary'], true) . "</pre>";
    echo "<p>High Deductible Plan:</p><pre>" . print_r($calculations3['High Deductible']['summary'], true) . "</pre>";

    echo "</div>";
}

// Main execution - Generate calculations for current data
// Ensure file_counts is an array to prevent errors
$file_counts = is_array($file_counts) ? $file_counts : ['EE' => 0, 'ES' => 0, 'EC' => 0, 'EF' => 0];
$allCalculations = generateQuoteCalculations($benefitPlansDetails, $lossPlansDetails, $file_counts, $feesData);

// Uncomment the line below to run tests (for development/debugging)
// testCalculations();
?>















<?php 

    $T_ANNUAL = 0; //$lossPlan->Spec_Deductible;
    $AG_CORRIDOR = 0;  //$lossPlan->Agg_Corridor;
    // Define specific rates for each tier (can be made dynamic later)
    $specificRate_EE = 284.88; // Employee Only
    $specificRate_ES = 468.36; // Employee + Spouse
    $specificRate_EC = 443.70; // Employee + Child(ren)
    $specificRate_EF = 739.52; // Employee + Family

    $T_ANNUAL = isset($T_ANNUAL) && $T_ANNUAL > 0 ? $T_ANNUAL : 75000;

    // Ensure AG_CORRIDOR is set and valid
    $AG_CORRIDOR = isset($AG_CORRIDOR) && $AG_CORRIDOR > 0 ? $AG_CORRIDOR : 1.25;

    // Calculate Expected Claims (C) - Specific Deductible annual divided by 12 months, then per employee
    $C = 0;
    if ($total_EMP > 0 && $T_ANNUAL > 0) {
        $C = ($T_ANNUAL / 12) / $total_EMP;
    }


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

    $totalFees = 105; // $70 TPA + $35 Broker default


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


?>
<div class="panel-table p-0 mt-3" style="width: 100%;overflow: scroll; max-height:700px;">
  <table class="table table-hover table-sm table-bordered table-illustrative-quote">
    <thead>

    </thead>
    <tbody>
        <tr>
        <th class="fixed-left"><strong>Employee Benefit Plan</strong></th>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                $planNames[] = htmlspecialchars($planss->plan_name);
                echo '<th colspan="'.count($lossPlansDetails).'" class="plan-name">'.htmlspecialchars($planss->plan_name).'</th>';
            }
        }else {
            echo '<th colspan="8" class="plan-name">No benefit plans selected</th>';
        }?>
      </tr>
      <tr>
        <th class="fixed-left"><strong>Network or Repricing</strong></th>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                echo '<th colspan="'.count($lossPlansDetails).'" class="plan-name">'.implode(', ', @$networkNames).'</th>';
            }
        }else {
            echo '<th colspan="8" class="plan-name">No Network</th>';
        }?>
      </tr>
        <tr>
            <th class="fixed-left"></th>
            <?php if (!empty($benefitPlansDetails)) {
                foreach ($benefitPlansDetails as $planss) {
                    if($lossPlansDetails){
                        foreach($lossPlansDetails as $lossPlan){ ?>
                        <th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
                    <?php } } ?>
            <?php } } ?>
        </tr>
      <tr>
        <td class="fixed-left full-width dark font-weight-bold">Stop Loss Plan</td>
        <td colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Specific Deductible</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){ ?>
                    <td class="text-center"><?php echo $lossPlan->Spec_Deductible;?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Specific Contract</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){ ?>
                    <td class="text-center"><?php echo $lossPlan->Spec_Contract;?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Aggregate Contract</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){ ?>
                    <td class="text-center"><?php echo $lossPlan->Agg_Contract;?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Aggregate Corridor</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){ ?>
                    <td class="text-center"><?php echo $lossPlan->Agg_Corridor;?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Aggregating Specific Deductible</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){ ?>
                    <td class="text-center"><?php echo $lossPlan->Aggregating_Spec_Deductible;?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Commission</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){ ?>
                    <td class="text-center"><?php echo $lossPlan->Commission;?></td>
                <?php } } ?>
        <?php } } ?>
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
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){ 
                        if($lossPlan->Spec_Deductible < 50000){
                            $ExpectedLargeClaims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $ExpectedLargeClaims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $riskMargin = round(($ExpectedLargeClaims * $lossPlan->Commission) / 100 , 2);
                        ?>
                    <td class="text-center"><?php echo round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EE'],2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Rate</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){ 
                        $EE = $file_counts['EE'] * 1;
                        $ES = $file_counts['ES'] * 2;
                        $EC = $file_counts['EC'] * 2.5;
                        $EF = $file_counts['EF'] * 4;
                        $total_memnber = $EE + $ES + $EC + $EF;
                        $MemberMonths = $total_memnber * 12;
                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Aggregate_Claims = 50000;
                        }else{
                            $Expected_Aggregate_Claims = 100000;
                        }
                        $Aggregate_Risk_Margin = $Expected_Aggregate_Claims * (($lossPlan->Agg_Corridor + $lossPlan->Commission)/100);
                        $Aggregate_Rate_PMPM = ($Expected_Aggregate_Claims + $Aggregate_Risk_Margin) / $MemberMonths;
                        ?>
                    <td class="text-center"><?php echo round($Aggregate_Rate_PMPM,2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Factor</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){ 
                        $Aggregate_Factor = 1+(($lossPlan->Aggregating_Spec_Deductible)/100);
                        // if($lossPlan->Spec_Deductible < 50000){
                        //     $ExpectedLargeClaims = (50000-$lossPlan->Spec_Deductible);
                        // }else{
                        //     $ExpectedLargeClaims = (100000-$lossPlan->Spec_Deductible);
                        // }
                        // $Attachment_Point = $ExpectedLargeClaims * $Aggregate_Factor;
                        ?>
                    <td class="text-center"><?php echo round($Aggregate_Factor,2);?></td>
                <?php } } ?>
        <?php } } ?>
        
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
