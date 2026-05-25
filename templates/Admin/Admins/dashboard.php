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
        
        <p style="font-size: 16px;color: #000;margin-bottom: 20px;line-height: 34px;">Welcome to the <strong>ERISA Quote Pro Tool </strong>Admin Panel<br>  
Access all quote requests, manage pricing, and monitor performance in one place. Stay organized and respond quickly to keep your sales pipeline moving.

 </p>
            <!--<div class="row row-stat">
                <div class="col-md-4">
                    <div class="panel panel-success-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;">
                            <div class="panel-icon" style="color: #000; font-size: 15px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px">45</div>
                            <div class="media-body" style="overflow: initial; width:68%" >
                                <h5 style="padding-top:0px">Total Member</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
			<div class="row row-stat">
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
                <div class="col-md-4">
                    <div class="panel panel-warning-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;border-radius: 17px;">
                            <div class="panel-icon" style="color: #000; font-size: 25px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px;font-weight: bold;">65</div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px;font-weight: bold;font-size: 17px;margin: 21px 10px;">Total Requested Quotes</h5>
                            </div>
                        </div>
                    </div>
                </div> 
                  <div class="col-md-4">
                    <div class="panel panel-success-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;border-radius: 17px;">
                            <div class="panel-icon" style="color: #000; font-size: 25px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px;font-weight: bold;">76</div>
                            <div class="media-body" style="overflow: initial;width:80%">
                                <h5 style="padding-top:0px;font-weight: bold;font-size: 17px;margin: 21px 10px;">Total Number of Pending Quotes</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-primary noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px;border-radius: 17px;">
                            <div class="panel-icon" style="color: #000; font-size: 25px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px;font-weight: bold;">43</div>
                            <div class="media-body" style="overflow: initial;width:78%">
                                <h5 style="padding-top:0px;font-weight: bold;font-size: 17px;margin: 21px 10px;">Total illustrative Quotes Ready</h5>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row row-stat">
                <div class="col-md-4">
                    <div class="panel panel-primary noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px; border-radius: 17px;">
                            <div class="panel-icon" style="color: #000; font-size: 25px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px;font-weight: bold;">59</div>
                            <div class="media-body" style="overflow: initial; width:78%" >
                                <h5 style="padding-top:0px;font-weight: bold;font-size: 17px;margin: 21px 10px;">Total Number of Cancelled Quotes</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-warning-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px; border-radius: 17px;">
                            <div class="panel-icon" style="color: #000; font-size: 25px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px;font-weight: bold;">78</div>
                            <div class="media-body" style="overflow: initial;width:68%">
                                <h5 style="padding-top:0px;font-weight: bold;font-size: 17px;margin: 21px 10px;">Total Number of Sold Quotes</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-success-alt noborder">
                        <div class="panel-heading panel-heading-dashboard noborder" style="padding:20px 10px; border-radius: 17px;">
                            <div class="panel-icon" style="color: #000; font-size: 25px;  padding-top: 15px; word-wrap: break-word;  text-align: center; margin-right:5px;font-weight: bold;">59</div>
                            <div class="media-body" style="overflow: initial; width:68%" >
                                <h5 style="padding-top:0px;font-weight: bold;font-size: 17px;margin: 21px 10px;">Total Number of Users</h5>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- contentpanel -->
	</div><!-- mainpanel -->
</div><!-- mainwrapper -->

