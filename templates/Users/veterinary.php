
<style>
    .list-review{
        position:absolute;
        right:20px;
        bottom:20px;
        background:#dfd91a;
        padding:4px 12px;
        border-radius:3px;
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        line-height:1.3;
    }

    .active {
        background-color: #337ab7;
        color: #fff;
        border-color: #337ab7;
    }
    .serviceType label.active {
      background-color: #337ab7; /* or any other color you prefer */
      color: #fff;
      border-color: #337ab7;
    }
    
    .serviceType label.active:hover {
      background-color: #23527c; /* or any other color you prefer */
    }
    .looking{
        margin-bottom: 10px;
    }
    .serviceType{
        margin-left: 115px;
    }
    .btn-group-toggle .btn-secondary input[type="checkbox"]:checked ~ span {
        font-weight: bold;
    }
    .card {
        margin-bottom: 20px;
    }

    .card-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-check-label {
        margin-right: 20px;
    }

    .btn-group {
        margin-bottom: 20px;
    }

    .btn {
        margin-right: 10px;
    }
    .vertical-expand {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px; /* adjust the width as needed */
        height: auto;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="heading">
		    <?php if($this->request->getQuery('typeofbusiness') == 2) { ?>
			    <h2>Find Mobile Clinic <span>Near You</span></h2>
			<?php } else { ?>
			    <h2>Find <span>Virtual</span> Veterinarian</h2>
			<?php } ?>
		</div>
	</div>
</div>
<div class="row">
	<div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <h5 class="card-title looking">I'm looking for veterinarian for my:</h5>
                    <form method="get" action="/users/veterinary" onsubmit="return cleanForm(this);">
                        <input name="typeofbusiness" value="<?php echo $this->request->getQuery('typeofbusiness'); ?>" type="hidden">
                        <div class="form-group">
                            <?php foreach($typeOfPets as $typeOfPet){ ?>
                            <label class="form-check-label">
                                <input class="form-check-input" id="P_<?php echo $typeOfPet->id;?>" type="checkbox" name="petType[]" value="<?php echo $typeOfPet->id;?>" <?php if (isset($_GET['petType']) && is_array($_GET['petType']) && in_array($typeOfPet->id, $_GET['petType'])) {
                                    echo 'checked';
                                }?>> <?php echo $typeOfPet->name;?>
                            </label>
                            <?php } ?>
                        </div>
                        <?php if($this->request->getQuery('typeofbusiness') == 2) { ?>
            			    <div class="form-group text-center form-inline">
                                <label for="location" class="d-block">Service near</label>
                                <input type="text" class="form-control" id="location" autocomplete="off" name="zipCode" minlength="5" maxlength="5" placeholder="Zip code" value="<?php echo h(@$_GET['zipCode']); ?>">
                            </div>
            			<?php } ?>
                        <div class="form-group text-center form-inline" id="reqSerDateDiv">
                            <label class="d-block">Requested Service Date:</label>
                            <div class="form-row" style="display: inline;">
                                <div class="col" style="display: inline;">
                                    <input type="text" id="requestServiceDate" autocomplete="off" class="form-control" name="request_service_date" placeholder="Requested Service date" value="<?php echo h(@$_GET['request_service_date']); ?>">
                                </div>
                            </div>
                        </div>
                        <?php $dogSizes = @$_GET['dogSize']; ?>
                        <div class="form-group" id="dog-size-div" <?php echo (!empty($dogSizes) || (isset($_GET['petType']) && is_array($_GET['petType']) && in_array(1, $_GET['petType']))) ? '' : 'style="display: none;"'; ?>>
                            <label class="d-block">My Dog Size</label>
                            <?php $dogSizes = @$_GET['dogSize']; ?>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary <?php echo (is_array($dogSizes) && in_array('small', $dogSizes)) ? 'active' : ''; ?>">
                                    <input type="checkbox" name="dogSize[]" value="small" autocomplete="off" <?php echo (is_array($dogSizes) && in_array('small', $dogSizes)) ? 'checked' : ''; ?>>
                                    <span></span> Small: (Up to 20 pounds)
                                </label>
                                <label class="btn btn-secondary <?php echo (is_array($dogSizes) && in_array('medium', $dogSizes)) ? 'active' : ''; ?>">
                                    <input type="checkbox" name="dogSize[]" value="medium" autocomplete="off" <?php echo (is_array($dogSizes) && in_array('medium', $dogSizes)) ? 'checked' : ''; ?>>
                                    <span></span> Medium: (20 to 60 pounds)
                                </label>
                                <label class="btn btn-secondary <?php echo (is_array($dogSizes) && in_array('large', $dogSizes)) ? 'active' : ''; ?>">
                                    <input type="checkbox" name="dogSize[]" value="large" autocomplete="off" <?php echo (is_array($dogSizes) && in_array('large', $dogSizes)) ? 'checked' : ''; ?>>
                                    <span></span> Large: (60 to 100 pounds)
                                </label>
                                <label class="btn btn-secondary <?php echo (is_array($dogSizes) && in_array('giant', $dogSizes)) ? 'active' : ''; ?>">
                                    <input type="checkbox" name="dogSize[]" value="giant" autocomplete="off" <?php echo (is_array($dogSizes) && in_array('giant', $dogSizes)) ? 'checked' : ''; ?>>
                                    <span></span> Extra Large: (Over 100 pounds)
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="/users/veterinary?typeofbusiness=<?php echo $this->request->getQuery('typeofbusiness');?>" style="display: inline-block; height: 46px; background-color: #726d6d; color: #fff; padding: 10px 20px; border: none; cursor: pointer;" class="reset-button">Reset</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <?php $GG = 0;
    if (@$users) {
        foreach ($users as $user) {
            if ($GG % 3 == 0) { ?>
                <div class="col-lg-4 col-md-6 col-sm-12" style="clear:both">
                <?php } else { ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                <?php } $GG++; ?>
                <div class="property_item classical-list">
                    <div class="image">
                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'veterinarydetail', base64_encode($user->id), $this->request->getQuery('typeofbusiness')]); ?>" class="listing-thumb" target="_blank">
                            <?php $BIMG = ($user->user->profile_picture != '') ? $this->Url->build('/') . 'img/uploads/users/' . $user->user->profile_picture : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                            <img src="<?php echo $BIMG; ?>" alt="latest property" class="img-responsive">
                        </a>
                        <!--<div class="listing-price-info"> -->
                        <!--    <span class="pricetag">Featured</span>-->
                        <!--    <span class="pricetag">$25 - $65</span>-->
                        <!--</div>-->
                        <!--<a href="#" class="tag_t"><i class="ti-heart"></i>Save</a>-->
                        
                        <!--REVIEWS-->
                        <span class="list-review" title="reviews"><?php echo (isset($user->reviews) && is_array($user->reviews)) ? count($user->reviews) : '0'; ?></span>
                        <!--RATING-->
                        <?php if ($user->averagerating == 5) { ?>
                            <span class="list-rate good" title="rating"><?php echo ($user->averagerating) ? $user->averagerating : '0'; ?></span>
                        <?php } elseif ($user->averagerating > 4) { ?>
                            <span class="list-rate great" title="rating"><?php echo ($user->averagerating) ? $user->averagerating : '0'; ?></span>
                        <?php } else { ?>
                            <span class="list-rate medium" title="rating"><?php echo ($user->averagerating) ? $user->averagerating : '0'; ?></span>
                        <?php } ?>
                    </div>
                    <div class="proerty_content">
                        <div class="author-avater">
                            <?php $IMG = ($user->user->profile_picture != '') ? $this->Url->build('/') . 'img/uploads/users/' . $user->user->profile_picture : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                            <img src="<?php echo $IMG; ?>" class="author-avater-img" alt="">
                        </div>
                        <div class="proerty_text">
                            <h3 class="captlize">
                                <a target="_blank" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'veterinarydetail', base64_encode($user->id), $this->request->getQuery('typeofbusiness')]); ?>"><?php echo $user->user->firstName; echo $user->user->lastName; ?></a><span class="veryfied-author"></span>
                            </h3>
                        </div>
                        <p class="property_add">Veterinarian</p>
                        <div class="property_meta">
                            <div class="list-fx-features">
                                <div class="listing-card-info-icon">
                                    <span class="inc-fleat inc-add"><?php echo $user->user->address; ?></span>
                                </div>
                                <div class="listing-card-info-icon d-flex align-items-center">
                                    <span class="" style="color: #29af6a;font-size:26px;margin-left: 16px;">$<?php echo (@$this->request->getQuery('typeofbusiness')==2) ? @round($user->mobileavailability->total_price, 1) : @round($user->videochatavailability[0]->total_price, 1); ?></span>
                                    <span class="ml-2"><?php echo (@$this->request->getQuery('typeofbusiness')==2 ? 'Per Visit' : 'Per Slot'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php }
    } else { ?>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <p>No data found</p>
        </div>
    <?php } ?>
</div>
<!-- pagination links -->
<?php if(!empty($users)): ?>
    <div class="pagination">
        <ul class="pagination pagination-sm">
            <?php
                echo $this->Paginator->prev('Previous', ['tag' => 'li']);
                echo $this->Paginator->numbers(['tag' => 'li', 'currentClass' => 'active']);
                echo $this->Paginator->next('Next', ['tag' => 'li']);
            ?>
        </ul>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#requestServiceDate", {
        minDate: "today",
        mode: "single",   // single - multiple - range
        dateFormat: "Y-m-d",
        allowInput: true,
    });
</script>
<script>
    const mainCheckbox = document.getElementById('P_1');
    const dogSizeDiv = document.getElementById('dog-size-div');

    mainCheckbox.addEventListener('change', function() {
      if (mainCheckbox.checked) {
        dogSizeDiv.style.display = "block";
      } else {
        dogSizeDiv.style.display = "none";
        // Clear the checkboxes
        const checkboxes = dogSizeDiv.querySelectorAll("input[type='checkbox']");
        checkboxes.forEach(checkbox => {
          checkbox.checked = false;
          checkbox.parentNode.classList.remove("active");
        });
      }
    });
</script>
<script>
    $(document).ready(function() {
        $('input[type="checkbox"]').on('change', function() {
            $(this).closest('label').toggleClass('active', this.checked);
        });
    });
</script>
<script>
    var date = new Date();
	date.setDate(date.getDate()-0);
    $('#requestedServiceDate').datetimepicker({
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        startDate: date,
        format: 'yyyy-mm-dd',
        daysOfWeekDisabled: ['2019-10-10'],
        datesDisabled: ['2019-10-10']
    });
</script>
<script>
    function cleanForm(form) {
        // Remove unchecked checkboxes (except for dog size checkboxes)
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            // Only remove the name attribute for checkboxes that are not dog size checkboxes
            if (!checkbox.checked && !checkbox.name.includes("dogSize")) {
                checkbox.name = ''; // Remove the name attribute for unchecked checkboxes
            }
        });
    
        // Remove empty text inputs
        const textInputs = form.querySelectorAll('input[type="text"]');
        textInputs.forEach(input => {
            if (input.value.trim() === '') {
                input.name = ''; // Remove the name attribute for empty text inputs
            }
        });
    
        // Remove unchecked radio buttons
        const radioButtons = form.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => {
            if (!radio.checked) {
                radio.name = ''; // Remove the name attribute for unchecked radio buttons
            }
        });
    
        // Check if the checkbox with id "P_1" is checked
        var isPetTypeChecked = document.getElementById('P_1').checked;
        
        // Get all dog size checkboxes
        var dogSizeChecked = form.querySelectorAll('input[name="dogSize[]"]:checked');
        
        // Log the state of each dog size checkbox
        const dogSizeCheckboxes = form.querySelectorAll('input[name="dogSize[]"]');
        dogSizeCheckboxes.forEach(checkbox => {
            // console.log(`Checkbox ${checkbox.value} checked: ${checkbox.checked}`);
        });
    
        // If the pet type is checked and no dog size is selected, show an alert
        if (isPetTypeChecked && dogSizeChecked.length === 0) {
            alert("Please select at least one dog size.");
            return false; // Prevent form submission
        }
    
        return true; // Allow the form to submit
    }
</script>
				