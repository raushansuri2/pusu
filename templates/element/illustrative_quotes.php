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
