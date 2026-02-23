<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Change Password </h4>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title" style="border-bottom:1px solid #eee; padding-bottom:10px;">A request was received to reset the password for your account. Please use the link below to set a new password.</h6>
                    <?php echo $this->Flash->render(); ?>
                    <form method="post" action="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'changepassword']); ?>" enctype="multipart/form-data" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="oldPassword" class="form-control" placeholder="********">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="newPassword" class="form-control" placeholder="********">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirmNewPassword" class="form-control" placeholder="********">
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary me-2">Reset Password</button>
                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']); ?>" class="btn btn-secondary">Cancel</a>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
