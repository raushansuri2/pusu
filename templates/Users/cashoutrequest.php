<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="heading">
		    <?php $amount = $type == 1 ? @$getInfo->user->wallet : @$getInfo->wallet; ?>
			<h2>Current In Wallet:<span> <?php echo '$'.$amount; ?></span></h2>
		</div>
	</div>
</div>
<div class="row">
	<form method="post" action="" />
		<div class="col-md-3 col-sm-5"></div>
		<div class="col-md-6 col-sm-5">
			<label>Enter a withdraw amount($) <span>*</span></label>
			<input style="width:100%" name="requested_amount" min="1" max="<?php echo $amount;?>" type="number" required>
			<br><br><button type="submit" class="theme-btn">Withdrawal</button>
		</div>
	</form>
</div>
				
				
				
				
				
				
					
					
