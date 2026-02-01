<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!--
	function validate(frm){
		var ans="0";
		typename="Mail";
		for(i=0; i<document.mail.elements.length; i++){
				if(document.mail.elements[i].type=="checkbox"){
					if(document.mail.elements[i].checked){
						ans="1";
						break;
					}
				}
		}
		if(ans=="0"){
			alert("Please select "+typename+" to be deleted");
			return false;
		}else{
				
				var answer = confirm('Are you sure you want to delete mail(s)');
				if(!answer)
					return false;
		}
		return true;
	}
	
//-->
</SCRIPT>

<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
	<tr>
    	<td width="50%" height="30" class="dataHeading"><?php __('Mail Manager');?></td>
		<td width="50%" align="right"><?php //if($loggedIn): ?>
			<?php 
				//echo $html->link($html->image("admin/logout.gif"), array('controller'=>'../users', 'action' => 'logout'), array('escape' => false));
			?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php //endif; ?>
		</td>
    </tr>
    <tr>
    	<td colspan="2" class="mainBodyTable" style="padding-left:5px;">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" class="upperheading" style="padding-left:5px;">This section displays the list of Mails</td>
    </tr>
    <tr>
        <td colspan="2" class="mainBodyTable" style="padding-left:5px;">&nbsp;</td>
    </tr>
	<tr>
		<td colspan="2">
			<?php e($form->create('Mail', array('action' => 'mailindex')));?>
<!--				<table cellspacing="0" cellpadding="0"  border="0" width="45%" class="reportTable">
				
					<tr><td colspan="2" class="head">Search</td></tr> 
						<tr>
							<td class="upperheading"><b>Select From Date</b> </td>
							<td><?php echo $form->dateTime('fromdate','DMY',null, null,null,false)?></td>
						</tr>
						<tr>
							<td class="upperheading"><b>Select To Date</b></td>
							<td><?php echo $form->dateTime('todate','DMY',null, null,null,false)?></td>
						</tr>
						<tr>
							<td colspan="2" align="center"><input type="submit" value="Search"  class="button" name="search"></td>
						</tr>
						
				
			</table>
-->			<?php e($form->end()); ?>
		</td>
	</tr>
	<tr>
        <td colspan="2" class="mainBodyTable">

<?php e($form->create('Mail', array('name' => 'mail','controller' => 'mails','action' => 'delete','onSubmit'=>'return validate(this)')));?>
<br><?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?>
	<table class="reportTable" cellspacing="0" cellpadding="4" width="90%" border="0">
	<tbody>
      <tr>
			<td class="head" align="left" width="15%"><?php __('User From');?></td>
			<td class="head" align="left" width="15%"><?php __('User To');?></td>
			<td class="head" align="left" width="15%"><?php __('Subject');?></td>
			<td class="head" align="center" width="15%"><?php __('Message');?></td>
			<td class="head" align="center" width="15%"><?php __('Date');?></td>
			<td class="head" align="center" width="8%"><?php __('Delete');?></td>
		</tr>
                <?php
				
				//echo "<pre>222";print_r($orders);
$i = 0;
foreach ($mails as $mail):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="upperheading"><?php echo $mail[User][firstName]; ?>&nbsp;<?php echo $mail[User][lastName]; ?></td>
		<td class="upperheading">
		<?php
		
		$sql="SELECT * FROM users WHERE id='".$mail[Mail][to_userid]."' ";
	   $sql=mysql_query($sql);
	   $row = mysql_fetch_array($sql);
	   
		echo $row[firstName]; ?>&nbsp;<?php echo $row[lastName]; ?>
		
		</td>
		<td class="upperheading" align="left">
		<?php
		$mail[Mail][subject]=htmlentities($mail[Mail][subject], ENT_COMPAT, "ISO-8859-1");
		echo $mail[Mail][subject]; ?>
		</td>
		<td class="upperheading" align="left"><?php
		echo $mail[Mail][message];
		?></td>
		<td class="upperheading" align="center"><?php echo date('d-m-Y',$mail[Mail][date]); ?></td>
		<td align="center"> 
			 <?php echo $form->checkbox($mail['Mail']['id'],array('value'=>$mail['Mail']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	<?php if(!$mails){ ?>
	<tr>
      <td  colspan="6" align="center" class="upperheading">No Sent Mail Found</td>
   </tr>
	<?php }else{ ?> 
		<tr>	
			<td colspan="5"></td><td align="center"><input type="submit" name="delete" value="Delete" class="button"></td>
    		</tr>
	<?php } ?>

<?php e($form->end()); ?>
			</tbody>
			</table>
<br>
	<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array($search_keyword), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers($search_keyword);?>
	<?php echo $paginator->next(__('next', true).' >>', array($search_keyword), null, array('class'=>'disabled'));?>
</div>
		</td>
	</tr>			
 </table>