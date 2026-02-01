<!DOCTYPE html>
<?php
use Cake\Core\Configure;
use Cake\View\Helper\HtmlHelper;
?>
<html lang="en">
    <head>
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $PATH;?>cake.icon.png">
		<?php echo $this->element('admin/head'); ?>
    </head>
    <body>
        <header>
			<input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo Configure::read('App.siteurl');?>" />
            <?php echo $this->element('admin/header'); ?>
        </header>
        <section>
            <?php echo $this->fetch('content'); ?>
        </section>
        <!--<div class="cl" style="min-height:50px;"></div>-->
        <div class="footer-heading">		
		    <p>copyright © <?php echo date('Y');?><strong> Dating APP LLP</strong> All rights reserved. <br>
            Reproduction in whole or in part in any form or medium without express written permission is prohibited.</p>
            <!--<p class="fl"><?php echo $this->Html->image('admin/logo.png', array('style' => 'width:50px; margin-right: 5px'));?> designed and developed by <a href="https://evirtualservices.com" target="_blank">E-virtual services</a></p>-->
		</div>
        <?php echo $this->element('admin/footer'); ?>
    </body>
</html>
