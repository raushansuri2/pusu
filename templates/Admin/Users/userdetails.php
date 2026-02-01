<div class="mainwrapper">
    <div class="leftpanel">
        <?= $this->element('admin/sidebar') ?>             
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-user"></i>
                </div>
                <div class="media-body" style="width:80%;">
                    <ul class="breadcrumb">
                        <li><a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']) ?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>">Manage Member</a></li>
                        <li>View Member Details</li>
                    </ul>
                    <h4>View Member Details</h4>
                </div>
                <div class="search-body">
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>" class="btn btn-primary mr5 ml10">Back</a>                    
                </div>              
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Member Detail</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Full Name: </strong></label>
                                <div class="col-sm-9"><?= h($user->firstName) ?> <?= h($user->lastName) ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Email: </strong></label>
                                <div class="col-sm-9"><?= h($user->email) ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Gender: </strong></label>
                                <div class="col-sm-9"><?= h($user->gender) ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Date Of Birth: </strong></label>
                                <div class="col-sm-9"><?= h($user->dob) ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Phone: </strong></label>
                                <div class="col-sm-9"><?= h($user->contactNumber) ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Address: </strong></label>
                                <div class="col-sm-9"><?= h($user->address) ?></div>
                            </div>
                            
                            <!-- <div class="form-group">
                                <label class="col-sm-2"><strong>ProfileImage: </strong></label>
                                <div class="col-sm-9">
                                    <?php
                                    $profilePic1 = trim($user->profile_picture);
                                    $imagePath = WWW_ROOT . 'img/uploads/users/' . $profilePic1;
                                    $defaultImage = $this->Url->build('/img/uploads/users/no-image-100x100.jpg');
                                    $profilePicture1 = $defaultImage; // Default image path

                                    // Check if the profile picture exists
                                    if ($profilePic1 !== '' && file_exists($imagePath)) {
                                        $profilePicture1 = $this->Url->build('/img/uploads/users/' . $profilePic1);
                                    }
                                    ?>
                                    <img src="<?= h($profilePicture1) ?>" style="max-height:150px; max-width:150px" alt="Profile Picture" /><br>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Status: </strong></label>
                                <div class="col-sm-9"><?= ($user->status == '1') ? 'Active' : 'Inactive' ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Created: </strong></label>
                                <div class="col-sm-9"><?= h(date('Y-m-d H:i:s', strtotime($user->created))) ?></div>
                            </div>
                        </div>
                    </div><!-- panel -->                    
                </div>
            </div><!-- row -->
        </div>
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->