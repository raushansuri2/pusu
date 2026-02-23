<div class="page-content">

     <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
        <h4 class="mb-3 mb-md-0"> <i class="link-icon icon-md" data-feather="users"></i> Edit Group </h4>
        <p class="small-text">A group (also known as a plan sponsor) is an employer or organization that offers a group health plan to its employees or members.</p>
        </div> 
    </div> 
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <?php echo $this->Flash->render(); ?>
                    <form method="post" action="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'groupedit',$group->id]); ?>" enctype="multipart/form-data" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Group Name *</label>
                                    <input type="text" class="form-control" name="group_name" placeholder="Group Name" required value="<?php echo $group->group_name; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">SIC or NAICS Code *</label>
                                    <input type="text" class="form-control" name="SIC_Code" placeholder="SIC or NAICS Code" value="<?php echo $group->group_name; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Address Line 1 *</label>
                                    <input type="text" class="form-control" name="address1" placeholder="Address Line 1" value="<?php echo $group->address1; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Address Line 2</label>
                                    <input type="text" class="form-control" name="address2" placeholder="Address Line 2" value="<?php echo $group->address2; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">City *</label>
                                    <input type="text" class="form-control" name="city" placeholder="City" value="<?php echo $group->city; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">State *</label>
                                    <select class="form-control form-select" name="state_name" required>
                                        <option value="">Select State</option>
                                        <?php
                                        // USA states list
                                        $states = [
                                            'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
                                            'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
                                            'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
                                            'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
                                            'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
                                            'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
                                            'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
                                            'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
                                            'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
                                            'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
                                            'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
                                            'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
                                            'WI' => 'Wisconsin', 'WY' => 'Wyoming'
                                        ];
                                        $selectedState = $group->state_name;
                                        foreach ($states as $code => $name) {
                                            echo '<option value="' . $code . '" ' . ($code == $selectedState ? 'selected' : '') . '>' . $name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Zip *</label>
                                    <input type="text" class="form-control" name="zip" placeholder="Zip" value="<?php echo $group->zip; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success me-2">Update Group</button>
						<button href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'group']); ?>" class="btn btn-secondary">Cancel</button>
                        
                    </form>


                </div>
            </div>
        </div>
    </div>

</div>
