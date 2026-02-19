<?php
$OPT = [
    1 => 'Shelter medicine',
    2 => 'Reptile and amphibian',
    3 => 'Exotic companion mammal',
    4 => 'Canine and feline',
    5 => 'Equine',
    6 => 'Fish (provisionally recognized March 2022)',
    7 => 'Food animal',
    8 => 'Dairy',
    9 => 'Swine health management',
    10 => 'Avian',
    11 => 'Beef cattle',
    12 => 'Feline',
    13 => 'Toxicology',
    14 => 'Cardiology',
    15 => 'Small animal internal medicine',
    16 => 'Large animal internal medicine',
    17 => 'Neurology',
    18 => 'Oncology',
    19 => 'Nutrition',
    20 => 'Virology',
    21 => 'Immunology',
    22 => 'Bacteriology/Mycology',
    23 => 'Parasitology',
    24 => 'Anatomic pathology',
    25 => 'Clinical pathology',
    26 => 'Epidemiology',
    27 => 'Radiation oncology',
    28 => 'Equine diagnostic imaging',
    29 => 'Canine',
    30 => 'Equine',
    31 => 'Small animal surgery',
    32 => 'Large animal surgery',
    33 => 'Equine dental'
];

$list = ['SAT' => 'SAT', 'SUN' => 'SUN', 'MON' => 'MON', 'TUE' => 'TUE', 'WED' => 'WED', 'THU' => 'THU', 'FRI' => 'FRI'];
?>

<style>
    .error {
        color: red;
        font-size: 12px;
    }
    .hidden {
        display: none;
    }
    #additionalCheckboxes {
        margin-left: 16px;
    }
    .serviceclass::-webkit-input-placeholder {
        color: #ff0000 !important;
    }
    .Red {
        color: #ff0000;
    }
    .green {
        color: #07B750 !important;
    }
    .vet-img {
        width: 100%;
        min-height: 115px;
        padding: 5px;
        border: 1px solid #CCC;
    }
    .img-delete {
        position: absolute;
        background: #000;
        color: #FFF;
        padding: 4px 10px;
        border-radius: 0px 0px 0px 9px;
        right: 15px;
        top: 0;
        display: none;
    }
    .img-delete a {
        color: #FFF;
    }
    .img-box:hover .img-delete {
        display: block !important;
    }
    .box {
        border: 1px solid #CCC;
        margin: 0 25px 20px 25px;
        border-radius: 9px;
        padding: 18px 0 21px 0;
    }
    #s2id_drop_down_weekoff {
        padding: 10px !important;
        height: auto;
    }
</style>

<link href="<?= $this->Url->build('/css/admin/select2.css') ?>" rel="stylesheet">

    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'veterinarianRegister'],
        'id' => 'myForm',
        'onsubmit'=>'return validatenew();', 
        'novalidate' => 'novalidate',
        'enctype' => 'multipart/form-data'
    ]) ?>
    <div class="col-md-10 col-sm-12 col-md-offset-1 mob-padd-0">
        <?= $this->Flash->render() ?>
        <input name="userId" value="<?= $this->request->getSession()->read('RitevetUsers.id') ?>" type="hidden">
        <input name="UTYPE" value="2" type="hidden">

        <!-- General Information -->
        <div class="add-listing-box edit-info mrg-bot-25 padd-bot-30 padd-top-25">
            <div class="listing-box-header">
                <div class="avater-box">
                    <?php $UIMG = ($UD->profile_picture != '') ? $this->Url->build('/') . 'img/uploads/users/' . $UD->profile_picture : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                    <img src="<?= $UIMG ?>" class="img-responsive img-circle edit-avater" alt="">
                    <div class ="upload-btn-wrapper">
                        <button class="btn theme-btn">Change Avatar</button>
                        <input type="file" name="profile_picture" id="profile_picture">
                    </div>
                </div>
                <h3><?= ucfirst($UD->firstName) . " " . ucfirst($UD->lastName) ?></h3>
                <p>Veterinarian</p>
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

        <!-- Veterinarian information -->
        <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
            <div class="listing-box-header">
                <h3>Veterinarian Licenses</h3>
            </div>
            <div class="row mrg-r-10 mrg-l-10">
                <div class="col-sm-12">
                    <a href="javascript:void(0);" id="clicktoAdd" class="pull-right">+ Add New License</a>
                </div>
                <?php $D = 0; 
                if (!empty($usersINfor->multilicenses)) {
                    foreach ($usersINfor->multilicenses as $VVM) { ?>
                        <div class="col-sm-12" id="<?= $D ?>">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Veterinary License Number <span class="mandatory">*</span></label>
                                    <?= $this->Form->control("LicenseNo[]", [
                                        'type' => 'number',
                                        'min' => 1,
                                        'value' => $VVM->licenceNo,
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter license number',
                                        'label' => false,
                                    ]); ?>
                                </div>
                                <div class="col-sm-6">
                                    <label>State <span class="mandatory">*</span></label>
                                    <?= $this->Form->control("stateId[]", [
                                        'options' => $stateList,
                                        'class' => 'form-control',
                                        'default' => $VVM->stateId ?? null,
                                        'empty' => 'Select State',
                                        'label' => false,
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <a href="<?= $this->Url->build('/users/deletelicence/' . $VVM->id) ?>" 
                               onclick="return confirm('Are you sure you want to delete this Veterinary License?');" 
                               style="color: #ff0000;">Remove</a>
                        </div>
                    <?php 
                        $D++;
                    }
                } else { ?>
                    <div class="col-sm-6">
                        <label>Veterinary License Number <span class="mandatory">*</span></label>
                        <?= $this->Form->control("LicenseNo[]", [
                            'type' => 'number',
                            'min' => 1,
                            'class' => 'form-control',
                            'placeholder' => 'Enter license number',
                            'label' => false,
                        ]); ?>
                    </div>
                    <div class="col-sm-6">
                        <label>State <span class="mandatory">*</span></label>
                        <?= $this->Form->control("stateId[]", [
                            'options' => $stateList,
                            'class' => 'form-control',
                            'default' => $users->stateId ?? null,
                            'empty' => 'Select State',
                            'label' => false,
                        ]); ?>
                    </div>
                <?php } ?>
                <div id="addM"></div>
            </div>
        </div>

        <div class="add-listing-box add-location mrg-bot-25 padd-bot-30 padd-top-25">
            <div class="listing-box-header">
                <h3>Business Information</h3>
            </div>
            <div class="row mrg-r-10 mrg-l-10">
                <div class="row mrg-r-10 mrg-l-10">
                    <p style="padding-top:13px;clear: both;">
                        <strong>Do you have American Board Certified specialization? <span class="mandatory">*</span></strong>
                    </p>
                    <div class="col-sm-4">
                        <label>&nbsp;</label>
                        <?= $this->Form->control('american_board_certified', [
                            'type' => 'select',
                            'options' => [0 => 'No', 1 => 'Yes'],
                            'class' => 'form-control',
                            'default' => $usersINfor->american_board_certified ?? null,
                            'label' => false,
                            'empty' => false,
                        ]); ?>
                    </div>
                    <div class="col-sm-8">
                        <label>If yes, select one or more specializations.</label>
                        <?php $isDisabled = ($usersINfor->american_board_certified ?? 0) == 1 ? false : true; ?>
                        <?= $this->Form->control('american_board_certified_option', [
                            'type' => 'select',
                            'options' => $OPT,
                            'multiple' => true,
                            'class' => 'form-control select2',
                            'default' => explode(",", $usersINfor->american_board_certified_option ?? ''),
                            'label' => false,
                            'disabled' => $isDisabled,
                        ]); ?>
                    </div>
                </div>
                <br>
                <div class="col-sm-12">
                    <label>Years in Business <span class="mandatory">*</span></label>
                    <?= $this->Form->number('YearInBusiness', [
                        'id' => 'YearInBusiness',
                        'class' => 'form-control',
                        'min' => 0,
                        'max' => 50,
                        'value' => h($usersINfor->YearInBusiness ?? ''),
                        'required' => true,
                        'placeholder' => 'Enter years in business (0-50)'
                    ]) ?>
                </div>
                <div class="row mrg-r-10 mrg-l-10">
                    <p style="padding-top:13px;clear: both;"><strong>Type Of Pets/Animals</strong> <span class="mandatory">*</span></p>
                    <p id="PET" style="color: #ff0000; display: none;">Please check at least one.</p>
                    <?php foreach ($typeOfPets as $typeOfPet) { ?>
                        <div class="col-sm-3">
                            <span class="custom-checkbox d-block">
                                <?php $checked2 = ''; if (in_array($typeOfPet->id, explode(",",$usersINfor->typeOfPets ?? ''))) { $checked2 = 'checked'; } ?>
                                <input type="checkbox" name="typeofPet[<?= $typeOfPet->id ?>]" value="<?= $typeOfPet->id ?>" class="PET" id="P_<?= $typeOfPet->id ?>" <?= $checked2 ?>>
                                <label for="P_<?= $typeOfPet->id ?>"></label><?= $typeOfPet->name; ?>
                            </span>
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
                <div class="row mrg-r-10 mrg-l-10">
                    <p style="padding-top:13px;clear: both;"><strong>Type of The Business <span class="mandatory">*</span></strong></p>
                    <p id="BT" style="color: #ff0000; display: none;">Please check at least one</p>
                    <?php $IIB = 1; $COUNTB = count($typeOfBusiness);
                        foreach ($typeOfBusiness as $typeOfBusine) {
                            if ($IIB % 2 != 0) { ?>
                                <div class="col-sm-6">
                            <?php } ?>
                            <span class="custom-checkbox d-block">
                                <?php $checked1 = ''; if (in_array($typeOfBusine->id, explode(",", $usersINfor->typeOfBusiness ?? ''))) { $checked1 = 'checked'; } ?>
                                <input type="checkbox" name="typeOfBusiness[]" value="<?= $typeOfBusine->id ?>" class="BUS" id="B_<?= $typeOfBusine->id ?>" <?= $checked1 ?>>
                                <label for="B_<?= $typeOfBusine->id ?>"></label><?= $typeOfBusine->name; ?>
                            </span>
                            <?php if ($IIB % 2 == 0 || $COUNTB == $IIB) { ?>
                                </div>
                            <?php } $IIB++;
                        } ?>
                </div>
            </div>
        </div>

        <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
            <div class="listing-box-header">
                <h3>Education and Licenses</h3>
            </div>
            <div class="row mrg-r-10 mrg-l-10">
                <p style="margin-left:20px;">Only files with the following extensions are allowed: .png, .jpg, .jpeg, .pdf.</p>
            </div>
            <div class="box">
                <div class="row mrg-r-10 mrg-l-10">
                    <div class="col-sm-12">
                        <label>Upload Veterinary College Transcript <span class="mandatory">*</span></label>
                        <p id="VETCT" style="color: #ff0000; display: none;"></p>
                    </div>
                    <?php 
                    $transcriptUploaded = false;
                    if (isset($usersINfor->images)) { 
                        foreach ($usersINfor->images as $vv2) {
                            if ($vv2->imageType == 'Transcript') {
                                $transcriptUploaded = true;
                                $filePath = '/img/uploads/multiimage/' . $vv2->image;
                                $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                ?>
                                <div style="margin-bottom:20px; position:relative" class="col-sm-2 img-box">
                                    <div class="img-delete">
                                        <a onclick="return confirm('Are you sure want to delete transcript?')" href="<?= $this->Url->build(['controller'=>'users','action'=>'imagedelete', $vv2->id]) ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                    <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                                        <img src="<?= $this->Url->build($filePath) ?>" class="vet-img" style="width: 100px; height: 100px;" />
                                    <?php } elseif ($fileExtension === 'pdf') { ?>
                                        <a href="<?= $this->Url->build($filePath) ?>" target="_blank">
                                            <img src="<?= $this->Url->build('/img/pdf-icon.png') ?>" class="vet-img" style="width: 100px; height: 100px;" />
                                        </a>
                                    <?php } else { ?>
                                        <span>Unsupported file type</span>
                                    <?php } ?>
                                </div>
                            <?php }
                        }
                    } ?>
                    
                    <div style="margin-top:10px;" class="col-sm-12">
                        <input type="file" id="uploadTranscript" name="uploadTranscript[]" multiple="multiple" style="padding: 13px; border-radius: 8px; font-size: 15px; background: #0342a1; text-align: center; color: #FFF;">
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="row mrg-r-10 mrg-l-10">
                    <div class="col-sm-12">
                        <label>Upload Veterinary License <span class="mandatory">*</span></label>
                        <p id="VETL" style="color: #ff0000; display: none;"></p>
                    </div>
                    <?php 
                    $licenseUploaded = false;
                    if (isset($usersINfor->images)) { 
                        foreach ($usersINfor->images as $vv2) {
                            if ($vv2->imageType == 'License') {
                                $licenseUploaded = true;
                                $filePath1 = '/img/uploads/multiimage/' . $vv2->image;
                                $fileExtension1 = strtolower(pathinfo($filePath1, PATHINFO_EXTENSION));
                                ?>
                                <div style="margin-bottom:20px; position:relative" class="col-sm-2 img-box">
                                    <div class="img-delete">
                                        <a onclick="return confirm('Are you sure want to delete license picture?')" href="<?= $this->Url->build(['controller'=>'users','action'=>'imagedelete', $vv2->id]) ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                    <?php if (in_array($fileExtension1, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                                        <img src="<?= $this->Url->build($filePath1) ?>" class="vet-img" style="width: 100px; height: 100px;" />
                                    <?php } elseif ($fileExtension1 === 'pdf') { ?>
                                        <a href="<?= $this->Url->build($filePath1) ?>" target="_blank">
                                            <img src="<?= $this->Url->build('/img/pdf-icon.png') ?>" class="vet-img" style="width: 100px; height: 100px;" />
                                        </a>
                                    <?php } else { ?>
                                        <span>Unsupported file type</span>
                                    <?php } ?>
                                </div>
                            <?php }
                        }
                    } ?>
                    
                    <div style="margin-top:10px;" class="col-sm-12">
                        <input type="file" id="uploadLicense" name="uploadLicense[]" multiple="multiple" style="padding : 13px; border-radius: 8px; font-size: 15px; background: #0342a1; text-align: center; color: #FFF;">
                    </div>
                </div>
            </div>
            <div class="box" id="BusinessLicense">
                <div class="row mrg-r-10 mrg-l-10">
                    <div class="col-sm-12">
                        <label>Upload mobile clinic business license No <span class="mandatory">*</span></label>
                        <p id="MCBL" style="color: #ff0000; display: none;"></p>
                    </div>
                    <?php 
                    $businessLicenseUploaded = false;
                    if (isset($usersINfor->images)) { 
                        foreach ($usersINfor->images as $vv2) {
                            if ($vv2->imageType == 'Business') {
                                $businessLicenseUploaded = true;
                                $filePath2 = '/img/uploads/multiimage/' . $vv2->image;
                                $fileExtension2 = strtolower(pathinfo($filePath2, PATHINFO_EXTENSION));
                                ?>
                                <div style="margin-bottom:20px; position:relative" class="col-sm-2 img-box">
                                    <div class="img-delete">
                                        <a onclick="return confirm('Are you sure want to delete Business picture?')" href="<?= $this->Url->build(['controller'=>'users','action'=>'imagedelete', $vv2->id]) ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                    <?php if (in_array($fileExtension2, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                                        <img src="<?= $this->Url->build($filePath2) ?>" class="vet-img" style="width: 100px; height: 100px;" />
                                    <?php } elseif ($fileExtension2 === 'pdf') { ?>
                                        <a href="<?= $this->Url->build($filePath2) ?>" target="_blank">
                                            <img src="<?= $this->Url->build('/img/pdf-icon.png') ?>" class="vet-img" style="width: 100px; height: 100px;" />
                                        </a>
                                    <?php } else { ?>
                                        <span>Unsupported file type</span>
                                    <?php } ?>
                                </div>
                            <?php }
                        }
                    } ?>
                    
                    <div style="margin-top:10px;" class="col-sm-12">
                        <input type="file" id="BImage" name="BImage[]" multiple="multiple" style="padding: 13px; border-radius: 8px; font-size: 15px; background: #0342a1; text-align: center; color: #FFF;">
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="row mrg-r-10 mrg-l-10">
                    <div class="col-sm-12">
                        <label>Upload Supporting Document:</label>
                        <p id="SDMIMES" style="color: #ff0000; display: none;"></p>
                    </div>
                    <?php 
                    if (isset($usersINfor->images)) { 
                        foreach ($usersINfor->images as $vv2) {
                            if ($vv2->imageType == 'Document') {
                                $filePath3 = '/img/uploads/multiimage/' . $vv2->image;
                                $fileExtension3 = strtolower(pathinfo($filePath3, PATHINFO_EXTENSION));
                                ?>
                                <div style="margin-bottom:20px; position:relative" class="col-sm-2 img-box">
                                    <div class="img-delete">
                                        <a onclick="return confirm('Are you sure want to delete document picture?')" href="<?= $this->Url->build(['controller'=>'users','action'=>'imagedelete', $vv2->id]) ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                    <?php if (in_array($fileExtension3, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                                        <img src="<?= $this->Url->build($filePath3) ?>" class="vet-img" style="width: 100px; height: 100px;" />
                                    <?php } elseif ($fileExtension3 === 'pdf') { ?>
                                        <a href="<?= $this->Url->build($filePath3) ?>" target="_blank">
                                            <img src="<?= $this->Url->build('/img/pdf-icon.png') ?>" class="vet-img" style="width: 100px; height: 100px;" />
                                        </a>
                                    <?php } else { ?>
                                        <span>Unsupported file type</span>
                                    <?php } ?>
                                </div>
                            <?php } 
                        }
                    } ?>
                    
                    <div style="margin-top:10px;" class="col-sm-12">
                        <input type="file" id="uploadDocument" name="uploadDocument[]" multiple="multiple" style="padding: 13px; border-radius: 8px; font-size: 15px; background: #0342a1; text-align: center; color: #FFF;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Availability START -->
        <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25" id="availability">
            <div id="listar-content" class="listar-content">
                <div class="listar-boxtitle" style="text-align: center;margin-bottom: 30px;">
                    <h3>Set Virtual Availability</h3>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <a href="javascript:void(0);" id="Addavailability" class="pull-right" style="margin-right:20px;">+ Add New</a>
                    </div>
                    <?php $k = 0; ?>
                    <?php if (isset($usersINfor->videochatavailability)) {
                        foreach ($usersINfor->videochatavailability as $avail) { ?>
                            <div class="col-sm-12">
                                <p id="AVAIL" style="color: #ff0000; display: none;">&nbsp;&nbsp;&nbsp;Please complete these fields.</p>
                                <div class="col-sm-6">
                                    <label> Working Start Hours <span class="mandatory">*</span></label>
                                    <input name="startTime[]" type="text" value="<?= date('h:i a', strtotime($avail->startTime)) ?>" id="startTime" class="TIme form-control" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label> Working End Hours <span class="mandatory">*</span></label>
                                    <input name="endTime[]" type="text" value="<?= date('h:i a', strtotime($avail->endTime)) ?>" id="endTime" class="TIme form-control" readonly>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <label>Working days <span class="mandatory">*</span></label>
                                    <select name="workdayarray[<?php echo $k; ?>][]" class="form-control" id="drop_down_weekon_<?php echo $k;?>" multiple>
                                        <?php foreach ($list as $option) { ?>
                                            <option value="<?= $option ?>" <?= $avail->{$option} == 1 ? 'selected' : '' ?>><?= $option ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Time slot duration (in minutes) <span class="mandatory">*</span></label>
                                    <?= $this->Form->control('time_slot_duration', [
                                        'options' => ['' => 'Select duration', '15' => '15', '30' => '30', '45' => '45', '60' => '60'],
                                        'default' => $avail->time_slot_duration,
                                        'class' => 'form-control',
                                        'id' => 'time_slot_duration',
                                        'label' => false,
                                    ]); ?>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <label>Price Per Slot ($) <span class="mandatory">*</span></label>
                                    <input name="price" type="number" min="1" value="<?= $avail->price ?? 0 ?>" id="price" class="form-control" onchange="calculateValue(this)">
                                    <span class="earn" id="earn" style="">The price will appear for pet parents as: $<?= round($avail->total_price ?? 0, 1) ?></span>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <a href="<?= $this->Url->build('/users/deleteAvailability/' . $avail->id) ?>" onclick="return confirm('Are you sure want to delete this availability?');" style="color: #ff0000;">Remove</a>
                                </div>
                            </div>
                            <?php $k++;
                        }
                    } else { ?>
                        <div class="col-sm-12">
                            <p id="AVAIL" style="color: #ff0000; display: none;">&nbsp;&nbsp;&nbsp;Please complete these fields.</p>
                            <div class="col-sm-6">
                                <label> Working Start Hours <span class="mandatory">*</span></label>
                                <input name="startTime[<?php echo $k; ?>]" type="text" id="startTime" class="TIme form-control" readonly >
                            </div>
                            <div class="col-sm-6">
                                <label> Working End Hours <span class="mandatory">*</span></label>
                                <input name="endTime[<?php echo $k; ?>]" type="text" id="endTime" class="TIme form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <label>Working days <span class="mandatory">*</span></label>
                                <select name="workdayarray[<?php echo $k; ?>][]" class="form-control" id="drop_down_weekon_<?php echo $k;?>" multiple>
                                    <?php foreach ($list as $option) { ?>
                                        <option value="<?= $option ?>"><?= $option ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Time slot duration (in minutes) <span class="mandatory">*</span></label>
                                <?= $this->Form->control('time_slot_duration', [
                                    'options' => ['' => 'Select duration', '15' => '15', '30' => '30', '45' => '45', '60' => '60'],
                                    'class' => 'form-control',
                                    'id' => 'time_slot_duration',
                                    'label' => false,
                                ]); ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <label>Price Per Slot ($) <span class="mandatory">*</span></label>
                                <input name="price" type="number" min="1" id="price" class="form-control" onchange="calculateValue(this)">
                                <span class="earn" id="earn" style="">The price will appear for pet parents as: $</span>
                            </div>
                        </div>
                    <?php } ?>
                    <div id="addA" class="col-sm-12"></div>
                </div>
            </div>
        </div>
        
        <!-- mobileAvailability -->
        <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25" id="mobileAvailability">
            <div id="listar-content" class="listar-content">
                <div class="listar-boxtitle" style="text-align: center;margin-bottom: 30px;">
                    <h3>Set Mobile Availability</h3>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p id="MobAVAIL" style="color: #ff0000; display: none;">&nbsp;&nbsp;&nbsp;Please select working days and price.</p>
                        <div class="col-sm-6">
                            <label>Working days <span class="mandatory">*</span></label>
                            <?= $this->Form->control('mobileworkdayarray', [
                                'options' => $list,
                                'default' => $mobileAvail->mobileworkdayarray ?? '',
                                'multiple' => true,
                                'class' => 'form-control',
                                'id'=>'drop_down_weekon',
                                'label' => false,
                            ]); ?>
                        </div>
                        <div class="col-sm-6">
                            <label>Price Per Visit ($) <span class="mandatory">*</span></label>
                            <input name="mobileprice" type="number" min="1" value="<?= round($mobileAvail->mobileprice ?? 0, 1) ?>" class="form-control" onchange="calculateValue(this)">
                            <span class="earn" id="earn" style="">The price will appear for pet parents as: $<?= round($mobileAvail->total_price ?? 0, 1) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bank Info-->
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

        <div class="col-sm-12">
            <div class="text-center">
                <button type="submit" class="btn theme-btn" title="Submit Listing">Submit</button>
            </div>
        </div>
    </div>
<?= $this->Form->end(); ?>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script src="<?php echo $this->Url->build('/');?>js/admin/select2.min.js"></script>
<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/timedropper.js"></script>

<script>
    jQuery("#american-board-certified-option").select2();
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
    
    $(document).ready(function() {
        $("#american-board-certified").on('change', function(){
            //alert($(this).val());
            if($(this).val() == '1'){
                $("#american-board-certified-option").attr('disabled',false);
            }else{
                $("#american-board-certified-option").attr('disabled',true);
            }
        });
    });
</script>
<script>
    var D = <?php echo json_encode($D); ?>; // Initialize D to the current number of licenses

    $(function() {
        $("#clicktoAdd").click(function(e) {
            D = parseInt(D) + 1;
            e.preventDefault();
    
            var html = '<div class="col-sm-12" id="row' + D + '">';
            html += '<div class="row">';
            html += '<div class="col-sm-6"><label>Veterinary License Number <span class="mandatory">*</span></label><input type="number" min="1" name="LicenseNo[]" id="VLicenseNo' + D + '" class="form-control" placeholder="Enter license number"></div>';
            html += '<div class="col-sm-6"><label>State <span class="mandatory">*</span></label><?php echo $this->Form->select("stateId[]", $stateList, ["class" => "form-control", "empty" => "Select State", "default" => $users->stateId]); ?></div>';
            html += '</div>';
            html += '<div class="col-sm-12"><a href="#" class="pull-left remove-license" data-id="' + D + '">- Remove license</a></div>';
            html += '</div>';
    
            $("#addM").append(html);
        });
    
        $(document).on("click", ".remove-license", function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            $("#row" + id).remove();
            D--; // Decrement D
        });
    });
</script>
<script>
    var K = <?php echo $k; ?>;
    var $selects = [];
    
    jQuery(document).ready(function($) { 
        $selects.push($('#drop_down_weekon_0').select2()); // the main select element
    
        $("#Addavailability").click(function(e) {
            K = parseInt(K) + 1;
            e.preventDefault();
    
            var html = '<div class="col-sm-12" id="' + K + '">';
            html += '<div class="col-sm-12"><div class="col-sm-6"><label>Working Start Hours <span class="mandatory">*</span></label><input name="startTime[' + K + ']" type="text" id="startTime' + K + '" class="TIme form-control td-input" readonly></div>';
            html += '<div class="col-sm-6"><label>Working End Hours <span class="mandatory">*</span></label><input name="endTime[' + K + ']" type="text" id="endTime' + K + '" class="TIme form-control" readonly></div></div>';
            html += '<div class="col-sm-12"><div class="col-sm-6"><label>Working Days <span class="mandatory">*</span></label><select name="workdayarray[' + K + '][]" class="form-control drop_down_weekon" id="drop_down_weekon_' + K + '" multiple><?php foreach ($list as $key => $value) { echo '<option value="' . $key . '">' . $value . '</option>'; } ?></select></div></div>';
            html += '<div class="col-sm-12"><a href="#" style="margin-right:20px;" class="clicktoREMOVE pull-right" data-id="' + K + '">- Remove</a></div>';
            html += '</div>';
    
            $("#addA").append(html);
            // $(".TIme").timeDropper();
            $("#startTime" + K).timeDropper();
            $("#endTime" + K).timeDropper();
            var $select = $("#drop_down_weekon_" + K).select2();
            
            $selects.push($select);
    
            $select.on("change", function(e) {
                var selectedValues = $(this).val();
                $.each($selects, function(index, select) {
                    if (select[0] !== $select[0]) {
                        $.each(selectedValues, function(index, value) {
                            select.find("option[value='" + value + "']").prop("disabled", true);
                        });
                    }
                });
            });
        });
    
        $('#drop_down_weekon_0').on("change", function(e) {
            var selectedValues = $(this).val();
            $.each($selects, function(index, select) {
                if (select[0] !== $(this)[0]) {
                    $.each(selectedValues, function(index, value) {
                        select.find("option[value='" + value + "']").prop("disabled", true);
                    });
                }
            });
        });
    
        $(document).on("click", ".clicktoREMOVE", function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            $("#" + id).remove();
            var $select = $("#drop_down_weekon_" + id);
            $selects.splice($selects.indexOf($select), 1);
            $.each($selects, function(index, select) {
                select.find("option").prop("disabled", false);
            });
        });
    });
</script>
<script>
     function validatenew(){
        $("#AVATARVALIDATE").hide();
        $("#BIOVALIDATE").hide();
        $("#BT").hide();
        $("#PET").hide();
        $("#SER").hide();
        $("#SPL").hide();
        $("#VETCT").hide();
        $("#VETL").hide();
        $("#MCBL").hide();
        $("#SDMIMES").hide();
        $("#AVAIL").hide();
        $('#MobAVAIL').hide();
        $("#DOG_TYPE").hide();
        
        var ErrorCount = 0;
        var focus = '';

        var fileUploaded = '<?php echo ($UD->profile_picture != '') ? 'true' : 'false'; ?>';
        var fileInput = document.getElementById('profile_picture');
        var filePath = fileInput.value;
        var maxFileSize = 2 * 1024 * 1024; // Set maximum file size to 2MB
        
        if (filePath === '' && fileUploaded === 'false') {
            $("#AVATARVALIDATE").text("Please select an image.").show();
            ErrorCount++;
            if (focus === '') {
                focus = 'profile_picture';
            }
        } else if (filePath !== '') {
            var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            if (!allowedExtensions.exec(filePath)) {
                $("#AVATARVALIDATE").text("Please select a valid image file (jpg, jpeg, png).").show();
                ErrorCount++;
                if (focus === '') {
                    focus = 'profile_picture';
                }
            } else {
                // Check file size
                var file = fileInput.files[0];
                if (file && file.size > maxFileSize) {
                    $("#AVATARVALIDATE").text("The file size exceeds the maximum limit of 2MB.").show();
                    ErrorCount++;
                    if (focus === '') {
                        focus = 'profile_picture';
                    }
                }
            }
        }
        
        // Check if bio is empty
        if ($("#BIO").val() === '') {
            $("#BIO").addClass('serviceclass');
            $("#BIO").attr("placeholder", "Please enter a bio.");
            ErrorCount++;
            if (focus === '') {
                focus = 'BIO';
            }
        } else if ($("#BIO").val().length < 50) {
            $("#BIO").addClass('serviceclass');
            $("#BIOVALIDATE").text("Bio must be at least 50 characters.").show();
            ErrorCount++;
            if (focus === '') {
                focus = 'BIO';
            }
        } else if (/^\d+$/.test($("#BIO").val())) {
            // Check if the bio consists only of numbers
            $("#BIO").addClass('serviceclass');
            $("#BIOVALIDATE").text("Bio cannot consist solely of numbers.").show();
            ErrorCount++;
            if (focus === '') {
                focus = 'BIO';
            }
        }
        
        // Check if Veterinary License Number is empty
        $("input[name='LicenseNo[]']").each(function(){
            if($(this).val() == ''){
                $(this).addClass('serviceclass');
                $(this).attr("placeholder", "Please Enter Veterinary License Number");
                ErrorCount++;
                if(focus ==''){
                    focus = $(this).attr('id');
                }
            }
        });
        
        // Check if State is empty
        $("select[name='stateId[]']").each(function(){
            if($(this).val() == ''){
                $(this).addClass('serviceclass');
                $(this).css("border", "1px solid #ff0000");
                $(this).attr("placeholder", "Please Select State");
                ErrorCount++;
                if(focus ==''){
                    focus = $(this).attr('id');
                }
            }else{
                $(this).removeClass('serviceclass');
                $(this).css("border", "");
            }
        });
        
        // Check if American Board Certified Option is selected only if American Board Certified is Yes
        if ($("select[name='american_board_certified']").val() == 1) {
            // Check if no options are selected in the select2
            if ($("select[name='american_board_certified_option[]']").val() == null || $("select[name='american_board_certified_option[]']").val().length === 0) {
                // Create or update the error message span
                let errorMessage = "Please Select Specialization.";
                let errorSpan = $("select[name='american_board_certified_option[]']").next("span.error-message");
        
                if (errorSpan.length === 0) {
                    // If the error span does not exist, create it
                    errorSpan = $("<span class='error-message' style='color: #ff0000;'></span>");
                    $("select[name='american_board_certified_option[]']").after(errorSpan);
                }
                errorSpan.text(errorMessage); // Set the error message text
        
                ErrorCount++;
                if (focus == '') {
                    focus = 's2id_autogen1'; // Adjust this if necessary to match your select2 ID
                }
            } else {
                // Remove the error message span if it exists
                $("select[name='american_board_certified_option[]']").next("span.error-message").remove();
            }
        }
        
        // Check if Years in Business is empty
        if($("#YearInBusiness").val() == ''){
            $("#YearInBusiness").addClass('serviceclass');
            $("#YearInBusiness").attr("placeholder", "Please Enter Years in Business");
            ErrorCount++;
            if(focus ==''){
                focus = 'YearInBusiness';
            }
        }
        
        if ($("#YearInBusiness").val() > 50) {
            $("#YearInBusiness").addClass('serviceclass');
            $("#YearInBusiness").attr("placeholder", "Please Enter Years in Business < 50");
            
            // Clear any existing error message
            $("#YearInBusiness").next("span.error").remove();
            
            // Create a new error message span
            $("<span class='error'>Please enter a value less than 50.</span>").insertAfter("#YearInBusiness");
            
            ErrorCount++;
            if (focus == '') {
                focus = 'YearInBusiness';
            }
        }
        
        // Check if Type Of Pet(s) is empty
        var PETchecked = false;
        $("input[name^='typeofPet']").each(function(){
            if($(this).is(':checked')){
                PETchecked = true;
            }
        });
        if(!PETchecked){
            $("#PET").show();
            ErrorCount++;
            if(focus ==''){
                focus = 'P_1';
            }
        }
        
        if ($('#P_1').is(':checked')) {
            var count_checked3 = $('input.DOG:checked').length;
            if (count_checked3 === 0) {
                $("#DOG_TYPE").show();
                ErrorCount++;
                if (focus === '') {
                    focus = 'P_1';
                }
            }
        }
        
        // Check if Type of The Business is empty
        var BTchecked = false;
        $("input[name^='typeOfBusiness']").each(function(){
            if($(this).is(':checked')){
                BTchecked = true;
            }
        });
        if(!BTchecked){
            $("#BT").show();
            ErrorCount++;
            if(focus ==''){
                focus = 'B_2';
            }
        }
        
        // Check if Veterinary College Transcript is empty
        var transcriptUploaded = <?php echo ($transcriptUploaded) ? 'true' : 'false'; ?>;
        
        if ($('input[name="uploadTranscript[]"]').val() == '' && !transcriptUploaded) {
            $("#VETCT").text("Please Upload Veterinary College Transcript.").show();
            ErrorCount++;
            if (focus == '') {
                focus = 'uploadTranscript';
            }
        } else if ($('input[name="uploadTranscript[]"]').val() != '') {
            var fileInput = $('input[name="uploadTranscript[]"]')[0];
            var filePath = fileInput.value;
            var fileName = filePath.split('\\').pop(); // get the file name with extension
            var fileExtension = fileName.split('.').pop(); // get the file extension
            var allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf']; // list of allowed extensions
        
            // Check file type
            if (allowedExtensions.indexOf(fileExtension.toLowerCase()) === -1) {
                $("#VETCT").text("Please select a valid image file (jpg, jpeg, png, pdf).").show();
                ErrorCount++;
                if (focus == '') {
                    focus = 'uploadTranscript';
                }
            } else {
                // Check file size
                var fileSize = fileInput.files[0].size; // size in bytes
                var maxSize;
        
                // Set max size based on file type
                if (['jpg', 'jpeg', 'png'].indexOf(fileExtension.toLowerCase()) !== -1) {
                    maxSize = 2 * 1024 * 1024; // 2MB for images
                } else if (fileExtension.toLowerCase() === 'pdf') {
                    maxSize = 5 * 1024 * 1024; // 5MB for PDF
                }
        
                // Check if file size exceeds the limit
                if (fileSize > maxSize) {
                    if (fileExtension.toLowerCase() === 'pdf') {
                        $("#VETCT").text("PDF file size must not exceed 5MB.").show();
                    } else {
                        $("#VETCT").text("Image file size must not exceed 2MB.").show();
                    }
                    ErrorCount++;
                    if (focus == '') {
                        focus = 'uploadTranscript';
                    }
                }
            }
        }
        
        // Check if Veterinary License is empty
        var licenseUploaded = <?php echo ($licenseUploaded) ? 'true' : 'false'; ?>;
        
        if ($('input[name="uploadLicense[]"]').val() == '' && !licenseUploaded) {
            $("#VETL").text("Please Upload Veterinary License.").show();
            ErrorCount++;
            if (focus == '') {
                focus = 'uploadLicense';
            }
        } else if ($('input[name="uploadLicense[]"]').val() != '') {
            var fileInput = $('input[name="uploadLicense[]"]')[0];
            var filePath = fileInput.value;
            var fileName = filePath.split('\\').pop(); // get the file name with extension
            var fileExtension = fileName.split('.').pop(); // get the file extension
            var allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf']; // list of allowed extensions
        
            // Check file type
            if (allowedExtensions.indexOf(fileExtension.toLowerCase()) === -1) {
                $("#VETL").text("Please select a valid image file (jpg, jpeg, png, pdf).").show();
                ErrorCount++;
                if (focus == '') {
                    focus = 'uploadLicense';
                }
            } else {
                // Check file size
                var fileSize = fileInput.files[0].size; // size in bytes
                var maxSize;
        
                // Set max size based on file type
                if (['jpg', 'jpeg', 'png', 'gif'].indexOf(fileExtension.toLowerCase()) !== -1) {
                    maxSize = 2 * 1024 * 1024; // 2MB for images
                } else if (fileExtension.toLowerCase() === 'pdf') {
                    maxSize = 5 * 1024 * 1024; // 5MB for PDF
                }
        
                // Check if file size exceeds the limit
                if (fileSize > maxSize) {
                    if (fileExtension.toLowerCase() === 'pdf') {
                        $("#VETL").text("PDF file size must not exceed 5MB.").show();
                    } else {
                        $("#VETL").text("Image file size must not exceed 2MB.").show();
                    }
                    ErrorCount++;
                    if (focus == '') {
                        focus = 'uploadLicense';
                    }
                }
            }
        }
        
        // Check if Business Picture is empty
        if ($("#BusinessLicense").css('display') != 'none') {
            var businessLicenseUploaded = <?php echo ($businessLicenseUploaded) ? 'true' : 'false'; ?>;
            
            if ($('input[name="BImage[]"]').val() == '' && !businessLicenseUploaded) {
                $("#MCBL").text("Please Upload mobile clinic business license No.").show();
                ErrorCount++;
                if (focus == '') {
                    focus = 'BImage';
                }
            } else if ($('input[name="BImage[]"]').val() != '') {
                var fileInput = $('input[name="BImage[]"]')[0];
                var filePath = fileInput.value;
                var fileName = filePath.split('\\').pop(); // get the file name with extension
                var fileExtension = fileName.split('.').pop(); // get the file extension
                var allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf']; // list of allowed extensions
        
                // Check file type
                if (allowedExtensions.indexOf(fileExtension.toLowerCase()) === -1) {
                    $("#MCBL").text("Please select a valid image file (jpg, jpeg, png, pdf).").show();
                    ErrorCount++;
                    if (focus == '') {
                        focus = 'BImage';
                    }
                } else {
                    // Check file size
                    var fileSize = fileInput.files[0].size; // size in bytes
                    var maxSize;
        
                    // Set max size based on file type
                    if (['jpg', 'jpeg', 'png'].indexOf(fileExtension.toLowerCase()) !== -1) {
                        maxSize = 2 * 1024 * 1024; // 2MB for images
                    } else if (fileExtension.toLowerCase() === 'pdf') {
                        maxSize = 5 * 1024 * 1024; // 5MB for PDF
                    }
        
                    // Check if file size exceeds the limit
                    if (fileSize > maxSize) {
                        if (fileExtension.toLowerCase() === 'pdf') {
                            $("#MCBL").text("PDF file size must not exceed 5MB.").show();
                        } else {
                            $("#MCBL").text("Image file size must not exceed 2MB.").show();
                        }
                        ErrorCount++;
                        if (focus == '') {
                            focus = 'BImage';
                        }
                    }
                }
            }
        }
        
        // Check if Supporting Document type
        if ($('input[name="uploadDocument[]"]').val() != '') {
            var fileInput = $('input[name="uploadDocument[]"]')[0];
            var filePath = fileInput.value;
            var fileName = filePath.split('\\').pop(); // get the file name with extension
            var fileExtension = fileName.split('.').pop(); // get the file extension
            var allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf']; // list of allowed extensions
        
            // Check file type
            if (allowedExtensions.indexOf(fileExtension.toLowerCase()) === -1) {
                $("#SDMIMES").text("Please select a valid image file (jpg, jpeg, png, pdf).").show();
                ErrorCount++;
                if (focus == '') {
                    focus = 'uploadDocument';
                }
            } else {
                // Check file size
                var fileSize = fileInput.files[0].size; // size in bytes
                var maxSize;
        
                // Set max size based on file type
                if (['jpg', 'jpeg', 'png'].indexOf(fileExtension.toLowerCase()) !== -1) {
                    maxSize = 2 * 1024 * 1024; // 2MB for images
                } else if (fileExtension.toLowerCase() === 'pdf') {
                    maxSize = 5 * 1024 * 1024; // 5MB for PDF
                }
        
                // Check if file size exceeds the limit
                if (fileSize > maxSize) {
                    if (fileExtension.toLowerCase() === 'pdf') {
                        $("#SDMIMES").text("PDF file size must not exceed 5MB.").show();
                    } else {
                        $("#SDMIMES").text("Image file size must not exceed 2MB.").show();
                    }
                    ErrorCount++;
                    if (focus == '') {
                        focus = 'uploadDocument';
                    }
                }
            }
        }
        
        
        // Check if Connect Direct is empty
        // var ConnectDirectChecked = false;
        // // var formData = new FormData();
        // $("input[name='MessageChat'], input[name='AudioChat']:visible, input[name='videoChat']:visible").each(function(){
        //     // formData.append($(this).attr('name'), $(this).val());
        //     if($(this).is(':checked')){
        //         ConnectDirectChecked = true;
        //     }
        // });
        // if(!ConnectDirectChecked){
        //     alert("Please select at least one Connect Direct option");
        //     ErrorCount++;
        //     if(focus ==''){
        //         focus = 'ConnectDirect';
        //     }
        // }
        
        // Check availability
        var AvailabilityChecked = false;
        var MobileAvailabilityChecked = false;
        
        if ($("#availability").is(":visible")) {
            var startTime = $("#startTime").val(); // e.g., "8:00 AM"
            var endTime = $("#endTime").val(); // e.g., "5:00 PM"
            var price = $("#price").val();
            var duration = $('#time_slot_duration').val();
        
            // Create an array of dropdown elements
            var workdayarrays = [
                $("#drop_down_weekon_0"),
                $("#drop_down_weekon_1"),
                $("#drop_down_weekon_2"),
                $("#drop_down_weekon_3"),
                $("#drop_down_weekon_4"),
            ];
        
            // Check if any dropdown exists and is not null
            var hasValidWorkday = workdayarrays.some(function (element) {
                return element.length > 0 && element.val() !== null && element.val() !== '';
            });
        
            // Validate that startTime and endTime are not empty
            if (startTime != '' && endTime != '' && hasValidWorkday && price != '0' && price != '' && duration != '') {
                // Validate time format (HH:MM AM/PM)
                var timeFormat = /^(0?[1-9]|1[0-2]):([0-5][0-9])\s*(AM|PM)$/i;
                console.log(startTime)
                console.log(endTime)
                if (timeFormat.test(startTime) && timeFormat.test(endTime)) {
                    // Create Date objects for comparison
                    var startDateTime = createDateFromTime(startTime);
                    var endDateTime = createDateFromTime(endTime);
        
                    // console.log(startDateTime);
                    // console.log(endDateTime);
                    // Check if endTime is before or equal to startTime
                    if (endDateTime <= startDateTime) {
                        $('#AVAIL').text('Working end hours cannot be before working start hours.').show();
                        ErrorCount++;
                        if (focus == '') {
                            focus = 'startTime';
                        }
                    } else {
                        AvailabilityChecked = true;
                    }
                } else {
                    $('#AVAIL').text('Invalid time format. Please use HH:MM AM/PM format.').show();
                    ErrorCount++;
                    if (focus == '') {
                        focus = 'startTime';
                    }
                }
            }
        
            if (!AvailabilityChecked) {
                $('#AVAIL').show();
                ErrorCount++;
                if (focus == '') {
                    focus = 'startTime';
                }
            }
        }
        
        if ($("#mobileAvailability").is(":visible")) {
            var workdayarray = $("#drop_down_weekon").val();
            var price        = $("#mobileprice").val();
            
            if(workdayarray != null && price != '0'){
                MobileAvailabilityChecked = true;
            }
            
            if(!MobileAvailabilityChecked){
                $('#MobAVAIL').show();
                ErrorCount++;
                if(focus ==''){
                    focus = 'drop_down_weekon';
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
        // BANK INFO
        // if ($("#ACName").val() === '' || $("#BankName").val() === '' || $("#AccountNo").val() === '' || $("#RoutingNo").val() === '' || $("#accountType").val() === '') {
        //     if ($("#PaypalEmail").val() === '' || $("#paypalAccount").val() === '') {
        //         $("#BANKINFO").show();
        //         ErrorCount++;
        //         focus = focus || 'ACName';
        //     }
        // }
        
        
        if(ErrorCount < 1){
            return true;
        }else{
            $("#" + focus).focus();
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
    $(document).ready(function() {
        var typeOfBusiness = '<?php echo $usersINfor->typeOfBusiness; ?>'.split(",");
    
        if (typeOfBusiness.includes('2') && typeOfBusiness.includes('3')) {
            // console.log("Both 2 and 3 are present")
            // Show all checkboxes
            $('#AudioChat').show();
            $('#videoChat').show();
            // $('#instantCallContainer').show();
            $('#availability').show();
            $('#BusinessLicense').show();
            $('#mobileAvailability').show();
        } else if (typeOfBusiness.includes('2')) {
            // console.log(2)
            // Hide the audio and video checkboxes
            $('#AudioChat').hide();
            $('#videoChat').hide();
            // $('#instantCallContainer').hide();
            $('#availability').hide();
            $('#BusinessLicense').show();
            $('#mobileAvailability').show();
        } else if (typeOfBusiness.includes('3')) {
            // console.log(3)
            // Show all checkboxes
            $('#AudioChat').show();
            $('#videoChat').show();
            // $('#instantCallContainer').show();
            $('#availability').show();
            $('#BusinessLicense').hide();
            $('#mobileAvailability').hide();
        }
        

        // Add an onchange event to the checkbox buttons
        $('input[name="typeOfBusiness[]"]').on('change', function() {
            $('.ajaxloader').fadeIn();
            // Check if the checkbox with value 2 is checked
            if ($(this).val() == '2' && this.checked) {
                if ($('input[name="typeOfBusiness[]"][value="3"]').is(':checked')) {
                    // If value 3 is already checked, keep all elements visible
                    $('#AudioChat').show();
                    $('#videoChat').show();
                    // $('#instantCallContainer').show();
                    $('#MessageChat').show(); // Show the MessageChat div
                    $('#BusinessLicense').show(); // Show the BusinessLicense div
                    $('#mobileAvailability').show();
                    $('#availability').show();
                } else {
                    $('#AudioChat').hide();
                    $('#videoChat').hide();
                    // $('#instantCallContainer').hide();
                    $('#MessageChat').show(); // Show the MessageChat div
                    $('#BusinessLicense').show(); // Show the BusinessLicense div
                    $('#mobileAvailability').show();
                    $('#availability').hide();
                }
            } else if ($(this).val() == '2' && !this.checked) {
                $('#BusinessLicense').hide();
                $('#mobileAvailability').hide();
            }
            
            // Check if the checkbox with value 3 is checked
            if ($(this).val() == '3' && this.checked) {
                if ($('input[name="typeOfBusiness[]"][value="2"]').is(':checked')) {
                    // If value 2 is already checked, keep all elements visible
                    $('#AudioChat').show();
                    $('#videoChat').show();
                    // $('#instantCallContainer').show();
                    $('#MessageChat').show(); // Show the MessageChat div
                    $('#BusinessLicense').show(); // Show the BusinessLicense div
                    $('#mobileAvailability').show();
                    $('#availability').show();
                } else {
                    $('#BusinessLicense').hide(); // Hide the BusinessLicense div
                    $('#mobileAvailability').hide();
                    $('#availability').show();
                    // $('#instantCallContainer').show();
                }
            } else if ($(this).val() == '3' && !this.checked) {
                $('#BusinessLicense').show(); // Show the BusinessLicense div
                $('#mobileAvailability').show();
                $('#availability').hide();
                // $('#instantCallContainer').hide();
                // $('#instantCall').prop('checked', false); // Uncheck the Instant Call checkbox
            }
            
            $('.ajaxloader').fadeOut();
        });
    });
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
    $(document).ready(function() {
        $('form').submit(function(event) {
            $(this).find('div[style*="display: none"]').find('input, select, span').prop('disabled', true);
        });
    });
</script>
<script>
    function calculateValue(input) {
        const rate = parseFloat(input.value);
        if (!isNaN(rate)) {
          const newValue = rate + (rate * 0.16);
          const earnElement = input.nextElementSibling;
          earnElement.textContent = "The price will appear for pet parents as: $" + newValue.toFixed(1);
          earnElement.style.display = "inline"; // Show the span element
        }
    }
</script>

