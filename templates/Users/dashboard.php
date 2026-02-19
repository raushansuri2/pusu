
<?php echo $this->Flash->render(); //pr($user);exit;?>
<!-- General Information -->
<div class="add-listing-box edit-info mrg-bot-25 padd-bot-30 padd-top-25">
	<div class="row">
		<div class="col-md-3">
		    <div style="margin:0;" class="listing-box-header">
    			<div class="avater-box">
    				<?php 
                    $UIMGS = ($user->profile_picture != '') ? 
                    $this->Url->build('/').'img/uploads/users/'. $user->profile_picture : 
                    $this->Url->build('/').'img/dummy.jpg'; ?>

    				<img src="<?php echo $UIMGS;?>" class="img-responsive img-circle edit-avater" alt="">
    			</div>
			    <h3><?php echo $user->firstName;?> <?php echo $user->lastName;?></h3>
			</div>
		</div>
		<div class="col-md-9">
		    <div class="row mrg-r-10 mrg-l-10 preview-info" style="margin-TOP: 56px;">
    			<div class="col-sm-6">
    				<label><i class="ti-mobile preview-icon call mrg-r-10"></i><?php echo $user->contactNumber;?></label>
    			</div>
    			<div class="col-sm-6">
    				<label><i class="ti-email preview-icon email mrg-r-10"></i><?php echo $user->email;?></label>
    			</div>
    			<div class="col-sm-6">
    				<label><i class="ti-location-pin preview-icon birth mrg-r-10"></i><?php echo $user->address;?></label>
    			</div>
    			<div class="col-sm-6">
    				<label><i class="ti-world preview-icon web mrg-r-10"></i><?php echo $countryName;?> - <?php echo $user->zipCode;?></label>
    			</div>
		    </div>
	    </div>
	</div>
</div>
					
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="heading">
			<h2>Join <span>Ritevet</span></h2>
			<!--<p>First, you should register now to can access services.</p>-->
			<p>Connecting immediately with a licensed veterinarian, find free stuff, pet store and other pet services on request.</p>
		</div>
	</div>
</div>
<div class="sec-bt">
	<div class="row">
		<?php if (empty($this->request->getSession()->read('RitevetUsers.usersinformation.UTYPE'))) { ?>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'veterinarian-register']); ?>">
                    <img src="/assets/img/vet.png" width="250">
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'other_pet_service_register']); ?>">
                    <img src="/assets/img/other.png" width="250">
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'pet_parent_register']); ?>">
                    <img src="/assets/img/parent.png" width="250">
                </a>
            </div>
        <?php } ?>
			
        <?php if (!empty($this->request->getSession()->read('RitevetUsers.usersinformation.UTYPE'))) { ?>
            <div style="margin:50px 0 20px 0" class="col-lg-12 col-md-12 col-sm-6">
                <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'request']); ?>">
                    <img src="/assets/img/request.png" width="250">
                </a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <a href="<?php echo $this->Url->build(['controller' => 'Freestaffs', 'action' => 'index']); ?>">
                    <img src="/assets/img/free.png" width="250">
                    <!--<img src="/assets/img/free_2.png" width="250">-->
                </a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'index']); ?>">
                    <img src="/assets/img/store.png" width="250">
                    <!--<img src="/assets/img/store_2.png" width="250">-->
                </a>
            </div>
        <?php } ?>
	</div>
</div>