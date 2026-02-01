<?php
$home = $about = $contact = $learn = '';
$request = $this->getRequest(); // Get the request object

if ($request->getParam('controller') === 'Pages' && $request->getParam('action') === 'home') {
    $home = 'active';
}
if ($request->getParam('controller') === 'Pages' && $request->getParam('action') === 'aboutus') {
    $about = 'active';
}
if ($request->getParam('controller') === 'Pages' && $request->getParam('action') === 'contactus') {
    $contact = 'active';
}
if ($request->getParam('controller') === 'Pages' && $request->getParam('action') === 'elearn') {
    $learn = 'active';
}
?>
<nav class="navbar navbar-default navbar-fixed navbar-transparent white bootsnav">
    <div class="nav-utility">
        <div class="module left" style="display:none">
            <i class="ti-mobile">&nbsp;</i>
            <span class="sub">Call us Today: <a href="tel:<?php echo $globalparameters->phoneNo; ?>" style="color: #fff;"><?php echo $globalparameters->phoneNo; ?></a></span>
        </div>
        <div class="module left">
            <i class="ti-email">&nbsp;</i>
            <span class="sub"><a href="mailto:ritevet@ritevet.com" target="_blank" style="color: #fff;">Contact us at ritevet@ritevet.com</a></span>
        </div>
        <div class="module right">
            <ul>
                <li><a href="<?php echo $this->Url->build(['controller' => 'Pages', 'action' => 'faqs']); ?>">FAQ</a></li>
                <li><a href="<?php echo $this->Url->build(['controller' => 'Pages', 'action' => 'howitwork']); ?>">How it works</a></li>
                <?php if ($request->getSession()->read('RitevetUsers.id') === null) { ?>
                    <li><a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'login']); ?>">Sign In</a></li>
                <?php } else { ?>
                    <li><a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'cart']); ?>"><i class="fa fa-shopping-cart"></i><span class="badge bg-a" id="HeaderC"><?php echo (@$CART) ? @$CART : '0'; ?></span></a></li>
                    <li><a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'logout']); ?>">Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid"> 
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
            <i class="ti-align-left"></i>
        </button>
        <!-- Start Header Navigation -->
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo $this->Url->build('/'); ?>">
                <img src="<?php echo $this->Url->build('/'); ?>assets/img/logo-white.png" class="logo logo-display" alt="">
                <img src="<?php echo $this->Url->build('/'); ?>assets/img/logo.png" class="logo logo-scrolled" alt="">
            </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="nav navbar-nav navbar-center" data-in="fadeInDown" data-out="fadeOutUp">
                <li class="<?php echo $home; ?>">
                	<a href="<?php echo $this->Url->build('/'); ?>">Home</a>
                </li>
                <li class="<?php echo $about; ?>">
                	<a href="<?php echo $this->Url->build(['controller' => 'Pages', 'action' => 'aboutus']); ?>">About Us</a>
                </li>
                <li class="<?php echo $contact; ?>">
                	<a href="<?php echo $this->Url->build(['controller' => 'Pages', 'action' => 'contactus']); ?>">Contact Us</a>
                </li>
                <li class="<?php echo $learn; ?>">
                	<a href="<?php echo $this->Url->build(['controller' => 'Pages', 'action' => 'elearn']); ?>">E-Learning</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                <?php if ($request->getSession()->read('RitevetUsers.id') === null) { ?>
                    <!--<li class="no-pd"><a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'login']); ?>" class="addlist"><i class="ti-user" aria-hidden="true"></i>Sign In</a></li>-->
                <?php } else { ?>
                    <li class="no-pd"><a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']); ?>" class="addlist"><i class="ti-user" aria-hidden="true"></i><?php echo @$user->firstName; ?> <?php echo @$user->lastName; ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>   
</nav>