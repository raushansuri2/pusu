<!--sidebar mid-->
            <!--<div class="mid_profile_top">
		  <div class="profile_mid_title">
			<div class="profile_mid_title">
			      <?php //echo $this->element('userinfo');?>
			</div>
		  </div>
	    </div>-->
	    <div class="colla_left">Secure File</div>
	    
<div class="golden_main_top">
<div class="sidebar-mid">
<div id="desh_mid_profile2">
	<div align="center"><strong><?php //echo $this->element('message'); ?></strong></div> 
	<div id="message_main_box">
		<!--message top main-->
		<div id="message_top_main">
				 
		<!--message main box left-->
			<div id="message_main_box_left">Draft</div>
			<!--message main box left closed-->
					  
			<!--message main box right-->
			<div id="message_main_box_right">
				<ul>
					<li><?php echo $this->Html->link('Write Email', array('controller'=>'gmails', 'action' => 'writemail', $loginid), array('escape' => false, 'class'=>'')); ?></li>
					<li><?php echo $this->Html->link('Inbox', array('controller'=>'gmails', 'action' => 'maillist', $loginid), array('escape' => false, 'class'=>'')); ?></li>
				</ul>
			</div>
			<!--meassage mainbox right closed-->
					  
		</div>
		<!--message top main closed-->
					  
		<!--message box title-->
		<div id="message_box_title">
			<?php echo $this->Form->create('Gmail', array('name' => 'sentmails','controller' => 'gmails','action' => 'sentmails','onSubmit'=>'return validatecho this)'));?>
			<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="margin-left:0px;">
				<tr>
					<td align="left" valign="top" class="message_title"><?php echo $this->Form->submit('admin/delete.gif', array('div' => false,'title'=>'Delete')); ?></td>
				  <td width="25%" align="left" valign="top" class="message_title">To</td>
				  <td width="40%" align="left" valign="top" class="message_title">Subject</td>
				  <td width="20%" align="left" valign="top" class="message_title">Date</td>
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

<div class="pagination" style="float:right; padding-right:10px;">
   <?php echo $this->Paginator->prev('<< '.__('previous', true), $search_keyword, null, array('class'=>'disabled'));?>
|
   <?php //echo $this->Paginator->numbers($search_keyword);?>
   <?=$this->Paginator->numbers(array('modulus' => 6, 'tag' => 'li', 'class' => '', 'separator' => '')); ?>
|
	<?php echo $this->Paginator->next('Next >', array('class' => '')); ?>
</div>
</div>
</div>