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
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        ?>
                    <td class="text-center">$<?php echo number_format($Aggregate_Accommodation, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EE'],2);

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

                        $Aggregate_Factor = 1+(($lossPlan->Aggregating_Spec_Deductible)/100);
                        $Aggregate_Accommodation = 0;

                        $Total_PEPM = $Specific_Rate + $Aggregate_Rate_PMPM + $Aggregate_Factor + $Aggregate_Accommodation + $TOTAL_FEE;
                        ?>
                    <td class="text-center">$<?php echo number_format($Total_PEPM, 2);?></td>
                <?php } } ?>
        <?php } } ?>
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
                    <td class="text-center"><?php echo round(($ExpectedLargeClaims + $riskMargin) / $file_counts['ES'],2);?></td>
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
                        ?>
                    <td class="text-center"><?php echo round($Aggregate_Factor,2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        ?>
                    <td class="text-center">$<?php echo number_format($Aggregate_Accommodation, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['ES'],2);

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

                        $Aggregate_Factor = 1+(($lossPlan->Aggregating_Spec_Deductible)/100);
                        $Aggregate_Accommodation = 0;

                        $Total_PEPM = $Specific_Rate + $Aggregate_Rate_PMPM + $Aggregate_Factor + $Aggregate_Accommodation + $TOTAL_FEE;
                        ?>
                    <td class="text-center">$<?php echo number_format($Total_PEPM, 2);?></td>
                <?php } } ?>
        <?php } } ?>
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
                    <td class="text-center"><?php echo round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EC'],2);?></td>
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
                        ?>
                    <td class="text-center"><?php echo round($Aggregate_Factor,2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        ?>
                    <td class="text-center">$<?php echo number_format($Aggregate_Accommodation, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EC'],2);

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

                        $Aggregate_Factor = 1+(($lossPlan->Aggregating_Spec_Deductible)/100);
                        $Aggregate_Accommodation = 0;

                        $Total_PEPM = $Specific_Rate + $Aggregate_Rate_PMPM + $Aggregate_Factor + $Aggregate_Accommodation + $TOTAL_FEE;
                        ?>
                    <td class="text-center">$<?php echo number_format($Total_PEPM, 2);?></td>
                <?php } } ?>
        <?php } } ?>
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
                    <td class="text-center"><?php echo round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EF'],2);?></td>
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
                        ?>
                    <td class="text-center"><?php echo round($Aggregate_Factor,2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        ?>
                    <td class="text-center">$<?php echo number_format($Aggregate_Accommodation, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EF'],2);

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

                        $Aggregate_Factor = 1+(($lossPlan->Aggregating_Spec_Deductible)/100);
                        $Aggregate_Accommodation = 0;

                        $Total_PEPM = $Specific_Rate + $Aggregate_Rate_PMPM + $Aggregate_Factor + $Aggregate_Accommodation + $TOTAL_FEE;
                        ?>
                    <td class="text-center">$<?php echo number_format($Total_PEPM, 2);?></td>
                <?php } } ?>
        <?php } } ?>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EE'],2);
                        $Monthly_Specific_Rate = $Specific_Rate * $file_counts['EE'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Specific_Rate, 2);?></td>
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
                        $Monthly_Aggregate_Rate = $Aggregate_Rate_PMPM * $file_counts['EE'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Rate, 2);?></td>
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
                        $Monthly_Aggregate_Factor = $Aggregate_Factor * $file_counts['EE'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Factor, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        $Monthly_Aggregate_Accommodation = $Aggregate_Accommodation * $file_counts['EE'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Accommodation, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Broker Fee</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $Broker_Fee = ($feesData['broker_fee'] ?? 0) * $file_counts['EE'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Broker_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">TPA, RBP, PBM, Service Providers</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $TPA_RBP_Fee = ($feesData['tpa_rbp_pbm_service_providers'] ?? 0) * $file_counts['EE'];
                        ?>
                    <td class="text-center">$<?php echo number_format($TPA_RBP_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $TPA_PPO_Fee = ($feesData['tpa_ppo_pbm_service_providers'] ?? 0) * $file_counts['EE'];
                        ?>
                    <td class="text-center">$<?php echo number_format($TPA_PPO_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total</td>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EE'],2);
                        $Monthly_Specific_Rate = $Specific_Rate * $file_counts['EE'];

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
                        $Monthly_Aggregate_Rate = $Aggregate_Rate_PMPM * $file_counts['EE'];

                        $Aggregate_Factor = 1+(($lossPlan->Aggregating_Spec_Deductible)/100);
                        $Monthly_Aggregate_Factor = $Aggregate_Factor * $file_counts['EE'];

                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        $Monthly_Aggregate_Accommodation = $Aggregate_Accommodation * $file_counts['EE'];

                        $Broker_Fee = ($feesData['broker_fee'] ?? 0) * $file_counts['EE'];
                        $TPA_RBP_Fee = ($feesData['tpa_rbp_pbm_service_providers'] ?? 0) * $file_counts['EE'];
                        $TPA_PPO_Fee = ($feesData['tpa_ppo_pbm_service_providers'] ?? 0) * $file_counts['EE'];

                        $Monthly_Total = $Monthly_Specific_Rate + $Monthly_Aggregate_Rate + $Monthly_Aggregate_Factor + $Monthly_Aggregate_Accommodation + $Broker_Fee + $TPA_RBP_Fee + $TPA_PPO_Fee;
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Total, 2);?></td>
                <?php } } ?>
        <?php } } ?>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['ES'],2);
                        $Monthly_Specific_Rate = $Specific_Rate * $file_counts['ES'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Specific_Rate, 2);?></td>
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
                        $Monthly_Aggregate_Rate = $Aggregate_Rate_PMPM * $file_counts['ES'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Rate, 2);?></td>
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
                        $Monthly_Aggregate_Factor = $Aggregate_Factor * $file_counts['ES'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Factor, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        $Monthly_Aggregate_Accommodation = $Aggregate_Accommodation * $file_counts['ES'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Accommodation, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Broker Fee</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $Broker_Fee = ($feesData['broker_fee'] ?? 0) * $file_counts['ES'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Broker_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">TPA, RBP, PBM, Service Providers</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $TPA_RBP_Fee = ($feesData['tpa_rbp_pbm_service_providers'] ?? 0) * $file_counts['ES'];
                        ?>
                    <td class="text-center">$<?php echo number_format($TPA_RBP_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $TPA_PPO_Fee = ($feesData['tpa_ppo_pbm_service_providers'] ?? 0) * $file_counts['ES'];
                        ?>
                    <td class="text-center">$<?php echo number_format($TPA_PPO_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total</td>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['ES'],2);
                        $Monthly_Specific_Rate = $Specific_Rate * $file_counts['ES'];

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
                        $Monthly_Aggregate_Rate = $Aggregate_Rate_PMPM * $file_counts['ES'];

                        $Aggregate_Factor = 1+(($lossPlan->Aggregating_Spec_Deductible)/100);
                        $Monthly_Aggregate_Factor = $Aggregate_Factor * $file_counts['ES'];

                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        $Monthly_Aggregate_Accommodation = $Aggregate_Accommodation * $file_counts['ES'];

                        $Broker_Fee = ($feesData['broker_fee'] ?? 0) * $file_counts['ES'];
                        $TPA_RBP_Fee = ($feesData['tpa_rbp_pbm_service_providers'] ?? 0) * $file_counts['ES'];
                        $TPA_PPO_Fee = ($feesData['tpa_ppo_pbm_service_providers'] ?? 0) * $file_counts['ES'];

                        $Monthly_Total = $Monthly_Specific_Rate + $Monthly_Aggregate_Rate + $Monthly_Aggregate_Factor + $Monthly_Aggregate_Accommodation + $Broker_Fee + $TPA_RBP_Fee + $TPA_PPO_Fee;
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Total, 2);?></td>
                <?php } } ?>
        <?php } } ?>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EC'],2);
                        $Monthly_Specific_Rate = $Specific_Rate * $file_counts['EC'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Specific_Rate, 2);?></td>
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
                        $Monthly_Aggregate_Rate = $Aggregate_Rate_PMPM * $file_counts['EC'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Rate, 2);?></td>
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
                        $Monthly_Aggregate_Factor = $Aggregate_Factor * $file_counts['EC'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Factor, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        $Monthly_Aggregate_Accommodation = $Aggregate_Accommodation * $file_counts['EC'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Accommodation, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Broker Fee</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $Broker_Fee = ($feesData['broker_fee'] ?? 0) * $file_counts['EC'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Broker_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">TPA, RBP, PBM, Service Providers</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $TPA_RBP_Fee = ($feesData['tpa_rbp_pbm_service_providers'] ?? 0) * $file_counts['EC'];
                        ?>
                    <td class="text-center">$<?php echo number_format($TPA_RBP_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $TPA_PPO_Fee = ($feesData['tpa_ppo_pbm_service_providers'] ?? 0) * $file_counts['EC'];
                        ?>
                    <td class="text-center">$<?php echo number_format($TPA_PPO_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total</td>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EC'],2);
                        $Monthly_Specific_Rate = $Specific_Rate * $file_counts['EC'];

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
                        $Monthly_Aggregate_Rate = $Aggregate_Rate_PMPM * $file_counts['EC'];

                        $Aggregate_Factor = 1+(($lossPlan->Aggregating_Spec_Deductible)/100);
                        $Monthly_Aggregate_Factor = $Aggregate_Factor * $file_counts['EC'];

                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        $Monthly_Aggregate_Accommodation = $Aggregate_Accommodation * $file_counts['EC'];

                        $Broker_Fee = ($feesData['broker_fee'] ?? 0) * $file_counts['EC'];
                        $TPA_RBP_Fee = ($feesData['tpa_rbp_pbm_service_providers'] ?? 0) * $file_counts['EC'];
                        $TPA_PPO_Fee = ($feesData['tpa_ppo_pbm_service_providers'] ?? 0) * $file_counts['EC'];

                        $Monthly_Total = $Monthly_Specific_Rate + $Monthly_Aggregate_Rate + $Monthly_Aggregate_Factor + $Monthly_Aggregate_Accommodation + $Broker_Fee + $TPA_RBP_Fee + $TPA_PPO_Fee;
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Total, 2);?></td>
                <?php } } ?>
        <?php } } ?>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EF'],2);
                        $Monthly_Specific_Rate = $Specific_Rate * $file_counts['EF'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Specific_Rate, 2);?></td>
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
                        $Monthly_Aggregate_Rate = $Aggregate_Rate_PMPM * $file_counts['EF'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Rate, 2);?></td>
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
                        $Monthly_Aggregate_Factor = $Aggregate_Factor * $file_counts['EF'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Factor, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Aggregate Accommodation</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        $Monthly_Aggregate_Accommodation = $Aggregate_Accommodation * $file_counts['EF'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Aggregate_Accommodation, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">Broker Fee</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $Broker_Fee = ($feesData['broker_fee'] ?? 0) * $file_counts['EF'];
                        ?>
                    <td class="text-center">$<?php echo number_format($Broker_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">TPA, RBP, PBM, Service Providers</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $TPA_RBP_Fee = ($feesData['tpa_rbp_pbm_service_providers'] ?? 0) * $file_counts['EF'];
                        ?>
                    <td class="text-center">$<?php echo number_format($TPA_RBP_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        $TPA_PPO_Fee = ($feesData['tpa_ppo_pbm_service_providers'] ?? 0) * $file_counts['EF'];
                        ?>
                    <td class="text-center">$<?php echo number_format($TPA_PPO_Fee, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total</td>
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
                        $Specific_Rate = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EF'],2);
                        $Monthly_Specific_Rate = $Specific_Rate * $file_counts['EF'];

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
                        $Monthly_Aggregate_Rate = $Aggregate_Rate_PMPM * $file_counts['EF'];

                        $Aggregate_Factor = 1+(($lossPlan->Aggregating_Spec_Deductible)/100);
                        $Monthly_Aggregate_Factor = $Aggregate_Factor * $file_counts['EF'];

                        if($lossPlan->Spec_Deductible < 50000){
                            $Expected_Total_Claims = 50000;
                            $Expected_Specific_Claims = (50000-$lossPlan->Spec_Deductible);
                        }else{
                            $Expected_Total_Claims = 100000;
                            $Expected_Specific_Claims = (100000-$lossPlan->Spec_Deductible);
                        }
                        $Aggregate_Accommodation = $Expected_Total_Claims / $Expected_Specific_Claims;
                        $Monthly_Aggregate_Accommodation = $Aggregate_Accommodation * $file_counts['EF'];

                        $Broker_Fee = ($feesData['broker_fee'] ?? 0) * $file_counts['EF'];
                        $TPA_RBP_Fee = ($feesData['tpa_rbp_pbm_service_providers'] ?? 0) * $file_counts['EF'];
                        $TPA_PPO_Fee = ($feesData['tpa_ppo_pbm_service_providers'] ?? 0) * $file_counts['EF'];

                        $Monthly_Total = $Monthly_Specific_Rate + $Monthly_Aggregate_Rate + $Monthly_Aggregate_Factor + $Monthly_Aggregate_Accommodation + $Broker_Fee + $TPA_RBP_Fee + $TPA_PPO_Fee;
                        ?>
                    <td class="text-center">$<?php echo number_format($Monthly_Total, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left full-width" colspan="999"></td>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Composite Aggregate Rate</td>
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
                    <td class="text-center">$<?php echo number_format($Aggregate_Rate_PMPM, 2);?></td>
                <?php } } ?>
        <?php } } ?>
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
                        $Specific_Rate_EE = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EE'],2);
                        $Specific_Rate_ES = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['ES'],2);
                        $Specific_Rate_EC = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EC'],2);
                        $Specific_Rate_EF = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EF'],2);
                        $Estimated_Spec_Premium = ($Specific_Rate_EE * $file_counts['EE'] + $Specific_Rate_ES * $file_counts['ES'] + $Specific_Rate_EC * $file_counts['EC'] + $Specific_Rate_EF * $file_counts['EF']) * 12;
                        ?>
                    <td class="text-center">$<?php echo number_format($Estimated_Spec_Premium, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Estimated Aggregate Premium</td>
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
                        $Estimated_Aggregate_Premium = $Aggregate_Rate_PMPM * $MemberMonths;
                        ?>
                    <td class="text-center">$<?php echo number_format($Estimated_Aggregate_Premium, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Total Attachment Point</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        if($lossPlan->Spec_Deductible < 50000){
                            $ExpectedLargeClaims = (50000-$lossPlan->Spec_Deductible);
                            $Expected_Aggregate_Claims = 50000;
                        }else{
                            $ExpectedLargeClaims = (100000-$lossPlan->Spec_Deductible);
                            $Expected_Aggregate_Claims = 100000;
                        }
                        $riskMargin = round(($ExpectedLargeClaims * $lossPlan->Commission) / 100 , 2);
                        $Specific_Rate_EE = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EE'],2);
                        $Specific_Rate_ES = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['ES'],2);
                        $Specific_Rate_EC = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EC'],2);
                        $Specific_Rate_EF = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EF'],2);
                        $Estimated_Spec_Premium = ($Specific_Rate_EE * $file_counts['EE'] + $Specific_Rate_ES * $file_counts['ES'] + $Specific_Rate_EC * $file_counts['EC'] + $Specific_Rate_EF * $file_counts['EF']) * 12;

                        $EE = $file_counts['EE'] * 1;
                        $ES = $file_counts['ES'] * 2;
                        $EC = $file_counts['EC'] * 2.5;
                        $EF = $file_counts['EF'] * 4;
                        $total_memnber = $EE + $ES + $EC + $EF;
                        $MemberMonths = $total_memnber * 12;
                        $Aggregate_Risk_Margin = $Expected_Aggregate_Claims * (($lossPlan->Agg_Corridor + $lossPlan->Commission)/100);
                        $Aggregate_Rate_PMPM = ($Expected_Aggregate_Claims + $Aggregate_Risk_Margin) / $MemberMonths;
                        $Estimated_Aggregate_Premium = $Aggregate_Rate_PMPM * $MemberMonths;

                        $Aggregate_Factor = 1+(($lossPlan->Aggregating_Spec_Deductible)/100);
                        $Total_Attachment_Point = $Estimated_Spec_Premium + $Estimated_Aggregate_Premium + ($Aggregate_Factor * $MemberMonths);
                        ?>
                    <td class="text-center">$<?php echo number_format($Total_Attachment_Point, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
      <tr>
        <td class="fixed-left font-weight-bold">Estimated Total Premium</td>
        <?php if (!empty($benefitPlansDetails)) {
            foreach ($benefitPlansDetails as $planss) {
                if($lossPlansDetails){
                    foreach($lossPlansDetails as $lossPlan){
                        if($lossPlan->Spec_Deductible < 50000){
                            $ExpectedLargeClaims = (50000-$lossPlan->Spec_Deductible);
                            $Expected_Aggregate_Claims = 50000;
                        }else{
                            $ExpectedLargeClaims = (100000-$lossPlan->Spec_Deductible);
                            $Expected_Aggregate_Claims = 100000;
                        }
                        $riskMargin = round(($ExpectedLargeClaims * $lossPlan->Commission) / 100 , 2);
                        $Specific_Rate_EE = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EE'],2);
                        $Specific_Rate_ES = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['ES'],2);
                        $Specific_Rate_EC = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EC'],2);
                        $Specific_Rate_EF = round(($ExpectedLargeClaims + $riskMargin) / $file_counts['EF'],2);
                        $Estimated_Spec_Premium = ($Specific_Rate_EE * $file_counts['EE'] + $Specific_Rate_ES * $file_counts['ES'] + $Specific_Rate_EC * $file_counts['EC'] + $Specific_Rate_EF * $file_counts['EF']) * 12;

                        $EE = $file_counts['EE'] * 1;
                        $ES = $file_counts['ES'] * 2;
                        $EC = $file_counts['EC'] * 2.5;
                        $EF = $file_counts['EF'] * 4;
                        $total_memnber = $EE + $ES + $EC + $EF;
                        $MemberMonths = $total_memnber * 12;
                        $Aggregate_Risk_Margin = $Expected_Aggregate_Claims * (($lossPlan->Agg_Corridor + $lossPlan->Commission)/100);
                        $Aggregate_Rate_PMPM = ($Expected_Aggregate_Claims + $Aggregate_Risk_Margin) / $MemberMonths;
                        $Estimated_Aggregate_Premium = $Aggregate_Rate_PMPM * $MemberMonths;

                        $Estimated_Total_Premium = $Estimated_Spec_Premium + $Estimated_Aggregate_Premium;
                        ?>
                    <td class="text-center">$<?php echo number_format($Estimated_Total_Premium, 2);?></td>
                <?php } } ?>
        <?php } } ?>
      </tr>
    </tbody>
  </table>
</div>














   </div>
</div>
