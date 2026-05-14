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
<div class="panel-table p-0 mt-3">
   <div class="table-responsive">
      <table class="table table-hover table-sm table-bordered table-illustrative-quote">
         <thead>
            <tr>
               <th class="fixed-left"><strong>Employee Benefit Plan</strong></th>
               <th colspan="8" class="plan-name">
                  <?php 
                  if (!empty($benefitPlansDetails)) {
                      $planNames = [];
                      foreach ($benefitPlansDetails as $plan) {
                          $planNames[] = htmlspecialchars($plan->plan_name ?? 'Unknown Plan');
                      }
                      echo implode(', ', $planNames);
                  } else {
                      echo 'Standard Plan';
                  }
                  ?>
               </th>
               <th colspan="8" class="plan-name">
                  <?php 
                  if (!empty($benefitPlansDetails) && count($benefitPlansDetails) > 1) {
                      $planNames = array_slice($benefitPlansDetails, 1);
                      $planNames = array_map(function($plan) {
                          return htmlspecialchars($plan->plan_name ?? 'Unknown Plan');
                      }, $planNames);
                      echo implode(', ', $planNames);
                  } else {
                      echo 'Alternative Plan';
                  }
                  ?>
               </th>
            </tr>
            <tr>
               <th class="fixed-left"><strong>Network or Repricing</strong></th>
               <th colspan="8" class="network-name">
                  <?php 
                  if (!empty($networksDetails)) {
                      $networkNames = [];
                      foreach ($networksDetails as $network) {
                          $networkNames[] = htmlspecialchars($network->name);
                      }
                      echo implode(', ', $networkNames);
                  } else {
                      echo 'Blue Cross/Blue Shield';
                  }
                  ?>
               </th>
               <th colspan="8" class="network-name">
                  <?php 
                  if (!empty($networksDetails)) {
                      echo implode(', ', $networkNames);
                  } else {
                      echo 'Blue Cross/Blue Shield';
                  }
                  ?>
               </th>
            </tr>
            <tr>
               <th class="fixed-left"></th>
               <?php for ($i = 1; $i <= 16; $i++): ?>
               <th class="text-center"><a href="" class="btn btn-secondary btn-xs btn-rounded">Print Quote</a></th>
               <?php endfor; ?>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td class="fixed-left full-width dark font-weight-bold">Stop Loss Plan</td>
               <td colspan="16"></td>
            </tr>
            <tr>
               <td class="fixed-left full-width" colspan="16"></td>
            </tr>
            <tr>
               <td class="fixed-left font-weight-bold">Specific Deductible</td>
               <?php 
               // Generate 8 columns for first plan, 8 for second plan
               if (!empty($lossPlansDetails)) {
                   for ($i = 0; $i < 8; $i++) {
                       $lossPlan = $lossPlansDetails[0] ?? null;
                       echo '<td class="text-center">$' . number_format($lossPlan->Spec_Deductible ?? 75000, 0) . '</td>';
                   }
                   for ($i = 0; $i < 8; $i++) {
                       $lossPlan = $lossPlansDetails[1] ?? $lossPlansDetails[0];
                       echo '<td class="text-center">$' . number_format($lossPlan->Spec_Deductible ?? 75000, 0) . '</td>';
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">$75,000</td>';
                   }
               }
               ?>
            </tr>
            <tr>
               <td class="fixed-left font-weight-bold">Specific Contract</td>
               <?php for ($i = 0; $i < 16; $i++) {
                   echo '<td class="text-center">12/18</td>';
               } ?>
            </tr>
            <tr>
               <td class="fixed-left font-weight-bold">Aggregate Contract</td>
               <?php for ($i = 0; $i < 16; $i++) {
                   echo '<td class="text-center">12/18</td>';
               } ?>
            </tr>
            <tr>
               <td class="fixed-left font-weight-bold">Aggregate Corridor</td>
               <?php 
               if (!empty($lossPlansDetails)) {
                   for ($i = 0; $i < 8; $i++) {
                       $lossPlan = $lossPlansDetails[0] ?? null;
                       echo '<td class="text-center">' . number_format($lossPlan->Agg_Corridor ?? 1.25, 2) . '</td>';
                   }
                   for ($i = 0; $i < 8; $i++) {
                       $lossPlan = $lossPlansDetails[1] ?? $lossPlansDetails[0];
                       echo '<td class="text-center">' . number_format($lossPlan->Agg_Corridor ?? 1.25, 2) . '</td>';
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">1.25</td>';
                   }
               }
               ?>
            </tr>
            <tr>
               <td class="fixed-left full-width dark font-weight-bold">Rates</td>
               <td colspan="16"></td>
            </tr>
            <tr>
               <td class="fixed-left full-width" colspan="16"></td>
            </tr>
            <?php foreach (['EE' => 'Employee Only (EE)', 'ES' => 'Employee + Spouse (ES)', 'EC' => 'Employee + Child(ren) (EC)', 'EF' => 'Employee + Family (EF)'] as $tier => $tierName): ?>
            <tr>
               <td class="fixed-left full-width font-weight-bold"><?php echo $tierName; ?></td>
               <td colspan="16"></td>
            </tr>
            <tr>
               <td class="fixed-left">Specific Rate</td>
               <?php 
               // Generate 16 columns with dynamic rates
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8); // 0 for first 8 columns, 1 for next 8
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation) {
                           echo '<td class="text-center">$' . number_format($calculation['specific_rates'][$tier] ?? 0, 2) . '</td>';
                       } else {
                           echo '<td class="text-center">$0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">$0.00</td>';
                   }
               }
               ?>
            </tr>
            <tr>
               <td class="fixed-left">Aggregate Rate</td>
               <?php 
               // Generate 16 columns with aggregate rates
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8);
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation) {
                           echo '<td class="text-center">$' . number_format($calculation['aggregate_rates'][$tier] ?? 0, 2) . '</td>';
                       } else {
                           echo '<td class="text-center">$0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">$0.00</td>';
                   }
               }
               ?>
            </tr>
            <tr>
               <td class="fixed-left">Aggregate Factor</td>
               <?php 
               // Generate 16 columns with aggregate factors
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8);
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation) {
                           $factor = 1.0;
                           if ($tier === 'ES') $factor = 1.9;
                           elseif ($tier === 'EC') $factor = 1.8;
                           elseif ($tier === 'EF') $factor = 2.6;
                           echo '<td class="text-center">' . number_format($calculation['loss_plan']->Agg_Corridor * $factor, 2) . '</td>';
                       } else {
                           echo '<td class="text-center">0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">0.00</td>';
                   }
               }
               ?>
            </tr>
            <tr>
               <td class="fixed-left">Aggregate Specific Deductible</td>
               <?php 
               // Generate 16 columns with aggregate specific deductible
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8);
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation) {
                           $totalEmployees = ($file_counts['EE'] + $file_counts['ES'] + $file_counts['EC'] + $file_counts['EF']);
                           $C = 0;
                           if ($totalEmployees > 0 && $calculation['loss_plan']->Spec_Deductible > 0) {
                               $C = ($calculation['loss_plan']->Spec_Deductible / 12) / $totalEmployees;
                           }
                           $factor = 1.0;
                           if ($tier === 'ES') $factor = 1.9;
                           elseif ($tier === 'EC') $factor = 1.8;
                           elseif ($tier === 'EF') $factor = 2.6;
                           echo '<td class="text-center">$' . number_format($C * $factor, 2) . '</td>';
                       } else {
                           echo '<td class="text-center">$0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">$0.00</td>';
                   }
               }
               ?>
            </tr>
            <?php endforeach; ?>
            <tr>
               <td class="fixed-left full-width dark font-weight-bold">Monthly Totals</td>
               <td colspan="16"></td>
            </tr>
            <tr>
               <td class="fixed-left full-width" colspan="16"></td>
            </tr>
            <?php foreach (['EE' => 'Employee Only (EE)', 'ES' => 'Employee + Spouse (ES)', 'EC' => 'Employee + Child(ren) (EC)', 'EF' => 'Employee + Family (EF)'] as $tier => $tierName): ?>
            <tr>
               <td class="fixed-left full-width font-weight-bold"><?php echo $tierName; ?></td>
               <td colspan="16"></td>
            </tr>
            <tr>
               <td class="fixed-left">Specific</td>
               <?php 
               // Generate 16 columns with specific monthly totals
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8);
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation && isset($calculation['monthly_totals'][$tier])) {
                           echo '<td class="text-center">$' . number_format($calculation['monthly_totals'][$tier]['specific'] ?? 0, 2) . '</td>';
                       } else {
                           echo '<td class="text-center">$0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">$0.00</td>';
                   }
               }
               ?>
            </tr>
            <tr>
               <td class="fixed-left">Aggregate</td>
               <?php 
               // Generate 16 columns with aggregate monthly totals
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8);
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation && isset($calculation['monthly_totals'][$tier])) {
                           echo '<td class="text-center">$' . number_format($calculation['monthly_totals'][$tier]['aggregate'] ?? 0, 2) . '</td>';
                       } else {
                           echo '<td class="text-center">$0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">$0.00</td>';
                   }
               }
               ?>
            </tr>
            <tr>
               <td class="fixed-left">Aggregate Accommodation</td>
               <?php 
               // Generate 16 columns with aggregate accommodation monthly totals
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8);
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation && isset($calculation['monthly_totals'][$tier])) {
                           echo '<td class="text-center">$' . number_format($calculation['monthly_totals'][$tier]['accommodation'] ?? 0, 2) . '</td>';
                       } else {
                           echo '<td class="text-center">$0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">$0.00</td>';
                   }
               }
               ?>
            </tr>
            <tr>
               <td class="fixed-left">TPA Fee</td>
               <?php 
               // Generate 16 columns with TPA fee monthly totals
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8);
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation && isset($calculation['monthly_totals'][$tier])) {
                           echo '<td class="text-center">$' . number_format($calculation['monthly_totals'][$tier]['tpa_fee'] ?? 0, 2) . '</td>';
                       } else {
                           echo '<td class="text-center">$0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">$0.00</td>';
                   }
               }
               ?>
            </tr>
            <tr>
               <td class="fixed-left">Broker Fee</td>
               <?php 
               // Generate 16 columns with broker fee monthly totals
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8);
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation && isset($calculation['monthly_totals'][$tier])) {
                           echo '<td class="text-center">$' . number_format($calculation['monthly_totals'][$tier]['broker_fee'] ?? 0, 2) . '</td>';
                       } else {
                           echo '<td class="text-center">$0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">$0.00</td>';
                   }
               }
               ?>
            </tr>
            <tr>
               <td class="fixed-left full-width font-weight-bold">Total</td>
               <?php 
               // Generate 16 columns with total monthly amounts
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8);
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation && isset($calculation['monthly_totals'][$tier])) {
                           echo '<td class="text-center font-weight-bold">$' . number_format($calculation['monthly_totals'][$tier]['total'] ?? 0, 2) . '</td>';
                       } else {
                           echo '<td class="text-center font-weight-bold">$0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center font-weight-bold">$0.00</td>';
                   }
               }
               ?>
            </tr>
            <?php endforeach; ?>
            <tr>
               <td class="fixed-left full-width dark font-weight-bold"></td>Quote Summary</td>
               <td colspan="16"></td>
            </tr>
            <?php foreach (['Estimated Spec Premium', 'Estimated Aggregate Premium', 'Total Attachment Point', 'Estimated Total Premium'] as $summaryItem): ?>
            <tr>
               <td class="fixed-left font-weight-bold"><?php echo $summaryItem; ?></td>
               <?php 
               // Generate 16 columns with summary values
               if (!empty($allCalculations)) {
                   $calculationKeys = array_keys($allCalculations);
                   for ($i = 0; $i < 16; $i++) {
                       $planIndex = floor($i / 8); // 0 for first 8 columns, 1 for next 8
                       $calculation = $allCalculations[$calculationKeys[$planIndex]] ?? null;
                       if ($calculation) {
                           $summaryKey = strtolower(str_replace(' ', '_', $summaryItem));
                           $value = $calculation['summary'][$summaryKey] ?? 0;
                           echo '<td class="text-center">$' . number_format($value, 2) . '</td>';
                       } else {
                           echo '<td class="text-center">$0.00</td>';
                       }
                   }
               } else {
                   for ($i = 0; $i < 16; $i++) {
                       echo '<td class="text-center">$0.00</td>';
                   }
               }
               ?>
            </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
</div>

<!-- Development Testing Panel (comment out in production) -->
<?php if (isset($_GET['debug']) && $_GET['debug'] == 'true'): ?>
<div class="card mt-3">
   <div class="card-header">
      <h5>Debug Information & Testing</h5>
   </div>
   <div class="card-body">
      <?php testCalculations(); ?>
      
      <h6>Current Data Structure:</h6>
      <pre><?php print_r([
          'file_counts' => $file_counts,
          'benefit_plans_count' => count($benefitPlansDetails ?? []),
          'loss_plans_count' => count($lossPlansDetails ?? []),
          'total_employees' => array_sum($file_counts ?? [])
      ]); ?></pre>
   </div>
</div>
<?php endif; ?>














      
   </div>
</div>