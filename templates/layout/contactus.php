<?php
$home = $about = $contact = '';
if ($this->request->getParam('controller') == 'Maintenance') {
    if ($this->request->getParam('action') == 'maintenance') {
        $home = 'active';
    } elseif ($this->request->getParam('action') == 'aboutus') {
        $about = 'active';
    } elseif ($this->request->getParam('action') == 'contactus') {
        $contact = 'active';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ritevet::Contact Us</title>
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
    
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body class="home-2">
    <div class="wrapper">
        <!-- Start Navigation -->
        <nav class="navbar navbar-default navbar-fixed white bootsnav on menu-center no-full navbar-transparent">
            <div class="nav-utility">
                <div class="module left" style="display:none">
                    <i class="ti-mobile">&nbsp;</i>
                    <span class="sub">Call us Today: <a href="tel:1800-234-5678" style="color: #fff;">1800-234-5678</a></span>
                </div>
                <div class="module left">
                    <i class="ti-email">&nbsp;</i>
                    <span class="sub"><a href="mailto:ritevet@ritevet.com" target="_blank" style="color: #fff;">Contact us at ritevet@ritevet.com</a></span>
                </div>
            </div>
            <div class="container-fluid"> 
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                    <i class="ti-align-left"></i>
                </button>
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">
                        <img src="/assets/img/logo-white.png" class="logo logo-display" alt="">
                        <img src="/assets/img/logo.png" class="logo logo-scrolled" alt="">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-center" data-in="fadeInDown" data-out="fadeOutUp">
                        <li class="<?= $home; ?>">
                            <a href="<?= $this->Url->build('/'); ?>">Home</a>
                        </li>
                        <li class="<?= $about; ?>">
                            <a href="/aboutus">About Us</a>
                        </li>
                        <li class="<?= $contact; ?>">
                            <a href="/contactus">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>   
        </nav>
        <!-- End Navigation -->
        <div class="clearfix"></div>

        <!-- Main Banner Section Start -->
        <div class="banner-inner dark-opacity" style="background-image:url(/assets/img/home-banner.jpg);" data-overlay="8">  
            <div class="container">
                <div class="title-content">
                    <h1>Contact Us</h1>
                    <div class="breadcrumbs">
                        <a href="https://ritevet.com/">Home</a>
                        <span class="gt3_breadcrumb_divider"></span>
                        <span class="current">Contact Us</span>
                        <span class="gt3_breadcrumb_divider"></span>  
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- Main Banner Section End -->

        <section>
            <div class="container">
                <div class="col-md-10 col-md-offset-1 col-sm-12 translateY-60">
                    <div class="col-md-12 col-sm-12">
                        <div class="detail-wrapper text-center padd-top-40 mrg-bot-10 padd-bot-40 light-bg">
                            <i class="theme-cl font-30 ti-location-pin"></i>
                            <h4>USA Office</h4>
                            16445 Tomahawk Drive, Gaithersburg, MD 20878
                        </div>
                    </div>
                </div>

                <section class="padd-top-0">
                    <div class="container">
                        <div class="col-md-6 col-sm-6">
                            <?= $this->Flash->render(); ?>
                            <form id="contactForm" method="post" accept-charset="utf-8" onsubmit="return validate();" action="/contactus">
                                <input type="hidden" name="_csrfToken" value="<?= $this->request->getAttribute('csrfToken') ?>">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
                                </div>
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <label>Message:</label>
                                    <textarea name="message" id="message" class="form-control height-120" placeholder="Message" required></textarea>
                                </div>
                                <div class="form-group">
                                    <button class="btn theme-btn" name="submit" id="submitButton">Send</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.312031449958!2d-73.46106868506354!3d40.71114697933196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89e9d550d3e6818d%3A0xe9c0276734feb241!2sN%20New%20York%20Dr%2C%20North%20Massapequa%2C%20NY%2011758%2C%20USA!5e0!3m2!1sen!2sin!4v1571653902390!5m2!1sen!2sin" width="550" height="400" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                    </div>
                </section>

                <script type="text/javascript">
                    function validate() {
                        $(".contactresponse").remove();
                        var REname = $("#name").val();
                        var REemail = $("#email").val();
                        var RMessage = $("#message").val();
                        var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
                        var reg = 0;

                        if (REname == '') {
                            reg++;
                            $("#name").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter name.</span>');
                        }
                        if (!filter.test(REemail)) {
                            reg++;
                            $("#email").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter valid email address.</span>');
                        }
                        if (RMessage == '') {
                            reg++;
                            $("#message").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter message.</span>');
                        }

                        if (reg < 1) {
                            // Disable the submit button and change its text
                            var submitButton = document.getElementById('submitButton');
                            submitButton.disabled = true; // Disable the button
                            submitButton.textContent = 'Sending...'; // Change button text
                            return true; // Allow form submission
                        } else {
                            return false; // Prevent form submission
                        }
                    }
                </script>
            </div>
        </section>
    </div>
</body>
</html>