<div style="background:#f6f6f6; padding:8PX; max-height:550px; overflow-y:auto;" id="view2">
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Gmail Subject</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$emaillist['Gmail']['subject']?></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Create Date</b></div>
		<div align="justify" style="float:left; width:400px;"><?php echo date("F j, Y",$emaillist['Gmail']['date']); ?></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Message</b></div>
		<div align="justify" style="float:left; width:400px;"><?=$emaillist['Gmail']['message']?></div>
		<div style="clear:both;"></div>
	</div>
</div>
