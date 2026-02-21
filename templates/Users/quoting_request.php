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
                            <form method="post" action="/quote-requests" class="w-100">
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
                                    <div class="col-md-4"><input type="checkbox" name="hide_expired" value="1" checked class="mt-2">
                                        Hide groups past effective date</div>
                                    <div class="col-md-4"><input type="text" name="keyword" placeholder="Search by group..." style="float: left;width: 85%;" class="form-control">
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
                                <tr>
                                    <td>
                                        <a href="quote-details.html" class="btn btn-xs btn-primary btn-rounded"><i class="icon icon-left s7-ticket"></i><i class="icon-md" data-feather="file"></i> #7695</a>
                                    </td>
                                    <td><a href="group-details.html" class="link-no-color">St. Joseph Montessori School</a></td>
                                    <td>3/24/2025</td>
                                    <td>2/24/2025</td>
                                    <td>2/1/2025</td>
                                    <td><span class="badge bg-warning">Illustrative Quote Ready</span></td>

                                </tr>
                                <tr>
                                    <td>
                                        <a href="quote-details.html" class="btn btn-xs btn-primary btn-rounded"><i class="icon icon-left s7-ticket"></i><i class="icon-md" data-feather="file"></i> #7696</a>
                                    </td>
                                    <td><a href="group-details.html" class="link-no-color">St. Joseph Montessori School</a></td>
                                    <td>2/24/2025</td>
                                    <td>2/24/2025</td>
                                    <td>2/1/2025</td>
                                    <td><span class="badge bg-danger">Draft</span></td>

                                </tr>
                                <tr>
                                    <td>
                                        <a href="quote-details.html" class="btn btn-xs btn-primary btn-rounded"><i class="icon icon-left s7-ticket"></i><i class="icon-md" data-feather="file"></i> #7693</a>
                                    </td>
                                    <td><a href="group-details.html" class="link-no-color">St. Joseph Montessori School</a></td>
                                    <td>5/24/2025</td>
                                    <td>2/24/2025</td>
                                    <td>2/1/2025</td>
                                    <td><span class="badge bg-success">Active</span></td>

                                </tr>
                                <tr>
                                    <td>
                                        <a href="quote-details.html" class="btn btn-xs btn-primary btn-rounded"><i class="icon icon-left s7-ticket"></i><i class="icon-md" data-feather="file"></i> #7692</a>
                                    </td>
                                    <td><a href="group-details.html" class="link-no-color">St. Joseph Montessori School</a></td>
                                    <td>9/24/2025</td>
                                    <td>2/24/2025</td>
                                    <td>2/1/2025</td>
                                    <td><span class="badge bg-info">Underwritten Quote Ready</span></td>

                                </tr>
                                <tr>
                                    <td>
                                        <a href="quote-details.html" class="btn btn-xs btn-primary btn-rounded"><i class="icon icon-left s7-ticket"></i><i class="icon-md" data-feather="file"></i> #7691</a>
                                    </td>
                                    <td><a href="group-details.html" class="link-no-color">St. Joseph Montessori School</a></td>
                                    <td>2/24/2025</td>
                                    <td>2/24/2025</td>
                                    <td>2/1/2025</td>
                                    <td><span class="badge bg-dark">Lost</span></td>

                                </tr>
                                <tr>
                                    <td>
                                        <a href="quote-details.html" class="btn btn-xs btn-primary btn-rounded"><i class="icon icon-left s7-ticket"></i><i class="icon-md" data-feather="file"></i> #7690</a>
                                    </td>
                                    <td><a href="group-details.html" class="link-no-color">St. Joseph Montessori School</a></td>
                                    <td>2/24/2025</td>
                                    <td>2/24/2025</td>
                                    <td>2/1/2025</td>
                                    <td><span class="badge bg-light text-dark">Cancelled</span></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
