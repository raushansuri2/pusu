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
			alert("Please select sent message to Delete");
			return false;
		}else{
			var answer = confirm('Are you sure you want to delete sent message(s)');
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
		  <!--<div class="colla_left">Sent</div>-->
		  
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
			<div id="message_main_box_left"> Sent Message</div>
			<!--message main box left closed-->
					  
			<!--message main box right-->
			<div id="message_main_box_right">
				<ul><li><?php echo $this->Html->link('Inbox', array('controller'=>'gmails', 'action' => 'maillist', $loginid), array('escape' => false, 'class'=>'')); ?></li>
					<li><?php echo $this->Html->link('Write Message', array('controller'=>'gmails', 'action' => 'writemail', $loginid), array('escape' => false, 'class'=>'')); ?></li>
					<li><?php echo $this->Html->link('Sent Message', array('controller'=>'gmails', 'action' => 'sentmails'), array('escape' => false, 'class'=>'active')); ?></li>
					
					<li><?php echo $this->Html->link('Draft', array('controller'=>'gmails', 'action' => 'readdraftmail'), array('escape' => false, 'class'=>'')); ?></li>
				</ul>
			</div>
			<!--meassage mainbox right closed-->
					  
		</div>
		<!--message top main closed-->
					  
		<!--message box title-->
		<div id="message_box_title" class="bicky_message_box_title">
			<?php echo $this->Form->create('Gmail', array('name' => 'sentmails','controller' => 'gmails','action' => 'sentmails','onsubmit'=>'return validate();'));?>
			<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="margin-left:0px;" class="bicky-table">
				<tr>
					<td align="left" valign="top" class="message_title message_title_new"><?php echo $this->Form->submit('admin/delete.gif', array('div' => false,'title'=>'Delete','confirm'=>'are you sure?')); ?></td>
				  <td width="25%" align="left" valign="top" class="message_title message_title_new">To</td>
				  <td width="40%" align="left" valign="top" class="message_title message_title_new">Subject</td>
				  <td width="24%" align="left" valign="top" class="message_title message_title_new">Date</td>
				</tr>
				
				<?php
				foreach($maillists as $maillist)
				{
					$firstname=$maillist[User][firstName];
					$lastname=$maillist[User][lastName];
					$subject=$maillist[Gmail][subject];
					$mail_id=$maillist[Gmail][id];
					$message=$maillist[Gmail][message];
					$date=$maillist[Gmail][date];
				     $name1=$this->requestAction('gmails/tousername/'.$maillist['Gmail']['to_userid']);
				  
				        //$name2=$this->requestAction('gmails/sentmails/'.$maillist['Gmail']['to_userid']);
			?>
				<tr>
					<td align="left" valign="top" class="message_text">
					<?php echo $this->Form->checkbox($maillist['Gmail']['id'],array('value'=>$maillist['Gmail']['id'])); ?>
					</td>
				  <td align="left" valign="top" class="message_text"><label>
					<?php echo $this->Html->link(__($name1[0][User][username],true), array('controller'=>'gmails', 'action' => 'sentreadmail', $maillist['Gmail']['id']), array('escape' => false, 'class'=>'link_txt')); ?>
				  </label>
				  </td>
				  <td align="left" valign="top" class="message_text">
						<?php echo $this->Html->link(__($subject,true), array('controller'=>'gmails', 'action' => 'sentreadmail', $maillist['Gmail']['id']), array('escape' => false, 'class'=>'link_txt')); ?>
				  </td>
				  <td align="left" valign="top" class="message_text">
					<?php $dt=date('d-m-Y',$date); ?>
						<?php echo $this->Html->link(__($dt,true), array('controller'=>'gmails', 'action' => 'sentreadmail', $maillist['Gmail']['id']), array('escape' => false, 'class'=>'link_txt')); ?>
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