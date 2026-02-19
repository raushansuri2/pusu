<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="heading">
			<h2>Total <span>Earnings($)</span></h2>
		</div>
	</div>
</div>
<?php echo $this->Flash->render(); ?>
<div class="row">
	<?php if(@$user) { ?>
		<div class="col-md-6 col-sm-5">
			<span class="place-box">
				<div class="place-box-content">
					<?php if($userInfo->UTYPE == 2){ ?>
						<h4>Veterinary Wallet</h4>
					<?php }else{ ?>
						<h4>Pet Service Provider Wallet</h4>
					<?php } ?>
						
					<?php if($userInfo->UTYPE == 2){ ?>
						<span><strong>Total Earn Amount:</strong> <?php echo '$'.($userInfo->wallet + $vateti_cashout_amount);?></span><br>
					<?php }else{ ?>	
						<span><strong>Total Earn Amount:</strong> <?php echo '$'.($userInfo->wallet + $other_pet_cashout_amount);?></span><br>
					<?php } ?>
						
					<span style="color: #10FF00"><strong>Current In Wallet:</strong> <?php echo '$'.$userInfo->wallet;?></span>
					<br><br>
					<a href="<?php echo $this->Url->build('/');?>users/cashoutrequest/<?php echo $userInfo->UTYPE; ?>" onclick="return confirm('Are you sure want to withdrawal?')" class="theme-btn">Withdrawal</a>
				</div>
				<div class="place-box-bg" style="background-image: url('<?php echo $this->Url->build('/');?>assets/img/8.png');"> </div>
			</span>
		</div>
		<div class="col-md-6 col-sm-5">
			<span class="place-box">
				<div class="place-box-content">
					<h4>Product Wallet</h4>
					<span><strong>Total Earn Amount:</strong> <?php echo '$'.($user->wallet + $product_cashout_amount);?></span><br>
					<span style="color: #10FF00"><strong>Current In Wallet:</strong> <?php echo '$'.$user->wallet;?></span>
					<br><br>
					<a href="<?php echo $this->Url->build('/');?>users/cashoutrequest/1" onclick="return confirm('Are you sure want to withdrawal?')" class="theme-btn">Withdrawal</a>
				</div>
				<div class="place-box-bg" style="background-image: url('<?php echo $this->Url->build('/');?>assets/img/8.png');"> </div>
			</span>
		</div>
	<?php } ?>
</div>
				