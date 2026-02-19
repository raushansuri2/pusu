<?php
// pr($usersINfor->images);exit;
//pr($_POST);exit;

$answerValues = array();
foreach ($homeServicesInfo as $info) {
    $answers = $info->answer;
    $answerValues[$info->question_id] = $answers;
}

function checkAnswer($homeServicesInfo, $questionId, $answer, $answerValues)
{
    foreach ($homeServicesInfo as $info) {
        $answers = $info->answer;

        if (!empty($answerValues) && isset($answerValues[$questionId]) && $answerValues[$questionId] === $answer) {
            return true;
        }
    }
    return false;
}

$finalRates = []; 
$rateValues = [];
$selectedWorkdays = [];
foreach ($rates as $rate) {
    $typeOfServiceId = $rate->typeofservice_id;
    $rateValues[$typeOfServiceId] = $rate->rate;
    $finalRates[$typeOfServiceId] = $rate->final_rate;
    $workdays = explode(',', $rate->service_work_days ?? '');
    $selectedWorkdays[$typeOfServiceId] = $workdays;
}

$list = ['SAT' => 'SAT', 'SUN' => 'SUN', 'MON' => 'MON', 'TUE' => 'TUE', 'WED' => 'WED', 'THU' => 'THU', 'FRI' => 'FRI'];

?>

<style>
    .serviceclass::-webkit-input-placeholder {
        color: #ff0000 !important;
    }
    .Red{color:#ff0000;}
    .green{color:#07B750 !important;}
    .rate {
        width: 43%;
    }
    .hidden {
      display: none;
    }
    #additionalCheckboxes{
        margin-left: 16px;
    }
</style>
<link href="<?php echo $this->Url->build('/');?>css/admin/select2.css" rel="stylesheet">

    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'otherPetServiceRegister'],
        'id' => 'myForm',
        'onsubmit'=>'return validatenew();', 
        'novalidate' => 'novalidate',
        'enctype' => 'multipart/form-data'
    ]) ?>
    <div class="col-md-10 col-sm-12 col-md-offset-1 mob-padd-0">
    	<?php echo $this->Flash->render(); ?>
    	<input name="userId" value="<?php echo $this->request->getSession()->read('RitevetUsers.id');?>" type="hidden">
    	<input name="UTYPE" value="3" type="hidden">
    	
        <!-- General Information -->
        <div class="add-listing-box edit-info mrg-bot-25 padd-bot-30 padd-top-25">
        	<div class="listing-box-header">
        		<div class="avater-box">
        			<?php
                    $UIMG = $UD->profile_picture ? 
                        $this->Url->build('/img/uploads/users/' . $UD->profile_picture) : 
                        $this->Url->build('/img/dummy.jpg');
                    ?> 
        			<img src="<?php echo $UIMG;?>" class="img-responsive img-circle edit-avater" alt="">
        			<div class="upload-btn-wrapper">
        				<button class="btn theme-btn">Change Avatar</button>
                        <?= $this->Form->file('profile_picture', ['id' => 'profile_picture']) ?>
        			</div>
        		</div>
        		<h3><?php echo ucfirst($UD->firstName)." ".ucfirst($UD->lastName);?></h3>
        		<p>Service Provider</p>
        		<p id="AVATARVALIDATE" style="color: #ff0000;"></p>
        	</div>
        	<div class="row mrg-r-10 mrg-l-10">
        		<div class="col-sm-12">
                    <label>Bio about you and your experience with pets: <span class="mandatory">*</span></label>
                    <?= $this->Form->textarea('biography', [
                        'id' => 'BIO',
                        'class' => 'form-control',
                        'style' => 'resize: none;',
                        'rows' => 5,
                        'value' => h($usersINfor->biography ?? ''),
                        'required' => true,
                        'placeholder' => 'Tell us about yourself and your experience with pets...'
                    ]) ?>
                    <p id="BIOVALIDATE" style="color: #ff0000;"></p>
                </div>
        	</div>
        </div>
        
        <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
            <div class="listing-box-header">
                <h3>Business information</h3>
                <!--<p>This information is needed to deposit your share of the company profit at the end of each year. If you are a virtual veterinarian your earning will be deposited to this account. </p>-->
            </div>
            <div class="row mrg-r-10 mrg-l-10">
                <div class="col-sm-12"><p>Please fill out the Business information</p></div>
    			<div class="row mrg-r-10 mrg-l-10">
                    <p style="padding-top:13px;clear: both;"><strong>Type Of Services <span class="mandatory">*</span></strong></p>
    				<p id="SER" style="color: #ff0000; display: none;">Please check at least one</p>
    				<?php $i = 0; foreach ($typeOfServices as $typeOfService) { ?>
                        <div class="col-sm-6">
                            <span class="custom-checkbox d-block">
                            <?php
                                // pr($rates);exit;
                                $checked1 = '';
                                if (isset($usersINfor->TypeOfService) && in_array($typeOfService->id, explode(",", $usersINfor->TypeOfService))) {
                                    $checked1 = 'checked';
                                }
                    
                                $typeOfServiceId = $typeOfService->id;
                                $submittedWorkdays = isset($_POST['serviceWorkday_' . $typeOfServiceId]) ? $_POST['serviceWorkday_' . $typeOfServiceId] : [];
                                $selectedDays = (isset($submittedWorkdays[$typeOfServiceId]) && $typeOfServiceId != 16) ? $submittedWorkdays[$typeOfServiceId] : ((isset($selectedWorkdays[$typeOfServiceId]) && $typeOfServiceId != 16) ? $selectedWorkdays[$typeOfServiceId] : []);
                                $rateValue = isset($rateValues[$typeOfServiceId]) ? $rateValues[$typeOfServiceId] : '';
                                $rateValue2 = isset($_POST['rate'][$typeOfServiceId]) ? $_POST['rate'][$typeOfServiceId] : '';
                                $finalRateValue = isset($finalRates[$typeOfServiceId]) ? number_format($finalRates[$typeOfServiceId], 1) : '';
                                $hasValue = (!empty($rateValue) || !empty($rateValue2)) && $typeOfServiceId != 16;
                                
                                //pr($selectedDays); exit;
                            ?>
                            <input type="checkbox" name="typeofService[<?php echo $typeOfService->id; ?>]" value="<?php echo $typeOfService->id; ?>" class="SER" id="S_<?php echo $typeOfService->id; ?>" <?php echo $checked1; ?>>
                            <label for="S_<?php echo $typeOfService->id; ?>"></label><?php echo $typeOfService->name; ?></span>
                            <input class="form-control rate" type="number" name="rate[<?php echo $typeOfService->id; ?>]" id="rate_<?php echo $typeOfService->id; ?>" style="<?php echo $hasValue ? 'display: inline;' : 'display: none;'; ?>" value="<?php echo !empty($rateValue) ? $rateValue : $rateValue2; ?>" min="1" placeholder="Enter a rate" onchange="calculateValue(this)">
                            <span class="type" id="type_<?php echo $typeOfService->id; ?>" style="<?php echo $hasValue ? 'display: inline;' : 'display: none;'; ?>">/ <?php echo $typeOfService->per; ?></span><br>
                            <span class="earn" id="earn_<?php echo $typeOfService->id; ?>" style="font-size: 12px; margin-left: 10px;<?php echo $hasValue ? 'display: inline;' : 'display: none;'; ?>">The price will appear for pet parents as: $<?php echo $finalRateValue; ?></span>
                            <p id="DESC_<?php echo $typeOfService->id; ?>" style="<?php echo !empty($selectedDays) ? 'display:block;' : 'display:none;'; ?>">Select your available days:</p>
                            <select name="serviceWorkday_<?php echo $typeOfService->id; ?>[]" class="form-control serviceDays" id="serviceWorkday_<?php echo $typeOfService->id; ?>" style="width:400px;<?php echo !empty($selectedDays) ? 'display:block;' : 'display:none;'; ?>" multiple>
                                <?php foreach ($list as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo in_array($key, $selectedDays) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                            <br>
                        </div>
                        <?php $i++; ?>
                    <?php } ?>
                </div>
    	      
                <div class="row mrg-r-10 mrg-l-10">
    			    <p style="padding-top:13px;clear: both;"><strong>Type Of Pets/Animals <span class="mandatory">*</span></strong></p>
    				<p id="PET" style="color: #ff0000; display: none;">Please check at least one.</p>
    				<?php foreach($typeOfPets as $typeOfPet){ ?>
    					<div class="col-sm-3">
    					    <span class="custom-checkbox d-block">
    					        <?php $checked2 = ''; if(isset($usersINfor->typeOfPets) && in_array($typeOfPet->id, explode(",", $usersINfor->typeOfPets))){ $checked2 = 'checked';} ?>
    					        <input type="checkbox" name="typeofPet[<?php echo $typeOfPet->id;?>]" value="<?php echo $typeOfPet->id;?>" class="PET" id="P_<?php echo $typeOfPet->id;?>" id="select<?php echo $typeOfPet->id;?>" <?php echo $checked2;?>>
    					        <label for="select<?php echo $typeOfPet->id;?>"></label><?php echo $typeOfPet->name;?></span>
    				    </div>
    				<?php } ?>
    				<?php
                        // Check if dog_type is set and not empty, then explode it into an array
                        $dogTypes = !empty($usersINfor->dog_type) ? explode(",", $usersINfor->dog_type) : [''];
                    
                        // Check if each dog type is in the array and set the checked variable accordingly
                        $checked3 = in_array('small', $dogTypes) ? 'checked' : '';
                        $checked4 = in_array('medium', $dogTypes) ? 'checked' : '';
                        $checked5 = in_array('large', $dogTypes) ? 'checked' : '';
                        $checked6 = in_array('giant', $dogTypes) ? 'checked' : '';
                    
                        // Determine the class for the div based on the type of pets
                        $divClass = !empty($usersINfor->typeOfPets) && in_array(1, explode(',', $usersINfor->typeOfPets)) ? '' : 'hidden';
                    ?>
    				<p id="DOG_TYPE" style="color: #ff0000; display: none;">&nbsp;&nbsp;&nbsp;What type of pets do you accept?</p>
    				<div id="additionalCheckboxes" class="<?php echo $divClass; ?>">
                        <label>
                          <input type="checkbox" class="DOG" name="dog_type[]" value="small" <?php echo $checked3;?>> Small: (Up to 20 pounds)
                        </label>
                        <br>
                        <label>
                          <input type="checkbox" class="DOG" name="dog_type[]" value="medium" <?php echo $checked4;?>> Medium: (20 to 60 pounds)
                        </label>
                        <br>
                        <label>
                          <input type="checkbox" class="DOG" name="dog_type[]" value="large" <?php echo $checked5;?>> Large: (60 to 100 pounds)
                        </label>
                        <br>
                        <label>
                          <input type="checkbox" class="DOG" name="dog_type[]" value="giant" <?php echo $checked6;?>> Extra Large: (Over 100 pounds)
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- This is availabilty div-->
    	<?php $availabilityClass = in_array(16, explode(",", $usersINfor->TypeOfService ?? '')) ? '' : 'display: none;'; ?>
    	<div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25" id="availability" style="<?php echo $availabilityClass; ?>">
    		<div id="listar-content" class="listar-content">
                <div class="listar-boxtitle" style="text-align: center;margin-bottom: 30px;">
                     <h3>Set video chat availability</h3>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <a href="javascript::" id="clicktoAdd" class="pull-right" style="margin-right:20px;">+ Add New</a>
                    </div>
                    <?php $k=0;?>
                    <?php if(isset($usersINfor->videochatavailability)) {
                        foreach($usersINfor->videochatavailability as $avail) { ?>
                            <?php if($k == 0) { ?>
                                <div class="col-sm-12">
                				    <p id="AVAILERROR" style="color: #ff0000; display: none;">&nbsp;&nbsp;&nbsp;Please fill in all required fields correctly.</p>
                                    <div class="col-sm-6">
                                      <label> Working Start Hours <span class="mandatory">*</span></label>
                                      <input name="startTime[<?php echo $k; ?>]" type="text" value="<?php echo date('h:i a',strtotime($avail->startTime));?>" id="startTime" class="TIme form-control" readonly>
                                    </div>
                                    <div class="col-sm-6">
                                      <label> Working End Hours <span class="mandatory">*</span></label>
                                      <input name="endTime[<?php echo $k; ?>]" type="text" value="<?php echo date('h:i a',strtotime($avail->endTime));?>" id="endTime" class="TIme form-control" readonly>
                                    </div>
                				</div>
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        <label>Working days <span class="mandatory">*</span></label>
                                        <select name="workdayarray[<?php echo $k; ?>][]" class="form-control" id="drop_down_weekon_<?php echo $k;?>" multiple>
                                            <?php foreach ($list as $option) { ?>
                                                <option value="<?php echo $option; ?>" <?php if ($avail->{$option} == 1) echo 'selected'; ?>><?php echo $option; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Time slot duration (in minutes) <span class="mandatory">*</span></label>
                                        <?php
                                        $timeSlotDuration = ['' => 'Select duration', '15' => '15', '30' => '30', '45' => '45', '60' => '60'];
                                        echo $this->Form->control('time_slot_duration', [
                                            'options' => $timeSlotDuration,
                                            'default' => isset($avail->time_slot_duration) && in_array($avail->time_slot_duration, ['15', '30', '45', '60']) ? $avail->time_slot_duration : '',
                                            'multiple' => false,
                                            'class' => 'form-control',
                                            'id' => 'time_slot_duration',
                                            'label' => false
                                        ]);
                                        ?>
                                    </div>
                				</div>
                				<div class="col-sm-12">
                				    <div class="col-sm-6">
                                        <label>Price Per Slot ($) <span class="mandatory">*</span></label>
                                        <input name="price" type="number" min="1" value="<?php echo $avail->price; ?>" id="price" class="form-control" onchange="calculateValue2(this)">
                                        <span class="earn" id="earn" style="">The price will appear for pet parents as: $<?php echo round($avail->total_price, 1); ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <!--<?php $instantCall = ''; if($usersINfor->instantCall == 1){ $instantCall = 'checked';} ?>-->
                                        <!--<div id="instantCallContainer" style="margin-top: 25px;font-weight: bold;font-size: 13px;">-->
                                        <!--    <span class="custom-checkbox d-block">-->
                                        <!--        <input type="checkbox" name="instantCall" id="instantCall" <?php echo $instantCall;?>>-->
                                        <!--        <label for="instantCall"></label>Instant Video Call-->
                                        <!--    </span>-->
                                        <!--</div>-->
                                    </div>
                				</div>
                				<div class="col-sm-12">
                				    <div class="col-sm-6">
                		                <a href="<?php echo $this->Url->build('/');?>users/deleteAvailability/<?php echo $avail->id;?>" onclick="return confirm('Are you sure want to delete this availability?');" style="color: #ff0000;">Remove</a>
                		            </div>
                		        </div>
                			<?php } else { ?>
                				<div class="col-sm-12">
                				    <p id="AVAILERROR" style="color: #ff0000; display: none;">&nbsp;&nbsp;&nbsp;Please fill in all required fields correctly.</p>
                                    <div class="col-sm-6">
                                      <label> Working Start Hours <span class="mandatory">*</span></label>
                                      <input name="startTime[<?php echo $k; ?>]" type="text" value="<?php echo date('h:i a',strtotime($avail->startTime));?>" id="startTime" class="TIme form-control" readonly>
                                    </div>
                                    <div class="col-sm-6">
                                      <label> Working End Hours <span class="mandatory">*</span></label>
                                      <input name="endTime[<?php echo $k; ?>]" type="text" value="<?php echo date('h:i a',strtotime($avail->endTime));?>" id="endTime" class="TIme form-control" readonly>
                                    </div>
                				</div>
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        <label>Working days <span class="mandatory">*</span></label>
                                        <select name="workdayarray[<?php echo $k; ?>][]" class="form-control" id="drop_down_weekon_<?php echo $k;?>" multiple>
                                            <?php foreach ($list as $option) { ?>
                                                <option value="<?php echo $option; ?>" <?php if ($avail->{$option} == 1) echo 'selected'; ?>><?php echo $option; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                				</div>
                				<div class="col-sm-12">
                				    <div class="col-sm-6">
                		                <a href="<?php echo $this->Url->build('/');?>users/deleteAvailability/<?php echo $avail->id;?>" onclick="return confirm('Are you sure want to delete this availability?');" style="color: #ff0000;">Remove</a>
                		            </div>
                		        </div>
                			<?php } ?>
                        <?php $k++;} ?>
                    <?php } else { ?>
        				<div class="col-sm-12">
        				    <p id="AVAILERROR" style="color: #ff0000; display: none;">&nbsp;&nbsp;&nbsp;Please fill in all required fields correctly..</p>
                            <div class="col-sm-6">
                              <label> Working Start Hours <span class="mandatory">*</span></label>
                              <input name="startTime[<?php echo $k; ?>]" type="text" id="startTime" class="TIme form-control" readonly>
                            </div>
                            <div class="col-sm-6">
                              <label> Working End Hours <span class="mandatory">*</span></label>
                              <input name="endTime[<?php echo $k; ?>]" type="text" id="endTime" class="TIme form-control" readonly>
                            </div>
        				</div>
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <label>Working days <span class="mandatory">*</span></label>
                                <select name="workdayarray[0][]" class="form-control" id="drop_down_weekon_0" multiple>
                                    <?php foreach ($list as $option) { ?>
                                        <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Time slot duration (in minutes) <span class="mandatory">*</span></label>
                                <?php
                                $timeSlotDuration = ['' => 'Select duration', '15' => '15', '30' => '30', '45' => '45', '60' => '60'];
                                echo $this->Form->control('time_slot_duration', [
                                    'options' => $timeSlotDuration,
                                    'default' => isset($avail->time_slot_duration) && in_array($avail->time_slot_duration, ['15', '30', '45', '60']) ? $avail->time_slot_duration : '',
                                    'multiple' => false,
                                    'class' => 'form-control',
                                    'id' => 'time_slot_duration',
                                    'label' => false
                                ]);
                                ?>
                            </div>
        				</div>
        				<div class="col-sm-12">
        				    <div class="col-sm-6">
                                <label>Price Per Slot ($) <span class="mandatory">*</span></label>
                                <input name="price" type="number" min="1" id="price" class="form-control" onchange="calculateValue2(this)">
                                <span class="earn" id="earn" style="">The price will appear for pet parents as: $</span>
                            </div>
                            <!--<div class="col-sm-6">-->
                            <!--    <div id="instantCallContainer" style="margin-top: 25px;font-weight: bold;font-size: 13px;">-->
                            <!--        <span class="custom-checkbox d-block">-->
                            <!--            <input type="checkbox" name="instantCall" id="instantCall">-->
                            <!--            <label for="instantCall"></label>Instant Video Call-->
                            <!--        </span>-->
                            <!--    </div>-->
                            <!--</div>-->
        				</div>
        			<?php } ?>
    				<div id="addM" class="col-sm-12">
    		
    	      		</div>
                </div>
            </div>
        </div>
    	<!-- END OF availabilty div-->
    	
        <!-- SRART OF PET HOME INFORMATIONS-->
        <?php //var_dump(in_array(11, explode(",",$usersINfor->TypeOfService))); exit;?>
        <?php $petInfoClass = in_array(7, explode(",", $usersINfor->TypeOfService ?? '')) || in_array(8, explode(",", $usersINfor->TypeOfService ?? '')) || in_array(9, explode(",", $usersINfor->TypeOfService ?? ''))
        || in_array(10, explode(",",$usersINfor->TypeOfService ?? '')) || in_array(11, explode(",",$usersINfor->TypeOfService ?? '')) || in_array(12, explode(",",$usersINfor->TypeOfService ?? ''))
        || in_array(14, explode(",",$usersINfor->TypeOfService ?? '')) || in_array(15, explode(",",$usersINfor->TypeOfService ?? '')) ? '' : 'display: none;'; ?>
        <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25" id="petInfo" style="<?php echo $petInfoClass; ?>">
    		<div id="listar-content" class="listar-content">
                <div class="listar-boxtitle" style="text-align: center;margin-bottom: 30px;">
                     <h3>Set home services info</h3>
                </div>
                <div class="row">
    				<div class="col-sm-12">
    				    <p id="QUES" style="color: #ff0000; display: none;">&nbsp;&nbsp;&nbsp;Please complete these questions.</p>
    				    <?php foreach($homeServicesQuestions as $ques) {?>
    				    <?php $questionValue = isset($_POST['answer_'.$ques->id]) ? $_POST['answer_'.$ques->id] : ''; ?>
                        <div class="col-sm-4">
                            <label><?php echo $ques->question; ?> <span class="mandatory">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer_<?php echo $ques->id; ?>" id="flexRadioDefault1" value="yes" <?php echo checkAnswer($homeServicesInfo, $ques->id, 'yes', $answerValues) || !empty($questionValue) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <?php echo $ques->option1; ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer_<?php echo $ques->id; ?>" id="flexRadioDefault2" value="no" <?php echo checkAnswer($homeServicesInfo, $ques->id, 'no', $answerValues) || !empty($questionValue) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    <?php echo $ques->option2; ?>
                                </label>
                            </div>
                        </div>
                        <?php } ?>
    				</div>
                </div>
            </div>
        </div>
        <!-- END OF PET HOME INFORMATIONS-->
        
        <!-- START OF UPLOAD ATTACHMENTS-->
        <?php $certClass = in_array(14, explode(",", $usersINfor->TypeOfService ?? '')) || in_array(15, explode(",",$usersINfor->TypeOfService ?? '')) 
        || in_array(16, explode(",",$usersINfor->TypeOfService ?? '')) ? '' : 'display: none;'; ?>
        <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25" id="CERT" style="<?php echo $certClass; ?>">
            <div class="listing-box-header">
                <h3>Upload information about your experience and/or certificates/licenses if available</h3>
            </div>
            <div class="row mrg-r-10 mrg-l-10">
                <p id="EXCERT" style="color: #ff0000; display: none;">&nbsp;&nbsp;&nbsp;&nbsp;Please upload your experience certificates.</p>
                <div class="col-sm-6">
                    <label>Experience Certificates <span class="mandatory">*</span></label>
                    <div class="" style="margin-bottom: 20px;">
                        <?php if(isset($usersINfor->images)){ 
                            foreach($usersINfor->images as $vv2){
                                if($vv2->imageType == 'EX_CERT'){ ?>
                                    <div style="margin-bottom:20px;" class="col-sm-3">
                                        <div class="img-delete">
                                            <a onclick="return confirm('Are you sure want to delete document?')" href="<?php echo $this->Url->build(['controller'=>'users','action'=>'imagedelete', $vv2->id]);?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                        <?php if (in_array(strtolower(pathinfo($vv2->image, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])) { ?>
                                            <img src="<?php echo $this->Url->build('/');?>img/uploads/multiimage/<?php echo $vv2->image;?>" 
                                                 class="vet-img" 
                                                 style="width: 100px; height: 100px;" />
                                        <?php } elseif (strtolower(pathinfo($vv2->image, PATHINFO_EXTENSION)) == 'pdf') { ?>
                                            <a href="<?php echo $this->Url->build('/');?>img/uploads/multiimage/<?php echo $vv2->image;?>" target="_blank">
                                                <img src="<?php echo $this->Url->build('/');?>img/pdf-icon.png" 
                                                     class="vet-img" 
                                                     style="width: 100px; height: 100px;" />
                                            </a>
                                        <?php } ?>
                                    </div>
                                <?php } 
                            }
                        } ?>
                    </div>
                    <input type="file" class="form-control" name="EX_CERT[]" id="exCertInput" multiple="multiple">
                </div>
            </div>
        </div>
        
        <!--BANK INFO-->
    	<div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
            <div class="listing-box-header">
                <h3>Add Bank Account Information</h3>
                <p style="width:81%; margin:0 auto;">You can skip this step, however you will need to enter your bank information later so we can deposit your earnings to it.</p>
            </div>
            <div class="col-sm-12">
                <p style="text-align: center;background: #0342a1;color: #FFF;padding: 7px 0px;margin-top: 20px;"><strong>Bank Account Information</strong></p>
            </div>
            <div class="col-sm-12">
                <p id="BANKINFO" style="color: #ff0000; display: none;">Please fill out the bank account information.</p>
            </div>
            <div class="row mrg-r-10 mrg-l-10">
                <div class="col-sm-6">
                    <label>Account Holder Name</label>
                    <?= $this->Form->text('ACName', [
                        'id' => 'ACName',
                        'class' => 'form-control',
                        'minlength' => 10,
                        'value' => h($usersINfor->ACName ?? ''),
                        'placeholder' => 'Enter account holder name'
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Bank Name</label>
                    <?= $this->Form->text('BankName', [
                        'id' => 'BankName',
                        'class' => 'form-control',
                        'value' => h($usersINfor->BankName ?? ''),
                        'placeholder' => 'Enter bank name'
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Account Number</label>
                    <?= $this->Form->number('AccountNo', [
                        'id' => 'AccountNo',
                        'class' => 'form-control',
                        'min' => 1,
                        'value' => h($usersINfor->AccountNo ?? ''),
                        'placeholder' => 'Enter account number'
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Swift number for accounts outside the US</label>
                    <?= $this->Form->number('swiftNumber', [
                        'id' => 'swiftNumber',
                        'class' => 'form-control',
                        'min' => 1,
                        'value' => h($usersINfor->swiftNumber ?? ''),
                        'placeholder' => 'Enter swift number'
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Routing Number</label>
                    <?= $this->Form->number('RoutingNo', [
                        'id' => 'RoutingNo',
                        'class' => 'form-control',
                        'min' => 1,
                        'value' => h($usersINfor->RoutingNo ?? ''),
                        'placeholder' => 'Enter routing number'
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Account Type</label>
                    <?= $this->Form->select('accountType', [
                        '' => 'Select an account type',
                        'Checking' => 'Checking',
                        'Savings' => 'Savings'
                    ], [
                        'id' => 'accountType',
                        'class' => 'form-control',
                        'value' => h($usersINfor->accountType ?? '')
                    ]) ?>
                </div>
            </div>
        </div>
        
    
        <!--<div class="col-sm-3"></div>-->
        <div class="col-sm-12 text-center">
            <button type="submit" class="btn theme-btn" title="Submit Listing">Submit</button>
        </div>
    </div>
<?php echo $this->Form->end();?>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script src="<?php echo $this->Url->build('/');?>js/admin/select2.min.js"></script>
<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/timedropper.js"></script>
<script>
    jQuery("#drop_down_weekon").select2();
	jQuery("#drop_down_weekon_0").select2();
	jQuery("#drop_down_weekon_1").select2();
	jQuery("#drop_down_weekon_2").select2();
	jQuery("#drop_down_weekon_3").select2();
	jQuery("#drop_down_weekon_4").select2();
	jQuery("#drop_down_weekon_5").select2();
	jQuery("#drop_down_weekon_6").select2();
	jQuery("#drop_down_weekon_7").select2();
	jQuery("#drop_down_weekon_8").select2();
	jQuery("#drop_down_weekon_9").select2();
	jQuery("#drop_down_weekon_10").select2();
</script>
<script>
    jQuery(document).ready(function($) {
        // Attach the serviceChange function to the onchange event of the checkboxes
        $('.SER').on('change', function(event) {
            serviceChange(event);
        });
    
        function serviceChange(event) {
            const checkbox = event.target;
            const rateInput = document.getElementById('rate_' + checkbox.value);
            const type = document.getElementById('type_' + checkbox.value);
            const earn = document.getElementById('earn_' + checkbox.value);
            const selectElement = document.getElementById('serviceWorkday_' + checkbox.value);
            const DESC = document.getElementById('DESC_' + checkbox.value);
    
            const checkboxValue = checkbox.value; // Use the value directly
            const availabilityDiv = document.getElementById('availability');
            const petInfoDiv = document.getElementById('petInfo');
            const certDiv = document.getElementById('CERT');
    
            // Manage visibility for availabilityDiv
            if (checkboxValue === '16') {
                availabilityDiv.style.display = checkbox.checked ? 'block' : 'none';
                $('#startTime').val(''); // Clear the value of the start time input
                $('#endTime').val('');   // Clear the value of the end time input
                $('#drop_down_weekon_0').val(null).trigger('change'); // Clear the selected value of the Select2 dropdown
                $('#time_slot_duration').val(''); // Clear the value of the time slot duration input
                $('#price').val('');     // Clear the value of the price input
                // $('#instantCall').prop('checked', false); // Uncheck the instant call checkbox
            } 
    
            // Manage visibility for petInfoDiv based on the state of checkboxes in the specified array
            const checkboxValuesToCheck = ['7', '8', '9', '10', '11', '12', '14', '15'];
            const anyCheckboxChecked = checkboxValuesToCheck.some(value => {
                const cb = document.querySelector(`input[type="checkbox"][value="${value}"]`);
                return cb && cb.checked;
            });

            petInfoDiv.style.display = anyCheckboxChecked ? 'block' : 'none';
    
            // Manage visibility for certDiv based on the state of checkboxes in the specified array
            const certCheckboxValues = ['14', '15', '16'];
            const anyCertCheckboxChecked = certCheckboxValues.some(value => {
                const cb = document.querySelector(`input[type="checkbox"][value="${value}"]`);
                return cb && cb.checked;
            });

            certDiv.style.display = anyCertCheckboxChecked ? 'block' : 'none';
    
            // Manage visibility for rateInput, type, earn, selectElement, and DESC
            if (checkbox.checked && checkboxValue !== '16') {
                rateInput.style.display = 'inline-block';
                type.style.display = 'inline';
                earn.style.display = 'inline';
                rateInput.disabled = false;
                rateInput.required = true; // Make rate input required
                selectElement.style.display = 'block'; // Show select element
                $(selectElement).select2(); // Initialize Select2
                selectElement.required = true; // Make select element required
                DESC.style.display = 'block';
            } else {
                // Hide elements when checkbox is not checked
                rateInput.style.display = 'none';
                type.style.display = 'none';
                earn.style.display = 'none';
                rateInput.disabled = true;
                rateInput.required = false; // Remove required attribute
                rateInput.value = ""; // Clear the rate input value
                
                // Hide select element and destroy Select2
                $(selectElement).select2('destroy'); // Destroy Select2 instance
                selectElement.style.display = 'none'; // Hide the original select element
                $(selectElement).val([]).trigger('change'); // Clear selection and trigger change
                selectElement.required = false; // Remove required attribute
                DESC.style.display = 'none';
            }
        }
    });
</script>
<script>
    function calculateValue(input) {
      const rate = parseFloat(input.value);
      if (!isNaN(rate)) {
        const newValue = rate + (rate * 0.16);
        const earnElement = document.getElementById("earn_" + input.id.split('_')[1]);
        earnElement.textContent = "The price will appear for pet parents as: $" + newValue.toFixed(1);
        earnElement.style.display = "inline"; // Show the span element
      }
    }
</script>
<script>
    function calculateValue2(input) {
        const rate = parseFloat(input.value);
        if (!isNaN(rate)) {
          const newValue = rate + (rate * 0.16);
          const earnElement = input.nextElementSibling;
          earnElement.textContent = "The price will appear for pet parents as: $" + newValue.toFixed(1);
          earnElement.style.display = "inline"; // Show the span element
        }
    }
</script>
<script>
    const mainCheckbox = document.getElementById('P_1');
    const additionalCheckboxes = document.getElementById('additionalCheckboxes');

    mainCheckbox.addEventListener('change', function() {
      if (mainCheckbox.checked) {
        additionalCheckboxes.classList.remove('hidden');
      } else {
        additionalCheckboxes.classList.add('hidden');
      }
    });
</script>
<script>
    // Define the function globally
    function validatenew() {
        var ErrorCount = 0;
        var focus = '';

        var fileUploaded = '<?php echo ($UD->profile_picture != '') ? 'true' : 'false'; ?>';
        var fileInput = document.getElementById('profile_picture');
        var filePath = fileInput.value;
        var maxFileSize = 2 * 1024 * 1024; // Set maximum file size to 2MB

        // Hide validation messages initially
        $("#AVATARVALIDATE, #BIOVALIDATE, #SER, #PET, #DOG_TYPE, #QUES, #AVAIL, #EXCERT, #BANKINFO").hide();

        // Validate profile picture
        if (filePath === '' && fileUploaded === 'false') {
            $("#AVATARVALIDATE").text("Please select an image.").show();
            ErrorCount++;
            focus = focus || 'profile_picture';
        } else if (filePath !== '') {
            var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            if (!allowedExtensions.exec(filePath)) {
                $("#AVATARVALIDATE").text("Please select a valid image file (jpg, jpeg, png).").show();
                ErrorCount++;
                focus = focus || 'profile_picture';
            } else {
                // Check file size
                var file = fileInput.files[0];
                if (file && file.size > maxFileSize) {
                    $("#AVATARVALIDATE").text("The file size exceeds the maximum limit of 2MB.").show();
                    ErrorCount++;
                    focus = focus || 'profile_picture';
                }
            }
        }

        // Validate bio
        var bioValue = $("#BIO").val();
        if (bioValue === '') {
            $("#BIO").addClass('serviceclass').attr("placeholder", "Please enter a bio.");
            ErrorCount++;
            focus = focus || 'BIO';
        } else if (bioValue.length < 50) {
            $("#BIO").addClass('serviceclass');
            $("#BIOVALIDATE").text("Bio must be at least 50 characters.").show();
            ErrorCount++;
            focus = focus || 'BIO';
        } else if (/^\d+$/.test(bioValue)) {
            $("#BIO").addClass('serviceclass');
            $("#BIOVALIDATE").text("Bio cannot consist solely of numbers.").show();
            ErrorCount++;
            focus = focus || 'BIO';
        }

        // Validate services
        var count_checked = $('input.SER:checked').length;
        if (count_checked === 0) {
            $("#SER").show();
            ErrorCount++;
            focus = focus || 'SER';
        }

        // Check required fields for selected services
        $('input.SER:checked').each(function() {
            var typeOfServiceId = $(this).val();
            var rateInput = document.getElementById(`rate_${typeOfServiceId}`);
            var selectElement = document.getElementById(`serviceWorkday_${typeOfServiceId}`);
            var errorMessageSpan = document.getElementById(`error_serviceWorkday_${typeOfServiceId}`);
        
            // Check for empty rate input
            if (rateInput && rateInput.value === '' && typeOfServiceId != 16) {
                rateInput.classList.add('serviceclass');
                rateInput.placeholder = "Enter a rate.";
                ErrorCount++;
                focus = focus || `rate_${typeOfServiceId}`;
            }
        
            // Check for unselected option in select element
            if (selectElement && selectElement.selectedOptions.length === 0 && typeOfServiceId != 16) {
                selectElement.classList.add('serviceclass');
        
                // Create or update the error message span
                if (!errorMessageSpan) {
                    errorMessageSpan = document.createElement('span');
                    errorMessageSpan.id = `error_serviceWorkday_${typeOfServiceId}`;
                    errorMessageSpan.className = 'error-message'; // Add a class for styling
                    selectElement.parentNode.insertBefore(errorMessageSpan, selectElement.nextSibling);
                }
                errorMessageSpan.textContent = "Please select your available days.";
                ErrorCount++;
                focus = focus || `serviceWorkday_${typeOfServiceId}`;
            } else {
                // Remove error message if selection is valid
                if (errorMessageSpan) {
                    errorMessageSpan.textContent = ""; // Clear the message
                    selectElement.classList.remove('serviceclass'); // Remove error class
                }
            }
        });

        // Validate pets
        var count_checked2 = $('input.PET:checked').length;
        if (count_checked2 === 0) {
            $("#PET").show();
            ErrorCount++;
            focus = focus || 'P_1';
        }

        if ($('#P_1').is(':checked')) {
            var count_checked3 = $('input.DOG:checked').length;
            if (count_checked3 === 0) {
                $("#DOG_TYPE").show();
                ErrorCount++;
                focus = focus || 'P_1';
            }
        }

        // Validate video availability
        var AvailabilityChecked = false;
        if ($("#availability").is(":visible")) {
            // Get input values and trim whitespace
            var startTime = $("#startTime").val().trim();
            var endTime = $("#endTime").val().trim();
            var price = $("#price").val().trim();
            var duration = $('#time_slot_duration').val().trim();
        
            // Create an array of dropdown elements
            var workdayarrays = [
                $("#drop_down_weekon_0"),
                $("#drop_down_weekon_1"),
                $("#drop_down_weekon_2"),
                $("#drop_down_weekon_3"),
                $("#drop_down_weekon_4"),
            ];
        
            // Check if any dropdown exists and is not null
            var hasValidWorkday = workdayarrays.some(function(element) {
                return element.length > 0 && element.val() !== null && element.val() !== '';
            });
        
            // Clear previous error messages
            $('#AVAILERROR').hide();
        
            // Validate that startTime and endTime are not empty
            if (startTime != '' && endTime != '' && hasValidWorkday && price != '0' && price != '' && duration != '') {
                // Validate time format (HH:MM AM/PM)
                var timeFormat = /^(0?[1-9]|1[0-2]):([0-5][0-9])\s*(AM|PM)$/i;
        
                if (timeFormat.test(startTime) && timeFormat.test(endTime)) {
                    // Create Date objects for comparison
                    var startDateTime = createDateFromTime(startTime);
                    var endDateTime = createDateFromTime(endTime);
        
                    console.log(startDateTime);
                    console.log(endDateTime);
                    // Check if endTime is before or equal to startTime
                    if (endDateTime <= startDateTime) {
                        $('#AVAILERROR').text('Working end hours cannot be before working start hours.').show();
                        ErrorCount++;
                        if (focus == '') {
                            focus = 'startTime';
                        }
                    } else {
                        AvailabilityChecked = true;
                    }
                } else {
                    $('#AVAILERROR').text('Invalid time format. Please use HH:MM AM/PM format.').show();
                    ErrorCount++;
                    if (focus == '') {
                        focus = 'startTime';
                    }
                }
            }
        
            // If availability is not checked, show error
            if (!AvailabilityChecked) {
                $('#AVAILERROR').show();
                ErrorCount++;
                focus = focus || 'startTime';
            }
        }

        // Validate questions
        if ($('#S_7').is(':checked') || $('#S_8').is(':checked') || $('#S_9').is(':checked') || $('#S_10').is(':checked') || $('#S_11').is(':checked') || $('#S_12').is(':checked') ||
            $('#S_14').is(':checked') || $('#S_15').is(':checked')) {
            var count_checked4 = $('input.form-check-input:checked').length;
            if (count_checked4 < 3) {
                $("#QUES").show();
                ErrorCount++;
                focus = focus || 'flexRadioDefault1';
            }
        }

        // Validate certificates
        var uploadedFile = '<?php echo $usersINfor->images[0]['imageType'];?>';
        if ($('#S_14').is(':checked') || $('#S_15').is(':checked') || $('#S_16').is(':checked')) {
            var fileInput = document.getElementById('exCertInput');

            // Check if no file is selected and uploadedFile is empty
            if (fileInput.files.length === 0 && uploadedFile === '') {
                $("#EXCERT").show();
                ErrorCount++;
                focus = focus || 'exCertInput';
            } else {
                // Validate file type and size
                var validImageTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                var validPdfType = 'application/pdf';
                var maxImageSize = 2 * 1024 * 1024; // 2 MB for images
                var maxPdfSize = 5 * 1024 * 1024; // 5 MB for PDFs

                for (var i = 0; i < fileInput.files.length; i++) {
                    var file = fileInput.files[i];
                    var fileType = file.type;
                    var fileSize = file.size;

                    // Check for valid file types
                    if (validImageTypes.includes(fileType)) {
                        // Validate image size
                        if (fileSize > maxImageSize) {
                            $("#EXCERT").text("Image size must be less than 2 MB.").show();
                            ErrorCount++;
                            focus = focus || 'exCertInput';
                        }
                    } else if (fileType === validPdfType) {
                        // Validate PDF size
                        if (fileSize > maxPdfSize) {
                            $("#EXCERT").text("PDF size must be less than 5 MB.").show();
                            ErrorCount++;
                            focus = focus || 'exCertInput';
                        }
                    } else {
                        // Invalid file type
                        $("#EXCERT").text("Invalid file type. Only PNG, JPG, JPEG, and PDF are allowed.").show();
                        ErrorCount++;
                        focus = focus || 'exCertInput';
                    }
                }
            }
        }
        
        var accountNo = $("#AccountNo").val();
        var routingNo = $("#RoutingNo").val();
        // Validate AccountNo
        if (accountNo !== '') {
            if (accountNo.length < 5 || accountNo.length > 17) {
                ErrorCount++;
                alert("Bank Account Number must be between 5 and 17 digits.");
                return false;
            }
        }
        
        // Validate RoutingNo
        if (routingNo !== '') {
            if (routingNo.length !== 9) {
                ErrorCount++;
                alert("Bank Routing Number must be exactly 9 digits.");
                return false;
            }
        }

        // if ($("#ACName").val() === '' || $("#BankName").val() === '' || $("#AccountNo").val() === '' || $("#RoutingNo").val() === '' || $("#accountType").val() === '') {
        //     if ($("#PaypalEmail").val() === '' || $("#paypalAccount").val() === '') {
        //         $("#BANKINFO").show();
        //         ErrorCount++;
        //         focus = focus || 'ACName';
        //     }
        // }

        // Return validation result
        if (ErrorCount < 1) {
            return true;
        } else {
            $("#" + focus).focus(); // Make sure the element with this ID exists
            return false;
        }
    }
    
    // Function to create a Date object from a 12-hour time format
    function createDateFromTime(time) {
        var parts = time.match(/(\d+):(\d+)\s*(AM|PM)/i);
        if (!parts) return null; // Invalid format
    
        var hours = parseInt(parts[1], 10);
        var minutes = parseInt(parts[2], 10);
        var period = parts[3].toUpperCase();
    
        // Convert to 24-hour format for Date object
        if (period === "PM" && hours < 12) {
            hours += 12; // Convert PM hours to 24-hour format
        }
        if (period === "AM" && hours === 12) {
            hours = 0; // Midnight case
        }
    
        // Create a Date object for comparison
        return new Date(1970, 0, 1, hours, minutes); // Use a fixed date (e.g., Jan 1, 1970)
    }
</script>
<script>
    var K = <?php echo $k; ?>;
    
    jQuery(document).ready(function($) { 
        $("#clicktoAdd").click(function(e) {
            K = parseInt(K) + 1;
            e.preventDefault();

            var html = '<div class="col-sm-12" id="' + K + '">';
            html += '<div class="col-sm-12"><div class="col-sm-6"><label>Working Start Hours <span class="mandatory">*</span></label><input name="startTime[' + K + ']" type="text" id="startTime' + K + '" class="TIme form-control td-input" readonly></div>';
            html += '<div class="col-sm-6"><label>Working End Hours <span class="mandatory">*</span></label><input name="endTime[' + K + ']" type="text" id="endTime' + K + '" class="TIme form-control" readonly></div></div>';
            html += '<div class="col-sm-12"><div class="col-sm-6"><label>Working Days <span class="mandatory">*</span></label><select name="workdayarray[' + K + '][]" class="form-control drop_down_weekon" id="drop_down_weekon_' + K + '" multiple><?php foreach ($list as $key => $value) { echo '<option value="' . $key . '">' . $value . '</option>'; } ?></select></div></div>';
            html += '<div class="col-sm-12"><a href="#" style="margin-right:20px;" class="clicktoREMOVE pull-right" data-id="' + K + '">- Remove</a></div>';
            html += '</div>';

            $("#addM").append(html);
            $("#startTime" + K).timeDropper();
            $("#endTime" + K).timeDropper();
            $("#drop_down_weekon_" + K).select2();
        });

        $(document).on("click", ".clicktoREMOVE", function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            $("#" + id).remove();
        });
    })
</script>
<script>
    jQuery(document).ready(function($) {
        $("#drop_down_weekon").select2();
    });
</script>
<script>
    jQuery("#serviceWorkday_7").select2();
    jQuery("#serviceWorkday_8").select2();
    jQuery("#serviceWorkday_9").select2();
    jQuery("#serviceWorkday_10").select2();
    jQuery("#serviceWorkday_11").select2();
    jQuery("#serviceWorkday_12").select2();
    jQuery("#serviceWorkday_13").select2();
    jQuery("#serviceWorkday_14").select2();
    jQuery("#serviceWorkday_15").select2();
    jQuery("#serviceWorkday_16").select2();
</script>