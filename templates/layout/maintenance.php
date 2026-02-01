<?php
   $home = $about = $contact ='';
   if($this->request->getParam('controller') == 'Maintenance' && $this->request->getParam('action') == 'maintenance'){
      $home = 'active';
   }
   if($this->request->getParam('controller') == 'Maintenance' && $this->request->getParam('action') == 'aboutus'){
      $about = 'active';
   }
   if($this->request->getParam('controller') == 'Maintenance' && $this->request->getParam('action') == 'contactus'){
      $contact = 'active';
   }
?>
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ritevet</title>
    <link rel="icon" href="<?php echo $this->Url->build('/');?>favicon.png" type="image/png">

    <!-- All plugins -->
    <link href="<?php echo $this->Url->build('/');?>assets/plugins/css/plugins.css" rel="stylesheet">	
    <!-- Custom style -->
    <link href="<?php echo $this->Url->build('/');?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo $this->Url->build('/');?>assets/css/responsiveness.css" rel="stylesheet">
    <link href="<?php echo $this->Url->build('/');?>assets/css/colors/main.css" rel="stylesheet" id="jssDefault">
    <style>
        #banner {
            height : 100vh;
        }
        
        .container .btn {
          position: absolute;
          top: 110%;
          left: 50%;
          transform: translate(-50%, -50%);
          -ms-transform: translate(-50%, -50%);
          background-color: #f1f1f1;
          color: black;
          font-size: 16px;
          padding: 16px 30px;
          border: none;
          cursor: pointer;
          border-radius: 5px;
          text-align: center;
          width: fit-content;
          /*background-color: #0a0add;*/
        }
        
        .container .btn:hover {
          background-color: #0d0dd5;
          color: white;
        }
        
        .navbar-nav {
            display:inline-block;
            float: none;
            vertical-align: top;
        }
    </style>
</head>

<body class="home-2">
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
                <!--<div class="module right">-->
                <!--    <ul>-->
                <!--        <li> <a href="https://ritevet.com/pages/faqs">FAQ</a></li>-->
                <!--        <li> <a href="https://ritevet.com/pages/howitwork">How it works</a></li>-->
                <!--    </ul>-->
                <!--</div>-->
            </div>
      
            <div class="container-fluid"> 
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
					<i class="ti-align-left"></i>
				</button>
				 <!--Start Header Navigation -->
				<div class="navbar-header">
					<a class="navbar-brand" href="">
						<img src="<?php echo $this->Url->build('/');?>assets/img/logo-white.png" class="logo logo-display" alt="">
						<img src="<?php echo $this->Url->build('/');?>assets/img/logo.png" class="logo logo-scrolled" alt="">
					</a>
				</div>
				 <!--/.navbar-collapse -->
				
				 <!--Collect the nav links, forms, and other content for toggling -->
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
			</div>   
		</nav>

		<!-- End Navigation -->
		<div class="clearfix"></div>
		<div class="banner dark-opacity" style="background-image:url(assets/img/home-banner.jpg);" data-overlay="8" id="banner">  
			<div class="container">
				<div class="banner-caption">
					<div class="col-md-12 col-sm-12 banner-text">
						<h1>Coming Soon!</h1>
						<br>
						<p>
						    Ready to join the pack? Sign up below and let us know your passion!<br>
						    Whether you're a devoted pet parent, a skilled veterinarian, or offer other pet services,<br> we've got a spot for you.
						</p>
						<br>
						<a class="btn" href="/early-access" role="button">sign up for early access</a>
					</div>
				</div>
			</div>
		</div>
		<!-- Main Banner Section Start -->
		<div class="clearfix"></div>
		<!-- Main Banner Section End -->
	</div><!--//wrape close-->
			
    <script src="<?php echo $this->Url->build('/');?>assets/js/jquery.min.js"></script>
	<script src="<?php echo $this->Url->build('/');?>assets/plugins/js/bootstrap.min.js"></script>
</body>
</html>