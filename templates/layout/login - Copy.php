<?php 
    $conr = $this->request->getParam('controller'); 
    $act = $this->request->getParam('action');

    // echo $act; exit; 
    $DAS = ($conr == 'Users' && $act=='dashboard') ? 'current' : '';
    $MANPROF = ($conr == 'Users' && in_array($act, ['editprofile','veterinarian-register','pet_parent_register','other_pet_service_register'])) ? 'current' : '';
    $EDTPROF = ($conr == 'Users' && in_array($act, ['editprofile'])) ? 'current' : '';
    $VETREG = ($conr == 'Users' && in_array($act, ['veterinarian-register'])) ? 'current' : '';
    $SERPROVREG = ($conr == 'Users' && in_array($act, ['other_pet_service_register'])) ? 'current' : '';
    $PETREG = ($conr == 'Users' && in_array($act, ['pet_parent_register'])) ? 'current' : '';
    $SER = ($conr == 'Users' && in_array($act, ['service','addservice','editservice'])) ? 'current' : '';
    $STF = ($conr == 'Freestaffs' && in_array($act, ['own','add','edit'])) ? 'current' : '';
    $PET = ($conr == 'Products' && in_array($act, ['own','add','edit'])) ? 'current' : '';
    $BAP = ($conr == 'Users' && in_array($act, ['request_service'])) ? 'current' : '';
    $APP = ($conr == 'Users' && in_array($act, ['requestedappointments', 'sentappointments','requestedappointmentsdetail','sentappointmentsdetail'])) ? 'current' : '';
    $REQAPP = ($conr == 'Users' && in_array($act, ['requestedappointments','requestedappointmentsdetail'])) ? 'current' : '';
    $SENTAPP = ($conr == 'Users' && in_array($act, ['sentappointments','sentappointmentsdetail'])) ? 'current' : '';
    
    $SET = ($conr == 'Users' && in_array($act, ['setavailability','setavailabilitypet'])) ? 'current' : '';
    $ORD = ($conr == 'Products' && in_array($act, ['productsOrders','orders','orderdetails','trackOrder'])) ? 'current' : '';
    $ORDMy = ($conr == 'Products' && in_array($act, ['orders'])) ? 'current' : '';
    $ORDRec = ($conr == 'Products' && in_array($act, ['productsOrders'])) ? 'current' : '';
    $ORDTRK = ($conr == 'Products' && in_array($act, ['trackOrder'])) ? 'current' : '';
    $EARN   = ($conr == 'Users' && in_array($act, ['earnamount'])) ? 'current' : '';
    $CHAT   = ($conr == 'Users' && in_array($act, ['allchats'])) ? 'current' : '';
    
    $os = 'No';
    // pr($this->request->getSession()->read("RitevetUsers"));exit;
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
    <?php echo $this->element('head');?>
    <body class="home-2">
	    <div class="wrapper">  
		    <!-- Start Navigation -->
		    <?php echo $this->element('header');?>
		    <!-- End Navigation -->
		    <div class="clearfix"></div>
		    <!-- Main Banner Section Start -->
		    <?php echo $this->element('breadcrum'); ?>
		    <div class="profile-link-new">
		        <div class="container" style="display: flex;justify-content: center;align-items: center;">
                    <div id="dash-bicky">
                	    <ul id="dashb-menu" class="dashmenu">
                    		<li class="nav-item">
                    		    <a class="<?php echo $DAS;?>" href="<?php echo $this->Url->build(['controller'=>'users', 'action'=>'dashboard']);?>">Dashboard</a>
                    		</li>
                    	    <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle waves-effect waves-light <?php echo $MANPROF ?>" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'editprofile']) ?>">
                                    Manage Profile
                                </a>
                                <ul class="dropdown-menu dropdown-primary">
                                    <li class="dropdown-item">
                                        <a class="<?php echo $EDTPROF ?>" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'editprofile']) ?>">
                                            Edit Profile
                                        </a>
                                    </li>
                                    <?php if (!empty($this->request->getSession()->read("RitevetUsers.usersinformation.UTYPE"))) { ?>
                                        <li class="dropdown-item">
                                            <?php if ($this->request->getSession()->read("RitevetUsers.usersinformation.UTYPE") == 2) { ?>
                                                <a class="<?php echo $VETREG ?>" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'veterinarian-register']) ?>">
                                                    Veterinarian
                                                </a>
                                            <?php } else { ?>
                                                <a><s>Veterinarian</s></a>
                                            <?php } ?>
                                        </li>
                                        <li class="dropdown-item">
                                            <?php if ($this->request->getSession()->read("RitevetUsers.usersinformation.UTYPE") == 1) { ?>
                                                <a class="<?php echo $PETREG ?>" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'pet_parent_register']) ?>">
                                                    Pet Parent
                                                </a>
                                            <?php } else { ?>
                                                <a><s>Pet Parent</s></a>
                                            <?php } ?>
                                        </li>
                                        <li class="dropdown-item">
                                            <?php if ($this->request->getSession()->read("RitevetUsers.usersinformation.UTYPE") == 3) { ?>
                                                <a class="<?php echo $SERPROVREG ?>" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'other_pet_service_register']) ?>">
                                                    Other Pet Service
                                                </a>
                                            <?php } else { ?>
                                                <a><s>Other Pet Service</s></a>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                    <!--
                                    <li class="dropdown-item">
                                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'currentsubscription']) ?>">
                                            Subscription
                                        </a>
                                    </li>
                                    -->
                                </ul>
                            </li>
                            
                    		<?php if (!empty($this->request->getSession()->read("RitevetUsers.usersinformation.UTYPE"))) { ?>
                                <li class="nav-item">
                                    <a class="<?php echo $STF; ?>" href="<?php echo $this->Url->build(['controller' => 'Freestaffs', 'action' => 'own']); ?>">Manage FreeStuff</a>
                                </li>
                                <li class="nav-item">
                                    <a class="<?php echo $PET; ?>" href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'own']); ?>">Manage PetStore</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="<?php echo $ORD; ?>" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'orders']); ?>">Orders</a>
                                    <ul class="dropdown-menu dropdown-primary">
                                        <li class="nav-item">
                                            <a class="<?php echo $ORDMy; ?>" href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'orders']); ?>">My Orders</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="<?php echo $ORDRec; ?>" href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'productsOrders']); ?>">My Products Orders</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="<?php echo $ORDTRK; ?>" href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'trackOrder']); ?>">Track Order</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="<?php echo $APP; ?>" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" href="">Appointments</a>
                                    <ul class="dropdown-menu dropdown-primary">
                                        <?php
                                        if ($this->request->getSession()->read("RitevetUsers.usersinformation.UTYPE") == 2 || $this->request->getSession()->read("RitevetUsers.usersinformation.UTYPE") == 3) {
                                            $os = 'Yes';
                                        }
                                        
                                        if ($os == 'Yes') { ?>
                                            <li class="nav-item">
                                                <a class="<?php echo $REQAPP; ?>" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'requestedappointments']); ?>">Requested Appointments</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="<?php echo $SENTAPP; ?>" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'sentappointments']); ?>">Appointments Sent</a>
                                            </li>
                                        <?php } else { ?>
                                            <li class="nav-item">
                                                <a class="<?php echo $SENTAPP; ?>" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'sentappointments']); ?>">Appointments Sent</a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php if($os == 'Yes'){?>
                        		<li class="nav-item">
                        			<a class="<?php echo $EARN;?>" href="<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'earnamount']);?>">Earnings</a>
                        		</li>
                        		<?php } ?>
                        		
                        		<!--<li class="nav-item">-->
                        		<!--	<a class="<?php echo $CHAT;?>" href="<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'allchats']);?>">Chats</a>-->
                        		<!--</li>-->
                            <?php } ?>
                    		
                    		<!--LOGOUT-->
                    		<li class="nav-item">
                    		    <a class="" href="<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'logout']);?>">Logout</a>
                    		</li>
                	    </ul>
                    </div>
                </div>
		    </div>
		    <div class="clearfix"></div>
		    <!-- Main Banner Section End -->
            <section>
			    <div class="container">
				    <?php echo $this->fetch('content'); ?>        
			    </div>
		    </section>
        </div>
		<!-- ================ Start Footer ======================= -->
		<?php echo $this->element('footer');?>		
		<!-- ================ End Footer Section ======================= -->
			
		<!-- ================== Login & Sign Up Window ================== -->
		<a id="back2Top" class="theme-bg" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>

		<!-- START JAVASCRIPT -->
		<?php echo $this->element('footer_bottom');?>

		<script type="text/javascript">
			function openRightMenu() {
				document.getElementById("rightMenu").style.display = "block";
			}
			function closeRightMenu() {
				document.getElementById("rightMenu").style.display = "none";
			}
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('[data-toggle="tooltip"]').tooltip();   
			});
		</script>
        <script type="text/javascript">
			var bic = document.getElementById("dashb-menu");
			if(bic.className == "dashmenu"){
				bic.className += " responsive";
			}else {
				bic.className = "dashmenu"
			}
        </script>
	</body>
</html>