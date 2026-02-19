<div class="row">
	<div class="col-md-10 col-md-offset-1">
	    <div class="heading">
			<!--<h2>Find Service Provide <span>Near You</span></h2>-->
			<h2><span>Choose</span> a service</h2>
		</div>
	</div>
</div>
	
<div class="row">
	<div class="col-md-2 col-sm-5">&nbsp;</div>
	<div class="col-md-4 col-sm-5">
		<a href="<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'request_service']);?>" class="place-box">
			<div class="place-box-content">
				<h4>Request a Veterinarian Service</h4>
			</div>
			<div class="place-box-bg" style="background-image: url('<?php echo $this->Url->build('/');?>assets/img/8.png');"> </div>
		</a>
	</div>
	<div class="col-md-4 col-sm-5">
		<a href="<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'request_other']);?>" class="place-box">
			<div class="place-box-content">
				<h4>Request Other Pet Service Provider</h4>
			</div>
			<div class="place-box-bg" style="background-image: url('<?php echo $this->Url->build('/');?>assets/img/9.png');"></div>
		</a>
	</div>
</div>
					
					
				