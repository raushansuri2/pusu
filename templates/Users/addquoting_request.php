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
                                <span class="me-2">#7802:</span>

                                <a href="group-details.html" class="link-no-color fw-semibold me-3">
                                    St. Joseph Montessori School
                                </a>

                                <span style="font-size: 12px;" class="badge bg-danger">
                                    Draft
                                </span>
                            </h4>
                        </div>
                        <div class="col-md-12">
                            <p style="font-size: 13px;margin-top: 7px;">
                                <strong>Final Proposals Due</strong> 7/4/25 <strong>Plan Effective</strong> 8/1/2025 <strong>Census Used</strong> Census St Joseph Motesorri.xlsx (71 members)
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
                    <form id="multiStepForm">
                        <div class="step active">
                            <div class="row">
                                <div class="col-12">

                                    <!-- SECTION: GROUP -->
                                    <h5 class="pb-1 mb-3" style="border-bottom: 1px solid #e6e6e6; font-weight: 500;">Get a quote on behalf of the following group</h5>


                                    <div class="row align-items-start mb-4">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="w-50">
                                                    <select class="form-select select2" name="business_group_id" id="business-group-id" required>
                                                        <option value="">Select a group</option>
                                                        <!-- options preserved -->
                                                    </select>
                                                </div>

                                                <span>or</span>

                                                <a href="add-new-group.html" class="btn btn-link fw-semibold p-0">
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
                                                <div id="add-new-census"></div>
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
                                                    <input type="date" class="form-control datepicker" name="date_policy_start" id="date-policy-start" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="date-policy-end" class="form-label">
                                                        Policy Termination Date
                                                    </label>
                                                    <input type="date" class="form-control datepicker" name="date_policy_end" id="date-policy-end" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="quotes-due-by" class="form-label">
                                                        Final Proposals Due
                                                    </label>
                                                    <input type="date" class="form-control datepicker" required>
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

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[0][status]" value="active" id="network-0">
                                        <label class="form-check-label" for="network-0">
                                            First Choice Health Plans of Mississippi
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[1][status]" value="active" id="network-1">
                                        <label class="form-check-label" for="network-1">
                                            Health Smart - Texas Only
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[2][status]" value="active" id="network-2">
                                        <label class="form-check-label" for="network-2">
                                            Alliance
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[3][status]" value="active" id="network-3">
                                        <label class="form-check-label" for="network-3">
                                            Anthem
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[4][status]" value="active" id="network-4">
                                        <label class="form-check-label" for="network-4">
                                            Health Smart (all states excl Texas)
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[5][status]" value="active" id="network-5">
                                        <label class="form-check-label" for="network-5">
                                            Cigna PPO
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[6][status]" value="active" id="network-6">
                                        <label class="form-check-label" for="network-6">
                                            Healthcare Highways
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[7][status]" value="active" id="network-7">
                                        <label class="form-check-label" for="network-7">
                                            Blue Cross / Blue Shield
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[8][status]" value="active" id="network-8">
                                        <label class="form-check-label" for="network-8">
                                            Cash Centric
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[9][status]" value="active" id="network-9">
                                        <label class="form-check-label" for="network-9">
                                            Healthlink and Freedom
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[10][status]" value="active" id="network-10">
                                        <label class="form-check-label" for="network-10">
                                            Encore
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[11][status]" value="active" id="network-11">
                                        <label class="form-check-label" for="network-11">
                                            Multiplan
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[12][status]" value="active" id="network-12">
                                        <label class="form-check-label" for="network-12">
                                            Medical Mutual of Ohio (MMO)
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[13][status]" value="active" id="network-13">
                                        <label class="form-check-label" for="network-13">
                                            MedCost
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[14][status]" value="active" id="network-14">
                                        <label class="form-check-label" for="network-14">
                                            Healthlink
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[15][status]" value="active" id="network-15">
                                        <label class="form-check-label" for="network-15">
                                            Reliance Health Partners RBP
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="quote_request_networks[16][status]" value="active" id="network-16">
                                        <label class="form-check-label" for="network-16">
                                            First Health
                                        </label>
                                    </div>

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

                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox" checked>
                                                            </td>
                                                            <td><strong>35k: 12-12</strong></td>
                                                            <td>$35,000</td>
                                                            <td>12/12</td>
                                                            <td>$0</td>
                                                            <td>12/12</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>35k: 12-18</strong></td>
                                                            <td>$35,000</td>
                                                            <td>12/18</td>
                                                            <td>$0</td>
                                                            <td>12/18</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>40k: 12-12</strong></td>
                                                            <td>$40,000</td>
                                                            <td>12/12</td>
                                                            <td>$0</td>
                                                            <td>12/12</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>40k: 12-18</strong></td>
                                                            <td>$40,000</td>
                                                            <td>12/18</td>
                                                            <td>$0</td>
                                                            <td>12/18</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>45k: 12-12</strong></td>
                                                            <td>$45,000</td>
                                                            <td>12/12</td>
                                                            <td>$0</td>
                                                            <td>12/12</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>45k: 12-18</strong></td>
                                                            <td>$45,000</td>
                                                            <td>12/18</td>
                                                            <td>$0</td>
                                                            <td>12/18</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>50k: 12-12</strong></td>
                                                            <td>$50,000</td>
                                                            <td>12/12</td>
                                                            <td>$0</td>
                                                            <td>12/12</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>50k: 12-18</strong></td>
                                                            <td>$50,000</td>
                                                            <td>12/18</td>
                                                            <td>$0</td>
                                                            <td>12/18</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>55k: 12-12</strong></td>
                                                            <td>$55,000</td>
                                                            <td>12/12</td>
                                                            <td>$0</td>
                                                            <td>12/12</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>55k: 12-18</strong></td>
                                                            <td>$55,000</td>
                                                            <td>12/18</td>
                                                            <td>$0</td>
                                                            <td>12/18</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>60k: 12-12</strong></td>
                                                            <td>$60,000</td>
                                                            <td>12/12</td>
                                                            <td>$0</td>
                                                            <td>12/12</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>60k: 12-18</strong></td>
                                                            <td>$60,000</td>
                                                            <td>12/18</td>
                                                            <td>$0</td>
                                                            <td>12/18</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>65k: 12-12</strong></td>
                                                            <td>$65,000</td>
                                                            <td>12/12</td>
                                                            <td>$0</td>
                                                            <td>12/12</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>65k: 12-18</strong></td>
                                                            <td>$65,000</td>
                                                            <td>12/18</td>
                                                            <td>$0</td>
                                                            <td>12/18</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>75k: 12-12</strong></td>
                                                            <td>$75,000</td>
                                                            <td>12/12</td>
                                                            <td>$0</td>
                                                            <td>12/12</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>75k: 12-18</strong></td>
                                                            <td>$75,000</td>
                                                            <td>12/18</td>
                                                            <td>$0</td>
                                                            <td>12/18</td>
                                                            <td>1.20</td>
                                                            <td>0.00%</td>
                                                        </tr>

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
                                            <form method="post" action="">

                                                <!-- Stop Loss Coverage Type -->
                                                <h5 class="mb-2">Stop Loss Coverage Type</h5>
                                                <div class="form-group custom-controls-stacked">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="include" value="both" class="custom-control-input" checked>
                                                        <span class="custom-control-label">Specific and aggregate stop loss</span> </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="include" value="spec" class="custom-control-input">
                                                        <span class="custom-control-label">Specific stop loss only</span> </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="include" value="agg" class="custom-control-input">
                                                        <span class="custom-control-label">Aggregate stop loss only</span> </label>
                                                </div>

                                                <!-- Prescription Drug Coverage -->
                                                <h5 class="mb-2">Stop Loss Prescription Drug Coverage</h5>
                                                <div class="form-group custom-controls-stacked">
                                                    <label>Specific includes prescription drugs?</label><br>
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="spec_include_rx" value="1" class="custom-control-input" checked>
                                                        <span class="custom-control-label">Yes</span> </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="spec_include_rx" value="0" class="custom-control-input">
                                                        <span class="custom-control-label">No</span> </label>
                                                </div>
                                                <div class="form-group custom-controls-stacked">
                                                    <label>Aggregate includes prescription drugs?</label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="agg_include_rx" value="1" class="custom-control-input" checked>
                                                        <span class="custom-control-label">Yes</span> </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" name="agg_include_rx" value="0" class="custom-control-input">
                                                        <span class="custom-control-label">No</span> </label>
                                                </div>

                                                <!-- Level Funded Option -->
                                                <h5 class="mb-2">Level Funded Stop Loss</h5>
                                                <div class="form-group">
                                                    <label class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="level_funded_included" value="1" class="custom-control-input">
                                                        <span class="custom-control-label">Include level quote if available?</span> </label>
                                                </div>
                                                <button type="submit" class="btn btn-primary"> Review Stop Loss Options </button>
                                            </form>
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
                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>Plan 1</strong></td>
                                                            <td>In: $250<br>Out: $500</td>
                                                            <td>In: 80%<br>Out: 60%</td>
                                                            <td>In: $2,000<br>Out: $4,000</td>
                                                            <td>In: Yes<br>Out: Yes</td>
                                                            <td>$0</td>
                                                            <td>$25</td>
                                                            <td>$50</td>
                                                            <td>Yes</td>
                                                            <td>$200</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>Plan 2</strong></td>
                                                            <td>In: $500<br>Out: $1,000</td>
                                                            <td>In: 80%<br>Out: 60%</td>
                                                            <td>In: $3,000<br>Out: $6,000</td>
                                                            <td>In: Yes<br>Out: Yes</td>
                                                            <td>$0</td>
                                                            <td>$25</td>
                                                            <td>$50</td>
                                                            <td>Yes</td>
                                                            <td>$200</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>Plan 3</strong></td>
                                                            <td>In: $1,500<br>Out: $3,000</td>
                                                            <td>In: 80%<br>Out: 60%</td>
                                                            <td>In: $3,500<br>Out: $7,000</td>
                                                            <td>In: Yes<br>Out: Yes</td>
                                                            <td>$0</td>
                                                            <td>$25</td>
                                                            <td>$50</td>
                                                            <td>Yes</td>
                                                            <td>$200</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>Plan 4</strong></td>
                                                            <td>In: $2,000<br>Out: $4,000</td>
                                                            <td>In: 80%<br>Out: 60%</td>
                                                            <td>In: $5,000<br>Out: $10,000</td>
                                                            <td>In: Yes<br>Out: Yes</td>
                                                            <td>$0</td>
                                                            <td>$35</td>
                                                            <td>$70</td>
                                                            <td>Yes</td>
                                                            <td>$300</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>Plan 5</strong></td>
                                                            <td>In: $3,500<br>Out: $7,000</td>
                                                            <td>In: 80%<br>Out: 60%</td>
                                                            <td>In: $5,000<br>Out: $10,000</td>
                                                            <td>In: Yes<br>Out: Yes</td>
                                                            <td>$0</td>
                                                            <td>$0</td>
                                                            <td>$0</td>
                                                            <td>Yes</td>
                                                            <td>$300</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>Plan 6</strong></td>
                                                            <td>In: $5,000<br>Out: $10,000</td>
                                                            <td>In: 70%<br>Out: 50%</td>
                                                            <td>In: $6,000<br>Out: $12,000</td>
                                                            <td>In: Yes<br>Out: Yes</td>
                                                            <td>$0</td>
                                                            <td>$0</td>
                                                            <td>$0</td>
                                                            <td>Yes</td>
                                                            <td>$300</td>
                                                        </tr>

                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" checked></td>
                                                            <td><strong>Plan 7</strong></td>
                                                            <td>In: $0<br>Out: $2,000</td>
                                                            <td>In: 100%<br>Out: 75%</td>
                                                            <td>In: $5,000<br>Out: $5,000</td>
                                                            <td>In: Yes<br>Out: Yes</td>
                                                            <td>$10</td>
                                                            <td>$25</td>
                                                            <td>$50</td>
                                                            <td>Yes</td>
                                                            <td>$200</td>
                                                        </tr>

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
                                            <a href="#" class="btn btn-rounded btn-secondary mr-1 d-print-none">Upload Files</a> <a href="#" class="btn btn-rounded btn-primary d-print-none" data-no-ajax="1">Download All</a>
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
                                    <div class="form-group textarea "><label for="general-notes">General Notes</label><textarea class="form-control " name="general_notes" id="general-notes" rows="5" maxlength="100000"></textarea></div>
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
                                                        <tr>
                                                            <td>TPA, PPO, PBM, Service Providers</td>
                                                            <td>PEPM</td>
                                                            <td style="max-width: 200px;">
                                                                <input type="text" class="form-control" name="fees[1d683413-08fe-4889-b165-bdf66b68d242]" id="fees-1d683413-08fe-4889-b165-bdf66b68d242" placeholder="70.00" value="70.00" aria-label="Fee amount">
                                                            </td>
                                                            <td>
                                                                <small class="text-muted">This fee is editable</small>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>Broker Fee</td>
                                                            <td>PEPM</td>
                                                            <td style="max-width: 200px;">
                                                                <input type="text" class="form-control" name="fees[d612aff2-5d13-46b2-983a-2ceca9c2fceb]" id="fees-d612aff2-5d13-46b2-983a-2ceca9c2fceb" placeholder="35.00" value="35.00" aria-label="Fee amount">
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
                            <button type="button" class="btn btn-secondary" id="prevBtn">Previous Step</button> &nbsp;
                            <button type="button" class="btn btn-primary" id="nextBtn">Next Step</button> &nbsp;
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
  }
});

    document.getElementById("prevBtn").addEventListener("click", () => {
  if (currentStep > 0) {
    currentStep--;
    updateSteps();
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