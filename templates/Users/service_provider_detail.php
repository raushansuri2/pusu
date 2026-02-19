<?php 
$aY = array();
for($i=date('Y'); $i<= date('Y')+15; $i++){
  $aY[$i] = $i;
}
?>

<link href="<?php echo $this->Url->build('/');?>css/datepicker/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?php echo $this->Url->build('/');?>css/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
.myclasse::-webkit-input-placeholder {color: #ff0000;}
/*---------------------------Card Option------------------------------*/
.modal-dialog {
    position: absolute;
    top: 5%;
    left: 30%;
}
.detail-check li {
    margin-bottom: 10px; 
    padding: 10px; 
}
.calendar {
    width: 100%;
    margin: 0 auto;
}
.calendar table {
    width: 100%;
    border-collapse: collapse;
}
.calendar th,
.calendar td {
    text-align: center;
    padding: 10px;
    border: 1px solid #ddd;
    width: 14.28%; /* For equal width days */
}
.calendar th {
    background-color: #f0f0f0;
    font-weight: bold;
}
.calendar td {
    height: 50px; /* Adjust height as needed */
}
.available {
    background-color: #bbf7b3;
}
.not-available {
    background-color: #eee;
}
.calendar-header {
    padding: 0px;
    width: 100%;
}
.calendar-header select {
    width: 100%;
}
.calendar-header .nav-link {
    cursor: pointer;
}
.calendar-body {
    box-sizing: border-box;
    margin: 0px;
    min-width: 0px;
    padding-left: 7px;
    padding-bottom: 12px;
    padding-top: 8px;
    display: flex;
    flex-direction: row;
    width: 100%;
}
.calendar-body .form-check {
    box-sizing: border-box;
    margin: 0px;
    min-width: 0px;
    display: flex;
    flex-direction: row;
    width: 50%;
    align-items: center;
}
.calendar-body .form-check span{
    padding-left: 5px;
}
.avail{
    border-radius: 4px;
    box-sizing: border-box;
    margin: 0px;
    min-width: 0px;
    background-color: #bbf7b3;
    width: 16px;
    height: 16px;
}
.notavail{
    border-radius: 4px;
    box-sizing: border-box;
    margin: 0px;
    min-width: 0px;
    background-color: #eee;
    width: 16px;
    height: 16px;
}
</style>

<section class="detail-section bg-image" style="background:url('<?php echo $this->Url->build('/');?>assets/img/slider-3.jpg');" data-overlay="6">
    <div class="clearfix"></div>
	<div class="profile-cover-content">
		<div class="container">
			<div class="cover-buttons">
				<ul>
					<!--<li><div class="buttons medium button-plain "><i class="fa fa-phone"></i><?php //echo $users->VPhone;?></div></li>-->
					<li><div class="buttons medium button-plain "><i class="fa fa-map-marker"></i><?php echo $users->user->city->name;?>, <?php echo $users->user->state->name;?>, <?php echo $users->user->zipCode;?></div></li>
					<li><div title="rating" class="inside-rating buttons listing-rating theme-btn button-plain"><span class="value"><?php echo (@$users->user->AVGRating) ? @$users->user->AVGRating : '0';?>/5</sup></div></li>
					<?php if(@$users->userId != $this->request->getSession()->read('RitevetUsers.id')){?>
					<!--<li><a href="#add-review" class="buttons btn-outlined medium add-review"><span class="hidden-xs"><i class="fa fa-comments-o"></i> Add review</span></a></li>-->
					<!--<li><a href="#" data-listing-id="74" data-nonce="01a769d424" class="buttons btn-outlined"><i class="fa fa-heart-o"></i><span class="">Bookmark</span> </a></li>-->
					<?php } ?>
					
				</ul>
			</div>
			<div class="listing-owner hidden-xs hidden-sm">
				<div class="listing-owner-avater">
					<?php $PIMG = ($users->user->profile_picture !='') ? $this->Url->build('/').'img/uploads/users/'.$users->user->profile_picture : $this->Url->build('/').'img/dummy.jpg'; ?>
					<img src="<?php echo $PIMG;?>" class="img-responsive img-circle" alt="" />
				</div>
				<div class="listing-owner-detail">
					<h4><?php echo $users->user->firstName;?> <?php echo $users->user->lastName;?></h4>
					<span class="theme-cl">Pet Service Provider</span>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="list-detail">
	<div class="container">
	    <?php echo $this->Flash->render(); ?>
		<div class="row">
			<!-- Start: Listing Detail Wrapper -->
			<div class="col-md-8 col-sm-8">
				<div class="detail-wrapper">
					<div class="detail-wrapper-body">
						<div class="listing-title-bar">
							<h3><?php echo $users->user->firstName;?> <?php echo $users->user->lastName;?> <span class="mrg-l-5 category-tag">Pet Service Provider</span></h3>
							<div>
								<a href="#listing-location" class="listing-address">
									<i class="ti-location-pin mrg-r-5"></i>
									<?php echo $users->user->city->name;?>, <?php echo $users->user->state->name;?>, <?php echo $users->user->country->name;?>
								</a>
								<div class="rating-box">
									<div class="review-comment-stars">
										<?php for($R=1; $R<=5; $R++){
										if($R <= @$users->user->AVGRating){?>
											<i class="fa fa-star"></i>
										<?php }else{ ?>
											<i class="fa fa-star empty"></i>
										<?php } }?>
										&nbsp;<a href="#" class="detail-rating-count"><?php echo $totalReviewCount;?> Rating</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
							
				<div class="detail-wrapper">
					<div class="detail-wrapper-header">
						<h4>Overview</h4>
					</div>
					<div class="detail-wrapper-body">
						<?php echo $users->biography;?>
					</div>
				</div>
				
				<!-- SERVICES SECTION START-->			
				<div class="detail-wrapper">
					<div class="detail-wrapper-header">
						<h4>Type Of Services</h4>
					</div>
				    <div class="detail-wrapper-body">
                        <ul class="detail-check">
                            <?php $ESS = explode(",", $users->TypeOfService);?>
                            <?php foreach($ESS as $va){ ?>
                            <?php foreach($typeOfServices as $service){ ?>
                            <?php if ($service['id'] == $va){ ?>
                            <?php foreach($typeOfServicesRates as $rate){ ?>
                            <?php if ($rate['typeofservice_id'] == $va){ ?>
                            <li style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                            <?php echo $service['name'];?>
                            <div style="text-align: right;">
                              <span style="font-size: 18px;">$<?php echo round($rate['final_rate'],1);?></span>
                              <br>
                              <span style="font-size: 12px; color: #666;"><?php echo ($va != 16) ? $service['per'] : 'Per '.@$videoChatAvail[0]->time_slot_duration. ' Mins'; ?></span>
                            </div>
                          </li>
                        <?php } ?>
                        <?php } ?>
                        <?php } ?>
                        <?php } ?>
                        <?php } ?>
                      </ul>
                    </div>
				</div>
				<!-- SERVICES SECTION END-->
				
				<!-- Type Of Pets/Animals SECTION START-->
                <?php if (count($typeOfPets) > 0) { ?>
                    <div class="detail-wrapper">
                        <div class="detail-wrapper-header">
                            <h4>Type Of Pets/Animals</h4>
                        </div>
                        <div class="detail-wrapper-body">
                            <ul class="detail-check">
                                <?php 
                                $selectedPetIds = explode(",", $users->typeOfPets); 
                                foreach ($selectedPetIds as $petId) { 
                                    foreach ($typeOfPets as $pet) { 
                                        if ($pet['id'] == $petId) { ?>
                                            <li style="display: flex; flex-direction: column; width: 100%;">
                                                <h5 style="font-weight: bold;"><?= $pet['name'] ?></h5>
                                                <?php if ($pet['id'] == 1) { // Check if the pet ID is 1 (for dogs) ?>
                                                    <ul style="list-style: none; padding-left: 0;">
                                                        <?php 
                                                        if (!empty($users->dog_type)) { 
                                                            $dogTypes = explode(",", $users->dog_type);
                                                            foreach ($dogTypes as $type) { ?>
                                                                <li><?= ucwords(trim($type)) ?></li>
                                                            <?php } 
                                                        } ?>
                                                    </ul>
                                                <?php } ?>
                                            </li>
                                            <!--<hr>-->
                                        <?php } 
                                    } 
                                } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <!-- Type Of Pets/Animals SECTION END-->
				
				<!-- MORE INFO SECTION START-->
                <?php if (count($homeServicesInfo) > 0) {?>
                    <div class="detail-wrapper">
                        <div class="detail-wrapper-header">
                            <h4>Home Services Info</h4>
                        </div>
                        <div class="detail-wrapper-body">
                            <?php foreach ($homeServicesInfo as $homeService) {?>
                                <h5 style="font-weight: 600;"><?= $homeService->homeservicesquestion->question ?></h5>
                                <p>Answer: <?= ucwords($homeService->answer) ?></p>
                                <hr>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
				<!-- MORE INFO SECTION START-->
				<!-- REVIEWS SECTION START-->
				<div class="detail-wrapper">
				    <div class="detail-wrapper-header">
				        <h4><?= $this->Paginator->counter('{{count}}') ?> Review(s)</h4>
				    </div>
				    <div class="detail-wrapper-body">
				        <ul class="review-list">
				            <?php foreach ($reviews as $review): ?>
				                <li>
				                    <div class="reviews-box">
				                        <div class="review-body">
				                            <div class="review-avatar">
				                                <?php 
				                                $rImg = !empty($review->reviewfrom->profile_picture) 
				                                    ? $this->Url->build("/img/uploads/users/{$review->reviewfrom->profile_picture}") 
				                                    : $this->Url->build('/img/dummy.jpg');
				                                ?>
				                                <img alt="" src="<?= h($rImg) ?>" class="avatar avatar-140 photo">
				                            </div>
				                            <div class="review-content">
				                                <div class="review-info">
				                                    <div class="review-comment">
				                                        <div class="review-author">
				                                            <?= h(!empty($review->reviewfrom->firstName) 
				                                                ? "{$review->reviewfrom->firstName} {$review->reviewfrom->lastName}" 
				                                                : '-') ?>
				                                        </div>
				                                        <div class="review-comment-stars">
				                                            <?php for ($r1 = 1; $r1 <= 5; $r1++): ?>
				                                                <i class="fa fa-star<?= $r1 <= $review->star ? '' : ' empty' ?>"></i>
				                                            <?php endfor; ?>
				                                        </div>
				                                    </div>
				                                    <div class="review-comment-date">
				                                        <div class="review-date">
				                                            <span><?= h($review->created->format('M jS, Y, g:i a')) ?></span>
				                                        </div>
				                                    </div>
				                                </div>
				                                <p><?= h($review->message) ?></p>
				                            </div>
				                        </div>
				                    </div>
				                </li>
				            <?php endforeach; ?>
				        </ul>

				        <div class="paging-container" id="add-review">
				            <?php if ($this->Paginator->counter('{{count}}') != 0): ?>
				                <p><?= $this->Paginator->counter('<p class="records-showing">Showing {{start}} - {{end}} of {{count}} Record(s)</p>') ?></p>
				                <?php if ($this->Paginator->counter('{{pages}}') > 1): ?>
				                    <ul class="pagination">
				                        <?= $this->Paginator->prev(__('Previous'), ['tag' => 'li', 'escape' => false], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a', 'escape' => false]) ?>
				                        <?= $this->Paginator->numbers(['separator' => '', 'currentTag' => 'a', 'ellipsis' => '', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1]) ?>
				                        <?= $this->Paginator->next(__('Next'), ['tag' => 'li', 'escape' => false, 'currentClass' => 'disabled'], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a', 'escape' => false]) ?>
				                    </ul>
				                <?php endif; ?>
				                <div class="cl"></div>
				            <?php endif; ?>
				        </div>
				    </div>
				</div>
				<!-- REVIEWS SECTION END-->
				
				<!--ADD REVIEW START-->
				<?php if(@$users->userId != $this->request->getSession()->read('RitevetUsers.id') && @$requests != 0){?>			
				<div class="detail-wrapper" id="add-review">
					<div class="detail-wrapper-header">
						<h4>Rate & Write Reviews</h4>
					</div>
				    <div class="detail-wrapper-body">
						<?php echo $this->Form->create('User',array('type'=>'file','class'=>'listar-formtheme listar-formaddlisting','name'=>'pages','onsubmit'=>'return validatereview();'))?>
                        <p><strong>Rate This</strong></p>
                        <div class="star-rating">
							<span class="fa fa-star-o" data-rating="1"></span>
							<span class="fa fa-star-o" data-rating="2"></span>
						    <span class="fa fa-star-o" data-rating="3"></span>
							<span class="fa fa-star-o" data-rating="4"></span>
							<span class="fa fa-star-o" data-rating="5"></span>
							<input type="hidden" name="star" class="rating-value" value="2.56">
						</div><br />
						<div class="row">
							<div class="col-sm-12">
								<textarea name="message" id="message" class="form-control height-110" placeholder="Tell us your experience..."></textarea>
							</div>
							<div class="col-sm-12">
								<button type="submit" class="btn theme-btn">Submit your review</button>
							</div>
						</div>
						</form>
					</div>
				</div>
				<?php } ?>
				<!--ADD REVIEW END-->
			</div>
			<!-- End: Listing Detail Wrapper -->
			
			<!-- Sidebar Start -->
			<div class="col-md-4 col-sm-12">
				<div class="sidebar">
				    <!-- Start: Availability -->
					<div class="widget-boxed">
						<div class="widget-boxed-header">
							<h4><i class="ti-time padd-r-10"></i>Availability</h4>
						</div>
						<div class="widget-boxed-body">
							<div class="side-list" id="calendar">
							    <div class="mt-5">
                                    <div class="calendar">
                                        <div class="calendar-header">
                                            <select class="form-control" id="mySelect">
                                                <?php $ESS = explode(",", $users->TypeOfService);?>
                                                <?php foreach($ESS as $va){ ?>
                                                    <?php foreach($typeOfServices as $service){ ?>
                                                        <?php if ($service['id'] == $va){ ?>
                                                            <?php foreach($typeOfServicesRates as $rate){ ?>
                                                                <?php if ($rate['typeofservice_id'] == $va){ ?>
                                                                    <option value="<?php echo $service['id'];?>"><?php echo $service['name'];?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <h2><?php echo date("F Y"); ?></h2>
                                        <div class="calendar-body">
                                            <div class="form-check">
                                                <div class="avail"></div>
                                                <span class="form-check-avail">Available</span>
                                            </div>
                                            <div class="form-check">
                                                <div class="notavail"></div>
                                                <span class="form-check-not-avail">Not available</span>
                                            </div>
                                        </div>
                                        <table id="calendar-table">
                                            
                                        </table>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
					<!-- End: Availability -->
					
					<!-- Start: Book A Reservation -->
					<div class="widget-boxed">
						<div class="widget-boxed-header">
							<h4><i class="ti-calendar padd-r-10"></i>Book an Appointment </h4>
						</div>
						<!-- The button that triggers the modal -->
                        <button type="button" class="btn reservation btn-radius theme-btn full-width mrg-top-10" data-toggle="modal" data-target="#myModal" data-modal-title="" <?= (@$users->userId != $this->request->getSession()->read('RitevetUsers.id')) ? '' : 'disabled' ?>>Book an Appointment</button>
					</div>
				</div>
				<!-- End: Book A Reservation -->
			</div>
			<!-- End: Sidebar Start -->
		</div>
	</div>
</section>
<!-- The modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="font-weight: 700;">Contact <?php echo $users->user->firstName;?></h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'makeRequestToServiceProvider']);?>" method="post" id="requestForm">
                	<input type="hidden" name="_csrfToken" value="<?= $this->request->getAttribute('csrfToken') ?>">
                    <input name="serviceProviderId" value="<?php echo $users->id;?>" type="hidden">
                    <input name="userId" value="<?php echo $users->userId;?>" type="hidden">
					<input name="UTYPE" value="<?php echo $users->UTYPE;?>" type="hidden">
                    <div class="form-group">
                        <label for="selectOption">What a service? <span style="color:red;">*</span></label>
                        <select class="form-control" id="selectOption" name="service_id">
                            <option value="">Select an option</option>
                            <?php $ESS = explode(",", $users->TypeOfService);?>
                            <?php foreach($ESS as $va){ ?>
                                <?php foreach($typeOfServices as $service){ ?>
                                    <?php if ($service['id'] == $va){ ?>
                                        <?php foreach($typeOfServicesRates as $rate){ ?>
                                            <?php if ($rate['typeofservice_id'] == $va){ ?>
                                                <option value="<?php echo $service['id'];?>"><?php echo $service['name'];?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" id="frequency-div" style="display: none;">
                        <label class="d-block">Select the dates you need the service: <span style="color:red;">*</span></label>
                        <div class="d-inline-block" data-toggle="buttons">
                            <input type="radio" name="frequency" id="oneTime" autocomplete="off" value="Single-Day" onchange="frequencyChanged(this)"> Single-Day
                            <input type="radio" name="frequency" id="repeat" autocomplete="off" value="Multi-Day" onchange="frequencyChanged(this)" style="margin-left:20px;"> Multiple-Days
                        </div>
                    </div>
                    <div class="form-group" id="requestedServiceDateDiv" style="display: none;">
                        <label for="requestedServiceDate">Requested Service Date: <span style="color:red;">*</span></label>
                        <input type="text" id="requestedServiceDate" name="requestedServiceDate" autocomplete="off" class="form-control" placeholder="mm-dd-yyyy">
                    </div>
                    <div class="form-group" id="whatDaysDiv" style="display: none;">
                        <label style="display:block; margin-right: 10px;">What days? <span style="color:red;">*</span></label>
                        <!--<span style="display: inline-block; width: 46%;">-->
                            <input type="text" autocomplete="off" id="startDate" name="startDate" class="form-control" placeholder="mm-dd-yyyy">
                        <!--</span>-->
                        <!--<span style="display: inline-block; margin: 0 10px;">to</span>-->
                        <!--<span style="display: inline-block; width: 46%;"><input type="text" autocomplete="off" id="endDate" name="endDate" class="form-control" placeholder="mm-dd-yyyy"></span>-->
                    </div>
                    <div class="form-group" id="timeSlot-div" style="display: none;">
                        <label for="timeSlot">What time slot? <span style="color:red;">*</span> <span style="font-size:10px;color:red;">Enter requested service date first</span></label>
                        <select class="form-control" id="timeSlot" name="time_slot">
                            
                        </select>
                    </div>
                    <div class="form-group" id="petParentLocDiv" style="display: none;">
                        <label style="display:block; margin-right: 10px;">What's your address , District ,Governorate? <span style="color:red;">*</span></label>
                        <span style="display: inline-block; width: 56%;"><input type="text" autocomplete="off" id="address" name="address" class="form-control" placeholder="Enter an address"></span>
                        <span style="display: inline-block; margin: 0 10px;">Zip Code <span style="color:red;">*</span></span>
                        <span style="display: inline-block; width: 26%;"><input type="text" autocomplete="off" id="zipcode" name="zipcode" maxlength="5" class="form-control" placeholder="Enter a zip code"></span>
                    </div>
                    <div class="form-group" id="preferedTimes-div" style="display: none;">
                        <label for="pettype">Select one option for your preferred time: <span style="color:red;">*</span></label>
                        <!--<input type="text" autocomplete="off" id="prefere_times" name="prefere_times" class="form-control" placeholder="Enter a preferred times">-->
                        <select class="form-control" id="prefere_times" name="prefere_times">
                            <option value="">Select one option</option>
                            <option value="Morning Time: between 6 am and 12 pm">Morning Time: between 6 am and 12 pm</option>
                            <option value="Afternoon or evening time: between 1 pm and 10 pm">Afternoon or evening time: between 1 pm and 10 pm</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="sendRequest" onclick="validateAndSubmit()">Send Request</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="<?php echo $this->Url->build('/');?>js/datepicker/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo $this->Url->build('/');?>js/datepicker/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script>
	function validatereview()
	{
		if($('#star').val() == ''){
			alert('Please select start');
			$( "#star" ).focus();
			return false;
		}
		if($('#message').val() == ''){
			alert('Please Enter message');
			$( "#message" ).focus();
			return false;
		}
	}
</script>	
<script>
    var $star_rating = $('.star-rating .fa');

    var SetRatingStar = function() {
        return $star_rating.each(function() {
            if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
                return $(this).removeClass('fa-star-o').addClass('fa-star');
            } else {
                return $(this).removeClass('fa-star').addClass('fa-star-o');
            }
        });
    };

    $star_rating.on('click', function() {
        $star_rating.siblings('input.rating-value').val($(this).data('rating'));
        return SetRatingStar();
    });

    SetRatingStar();
</script>
<script>
  $(document).on('DOMContentLoaded', function() {
    $('#mySelect').trigger('change');
  });
</script>
<script>
    $(document).ready(function() {
        $('#mySelect').on('change', function() {
          $('.ajaxloader').fadeIn();
          var serviceId = $(this).val();
          var userId = '<?php echo @$users->userId;?>';
        //   console.log('Selected service ID:', serviceId);
          $.ajax({
            type: 'POST',
            url: "<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'getserviceworkdays']);?>",
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            data: {
              service_id: serviceId,
              user_id: userId
            },
            dataType: 'json', // Specify the data type as JSON
            success: function(response) {
              $('.ajaxloader').fadeOut();
            //   console.log('Response:', response);
              
              // Access the service_work_days property
              var serviceWorkDays = response.service_work_days.split(',');
            //   console.log('Service work days:', serviceWorkDays);
              
              if (serviceWorkDays) {
                // Create an array to store the available days of the week
                var availableDaysOfWeek = [];
                
                // Loop through the serviceWorkDays array
                serviceWorkDays.forEach(function(day) {
                //   console.log('Processing day:', day);
                  var dayAbbrev = day.trim(); // remove any whitespace
                  
                  // Map the day abbreviations to their corresponding day of the week
                  switch (dayAbbrev) {
                      case 'MON':
                        availableDaysOfWeek.push(1); // Monday
                        break;
                      case 'TUE':
                        availableDaysOfWeek.push(2); // Tuesday
                        break;
                      case 'WED':
                        availableDaysOfWeek.push(3); // Wednesday
                        break;
                      case 'THU':
                        availableDaysOfWeek.push(4); // Thursday
                        break;
                      case 'FRI':
                        availableDaysOfWeek.push(5); // Friday
                        break;
                      case 'SAT':
                        availableDaysOfWeek.push(6); // Saturday
                        break;
                      case 'SUN':
                        availableDaysOfWeek.push(0); // Sunday
                        break;
                    }
                });
                
                // Generate the calendar for the current month and year
                const table = document.getElementById('calendar-table');
                table.innerHTML = '';
                function generateCalendar(month, year) {
                  // Get the number of days in the month
                  const daysInMonth = getDaysInMonth(month, year);
                
                  // Create the table header
                  const thead = table.createTHead();
                  const headerRow = thead.insertRow();
                  ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'].forEach((day) => {
                    const th = document.createElement('th');
                    th.textContent = day;
                    headerRow.appendChild(th);
                  });
                
                  // Create the table body
                  const tbody = table.createTBody();
                
                  // Initialize the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                  let dayOfWeek = getDayOfWeek(month, year);
                
                  // Create the table rows and cells
                  let row = tbody.insertRow();
                  for (let i = 0; i < dayOfWeek; i++) {
                    const cell = row.insertCell();
                    cell.textContent = '';
                  }
                  for (let i = 1; i <= daysInMonth; i++) {
                    const cell = row.insertCell();
                    cell.textContent = i;
                    // Add class for available or not available days
                    if (isAvailable(i, month, year)) {
                      cell.classList.add('available');
                    } else {
                      cell.classList.add('not-available');
                    }
                    dayOfWeek = (dayOfWeek + 1) % 7;
                    if (dayOfWeek === 0 && i < daysInMonth) {
                      row = tbody.insertRow();
                    }
                  }
                  if (row.cells.length < 7) {
                    while (row.cells.length < 7) {
                      const cell = row.insertCell();
                      cell.textContent = '';
                    }
                  } else if (row.cells.length === 7 && dayOfWeek !== 0) {
                    const newRow = tbody.insertRow();
                    let newDayOfWeek = dayOfWeek;
                    while (newDayOfWeek < 7) {
                      const cell = newRow.insertCell();
                      cell.textContent = '';
                      newDayOfWeek++;
                    }
                  }
                }
                
                // Function to get the number of days in a month
                function getDaysInMonth(month, year) {
                  return new Date(year, month, 0).getDate();
                }
                
                // Function to get the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                function getDayOfWeek(month, year) {
                  return new Date(year, month - 1, 1).getDay();
                }
                
                // Function to check if a day is available
                function isAvailable(day, month, year) {
                  const date = new Date(year, month - 1, day);
                  const dayOfWeek = date.getDay();
                  // Check if the day is available based on the availableDaysOfWeek array
                  return availableDaysOfWeek.includes(dayOfWeek);
                }
                generateCalendar(new Date().getMonth() + 1, new Date().getFullYear());
              } else {
                console.log('serviceWorkDays is null or undefined');
              }
            }
          });
        });
    });
</script>
<script>
    jQuery(document).ready(function($) {
        var date = new Date();
        date.setDate(date.getDate() - 0);
        
        var disabledDaysForService        = <?php echo json_encode(@$disabledDaysForService); ?>;
        var disabledDaysForVirtualService = <?php echo json_encode(@$disabledDaysForVirtualService); ?>;
        
        $('#selectOption').on('change', function() {
            $('.ajaxloader').fadeIn();
            
            document.getElementById('oneTime').checked = false;
            document.getElementById('repeat').checked = false;
            document.getElementById('timeSlot').value = '';
            document.getElementById('timeSlot').innerHTML = '';
            document.getElementById('requestedServiceDate').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('address').value = '';
            document.getElementById('zipcode').value = '';
            document.getElementById('prefere_times').value = '';
            
            var selectedValue = $(this).val();
            var userId = '<?php echo @$users->userId; ?>';
            
            if(selectedValue == 16) {
                var disabledDays = disabledDaysForVirtualService;
            }else{
                var disabledDays = disabledDaysForService[selectedValue];
            }
            
            // console.log(disabledDays); // array of disabled days for the selected value
            
            document.getElementById('timeSlot-div').style.display = 'none';
            document.getElementById('petParentLocDiv').style.display = 'none';
            document.getElementById('preferedTimes-div').style.display = 'none';
            document.getElementById('whatDaysDiv').style.display = 'none';
            document.getElementById('frequency-div').style.display = 'none';
            document.getElementById('requestedServiceDateDiv').style.display = 'none';

            
            if (selectedValue == 8 || selectedValue == 9 || selectedValue == 16) {
                document.getElementById('requestedServiceDateDiv').style.display = 'block';
                
                if(selectedValue == 16) {
                    document.getElementById('timeSlot-div').style.display = 'block';
                }
                if(selectedValue == 8 || selectedValue == 9) {
                    document.getElementById('petParentLocDiv').style.display = 'block';
                    document.getElementById('preferedTimes-div').style.display = 'block';
                }
            } else if (selectedValue == 7 || selectedValue == 10 || selectedValue == 11 || selectedValue == 12 || selectedValue == 13 || selectedValue == 14 || selectedValue == 15) {
                if(selectedValue == 7 || selectedValue == 10 || selectedValue == 11 || selectedValue == 12 || selectedValue == 13 || selectedValue == 14 || selectedValue == 15) {
                    document.getElementById('petParentLocDiv').style.display = 'block';
                    document.getElementById('preferedTimes-div').style.display = 'block';
                }
                document.getElementById('frequency-div').style.display = 'block';
            } 
            
            // $('#requestedServiceDate').datetimepicker('remove'); // Remove the datetimepicker instance
            // $('#startDate').datetimepicker('remove'); // Remove the datetimepicker instance
            
            flatpickr("#requestedServiceDate", {
                minDate: "today",
                mode: "single",   // single - multiple - range
                dateFormat: "m-d-Y",
                allowInput: true,
                disable: [
                    function(date) {
                        // Get the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                        const day = date.getDay();
                        // Check if the day is in the enabledDays array
                        return disabledDays.includes(day);
                    }
                ]
            });
            
            flatpickr("#startDate", {
                minDate: "today",
                mode: "multiple",   // single - multiple - range
                dateFormat: "m-d-Y",
                allowInput: true,
                disable: [
                    function(date) {
                        // Get the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                        const day = date.getDay();
                        // Check if the day is in the enabledDays array
                        return disabledDays.includes(day);
                    }
                ]
            });
            
            $('.ajaxloader').fadeOut();
        });
    });
</script>
<script>
    const timeSlotSelect = document.getElementById('timeSlot');

    $("#requestedServiceDate").change(function() {
        // code to be executed when the onchange event is triggered
        console.log("onchange event triggered");
      
        const selectedDate = new Date(this.value);
        const dayIndex = selectedDate.getDay(); // 0-6 (Sunday-Saturday)
        const daysOfWeek = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
        const dayName = daysOfWeek[dayIndex];

        // console.log("Selected Date:", selectedDate.toDateString());
        // console.log("Day of the Week:", dayName);
        
        var userId = '<?php echo @$users->userId;?>';
        // Send AJAX request to get slots array
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'divideTimeIntoSlots']);?>",
            data: { day: dayName, userid : userId},
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                const slotsArray = (response);
                // console.log("Slots Array:", slotsArray);

                // Populate the select element with the slots array
                timeSlotSelect.innerHTML = '<option value="">Select time slot</option>'; // Reset the select element
                slotsArray.forEach(function(slot) {
                    const option = document.createElement("option");
                    option.value = slot;
                    option.text = slot;
                    timeSlotSelect.appendChild(option);
                });
            }
        });
    });
</script>
<script>
    function frequencyChanged(radio) {
        var selectedValue = radio.value;
        if (selectedValue == 'Single-Day') {
            document.getElementById('requestedServiceDate').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('requestedServiceDateDiv').style.display = 'block';
            document.getElementById('whatDaysDiv').style.display = 'none';
        } else if (selectedValue == 'Multi-Day') {
            document.getElementById('requestedServiceDate').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('whatDaysDiv').style.display = 'block';
            document.getElementById('requestedServiceDateDiv').style.display = 'none';
        }
    }
</script>
<script>
    function validateAndSubmit() {
        var selectOption = document.getElementById("selectOption");
        var timeSlot = document.getElementById("timeSlot");
        var frequencyDiv = document.getElementById("frequency-div");
        var requestedServiceDateDiv = document.getElementById("requestedServiceDateDiv");
        var whatDaysDiv = document.getElementById("whatDaysDiv");
        var timeSlotDiv = document.getElementById("timeSlot-div");
        var petParentLocDiv = document.getElementById('petParentLocDiv');
        var preferedTimesDiv = document.getElementById('preferedTimes-div');
        // var instantCall = '<?php echo @$users->instantCall;?>';

        if (selectOption.value == "") {
            alert("Please select a service.");
            return;
        }
        
        if (frequencyDiv.style.display != "none") {
            var frequency = document.querySelector('input[name="frequency"]:checked');
            if (!frequency) {
                alert("Please select the dates you need the service.");
                return;
            }
        }
        
        if (requestedServiceDateDiv.style.display != "none") {
            var requestedServiceDate = document.getElementById("requestedServiceDate").value;
            if (requestedServiceDate == "") {
                alert("Please enter a requested service date.");
                return;
            }

            // Validate that the requested service date is at least 24 hours from now
            if (selectOption.value !== "16") {
                var requestedDate = new Date(requestedServiceDate);
                var currentDate = new Date();
                var timeDifference = requestedDate - currentDate; // difference in milliseconds
                var hoursDifference = timeDifference / (1000 * 60 * 60); // convert to hours
                
                if (hoursDifference < 24) {
                    alert("You must request the service at least 24 hours in advance.");
                    return;
                }
            }
        }
        
        if (whatDaysDiv.style.display != "none") {
            var startDatesInput = document.getElementById("startDate").value;
            if (startDatesInput == "") {
                alert("Please enter days.");
                return;
            }

            // Split the input by commas and trim whitespace
            var startDatesArray = startDatesInput.split(',').map(date => date.trim());
            var lastValidDate = null; // To keep track of the last valid date

            for (let dateStr of startDatesArray) {
                var currentDate = new Date(dateStr);
                if (isNaN(currentDate.getTime())) {
                    alert(`Invalid date format: ${dateStr}. Please enter a valid date.`);
                    return;
                }

                // Check if the current date is earlier than the last valid date
                if (lastValidDate && currentDate < lastValidDate) {
                    alert(`The date ${dateStr} cannot be earlier than the previous date ${lastValidDate.toLocaleDateString()}.`);
                    return;
                }

                // Update the last valid date
                lastValidDate = currentDate;
            }

            // Check if the first date is at least 24 hours from now
            if (startDatesArray.length > 0) {
                var firstDate = new Date(startDatesArray[0]);
                var currentDate = new Date();
                var timeDifference = firstDate - currentDate; // difference in milliseconds
                var hoursDifference = timeDifference / (1000 * 60 * 60); // convert to hours

                if (hoursDifference < 24) {
                    alert(`You must request the service for ${firstDate.toLocaleDateString()} at least 24 hours in advance.`);
                    return;
                }
            }
        }
        
        if (timeSlotDiv.style.display != "none") {
            if (timeSlot.value == "") {
                alert("Please select a time slot.");
                return;
            }
            
            if (selectOption.value === "16") {
                var currentDate = new Date();
                var requestedDate = new Date(requestedServiceDate);
                var requestedDateString = requestedDate.toISOString().split('T')[0]; // Get requested date in YYYY-MM-DD format
                var today = new Date();
                today.setHours(0, 0, 0, 0); // Set to start of today
            
                // Check if requestedDate is today and provider can accept instant call
                if (requestedDate.getTime() === today.getTime()) {
                    // Prevent user from selecting time slots before now
                    var timeSlotParts = timeSlot.value.split(' - ');
                    var startTime = timeSlotParts[0].trim(); // Get the start time (e.g., "8:00 AM")
            
                    // Parse the start time 
                    var [time, modifier] = startTime.split(' ');
                    var [hours, minutes] = time.split(':').map(Number);
                    if (modifier === "PM" && hours < 12) {
                        hours += 12; // Convert PM hours to 24-hour format
                    }
                    if (modifier === "AM" && hours === 12) {
                        hours = 0; // Convert 12 AM to 0 hours
                    }
            
                    var selectedTime = new Date(requestedDate);
                    selectedTime.setHours(hours, minutes, 0, 0); // Set the time based on the selected time slot
            
                    // Check if the selected time is before the current time
                    if (selectedTime < currentDate) {
                        alert("You cannot select a time slot before the current time.");
                        return;
                    }
                }
            
                // Validate time slot for tomorrow
                var tomorrow = new Date();
                tomorrow.setDate(currentDate.getDate() + 1);
                tomorrow.setHours(0, 0, 0, 0); // Set to start of tomorrow
            
                if (requestedDateString === tomorrow.toISOString().split('T')[0]) {
                    var timeSlotParts = timeSlot.value.split(' - ');
                    var startTime = timeSlotParts[0].trim(); // Get the start time (e.g., "8:00 AM")
            
                    // Parse the start time
                    var [time, modifier] = startTime.split(' ');
                    var [hours, minutes] = time.split(':').map(Number);
                    if (modifier === "PM" && hours < 12) {
                        hours += 12; // Convert PM hours to 24-hour format
                    }
                    if (modifier === "AM" && hours === 12) {
                        hours = 0; // Convert 12 AM to 0 hours
                    }
            
                    var selectedTime = new Date(requestedDate);
                    selectedTime.setHours(hours, minutes, 0, 0); // Set the time based on the selected time slot
            
                    var validTimeSlot = new Date(currentDate.getTime() + 24 * 60 * 60 * 1000); // 24 hours from now
            
                    if (selectedTime < validTimeSlot) {
                        alert("You must select a time slot that is at least 24 hours from now.");
                        return;
                    }
                } else {
                    // Check if requestedDate is not today or tomorrow
                    var timeDifference = requestedDate - currentDate; // difference in milliseconds
                    var hoursDifference = timeDifference / (1000 * 60 * 60); // convert to hours
            
                    if (hoursDifference < 24) {
                        alert("You must request the service at least 24 hours in advance.");
                        return;
                    }
                }
            }
        }
        
        if (petParentLocDiv.style.display !== 'none') {
            const addressInput = document.getElementById('address');
            const zipcodeInput = document.getElementById('zipcode');
        
            const addressPattern = /^(?=.*[a-zA-Z])[a-zA-Z0-9\s,\.]{10,}$/; // min 10 chars, must contain at least one letter
            const zipcodePattern = /^[0-9]{5}$/; // 5-digit zipcode
        
            if (addressInput.value.trim() === '') {
                alert('Please enter an address');
                return;
            } else if (!addressPattern.test(addressInput.value.trim())) {
                alert('Invalid address format. Please enter a valid address that includes letters and is at least 10 characters long.');
                return;
            }
        
            if (zipcodeInput.value.trim() === '') {
                alert('Please enter a zipcode');
                return;
            } else if (!zipcodePattern.test(zipcodeInput.value.trim())) {
                alert('Please enter a 5-digit zipcode.');
                return;
            } else if (parseInt(zipcodeInput.value.trim(), 10) < 1) {
                alert('Invalid zipcode format.');
                return;
            }
        }
        
        if (preferedTimesDiv.style.display !== 'none') { 
            const prefere_times = document.getElementById('prefere_times');
            const timesInput = prefere_times.value.trim();
            
            if (timesInput === '') {
                alert('Please enter preferred times.');
                return;
            }
        }
        
        document.getElementById("requestForm").submit();
    }
</script>
<script>
    $('button[data-target="#myModal"]').on('click', function() {
      $('#requestForm').trigger('reset');
      $('#requestForm select option').prop('selected', false);
      $('#requestForm input[type="radio"]').prop('checked', false);
      $('#requestForm input[type="text"]').val('');
      $('#myModal div[style*="display: none"]').hide();
    });
</script>
<script>
    $(document).ready(function() {
        // Detect the user's timezone
        const userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        console.log("Timezone set successfully:", userTimeZone);
        // Append a hidden input field to the form
        $('#requestForm').append('<input type="hidden" name="timezone" value="' + userTimeZone + '">');
        // You can also use the following code to create the input field
        // var input = $('<input>').attr('type', 'hidden').attr('name', 'timezone').val(userTimeZone);
        // $('#requestForm').append(input);
    });
</script>
