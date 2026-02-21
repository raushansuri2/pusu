<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Groups</h4>
            <p class="small-text">All the groups you currently manage in ERISAQuote Pro.</p>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">


            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupAdd']); ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="plus-square"></i>
                Add New Groups
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 48px;"></th>
                                    <th>Group Name</th>
                                    <th width="40%">Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Zip</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        <div class="circle">A</div>
                                    </td>
                                    <td>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupDetails']); ?>">
                                            AP Professional Security, LLC
                                        </a>
                                    </td>
                                    <td>
                                        1110 London Street
                                        Suite 101
                                    </td>
                                    <td>Myrtle Beach</td>
                                    <td>SC</td>
                                    <td>29577</td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="circle">A</div>
                                    </td>
                                    <td>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupDetails']); ?>">
                                            Arion Care Solutions V2
                                        </a>
                                    </td>
                                    <td>3200 Dobson Road</td>
                                    <td>Chandler</td>
                                    <td>AZ</td>
                                    <td>85224</td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="circle">C</div>
                                    </td>
                                    <td>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupDetails']); ?>">
                                            Colvin Ford
                                        </a>
                                    </td>
                                    <td>1925 OR-99 W</td>
                                    <td>McMinnville</td>
                                    <td>OR</td>
                                    <td>97128</td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="circle">I</div>
                                    </td>
                                    <td>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupDetails']); ?>">
                                            Innerstaff PEO
                                        </a>
                                    </td>
                                    <td>808 W. Dallas Street</td>
                                    <td>Conroe</td>
                                    <td>TX</td>
                                    <td>77301</td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="circle">S</div>
                                    </td>
                                    <td>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupDetails']); ?>">
                                            Sawyer Manufacturing Company
                                        </a>
                                    </td>
                                    <td>7799 S. Regency Drive</td>
                                    <td>Tulsa</td>
                                    <td>OK</td>
                                    <td>74131</td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="circle">T</div>
                                    </td>
                                    <td>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupDetails']); ?>">
                                            Test New Group
                                        </a>
                                    </td>
                                    <td>1 Infinite Loop</td>
                                    <td>New York</td>
                                    <td>NY</td>
                                    <td>10118</td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="circle">T</div>
                                    </td>
                                    <td>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupDetails']); ?>">
                                            TSI Touch Inc
                                        </a>
                                    </td>
                                    <td>1 Millennium Drive</td>
                                    <td>Uniontown</td>
                                    <td>PA</td>
                                    <td>15401</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
