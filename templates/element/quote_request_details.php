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
    <div class="panel-heading panel-heading-divider"> <span class="panel-title"> <i class="link-icon icon-md" data-feather="dollar-sign"></i> Claims Experience </span> </div>
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
