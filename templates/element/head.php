<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= h($this->request->getAttribute('csrfToken')) ?>">
  
  <title><?php echo $layoutTitle;?></title>
    <!-- All plugins -->
  <link href="<?php echo $this->Url->build('/');?>assets/plugins/css/plugins.css" rel="stylesheet">	
    <!-- Custom style -->
  <link href="<?php echo $this->Url->build('/');?>assets/css/style.css" rel="stylesheet">
	<link href="<?php echo $this->Url->build('/');?>assets/css/responsiveness.css" rel="stylesheet">
	<link  type="text/css" rel="stylesheet" id="jssDefault" href="<?php echo $this->Url->build('/');?>assets/css/colors/main.css">
  <link rel="icon" type="image/png" href="<?php echo $this->Url->build('/');?>favicon.png"/>
	<style>
	    body {
            font-family: Arial, sans-serif; /* Set a default font for better readability */
            background-color: #f8f9fa; /* Light background for contrast */
            color: #333; /* Dark text for readability */
        }
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
	</style>
    <!--<link rel="manifest" href="<?php echo $this->Url->build('/');?>site.webmanifest">-->
    
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo $this->Url->build('/');?>js/html5shiv.min.js"></script>
      <script src="<?php echo $this->Url->build('/');?>js/respond.min.js"></script>
    <![endif]-->
    <!--<script src="https://cdn.agora.io/sdk/release/AgoraRTCSDK-3.4.1.js"></script>-->
</head>


