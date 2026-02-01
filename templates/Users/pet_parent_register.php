<?php
$YEAR = [];
for($IE=date('Y'); $IE<=date('Y')+11; $IE++){
$YEAR[$IE] = $IE;	
}
echo $this->Html->css(array('admin/jquery.fancybox'));
echo $this->Html->script(array('admin/jquery.fancybox.pack'));
?>

<?php echo $this->Form->create('Users', ['type' =>'file', 'novalidate'=>'novalidate', 'onsubmit'=>'return validatenew();']);?>
<div class="col-md-10 col-sm-12 col-md-offset-1 mob-padd-0">
	<?php echo $this->Flash->render(); ?>
    <!-- General Information -->
    <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
        <div class="listing-box-header">
            <h3>Pet & Parent Information</h3>
        </div>
        <input name="userId" value="<?php echo $this->request->session()->read('RitevetUsers.id');?>" type="hidden">
	    <input name="UTYPE" value="1" type="hidden">
        <div class="row mrg-r-10 mrg-l-10">
            <p style="text-align:center;">Name of Pet Parent:</p>
            <div class="col-sm-6">
                <label>First Name: <strong><?php echo $UD->firstName;?></strong></label>
                <!-- <input name="VFirstName" id="VFirstName" value="<?php echo @$usersINfor->VFirstName;?>" type="text" class="form-control"> -->
            </div>
            <div class="col-sm-6">
                <label>Last Name: <strong><?php echo $UD->lastName;?></strong></label>
                <!-- <input name="VLastName" id="VLastName" value="<?php echo @$usersINfor->VLastName;?>" type="text" class="form-control"> -->
            </div>
              
            <div class="row mrg-r-10 mrg-l-10">
			    <p style="padding-top:13px;clear: both;"><strong>Type of Pets (s)</strong></p>
			    <?php $II=1; $COUNT = count($typeOfPets);
			    foreach($typeOfPets as $typeOfPet){
				    if($II%2 != 0){?>
				        <div class="col-sm-3">
				    <?php } ?>
					<span class="custom-checkbox d-block">
					<?php $checked = ''; if(in_array($typeOfPet->id, explode(",",@$usersINfor->typeOfPets))){ $checked = 'checked';} ?>
					<input name="typeofPet[<?php echo $typeOfPet->id;?>]" value="<?php echo $typeOfPet->id;?>" id="" type="checkbox" id="select<?php echo $typeOfPet->id;?>" <?php echo $checked;?>>
					<label for="select<?php echo $typeOfPet->id;?>"></label><?php echo $typeOfPet->name;?> </span>
				    <?php if($II%2 ==0 || $COUNT == $II){ ?>
				        </div>
				    <?php } $II++;
			    }?>
			
            </div>
             
            <div class="col-sm-12">
                <label>Explain about the other animal</label>
                <input name="otherPet" value="<?php echo @$usersINfor->otherPet;?>" id="otherPet" type="text" class="form-control">
            </div>
        </div>
    </div>
        
    <?php /*
        <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
          <div class="listing-box-header">
            <h3>Credit card info</h3>
            <!--<p>This information is needed to deposit your share of the company profit at the end of each year. If you are a virtual veterinarian your earning will be deposited to this account. </p>-->
          </div>
	  
            <div class="row mrg-r-10 mrg-l-10">
            <p>Please fill out the Credit Card information</p>
              <div class="col-sm-6">
                <label>Card Number</label>
                <input name="cardNo" value="<?php echo @$usersINfor->cardNo;?>" id="cardNo" type="text" class="form-control">
              </div>
              
                <div class="col-sm-6">
                <label> Expiry Month</label>
		<?php $OPT =['01'=>'JAN (01)','02'=>'FEB (02)','03'=>'MAR (03)','04'=>'APR (04)','05'=>'MAY (05)','06'=>'JUN (06)','07'=>'JUL (07)','08'=>'AUG (08)','09'=>'SEP (09)','10'=>'OCT (10)','11'=>'NOV (11)','12'=>'DEC (12)'];?>
		<?php echo $this->Form->input('expMon', ['options' => $OPT, 'default'=>@$usersINfor->expMon,'empty' => 'Select Month', 'class' => 'form-control', 'style' => 'width:100%', 'label' => false,'div' => false]);?>
              </div>
		<div class="col-sm-6">
                <label> Expiry Year</label>
                <?php echo $this->Form->input('expYear', ['options' => $YEAR, 'min'=>date('Y'),'default'=>@$usersINfor->expYear, 'empty' => 'Select Year', 'class' => 'form-control ', 'data-placeholder' => 'Choose One', 'style' => 'width:100%', 'label' => false,'div' => false]);?>
              </div>
		
		<div class="col-sm-6">
                <label>CVV</label>
                <input name="CVV" id="CVV" value="<?php echo @$usersINfor->CVV;?>" type="number" min="0" max="9999" class="form-control">
              </div>
              
              <div class="col-sm-6">
                <label>Address</label>
                <input name="VBusinessAddress" value="<?php echo @$usersINfor->VBusinessAddress;?>" id="VBusinessAddress" type="text" class="form-control">
              </div>
              <!--<p style="text-align:center">I will add this information later</p>-->
              <div class="col-sm-6">
                <label>City</label>
                <input name="Vcity" id="Vcity" value="<?php echo @$usersINfor->Vcity;?>" type="text" class="form-control">
              </div>
              <div class="col-sm-6">
                <label>State</label>
                <input name="VAstate" id="VAstate" value="<?php echo @$usersINfor->VAstate;?>" type="text" class="form-control">
              </div>
	      <div class="col-sm-6">
                <label>Zipcode</label>
                <input name="VZipcode" id="VZipcode" value="<?php echo @$usersINfor->VZipcode;?>" type="text" class="form-control">
              </div>
              
            </div>
          
        </div>
	*/ ?>
	
	
	    <?php /*
	    <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
          <div class="listing-box-header">
            <h3>Add bank info</h3>
            <p>Information to deposit your end of year profilt share. </p>
          </div>
	  
            <div class="row mrg-r-10 mrg-l-10">
            <div class="col-sm-12"><p>Please fill out the bank information</p></div>
              <div class="col-sm-6">
                <label>Bank Account Name</label>
                <input name="ACName" id="ACName" value="<?php echo @$usersINfor->ACName;?>" type="text" class="form-control">
              </div>
              
                <div class="col-sm-6">
                <label> Bank Name</label>
                <input name="BankName" id="BankName" value="<?php echo @$usersINfor->BankName;?>" type="text" class="form-control">
              </div>
             
              <div class="col-sm-6">
                <label>Bank Account Number</label>
                <input name="AccountNo" id="AccountNo" value="<?php echo @$usersINfor->AccountNo;?>" type="text" class="form-control">
              </div>
              <div class="col-sm-6">
                <label>Swift number for accounts outside the US</label>
                <input name="swiftNumber" id="swiftNumber" value="<?php echo @$usersINfor->swiftNumber;?>" type="text" class="form-control">
              </div>
              <!--<p style="text-align:center; position:inherit">I will add this information later</p>-->
              <div class="col-sm-6">
                <label>Routing Code</label>
                <input name="RoutingNo" id="RoutingNo" value="<?php echo @$usersINfor->RoutingNo;?>" type="text" class="form-control">
              </div>
              <div class="col-sm-6">
                <label>Account Type</label>
                <input name="accountType" id="accountType" value="<?php echo @$usersINfor->accountType;?>" type="text" class="form-control">
              </div>
              <!--<p style="text-align:center; position:inherit;"><strong>Add PayPal Account Information (optional)</strong></p>-->
              <!--<div class="col-sm-6">
                <label>Registered email Address</label>
                <input name="PaypalEmail" id="PaypalEmail" value="<?php echo @$usersINfor->PaypalEmail;?>" type="text" class="form-control">
              </div>
              <div class="col-sm-6">
                <label>Account Number</label>
                <input name="paypalAccount" id="paypalAccount" value="<?php echo @$usersINfor->paypalAccount;?>" type="text" class="form-control">
              </div>-->
            </div>
          
        </div> */?>
        
        <div class="col-sm-3"></div>
       <div class="col-sm-3"> <div class="text-center"> <button type="submit" class="btn theme-btn" title="Submit Listing">Submit</button> </div></div>
        <div class="col-sm-3"> <!--<div class="text-center"> <a href="#" class="btn theme-btn" title="Submit Listing">SKIP</a> </div>--></div>
        <div class="col-sm-3"></div>
      </div>
<?php echo $this->Form->end();?>

<style>
    .serviceclass::-webkit-input-placeholder {
        color: #ff0000 !important;
    }
    .Red{color:#ff0000;}
    .green{color:#07B750 !important;}
</style>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#expmon").on('change', function(){
			$("#expmon").next(".nice-select").hide();
			$("#expmon").attr('style','color:#445461 !important');
		});
		$("#expyear").on('change', function(){
			$("#expyear").next(".nice-select").hide();
			$("#expyear").attr('style','color:#445461 !important');
		});
	});
function validatenew(){
        var ErrorCount = 0;
        var focus = '';
        if($("#VFirstName").val() == ''){
            $("#VFirstName").addClass('serviceclass');
            $("#VFirstName").attr("placeholder", "Please Enter first name");
            ErrorCount++;
            if(focus ==''){
                focus = 'VFirstName';
            }
        }
        if($("#VLastName").val() == ''){
            $("#VLastName").addClass('serviceclass');
            $("#VLastName").attr("placeholder", "Please Enter last name");
            ErrorCount++;
            if(focus ==''){
                focus = 'VLastName';
            }
        }
        
        if($("#cardNo").val() == ''){
            $("#cardNo").addClass('serviceclass');
            $("#cardNo").attr("placeholder", "Please Enter card no");
            ErrorCount++;
            if(focus ==''){
                focus = 'cardNo';
            }
        }
        if($("#expmon").val() == ''){
            //$("#expmon").addClass('red');
	    $("#expmon").attr('style','color:#ff0000 !important');
	    $("#expmon").next(".nice-select").hide();
            $("#expmon").attr("placeholder", "Please select expiry month");
            ErrorCount++;
            if(focus ==''){
                focus = 'expMon';
            }
        }
	if($("#expyear").val() == ''){
            $("#expyear").attr('style','color:#ff0000 !important');
	    $("#expyear").next(".nice-select").hide();
            $("#expyear").attr("placeholder", "Please select expiry year");
            ErrorCount++;
            if(focus ==''){
                focus = 'expyear';
            }
        }
	if($("#expmon").val() != '' && $("#expyear").val() != ''){
		var today = new Date();
		var n = today.getMonth();
		var year = today.getFullYear();
		if (year >= $("#expyear").val() && n > $("#expmon").val()) {
		   $("#expyear").attr('style','color:#ff0000 !important');
		   ErrorCount++;
		   if(focus ==''){
			focus = 'expyear';
		   }
		}
        }
	if($("#CVV").val() == ''){
            $("#CVV").addClass('serviceclass');
            $("#CVV").attr("placeholder", "Please Enter cvv");
            ErrorCount++;
            if(focus ==''){
                focus = 'CVV';
            }
        }
	if($("#cardNo").val() == ''){
            $("#cardNo").addClass('serviceclass');
            $("#cardNo").attr("placeholder", "Please Enter card no");
            ErrorCount++;
            if(focus ==''){
                focus = 'cardNo';
            }
        }
        
       if(ErrorCount < 1){
        return true;
       }else{
        $( "#"+focus ).focus();
        return false;
       }
    }
</script>

<!--************LOGIN SCRIPT****************-->
<script type="text/javascript">
	jQuery("#loginSubmit").on('click', function(){
	
	jQuery(".loginResponse").remove();
	var loginEmail = jQuery("#loginEmail").val();
	var loginPassword = jQuery("#loginPassword").val();
	
	var login = 0;
	if(loginEmail == ''){
		login++;
		jQuery("#loginEmail").after('<span class="loginResponse" style="color: rgb(255, 0, 0);">Please enter name</span>');
	}
	if(loginPassword == ''){
		login++;
		jQuery("#loginPassword").after('<span class="loginResponse" style="color: rgb(255, 0, 0);">Please enter phone</span>');
	}
	
	//alert(login);
	if (login < 1) {
		$('#loginSubmit').prop('disabled', true);
		$("#newsAjaxLoader").css("display", "block");
		jQuery.ajax({
			data:{email:loginEmail,password:loginPassword},
			type: 'POST',
			url : "<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'login']);?>",
			success: function(response) {
			    //alert(response);
			    $('#loginSubmit').prop('disabled', false);
			    $("#newsAjaxLoader").css("display", "none");
			    //jQuery("#mce-EMAIL-footer").val('');
			    if(response == 'success'){
			    window.location.href = "<?php echo $this->Url->build('/');?>dashboard";
			    }else{
				jQuery("#loginSubmit").after(response);
			    }
			    return false;
			}
		});
	  }	  
	});
</script>


<!--<script type="text/javascript">
$(document).ready(function(){	
	$(".readmore").fancybox({
		maxWidth	: 600,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});	
	
});
</script>-->