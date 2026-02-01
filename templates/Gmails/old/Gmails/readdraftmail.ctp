<script>
      function validate(){
		var ans="0";
		for(i=0; i<document.sentmails.elements.length; i++){
			if(document.sentmails.elements[i].type=="checkbox"){
				if(document.sentmails.elements[i].checked){
					ans="1";
					break;
				}
			}
		}
		if(ans=="0"){
			alert("Please Select Draft to Delete");
			return false;
		}else{
			var answer = confirm('Are you sure you want to delete draft(s)');
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
			      <?php// echo $this->element('userinfo');?>
			</div>
		  </div>
	    </div>-->
<div class="clr"></div>
<?php echo $this->element('front_message');?>
<div class="golden_main_top" style="margin-top: 0px;">
<div class="sidebar-mid">
<div class="bicky-gmail">
<div id="desh_mid_profile2">
	<div id="message_main_box">
		<!--message top main-->
		<?php echo $this->element('front_message');?>
		<?php //echo  $session->flash("flash", array("element" => "alert"));?>
		<div id="message_top_main">
				
		<!--message main box left-->
			<div id="message_main_box_left"> Draft Message</div>
			<!--message main box left closed-->
					  
			<!--message main box right-->
			<div id="message_main_box_right">
				<ul>
					<ul><li><?php echo $this->Html->link('Inbox', array('controller'=>'gmails', 'action' => 'maillist', $loginid), array('escape' => false, 'class'=>'')); ?></li>
					<li><?php echo $this->Html->link('Write Message', array('controller'=>'gmails', 'action' => 'writemail', $loginid), array('escape' => false, 'class'=>'')); ?></li>
					<li><?php echo $this->Html->link('Sent Message', array('controller'=>'gmails', 'action' => 'sentmails'), array('escape' => false, 'class'=>'')); ?></li>
					
					<li><?php echo $this->Html->link('Draft', array('controller'=>'gmails', 'action' => 'readdraftmail'), array('escape' => false, 'class'=>'active')); ?></li>
				</ul>
				</ul>
			</div>
			<!--meassage mainbox right closed-->
					  
		</div>
		<!--message top main closed-->
					  
		<!--message box title-->
		<div id="message_box_title" class="bicky_message_box_title">
			<?php echo $this->Form->create('Gmail', array('name' => 'sentmails','controller' => 'gmails','action' => 'readdraftmail','onsubmit'=>'return validate();'));?>
			<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="margin-left:0px;" class="bicky-table">
				<tr>
					<td align="left" valign="top" class="message_title message_title_new"><?php echo $this->Form->submit('admin/delete.gif', array('div' => false,'title'=>'Delete')); ?></td>
				  <td width="25%" align="left" valign="top" class="message_title message_title_new">To</td>
				  <td width="40%" align="left" valign="top" class="message_title message_title_new">Subject</td>
				  <td width="24%" align="left" valign="top" class="message_title message_title_new">Date</td>
				</tr>
				
				<?php
				//echo "<pre>"; print_r($maillists);die();
				foreach($maillists as $maillist)
				{
					$firstname=$maillist[Draft][to_user_name];
					$lastname=$maillist[User][lastName];
					$subject=$maillist[Draft][subject];
					$mail_id=$maillist[Draft][id];
					$message=$maillist[Draft][message];
					$date=$maillist[Draft][created];
				     //$name1=$this->requestAction('gmails/tousername/'.$maillist['Gmail']['to_userid']);
				  
				        //$name2=$this->requestAction('gmails/sentmails/'.$maillist['Gmail']['to_userid']);
			?>
				<tr>
					<td align="left" valign="top" class="message_text message_text_new">
					<?php echo $this->Form->checkbox($maillist['Draft']['id'],array('value'=>$maillist['Draft']['id'])); ?>
					</td>
				  <td align="left" valign="top" class="message_text message_text_new"><label>
					<?php echo $this->Html->link(__($firstname,true), array('controller'=>'gmails', 'action' => 'draftmailread', $maillist['Draft']['id']), array('escape' => false, 'class'=>'link_txt')); ?>
				  </label>
				  </td>
				  <td align="left" valign="top" class="message_text message_text_new">
						<?php echo $this->Html->link(__($subject,true), array('controller'=>'gmails', 'action' => 'draftmailread', $maillist['Draft']['id']), array('escape' => false, 'class'=>'link_txt')); ?>
				  </td>
				  <td align="left" valign="top" class="message_text message_text_new">
					<?php $dt=date('d-m-Y',$date); ?>
						<?php echo $this->Html->link(__($date), array('controller'=>'gmails', 'action' => 'draftmailread', $maillist['Draft']['id']), array('escape' => false, 'class'=>'link_txt')); ?>
				  </td>
				</tr>
				<?php
				}
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