<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $title_for_layout; ?></title>
	</head>
	<body>
		<table cellspacing="0" align="center" cellpadding="0" border="0" width="500">
			<?php echo $this->fetch('content'); ?>
			<tr>
				<td>
					<p style=" font-size:16px; color:#50505a; line-height:28px; font-family:Arial; font-weight:normal; margin:0; padding:0 0 25px; ">If you have any  problems, feel free to contact our support Team : <a href="mailto:<?php echo $templatemail;?>" style="color:#4087c3; text-decoration:none;"><?php echo $templatemail;?> </a> </p>
			
				</td>
			</tr>
			<tr>
				<td style="padding:25px 0; border-top:1px solid #e9e9ea;">
					<a href="#"><img src="<?php echo $imagepath; ?>img/logo-black.png" width="" height="" /></a>
					<p style=" font-size:14px; color:#50505a; font-family:Arial; font-weight:normal; margin:10px 0 0;">Ritevet &copy; <?php echo date('Y');?></p>
				</td>
			</tr>
		</table>
	</body>
</html>
