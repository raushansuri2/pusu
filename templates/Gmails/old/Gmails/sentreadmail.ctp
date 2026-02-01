<div class="bicky-gmail">

<div id="desh_mid_profile2">
          <!--desh mid profile title-->
          <div id="message_main_box">
		<!--message top main-->
		<div id="message_top_main">
			<div id="message_main_box_left">Sent Message</div>
             <div style="width:200px; float:right; mmargin-top:14px; margin-right:10px; cursor:pointer;">
            <?php echo $this->Html->image('back.png',array("alt" => "Back", 'title'=>'Back','style'=>'float:right;','onclick'=>"history.back();"    )); ?>
             </div>
			</div>



<?php //echo "<pre>"; print_r($mails[0]) ; die(); ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bicky-table" >
<tr>
<td width="15%" align="left" class="maillist maillist_new">
		<b>Delete: </b>	  </td>
		<td class="maillist maillist_content" >
			<?php echo $this->Form->create('Gmail', array('name' => 'maillist','controller' => 'mails','action' => 'sentmails','onSubmit'=>'return validate(this)'));?>
			<?php echo $this->Form->hidden('id', array('id'=>'emailid','value'=>$mails[0][Gmail][id])); ?>
			<?php echo $this->Form->submit('admin/delete.gif', array('div' => false,'class'=>'delete-icon','title'=>'Delete')); ?>
			<?php echo $this->Form->end(); ?>
		</td>
	</tr>
   <tr>
	  <td width="15%" align="left" class="maillist maillist_new">
		<b>From: </b>	  </td>
	  <td  width="85%" class="maillist maillist_content">
		<?php echo $mails[0][User][username]; ?>	  </td>
   </tr>
   <tr>
	  <td width="15%" align="left" class="maillist maillist_new">
		<b>To : </b>	  </td>
	  <td width="85%" class="maillist maillist_content">
		<?php	 echo $To_user[0][User][username]; 		//echo $_SESSION[User][user][User][firstName]; ?>
	  </td>
   </tr>
   <tr>
	  <td width="15%" align="left" class="description maillist_new">
		<b>Subject : </b>	  </td>
	  <td width="85%" class="description maillist_content">
		<?php echo $mails[0][Gmail][subject]; ?>	  </td>
   </tr>
   <tr>
	  <td width="15%" align="left" class="description maillist_new">
		<b> Date :  </b>
	  </td>
	  <td  width="85%" class="description maillist_content">
		<?php echo date('F j, Y, g:i a',$mails[0][Gmail][date]); ?>
	  </td>
   </tr>
</table>
<div style="padding-bottom:50px; padding-top:20px; border:1px solid rgb(192, 192, 192);">
<table border="0" cellpadding="5" cellspacing="0" width="100%" >
	<tr>
		<td class="maillist" style="padding:10px;">
			
			<?php echo $mails[0][Gmail][message]; ?>
			
		</td>
	</tr>
</table>
</div>
<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tr>
		<td style="padding:15px;">
			
		</td>
	</tr>
</table>

</div>
</div>
</div>