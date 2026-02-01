<script>
      function validate(){
		var ans="0";
		for(i=0; i<document.maillist.elements.length; i++){
			if(document.maillist.elements[i].type=="checkbox"){
				if(document.maillist.elements[i].checked){
					ans="1";
					break;
				}
			}
		}
		if(ans=="0"){
			alert("Please select Inbox to Delete");
			return false;
		}else{
			var answer = confirm('Are you sure you want to delete inbox(s)');
			if(!answer)
				return false;
		}
		return true;
	}	
</script>

<!--sidebar mid-->

            <!--<div class="mid_profile_top">
		  <div class="profile_mid_title">
			<div class="profile_mid_title">
			      <?php //echo $this->element('userinfo');?>
			</div>
		  </div>
	    </div>-->
	    <!--<div class="colla_left">Inbox</div>-->
	    
<div class="clr"></div>
<?php echo $this->element('front_message');?>
<div class="golden_main_top" style="margin-top: 0px;">
<div class="sidebar-mid">
<div class="bicky-gmail">
<div id="desh_mid_profile2">

	<div id="message_main_box">
		<!--message top main-->
		<div id="message_top_main">
				 
		<!--message main box left-->
			<div id="message_main_box_left">Electronic Message</div>
			<!--message main box left closed-->
					  
			<!--message main box right-->
			<div id="message_main_box_right">
				<ul>
				    <li><?php echo $this->Html->link('Inbox', array('controller'=>'gmails', 'action' => 'maillist', $loginid), array('escape' => false, 'class'=>'active')); ?></li>
					<li><?php echo $this->Html->link('Write Message', array('controller'=>'gmails', 'action' => 'writemail'), array('escape' => false, 'class'=>'')); ?></li>
					<li><?php echo $this->Html->link('Sent Message', array('controller'=>'gmails', 'action' => 'sentmails'), array('escape' => false, 'class'=>'')); ?></li>
					<li><?php echo $this->Html->link('Draft', array('controller'=>'gmails', 'action' => 'readdraftmail'), array('escape' => false, 'class'=>'')); ?></li>
				</ul>
			</div>
			<!--meassage mainbox right closed-->
					  
		</div>
		<!--message top main closed-->
					  
		<!--message box title-->
		<div id="message_box_title" class="bicky_message_box_title">
			<?php echo $this->Form->create('Gmail', array('name' => 'maillist','controller' => 'gmails','action' => 'maillist','onsubmit'=>'return validate();'));?>
			<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="margin-left:0px;" class="bicky-table">
				<tr>
					<td align="left" valign="top" class="message_title message_title_new"><?php echo $this->Form->submit('admin/delete.gif', array('div' => false,'title'=>'Delete')); ?></td>
				  <td width="25%" align="left" valign="top" class="message_title message_title_new">From</td>
				  <td width="40%" align="left" valign="top" class="message_title message_title_new">Subject</td>
				  <td width="24%" align="left" valign="top" class="message_title message_title_new">Date </td>
				</tr>
				
				<?php
				if(count($maillists) != '0')
				{
				foreach($maillists as $maillist)
				{
					$firstname=$maillist[User][firstName];
					$lastname=$maillist[User][lastName];
					$subject=$maillist[Gmail][subject];
					$mail_id=$maillist[Gmail][id];
					$message=$maillist[Gmail][message];
					$date=$maillist[Gmail][date];
			?>
				<tr>
					<td align="left" valign="top" class="message_text message_text_new"><?php echo $this->Form->checkbox($maillist['Gmail']['id'],array('value'=>$maillist['Gmail']['id'])); ?> </td>
				  <td align="left" valign="top" class="message_text message_text_new"><label>
					<?php echo $this->Html->link(__($maillist['User']['firstName'],true), array('controller'=>'gmails', 'action' => 'readmail', $maillist['Gmail']['id']), array('escape' => false, 'class'=>'link_txt')); ?>
				  </label>
				  </td>
				  <td align="left" valign="top" class="message_text message_text_new">
						<?php echo $this->Html->link(__($subject,true), array('controller'=>'gmails', 'action' => 'readmail', $maillist['Gmail']['id']), array('escape' => false, 'class'=>'link_txt')); ?>
				  </td>
				  <td align="left" valign="top" class="message_text message_text_new">
					<?php $dt=date('d-m-Y',$date); ?>
						<?php echo $this->Html->link(__($dt,true), array('controller'=>'gmails', 'action' => 'readmail', $maillist['Gmail']['id']), array('escape' => false, 'class'=>'link_txt')); ?>
				  </td>
				</tr>
				<?php
				}
				}else{?>
				 <tr>
					
				  <td align="left" valign="top" colspan="4" aline="center" class="message_text message_text_new">
					No Message Found !	
				  </td>
				 
				</tr>   
				    
				<?php }
				?>
			</table>
			<?php echo $this->Form->end(); ?>
		</div>
					  <!--message box title closed-->
	</div>
</div>
</div>
<div class="pagination" style="float:right; padding-right:10px;">
    <?php if($this->Paginator->hasPrev()){?>  
   <?php echo $this->Paginator->prev('<< '.__('previous', true), $search_keyword, null, array('class'=>'disabled'));?>
|
<?php } ?>
   <?php //echo $this->Paginator->numbers($search_keyword);?>
    
   <?php if($this->Paginator->hasNext()){?>
   <?= $this->Paginator->numbers(array('modulus' => 6, 'tag' => 'li', 'class' => '', 'separator' => '')); ?>
|
	<?php echo $this->Paginator->next('Next >', array('class' => '')); ?>
	<?php } ?>
</div>
</div>
</div>