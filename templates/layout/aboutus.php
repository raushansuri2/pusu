<?php
   $home = $about = $contact ='';
   if($this->request->getParam('controller') == 'Maintenance' && $this->request->getParam('action')== 'maintenance'){
      $home = 'active';
   }
   if($this->request->getParam('controller') == 'Maintenance' && $this->request->getParam('action')== 'aboutus'){
      $about = 'active';
   }
   if($this->request->getParam('controller') == 'Maintenance' && $this->request->getParam('action')== 'contactus'){
      $contact = 'active';
   }
   
?>
<html class="no-js" lang="en"><head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ritevet::about-us</title>
    <link rel="icon" href="<?php echo $this->Url->build('/');?>favicon.png" type="image/png">

    <!-- All plugins -->
    <link href="/assets/plugins/css/plugins.css" rel="stylesheet">	
    <!-- Custom style -->
   <link href="/assets/css/style.css" rel="stylesheet">
			<link href="/assets/css/responsiveness.css" rel="stylesheet">
			<link type="text/css" rel="stylesheet" id="jssDefault" href="/assets/css/colors/main.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="/js/html5shiv.min.js"></script>
      <script src="/js/respond.min.js"></script>
    <![endif]-->
    
	</head>



	<body class="home-2" cz-shortcut-listen="true"><div class="wrapper">
		<div class="wrapper">  
        
			<!-- Start Navigation -->
			      <nav class="navbar navbar-default navbar-fixed navbar-transparent white bootsnav on menu-center no-full">
		<div class="nav-utility">
		   <div class="module left" style="display:none"><i class="ti-mobile">&nbsp;</i>
	            <span class="sub">Call us Today: <a href="tel:1800-234-5678" style="color: #fff;">1800-234-5678</a></span>
            </div>
        	<div class="module left"><i class="ti-email">&nbsp;</i>
	            <span class="sub"><a href="mailto:ritevet@ritevet.com" target="_blank" style="color: #fff;">Contact us at ritevet@ritevet.com</a></span>
            </div>
        </div>
      
        <div class="container-fluid"> 
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
				<i class="ti-align-left"></i>
			</button>
			<!-- Start Header Navigation -->
			<div class="navbar-header">
				<a class="navbar-brand" href="/">
					<img src="/assets/img/logo-white.png" class="logo logo-display" alt="">
					<img src="/assets/img/logo.png" class="logo logo-scrolled" alt="">
				</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="navbar-menu">
				<ul class="nav navbar-nav navbar-center" data-in="fadeInDown" data-out="fadeOutUp">
						<li class="<?php echo $home;?>">
						    <a href="<?php echo $this->Url->build('/');?>">Home</a>
						</li>
                        <li class="<?php echo $about;?>">
                            <a href="/aboutus">About Us</a>
                        </li>
                        <li class="<?php echo $contact;?>">
                            <a href="/contactus">Contact Us</a>
                        </li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>   
	</nav>
   
			<!-- End Navigation -->
			<div class="clearfix"></div>
			
			<!-- Main Banner Section Start -->
			
			
<div class="banner-inner dark-opacity" style="background-image:url(/assets/img/home-banner.jpg);" data-overlay="8">  
<div class="container">
  <div class="title-content">
    <h1>About Us</h1>
    <div class="breadcrumbs">
              <a href="https://ritevet.com/">Home</a>
     <span class="gt3_breadcrumb_divider"></span>        <span class="current">About Us </span>
      <span class="gt3_breadcrumb_divider"></span>  
    </div>
  </div>
</div>
</div>


			<div class="clearfix"></div>
			<!-- Main Banner Section End -->
            
            
			

            
       
      <section>
				<div class="container">
      
				 
		<div class="col-md-6 col-sm-12"><img class="" src="/assets/img/about.jpg" style="width:100%;"></div>
                    
            <div class="col-md-6 col-sm-12">
            <div class="heading">
		<h2 style="padding:0">About <span>RiteVet</span></h2>
		<!--<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>-->
		</div>
		
		<p>RiteVet is an inclusive community that brings together pet parents, pet service providers, and pet lovers. You can join our community via our website, iOS app, or Android app. Our pet service providers offer a wide range of services, including veterinary services, grooming, boarding, training, supplies, merchandise, and freebies.
You can have a virtual chat with a veterinarian about your pet's health conditions, request a mobile vet to come to your home, or make an appointment with any other pet service provider through our website or app.</p>
<p>RiteVet does not provide services directly. Instead, RiteVet functions as a platform that connects pet parents with pet service providers. All users, including pet parents, pet service providers, and veterinarians, are solely responsible for ensuring they comply with their country’s, state’s, and local government licensing and operational requirements, regulations, and all applicable telehealth laws.</p>		</div>
        
				</div>
			</section>

<style>
.serviceclass::-webkit-input-placeholder {
color: #ff0000 !important;
}
.serviceclass2::-webkit-input-placeholder {
color: #00FF55 !important;
}
</style>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script>
jQuery(document).ready(function(){
$("#SUBSCRIBEbutton").on('click', function(){
var SUBID = $("#SUBSCRIBEEMAIL").val();
//alert(SUBID);
var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
if (!$("#SUBSCRIBEEMAIL").val().match(pattern)) {
$("#SUBSCRIBEEMAIL").val('');
$("#SUBSCRIBEEMAIL").addClass('serviceclass');
$("#SUBSCRIBEEMAIL").attr("placeholder", 'Invaild Email');
return false;
}else{
$("#newsAjaxLoader").css("display", "block");
$.ajax({
url : "/pages/subscriber/"+SUBID,
success: function(response) {
var obj = jQuery.parseJSON(response);
//alert(obj.message);
if(obj.type == 'ERROR'){
$("#SUBSCRIBEEMAIL").addClass('serviceclass');
}else{
$("#SUBSCRIBEEMAIL").addClass('serviceclass2');
}
$("#SUBSCRIBEEMAIL").val('');
$("#SUBSCRIBEEMAIL").attr("placeholder", obj.message);
$("#newsAjaxLoader").css("display", "none");
//alert(response);
//jQuery("#department_name").html(response);
//var obj = jQuery.parseJSON(response);
//alert(JSON.stringify(response));
//alert( obj.contactperson);
//$('select#subcategory').html(response);
//$('#subcategory').html(response);
return false;
}
});
}
});
});
</script>





<div id="newsAjaxLoader" style="width: 100%; height: 100%; position: fixed; z-index: 10000000; top: 0px; left: 0px; right: 0px; bottom: 0px; margin: auto; display: none;">
<div style="width: 250px; height: 75px; text-align: center; position: fixed; top: 0px; left: 0px; right: 0px; bottom: 0px; margin: auto; font-size: 16px; z-index: 10; color: rgb(255, 255, 255);">
<img src="/img/admin/fancybox_loading@2x.gif"></div><div class="bg" style="background: rgb(0, 0, 0) none repeat scroll 0% 0%; opacity: 0.7; width: 100%; height: 100%; position: absolute; top: 0px;">
</div>
</div>

		

        
			
			<!-- START JAVASCRIPT -->
						<script src="/assets/js/jquery.min.js"></script>
			<script src="/assets/plugins/js/bootstrap.min.js"></script>
			<script src="/assets/plugins/js/bootsnav.js"></script>
			<script src="/assets/plugins/js/bootstrap-select.min.js"></script>
			<script src="/assets/plugins/js/bootstrap-touch-slider-min.js"></script>
			<script src="/assets/plugins/js/jquery.touchSwipe.min.js"></script>
			<script src="/assets/plugins/js/chosen.jquery.js"></script>
			<script src="/assets/plugins/js/datedropper.min.js"></script>
			<script src="/assets/plugins/js/dropzone.js"></script>
			<script src="/assets/plugins/js/jquery.counterup.min.js"></script>
			<script src="/assets/plugins/js/jquery.fancybox.js"></script>
			<!--<script src="/assets/plugins/js/jquery.nice-select.js"></script>-->
			<script src="/assets/plugins/js/jqueryadd-count.js"></script>
			<script src="/assets/plugins/js/jquery-rating.js"></script>
			<script src="/assets/plugins/js/slick.js"></script>
			<script src="/assets/plugins/js/timedropper.js"></script>
			<script src="/assets/plugins/js/waypoints.min.js"></script>
			<script src="/assets/js/jQuery.style.switcher.js"></script>
			<script src="/assets/js/custom.js"></script>
			
			
			<script>
				this.$('.TIme').timeDropper({
					setCurrentTime: false,
					meridians: true,
					primaryColor: "#e8212a",
					borderColor: "#e8212a",
					//minutesInterval: '15',
					//interval: '15 min',
					init_animation: "fadein",
					autoswitch:false
				});
			</script>
			<script>
				$('.droperDate').dateDropper({
						format: 'Y-m-d',
						Default: false
				});
				//$('.droperDate').dateDropper();
			</script>
<style>
	.ajaxloader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		opacity: 0.7;
		background: url('/img/admin/fancybox_loading@2x.gif') 50% 50% no-repeat rgb(249,249,249);
	}
</style>
			<div class="ajaxloader" style="display: none;"></div>			
			<script>
				function openRightMenu() {
					document.getElementById("rightMenu").style.display = "block";
				}
				function closeRightMenu() {
					document.getElementById("rightMenu").style.display = "none";
				}
			</script>
			
			<script>
				$(document).ready(function(){
					$('[data-toggle="tooltip"]').tooltip();   
				});
			</script>
			
			<script type="text/javascript">
				  $(document).ready(function() {
				  $('select').niceSelect();
				});	
			</script>
			
		</div>
	
</div></body></html>