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
                <span class="me-2">#7802:</span>

                <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'quotingDetail',$RequestQuots->quotgroup->id]);?>" class="link-no-color fw-semibold me-3">
                    <?php echo $RequestQuots->quotgroup->group_name;?>
                </a>

                <span style="font-size: 12px;" class="badge bg-warning">
                    Illustrative Quote Ready
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

                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-xs btn-primary btn-rounded dropdown-toggle"> <i data-feather="settings" class="icon-sm"></i>

                    Update Quote Request Status
                    <span class="icon-dropdown s7-angle-down"></span>
                </button>

            </div>
        </div>
        <div class="col-md-12">
            <p style="font-size: 13px;margin-top: 7px;">
                <strong>Final Proposals Due</strong> 7/4/25 <strong>Plan Effective</strong> 8/1/2025 <strong>Census Used</strong> Census St Joseph Motesorri.xlsx (71 members)
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
                                                        Prodigy Health Insurance
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
                                        <div class="col-9"> <span class="panel-title"> <i data-feather="sun" class="icon-sm"></i> Illustrative Quotes </span> <span class="panel-subtitle">#7802: St. Joseph Montessori School</span> </div>
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
                                                        <td><?php echo ($file_counts['EE'] + $file_counts['ES'] + $file_counts['EC']+ $file_counts['EF']) ?? 0; ?></td>
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
                                                        <tr>
                                                            <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
                                                            <td>$70.00 PEPM</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fixed-left">Broker Fee</td>
                                                            <td>$35.00 PEPM</td>
                                                        </tr>
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
                                                    <th colspan="1" class="plan-name">Plan 5</th>
                                                </tr>
                                                <tr class="table-light">
                                                    <th><strong>Network or Repricing</strong></th>
                                                    <th colspan="1" class="network-name">Cigna PPO</th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th class="text-center"><a href="view-quote.html" class="btn btn-primary btn-sm btn-rounded">Print Quote</a></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="table-light">
                                                    <td colspan="2 table-light">Stop Loss Plan</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Specific Deductible</td>
                                                    <td class="text-center">$35,000</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Specific Contract</td>
                                                    <td class="text-center">12/18</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Aggregate Contract</td>
                                                    <td class="text-center">12/18</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Aggregate Corridor</td>
                                                    <td class="text-center">1.25</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Aggregating Specific Deductible</td>
                                                    <td class="text-center">$0</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Commission</td>
                                                    <td class="text-center">0.00%</td>
                                                </tr>



                                                <tr>
                                                    <td colspan="2" class="table-light">Tier 1: Employee Only (EE)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$246.50</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$42.48</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$643.35</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
                                                    <td class="text-center">$1,037.33</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="table-light">Tier 2: Employee + Spouse (ES)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$468.36</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$80.71</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$1,222.37</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
                                                    <td class="text-center">$1,876.44</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="table-light">Tier 3: Employee + Child(ren) (EC)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$443.70</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$76.46</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$1,158.04</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
                                                    <td class="text-center">$1,783.20</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Tier 4: Employee + Family (EF)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$739.52</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$127.44</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$1,930.06</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total PEPM Including Fees</td>
                                                    <td class="text-center">$2,902.01</td>
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
                                                    <td class="text-center">$5,176.60</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$892.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$13,510.35</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
                                                    <td class="text-center">$1,470.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Broker Fee</td>
                                                    <td class="text-center">$735.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total</td>
                                                    <td class="text-center">$21,783.95</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width" colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Tier 2: Employee + Spouse (ES)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$936.71</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$161.42</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$2,444.74</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
                                                    <td class="text-center">$140.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Broker Fee</td>
                                                    <td class="text-center">$70.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total</td>
                                                    <td class="text-center">$3,752.87</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width" colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Tier 3: Employee + Child(ren) (EC)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$0.00</td>
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
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width" colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Tier 4: Employee + Family (EF)</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Specific Rate</td>
                                                    <td class="text-center">$8,134.66</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Rate</td>
                                                    <td class="text-center">$1,401.78</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Factor</td>
                                                    <td class="text-center">$21,230.66</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Aggregate Accommodation</td>
                                                    <td class="text-center">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">TPA, PPO, PBM, Service Providers</td>
                                                    <td class="text-center">$770.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left">Broker Fee</td>
                                                    <td class="text-center">$385.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total</td>
                                                    <td class="text-center">$31,922.11</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width" colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Composite Aggregate Rate</td>
                                                    <td class="text-center">$72.21</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left full-width" colspan="999"></td>
                                                </tr>
                                                <tr>
                                                    <td class="table-light" colspan="2">Quote Summary</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Estimated Spec Premium</td>
                                                    <td class="text-center">$170,975.78</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Estimated Aggregate Premium</td>
                                                    <td class="text-center">$29,462.41</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Total Attachment Point</td>
                                                    <td class="text-center">$446,229.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="fixed-left font-weight-bold">Estimated Total Premium</td>
                                                    <td class="text-center">$200,438.20</td>
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
                                <li class="timeline-item" id="">
                                    <div class="timeline-content timeline-type file">
                                        <div class="timeline-icon"><i class="link-icon icon-md" data-feather="file-text"></i></div>
                                        <div class="timeline-header"> <span class="timeline-author"> ERISAQuote Pro </span>
                                            <p class="timeline-activity mr-1"> updated Quote Request #7802 </p>
                                            <span class="timeline-time"> Jun 30, 2025 (5:52pm) </span>
                                            <div class="timeline-summary">
                                                <table class="table table-bordered table-sm font-size-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="diff"> <span class="old"><span class="old badge badge-secondary">Pending Decision</span></span> <span class="arrow">→</span> <span class="new"><span class="new badge badge-secondary">Illustrative Quote Ready</span></span> </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item" id="">
                                    <div class="timeline-content timeline-type file">
                                        <div class="timeline-icon"><i class="link-icon icon-md" data-feather="share-2"></i></div>
                                        <div class="timeline-header"> <span class="timeline-author"> ERISAQuote Pro </span>
                                            <p class="timeline-activity mr-1"> updated Prodigy Health Insurance's submission </p>
                                            <span class="timeline-time"> Jun 30, 2025 (5:52pm) </span>
                                            <div class="timeline-summary">
                                                <table class="table table-bordered table-sm font-size-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="diff"> <span class="old"><span class="old badge badge-secondary">Received</span></span> <span class="arrow">→</span> <span class="new"><span class="new badge badge-secondary">Illustrative Quote Ready</span></span> </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item" id="">
                                    <div class="timeline-content timeline-type file">
                                        <div class="timeline-icon"><i class="link-icon icon-md" data-feather="sun"></i></div>
                                        <div class="timeline-header"> <span class="timeline-author"> Prodigy Health Insurance </span>
                                            <p class="timeline-activity mr-1"> added <a href="#">1 Illustrative quote(s)</a> </p>
                                            <span class="timeline-time"> Jun 30, 2025 (5:49pm) </span>
                                            <div class="timeline-summary"> </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item" id="">
                                    <div class="timeline-content timeline-type file">
                                        <div class="timeline-icon"><i class="link-icon icon-md" data-feather="file-text"></i></div>
                                        <div class="timeline-header"> <span class="timeline-author"> ERISAQuote Pro </span>
                                            <p class="timeline-activity mr-1"> updated Quote Request #7802 </p>
                                            <span class="timeline-time"> Jun 30, 2025 (5:49pm) </span>
                                            <div class="timeline-summary">
                                                <table class="table table-bordered table-sm font-size-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="diff"> <span class="old"><span class="old badge badge-secondary">Draft</span></span> <span class="arrow">→</span> <span class="new"><span class="new badge badge-secondary">Pending Decision</span></span> </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item" id="">
                                    <div class="timeline-content timeline-type file">
                                        <div class="timeline-icon"><i class="link-icon icon-md" data-feather="share-2"></i></div>
                                        <div class="timeline-header"> <span class="timeline-author"> ERISAQuote Pro </span>
                                            <p class="timeline-activity mr-1"> updated Prodigy Health Insurance's submission </p>
                                            <span class="timeline-time"> Jun 30, 2025 (5:49pm) </span>
                                            <div class="timeline-summary">
                                                <table class="table table-bordered table-sm font-size-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="diff"> <span class="old"><span class="old badge badge-secondary">Submitted</span></span> <span class="arrow">→</span> <span class="new"><span class="new badge badge-secondary">Received</span></span> </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item" id="">
                                    <div class="timeline-content timeline-type file">
                                        <div class="timeline-icon"><i class="link-icon icon-md" data-feather="sun"></i></div>
                                        <div class="timeline-header"> <span class="timeline-author"> John Youngs </span>
                                            <p class="timeline-activity mr-1"> submitted to Prodigy Health Insurance </p>
                                            <span class="timeline-time"> Jun 30, 2025 (5:48pm) </span>
                                            <div class="timeline-summary">
                                                <table class="table table-bordered table-sm font-size-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100px">Status</td>
                                                            <td><span class="badge badge-secondary">Submitted</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item" id="">
                                    <div class="timeline-content timeline-type file">
                                        <div class="timeline-icon"><i class="link-icon icon-md" data-feather="file-text"></i></div>
                                        <div class="timeline-header"> <span class="timeline-author"> John Youngs </span>
                                            <p class="timeline-activity mr-1"> drafted Quote Request #7802 </p>
                                            <span class="timeline-time"> Jun 30, 2025 (5:40pm) </span>
                                            <div class="timeline-summary">
                                                <table class="table table-bordered table-sm font-size-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100px">Status</td>
                                                            <td><span class="badge badge-secondary">Draft</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item" id="">
                                    <div class="timeline-content timeline-type file">
                                        <div class="timeline-icon"><i class="link-icon icon-md" data-feather="sun"></i></div>
                                        <div class="timeline-header"> <span class="timeline-author"> John Youngs </span>
                                            <p class="timeline-activity mr-1"> uploaded 1 document(s) </p>
                                            <span class="timeline-time"> Jun 30, 2025 (5:40pm) </span>
                                            <div class="timeline-summary">
                                                <table class="table table-bordered table-sm font-size-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td><a href="#">Census St Joseph Motesorri.xlsx</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </li>
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
                                                <dd class="col-sm-8"><?php echo htmlspecialchars($RequestQuots->quotgroup->business_classification ?? ''); ?></dd>
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
                                                        <?php endif; ?>



                                                        <?php if (empty($censusData) ): ?>
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
