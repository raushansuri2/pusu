             <!-- <div class="mid_profile_top">-->
			<!--<h1>Chris Jones <img src="img/m-info.png" /></h1>-->
			<!--<div class="profile_mid_title">
				
			<div class="profile_mid_title">
			      <?php //echo $this->element('userinfo');?>
			   </div>
			</div>
			</div>-->
<div class="bicky-gmail">
<div id="desh_mid_profile2">
          <!--desh mid profile title-->
          <div id="message_main_box">
		<!--message top main-->
		<div id="message_top_main">
			<div id="message_main_box_left">Read Message</div>
        
			</div>
			<div class="message_main_box_left_head">
				<a onClick="history.back();">Back</a>
            </div>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bicky-table">
	<tr>
	<td width="15%" align="left" class="maillist maillist_new">
		<b>Delete: </b>	  </td>
		<td class="maillist maillist_content">			
			<?php echo $this->Form->create('Gmail', array('name' => 'maillist','controller' => 'mails','action' => 'maillist','onSubmit'=>'return validate(this)'));?>
			<?php echo $this->Form->hidden('id', array('id'=>'emailid','value'=>$mails[0][Gmail][id])); ?>
			<?php echo $this->Form->submit('admin/delete.gif', array('div' => false,'class'=>'delete-icon','title'=>'delete')); ?>
			<?php echo $this->Form->end(); ?>            
		</td>
	</tr>

   <tr>
	 <td width="15%" align="left" class="maillist maillist_new">
		<b>From: </b>	  </td>
	  <td  width="85%" class="maillist maillist_content">
		<?php echo $to_user_detail[0][User][username]; ?></td>
   </tr>
   <tr>
	  <td width="15%" align="left" class="maillist maillist_new">
		<b>To : </b>	  </td>
	   <td  width="85%" class="maillist maillist_content">
		<?php echo $_SESSION[User][user][User][firstName]; ?> <?php echo $_SESSION[User][user][User][lastName]; ?> &nbsp;<!--< <?php //echo $_SESSION[User][user][User][emailId]; ?>>-->
	  </td>
   </tr>
   <tr>
	  <td width="15%" align="left" class="description maillist_new">
		<b>Subject: </b>	  </td>
	 <td  width="85%" class="maillist maillist_content">
		<?php echo $mails[0][Gmail][subject]; ?>	  </td>
   </tr>
   <tr>
	 <td width="15%" align="left" class="description maillist_new">
		<b>Date: </b>	  </td>
	  <td  width="85%" class="maillist maillist_content">
		<?php echo date('F j, Y, g:i a',$mails[0][Gmail][date]); ?>	  </td>
   </tr>
</table>
<div class="message_comment">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tr>
		<td class="description" style="padding:10px;">
			
			<?php echo $mails[0][Gmail][message]; ?>
			
		</td>
	</tr>
</table>
</div>
<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tr>
		<td style="padding:15px 0;">
			<?php //echo $this->Html->image('reply.png',array('url'=>array('controller'=>'gmails','action'=>'writemail',$mails[0][Gmail][user_id]))); ?>
		
		
			<?php echo $this->Html->link('Reply',array('controller'=>'gmails','action'=>'writemail',$mails[0][Gmail][user_id]),array('class'=>'btn')); ?>
		
		
		</td>
	</tr>
</table>

</div>
</div>
</div>


