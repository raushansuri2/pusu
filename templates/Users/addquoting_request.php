<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#business-group-id").on('change', function() {

            var selectedValue = $(this).val();
            var selectedText = $(this).find("option:selected").text();

            $("#group_ID").html('#'+selectedValue);
            $("#group_NAME").html(selectedText);

        });

        $("#date-policy-start, #final_proposal_date").on("input change", function () {
            headerset();
        });

    });

        function headerset(){
            $("#PE").html($("#date-policy-start").val());
            $("#FPD").html($("#final_proposal_date").val());
        }
</script>

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="quote-request.html">Quote Requests</a></li>
            <li class="breadcrumb-item active" aria-current="page">New Quote Request</li>
        </ol>
    </nav>



    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <!-- Left: Title -->
                        <div class="col-md-12">
                            <h4 class="mb-0 d-flex align-items-center flex-wrap">
                                <span class="me-2" id="group_ID">#:</span>

                                <a href="javascript::" class="link-no-color fw-semibold me-3" id="group_NAME">

                                </a>

                                <span style="font-size: 12px;" class="badge bg-danger">
                                    Draft
                                </span>
                            </h4>
                        </div>
                        <div class="col-md-12">
                            <p style="font-size: 13px;margin-top: 7px;">
                                <strong>Final Proposals Due</strong><spam id="FPD"> 7/4/25 </spam><strong> &nbsp;&nbsp;&nbsp; Plan Effective</strong> <spam id="PE">8/1/2025</spam> <strong>  &nbsp;&nbsp;&nbsp;Census Used</strong> Census St Joseph Motesorri.xlsx (71 members)
                            </p>
                        </div>
                    </div>
                    <ul class="stepper mb-4">
                        <li class="active">Group</li>
                        <li>Networks/Repricing</li>
                        <li>Stop Loss</li>
                        <li>Employee Benefit Plans</li>
                        <li>Attachments & Notes</li>
                        <li>Submit</li>
                    </ul>

                    <!-- Form -->
                    <!-- <form id="multiStepForm"> -->
                    <?php echo $this->Flash->render(); ?>
                    <form method="post" action="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'addquotingRequest']); ?>?programid=<?php echo $this->request->getQuery('programid'); ?>" enctype="multipart/form-data" accept-charset="utf-8">
                        <div class="step active">
                            <div class="row">
                                <div class="col-12">

                                    <!-- SECTION: GROUP -->
                                    <h5 class="pb-1 mb-3" style="border-bottom: 1px solid #e6e6e6; font-weight: 500;">Get a quote on behalf of the following group</h5>


                                    <div class="row align-items-start mb-4">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="w-50">
                                                    <select class="form-select select2" name="group_id" id="business-group-id" required>
                                                        <option value="">Select a group</option>
                                                        <?php
                                                        foreach ($group_list as $code => $name) {
                                                            echo '<option value="' . $code . '">' . $name . '</option>';
                                                        }
                                                        ?>
                                                        <!-- options preserved -->
                                                    </select>
                                                </div>

                                                <span>or</span>

                                                <a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'groupAdd']);?>" class="btn btn-link fw-semibold p-0">
                                                    Add a new group
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-md-5 offset-md-1">
                                            <div class="border rounded p-3 ">
                                                <i data-feather="info"></i>
                                                <h6 class="text-muted mt-3 mb-2">Groups</h6>
                                                <p class="mb-0">
                                                    To obtain quotes, carriers and MGUs require basic information about the group
                                                    (also known as the plan sponsor). Required information includes the group name
                                                    and industry classification code.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SECTION: CENSUS -->
                                    <h5 class="pb-1 mb-3" style="border-bottom: 1px solid #e6e6e6; font-weight: 500;">Upload the group's census</h5>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="w-50">
                                                    <select class="form-select select2 form-control">
                                                        <option value="">Select a group first...</option>
                                                        <option value="">Group 1</option>
                                                        <option value="">Group 2</option>
                                                        <option value="">Group 3</option>
                                                    </select>
                                                </div>
                                                <span>or</span>
                                                <div> <input type="file" name="census_file" id="actual-btn" hidden/>
                                                    <label for="actual-btn" class="custom-file-button mt-0">Upload a new census</label>
                                                    <span id="file-chosen">No file chosen</span>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-5 offset-md-1">
                                            <div class="border rounded p-3">
                                                <i data-feather="info"></i>
                                                <h6 class="text-muted mt-3 mb-2">About uploading a census</h6>
                                                <p class="mb-3">
                                                    A census is required for all quote requests. The uploaded census must follow
                                                    the required format.
                                                </p>
                                                <a href="#" class="btn btn-sm btn-primary" download>
                                                    Download Census Template
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SECTION: POLICY DATES -->
                                    <h5 class="pb-1 mb-3" style="border-bottom: 1px solid #e6e6e6; font-weight: 500;">Policy dates and deadlines</h4>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="date-policy-start" class="form-label">
                                                        Policy Effective Date
                                                    </label>
                                                    <input type="date" class="form-control datepicker" onkeyup="headerset()" name="Policy_Effective_Date" id="date-policy-start" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="date-policy-end" class="form-label">
                                                        Policy Termination Date
                                                    </label>
                                                    <input type="date" class="form-control datepicker" onkeyup="headerset()" name="Policy_Termination_Date" id="date-policy-end" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="quotes-due-by" class="form-label">
                                                        Final Proposals Due
                                                    </label>
                                                    <input type="date" onkeyup="headerset()" name="Final_Proposals_Due" id="final_proposal_date" class="form-control datepicker" required>
                                                </div>
                                            </div>

                                            <div class="col-md-5 offset-md-1">
                                                <div class="border rounded p-3">
                                                    <i data-feather="info" class="mb-2"></i>
                                                    <dl class="mb-0">
                                                        <dt class="text-muted">Policy Effective Date</dt>
                                                        <dd>The start date of the benefit plan.</dd>

                                                        <dt class="text-muted">Policy Termination Date</dt>
                                                        <dd>The end date of the benefit plan.</dd>

                                                        <dt class="text-muted">Final Proposals Due</dt>
                                                        <dd>
                                                            Indicates when final proposals are due to your organization.
                                                        </dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>


                        </div>

                        <div class="step">
                            <div class="row">
                                <div class="col-12">
                                    <p>Please choose from the following networks or repricing:</p>
                                    <?php
                                    foreach($network_list as $key=>$network){ ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="quote_request_networks[<?php echo $key; ?>]" value="<?php echo $key;?>" id="network-<?php echo $key; ?>">
                                            <label class="form-check-label" for="network-<?php echo $key; ?>">
                                                <?php echo $network;?>
                                            </label>
                                        </div>
                                    <?php } ?>



                                </div>
                            </div>

                        </div>

                        <div class="step">
                            <div id="stop-loss-plans" class="row">
                                <div class="col-12">

                                    <div class="card">
                                        <!-- Header -->
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-md-9">
                                                    <h5 class="mb-0">
                                                        <i class="link-icon" data-feather="x"></i>
                                                        Requested Stop Loss Plan Designs
                                                    </h5>
                                                </div>
                                                <div class="col-md-3 text-md-end mt-2 mt-md-0">
                                                    <a href="#" class="btn btn-primary btn-sm">
                                                        Change Stop Loss Options
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Body -->
                                        <div class="card-body">

                                            <!-- RX Coverage -->
                                            <h6 class="fw-bold">Prescription Drug Coverage Options</h6>
                                            <hr>

                                            <dl class="row mb-3">
                                                <dt class="col-sm-6">Specific Includes Rx Coverage? - Yes</dt>

                                                <dt class="col-sm-6">Aggregate Includes Rx Coverage? - Yes</dt>
                                            </dl>

                                            <!-- Plans Table -->
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th></th>
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
                                                        <?php foreach($loss_plans_list as $loss_plans){ ?>
                                                            <tr>
                                                                <td>
                                                                    <input class="form-check-input" type="checkbox" name="loose[]" value="<?php echo $loss_plans->id;?>" checked>
                                                                </td>
                                                                <td><strong><?php echo $loss_plans->plan_name;?></strong></td>
                                                                <td>$<?php echo $loss_plans->Spec_Deductible;?></td>
                                                                <td><?php echo $loss_plans->Spec_Contract;?></td>
                                                                <td>$<?php echo $loss_plans->Aggregating_Spec_Deductible;?></td>
                                                                <td><?php echo $loss_plans->Agg_Contract;?></td>
                                                                <td><?php echo $loss_plans->Agg_Corridor;?></td>
                                                                <td><?php echo $loss_plans->Commission;?>%</td>
                                                            </tr>
                                                        <?php } ?>

                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card mt-4">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-md-12">
                                            <h5 class="mb-0">
                                                Change Stop Loss Coverage Type
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="row mt-3">
                                        <div class="col-md-6">


                                                <!-- Stop Loss Coverage Type -->
                                                <h5 class="mb-2">Stop Loss Coverage Type</h5>
                                                <div class="form-group custom-controls-stacked">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="Stop_Loss_Coverage_Type" value="both" class="custom-control-input" checked>
                                                        <span class="custom-control-label">Specific and aggregate stop loss</span> </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="Stop_Loss_Coverage_Type" value="spec" class="custom-control-input">
                                                        <span class="custom-control-label">Specific stop loss only</span> </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="Stop_Loss_Coverage_Type" value="agg" class="custom-control-input">
                                                        <span class="custom-control-label">Aggregate stop loss only</span> </label>
                                                </div>

                                                <!-- Prescription Drug Coverage -->
                                                <h5 class="mb-2">Stop Loss Prescription Drug Coverage</h5>
                                                <div class="form-group custom-controls-stacked">
                                                    <label>Specific includes prescription drugs?</label><br>
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="Specific_includes_PD" value="1" class="custom-control-input" checked>
                                                        <span class="custom-control-label">Yes</span> </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="Specific_includes_PD" value="0" class="custom-control-input">
                                                        <span class="custom-control-label">No</span> </label>
                                                </div>
                                                <div class="form-group custom-controls-stacked">
                                                    <label>Aggregate includes prescription drugs?</label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="Aggregate_includes_PD" value="1" class="custom-control-input" checked>
                                                        <span class="custom-control-label">Yes</span> </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="Aggregate_includes_PD" value="0" class="custom-control-input">
                                                        <span class="custom-control-label">No</span> </label>
                                                </div>

                                                <!-- Level Funded Option -->
                                                <h5 class="mb-2">Level Funded Stop Loss</h5>
                                                <div class="form-group">
                                                    <label class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="Include_level_quote" value="1" class="custom-control-input">
                                                        <span class="custom-control-label">Include level quote if available?</span> </label>
                                                </div>
                                                <button class="btn btn-primary"> Review Stop Loss Options </button>

                                        </div>

                                        <!-- Help Panel -->
                                        <div class="col-md-5 offset-1">
                                            <div class="panel">
                                                <div class="panel-body">
                                                    <h1><i class="icon s7-help1"></i></h1>
                                                    <h6 class="text-muted">About stop loss options</h6>
                                                    <p> Use this section to choose specific and/or aggregate stop loss coverage and indicate if
                                                        prescription drug coverage is included. Also indicate if you want to see a level-funded option. <br>
                                                        <em>Note: not all carriers or MGUs have level-funded options available.</em>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="step">

                            <div id="employee-benefit-plans" class="row">
                                <div class="col-12">

                                    <div class="card">
                                        <!-- Header -->
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="link-icon" data-feather="monitor"></i>
                                                Requested Employee Benefit Plans
                                            </h5>
                                        </div>

                                        <!-- Body -->
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th></th>
                                                            <th>Plan Name</th>
                                                            <th>Deductible</th>
                                                            <th>Coinsurance</th>
                                                            <th>OOP Maximum</th>
                                                            <th>OOP Includes<br>Deductible?</th>
                                                            <th>Rx Copay<br>(Generic)</th>
                                                            <th>Rx Copay<br>(Formulary)</th>
                                                            <th>Rx Copay<br>(Non-Formulary)</th>
                                                            <th>Rx Covers<br>Specialty?</th>
                                                            <th>Rx Copay<br>(Specialty)</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php foreach($benifit_plans_list as $benifit_plans){ ?>
                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" name="benifit_plans[]" value="<?php echo $benifit_plans->id;?>" checked></td>
                                                            <td><strong><?php echo $benifit_plans->plan_name; ?></strong></td>
                                                            <td>In: $<?php echo $benifit_plans->deductible_in; ?><br>Out: $<?php echo $benifit_plans->deductible_out; ?></td>
                                                            <td>In: <?php echo $benifit_plans->coinsurance_in; ?>%<br>Out: <?php echo $benifit_plans->coinsurance_out; ?>%</td>
                                                            <td>In: $<?php echo $benifit_plans->oop_maximum_in; ?><br>Out: $<?php echo $benifit_plans->oop_maximum_out; ?></td>
                                                            <td>In: <?php echo ($benifit_plans->oop_includes_deductible_in == 1) ? 'Yes' : 'No'; ?><br>Out: <?php echo ($benifit_plans->oop_includes_deductible_out == 1) ? 'Yes' : 'No'; ?></td>
                                                            <td>$<?php echo $benifit_plans->rx_copay_generic; ?></td>
                                                            <td>$<?php echo $benifit_plans->rx_copay_formulary; ?></td>
                                                            <td>$<?php echo $benifit_plans->rx_copay_non_formulary; ?></td>
                                                            <td><?php echo ($benifit_plans->rx_covers_specialty == 1) ? 'Yes' : 'NO'; ?></td>
                                                            <td>$<?php echo $benifit_plans->rx_copay_specialty; ?></td>
                                                        </tr>
                                                        <?php } ?>


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="step">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-heading-divider">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <span class="panel-title">
                                                <i data-feather="file-plus"></i> Attachments </span>
                                            <span class="panel-subtitle">Upload any attachment such as claims experience (if available), case management history or anything else. </span>
                                        </div>

                                        <div class="col-md-3 text-right">
                                            <input type="file" name="attach_file" class="btn btn-rounded btn-secondary mr-1 d-print-none" />
                                            <!-- <a href="#" class="btn btn-rounded btn-secondary mr-1 d-print-none">Upload Files</a>  -->
                                            <a href="#" class="btn btn-rounded btn-primary d-print-none" data-no-ajax="1">Download All</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="alert alert-info alert-simple">
                                        <div class="icon"><span class="s7-info"></span></div>
                                        <div class="message">
                                            No attachments have been uploaded. </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading panel-heading-divider">
                                    <span class="panel-title"><i data-feather="file-text"></i> Quote Request Notes</span>
                                    <span class="panel-subtitle">
                                        Add any notes regarding the quote request below to provide the quote request recipient with additional information. </span>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group textarea "><label for="general-notes">General Notes</label><textarea class="form-control " name="notes" id="general-notes" rows="5" maxlength="100000"></textarea></div>
                                </div>
                            </div>

                        </div>

                        <div class="step">
                            <div class="card" style="margin-bottom: 20px;">

                                <!-- Header -->
                                <div class="card-header">
                                    <div class="d-flex flex-column">
                                        <h5 class="mb-1">
                                            <i class="bi bi-magic me-2"></i>
                                            Quote Request Recipients
                                        </h5>
                                        <small class="text-muted">
                                            You will receive instant quotes from our partners for the PPOs or repricing listed below.
                                        </small>
                                    </div>
                                </div>

                                <!-- Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="w-50">Quoting Partner</th>
                                                    <th>Networks or Repricing</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="d-flex align-items-center gap-3">
                                                        <img src="<?php echo $this->Url->build('/');?>images/logod.svg" style="max-height: 110px;width: 290px;border-radius: 0;" alt="Prodigy Health Insurance logo" class="img-fluid" style="max-height: 40px;">
                                                        <span>Prodigy Health Insurance</span>
                                                    </td>

                                                    <td>
                                                        <ul class="mb-0 ps-3">
                                                            <li>First Choice Health Plans of Mississippi</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">

                                    <div class="card">
                                        <!-- Header -->
                                        <div class="card-header">
                                            <h5 class="mb-1">
                                                <i class="bi bi-calculator me-2"></i>
                                                Fees
                                            </h5>
                                            <small class="text-muted">
                                                The fees listed below will be added to the proposal.
                                            </small>
                                        </div>

                                        <!-- Body -->
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Fee</th>
                                                            <th>Type</th>
                                                            <th>Amount</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php if($fees_list){
                                                            foreach($fees_list as $key=>$fees){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $fees->name; ?></td>
                                                            <td><?php echo $fees->value_type;?></td>
                                                            <td style="max-width: 200px;">
                                                                <input type="text" class="form-control" name="<?php echo strtoupper(str_replace(" ", "_", trim($fees->name)));?>" id="TPA_PPO_<?php echo $key;?>" placeholder="70.00" value="<?php echo $fees->value;?>" aria-label="Fee amount" <?php echo ($fees->is_editable != 1) ? "disabled" :"";?>>
                                                            </td>
                                                            <td>
                                                                <?php if($fees->is_editable == 1){ ?>
                                                                <small class="text-muted">This fee is editable</small>
                                                                <?php }else{ ?>
                                                                <small class="text-muted">This fee is not editable</small>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php } } ?>
                                                        <tr>
                                                            <td>Broker Fee</td>
                                                            <td>PEPM</td>
                                                            <td style="max-width: 200px;">
                                                                <input type="text" class="form-control" name="fees_broker_fee" id="Broke_Fee" placeholder="35.00" value="35.00" aria-label="Fee amount">
                                                            </td>
                                                            <td>
                                                                <small class="text-muted">This fee is editable</small>
                                                            </td>
                                                        </tr>
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                        <!-- Navigation buttons -->
                        <div class="d-flex   mt-4">
                            <button type="button" class="btn btn-secondary" id="prevBtn" style="display:none;">Previous Step</button> &nbsp;
                            <button type="button" class="btn btn-primary" id="nextBtn">Next Step</button> &nbsp;
                            <button type="submit" class="btn btn-primary" id="submit" style="display: none;">Submit</button> &nbsp;
                            <button type="button" class="btn btn-warning" id="nextBtn">Save Draft</button>
                        </div>
                    </form>



                </div>
            </div>
        </div>
    </div>


</div>




<script>
  let currentStep = 0;
    const steps = document.querySelectorAll(".step");
    const stepItems = document.querySelectorAll(".stepper li");

    document.getElementById("nextBtn").addEventListener("click", () => {
  if (currentStep < steps.length - 1) {
    currentStep++;
    updateSteps();
    document.getElementById("prevBtn").style.display = "block";
    if(currentStep == 5){
        document.getElementById("submit").style.display = "block";
        document.getElementById("nextBtn").style.display = "none";
    }else{
        document.getElementById("submit").style.display = "none";
        document.getElementById("nextBtn").style.display = "block";
    }

  }
});

    document.getElementById("prevBtn").addEventListener("click", () => {
  if (currentStep > 0) {
    currentStep--;
    updateSteps();
    if(currentStep == 0){
        document.getElementById("prevBtn").style.display = "none";
    }
    if(currentStep == 5){
        document.getElementById("submit").style.display = "block";
        document.getElementById("nextBtn").style.display = "none";
    }else{
        document.getElementById("submit").style.display = "none";
        document.getElementById("nextBtn").style.display = "block";
    }
  }
});

    function updateSteps() {
        steps.forEach((step, index) => {
        step.classList.toggle("active", index === currentStep);
    });

    stepItems.forEach((item, index) => {
        item.classList.toggle("active", index === currentStep);
    });

    document.getElementById("nextBtn").innerText =
    currentStep === steps.length - 1 ? "Submit" : "Next";
}

</script>

<style>.custom-file-button {
  background-color: #198754;
  color: white;
  padding: 0.5rem;
  font-family: sans-serif;
  border-radius: 0.3rem;
  cursor: pointer;
  /* Ensure margin works correctly */
  display: inline-block;
  margin-top: 1rem;
}</style>
