<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Quote Requests</h4>
            <p class="small-text">To limit the number of quote requests shown in this view, use the filters below or search on the group name.</p>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">


            <a href="new-quote-request.html" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="plus-square"></i>
                New Quote Request
            </a>
        </div>
    </div>
    <div class="row">

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="col-md-12">
                        <div class="d-flex">
                            <form method="get" action="" class="w-100">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select name="status[]" id="qr-filter-status" class="form-control">
                                            <option value="Filter by Quote Request Status:" selected disabled>Filter by Quote Request Status:</option>
                                            <option value="draft">Draft</option>
                                            <option value="submitted">Waiting on Carriers</option>
                                            <option value="illustrative">Illustrative Quote Ready</option>
                                            <option value="underwritten-submitted">Underwritten Quote Requested</option>
                                            <option value="ai-underwritten">AI Underwritten Quote Ready</option>
                                            <option value="underwritten">Underwritten Quote Ready</option>
                                            <option value="sold">Sold</option>
                                            <option value="active">Active</option>
                                            <option value="lost">Lost</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="terminating">Terminating</option>
                                            <option value="terminated">Terminated</option>
                                            <option value="pending">Pending Decision</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <?php $hideExpired = $this->request->getQuery('hide_expired') ?? 1; ?>
                                        <input type="hidden" name="hide_expired" value="0">
                                        <input
                                            type="checkbox"
                                            name="hide_expired"
                                            value="1"
                                            class="mt-2"
                                            <?= ($hideExpired == 1) ? 'checked' : '' ?>
                                        >
                                        Hide groups past effective date</div>
                                    <div class="col-md-4">
                                        <input type="text" name="keyword" placeholder="Search by group..." style="float: left;width: 85%;" class="form-control">
                                        <button type="submit" class="btn btn-primary">Go</button>
                                    </div>
                                </div>



                            </form>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="" class="table table-bordered">
                            <thead>
                                <tr class="table-light">
                                    <th>Quote Request #</th>
                                    <th>Group</th>
                                    <th>Created</th>
                                    <th>Final Proposals Due</th>
                                    <th>Effective Date </th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php //pr($request_quote_list); die;
                                if(!empty($request_quote_list)) {
                                    foreach($request_quote_list as $request_quote) {
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'quotingDetail', $request_quote->id]);?>" class="btn btn-xs btn-primary btn-rounded"><i class="icon icon-left s7-ticket"></i><i class="icon-md" data-feather="file"></i> <?php echo '#'.$request_quote->id;?></a>
                                    </td>
                                    <td><a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'groupDetails', $request_quote->quotgroup->id]);?>" class="link-no-color"><?php echo $request_quote->quotgroup->group_name;?></a></td>
                                    <td><?php echo $request_quote->created_at; ?></td>
                                    <td><?php echo $request_quote->Final_Proposals_Due; ?></td>
                                    <td><?php echo $request_quote->Policy_Effective_Date; ?></td>
                                    <td><?php if($request_quote->status == 1){?>
                                        <span class="badge bg-success">Active</span>
                                    <?php } elseif($request_quote->status == 2) { ?>
                                        <span class="badge bg-warning">Illustrative Quote Ready</span>
                                    <?php }elseif($request_quote->status == 3) { ?>
                                        <span class="badge bg-danger">Closed</span>
                                    <?php }elseif($request_quote->status == 0){?>
                                        <span class="badge bg-secondary">Unknown</span>
                                    <?php }elseif($request_quote->status == 4){?>
                                        <span class="badge bg-info">Draft</span>
                                    <?php }elseif($request_quote->status == 5){?>
                                        <span class="badge bg-dark">Unknown</span>
                                    <?php }else{?>
                                        <span class="badge bg-light text-dark"> Unknown</span>
                                    <?php }?>
                                </td>

                                </tr>
                                <?php }
                                } ?>






                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
