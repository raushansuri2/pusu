<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $title_for_layout; ?></title>
	</head>
	<body style="background:#f4f4f4;">
		<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#fff;">
			<tr>
				<td height="40" style="padding:0 74px;">&nbsp;</td>
			</tr>
			<tr>
				<td style="padding:0 74px;"><img src="<?php echo Configure::read('App.siteurl');?>img/admin/logo.png" width="" height="" />
					sadsa dsa dsad sad asdsadsad		
				</td>
			</tr>
			<tr>
				<td height="20" style="padding:0 74px;">&nbsp;</td>
			</tr>
			<?php echo $this->fetch('content'); ?>
			<tr>
				<td height="20" style="padding:0 74px;">&nbsp;</td>
			</tr>
			<tr>
				<td style="padding:10px 74px; font-size:14px; color:#777777; font-family:Arial, Helvetica, sans-serif; line-height:22px;">
				Best Regards,<br />
				TippingTree<br />
				Don't Worry, Be Happy</td>
			</tr>
			
		</table>
	</body>
</html>
