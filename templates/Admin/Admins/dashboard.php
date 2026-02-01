<?php use Cake\Core\Configure;?>
<style>
.noborder {
    border: 0 none;
    height: 96px;
}
</style>

<div class="mainwrapper">
	<div class="leftpanel">
		<?php echo $this->element('admin/sidebar');?>        
	</div><!-- leftpanel -->
	
	<div class="mainpanel">
		<div class="pageheader">
			<div class="media">
				<div class="pageicon pull-left">
					<i class="fa fa-dashboard"></i>
				</div>
				<div class="media-body">
					<ul class="breadcrumb">
						<li><i class="glyphicon glyphicon-home"></i> Dashboard</li>
					</ul>
					<h4>Dashboard</h4>
				</div>
                <?php echo $this->Flash->render(); ?>
				<div class="search-body">					
				<div class="last-login"><label>Last Login : </label><?php echo date('l, M d, Y H:i', strtotime($this->request->getSession()->read('AnnuityAdmin.lastLogin'))); ?></div> 
				</div>
			</div><!-- media -->
		</div><!-- pageheader -->
		
		<div class="contentpanel">
            <div class="row row-stat">
                <div class="col-md-4">
                    <div class="panel panel-success-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $memberCount;?></div>
                            <div class="media-body" style="overflow: initial; width:68%" >
                                <h5 style="padding-top:0px">Total Member</h5>
                            </div>
                        </div>
                    </div>
                </div>	
                <div class="col-md-4">
                    <div class="panel panel-warning-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $postCount;?></div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px">Total Posts</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-primary noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $likeCount;?></div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px">Total Likes</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
			<div class="row row-stat">
                <div class="col-md-4">
                    <div class="panel panel-success-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $commentCount; ?></div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px">Total Comments</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-4">
                    <div class="panel panel-warning-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $ordersInreviewCount; ?></div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px">Total IPO Stage-3</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-primary noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $productsCount;?></div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px">Total IPO Stage-4</h5>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="row row-stat">
                <!-- <div class="col-md-4">
                    <div class="panel panel-success-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $otherpetserviceCount;?></div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px">Total IPO Stage-5</h5>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="col-md-4">
                    <div class="panel panel-warning-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $ordersShippedCount; ?></div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px">Total Orders Shipped</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-primary noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $freeStuffsCount;?></div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px">Total Free Stuffs</h5>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <!-- <div class="row row-stat">
                <div class="col-md-4">
                    <div class="panel panel-success-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $petParentCount;?></div>
                            <div class="media-body" style="overflow: initial; width:68%" >
                                <h5 style="padding-top:0px">Total Pet Parents</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-warning-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px"><?php echo $ordersDeliveredCount; ?></div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px">Total Orders Delivered</h5>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div> -->
        </div><!-- contentpanel -->
	</div><!-- mainpanel -->
</div><!-- mainwrapper -->

        