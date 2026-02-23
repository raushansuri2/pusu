<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Account Settings </h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <?php echo $this->Flash->render(); ?>
                    <form method="post" action="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'editprofile']); ?>" enctype="multipart/form-data" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" value="<?php echo $user->email;?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="firstName" value="<?php echo $user->firstName;?>" class="form-control" value="John">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="lastName" value="<?php echo $user->lastName;?>" class="form-control" value="Youngs">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="contactNumber" value="<?php echo $user->contactNumber;?>"  class="form-control" value="(916) 804-6009">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Timezone *</label>
                                    <select class="form-control form-select" name="time_zone" required>
                                        <option value="">Select Timezone</option>
                                        <?php
                                        // Common timezone list
                                        $timezones = [
                                            'America/New_York' => 'Eastern Time (ET)',
                                            'America/Chicago' => 'Central Time (CT)',
                                            'America/Denver' => 'Mountain Time (MT)',
                                            'America/Los_Angeles' => 'Pacific Time (PT)',
                                            'America/Anchorage' => 'Alaska Time (AKT)',
                                            'America/Honolulu' => 'Hawaii Time (HT)',
                                            'America/Phoenix' => 'Mountain Time (AZ)',
                                            'America/Indiana/Indianapolis' => 'Eastern Time (IN)',
                                            'America/Detroit' => 'Eastern Time (MI)',
                                        ];
                                        
                                        foreach ($timezones as $value => $label) {
                                            $selected = ($user->time_zone == $value) ? 'selected' : '';
                                            echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary me-2">Save Settings</button>
                        <!-- <button class="btn btn-secondary">Cancel</button> -->
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
