<?php
use Cake\Core\Configure;

?>
	<!-- Load jquery for ckeditor and ckfinder -->
	<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
	<?php //echo $this->Html->script('ckfinder/ckfinder.js'); ?>
	
	<!-- End Load jquery for ckeditor and ckfinder -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<?php echo $this->Html->script('ajquery.tokeninput.js'); ?>
	<?php echo $this->Html->css('token-input-facebook.css'); ?>

<?php
//echo "<pre>"; print_r($emaillist);
	$i=1;
	$count=count($emaillist);
	$tag="[";
	foreach($emaillist as $key=>$email)
	{
		$tag.='{"name":"'.$email.'","id":"'.$key.'"}';
		if($i<$count)
		{
			$tag.=',';
		}
		$i++;
	}
	$tag.="]";
	
/*echo $tag;
	die('aa')*/
$prepopulate="[";
	if(!empty($emailx))
	{
		$prepopulate.='{"name":"'.$emailx['User']['username'].'","id":"'.$emailx['User']['emailId'].'"}';
		$prepopulate.=',';
	}
	
	$prepopulate.="]";
	
//echo $prepopulate; die();
?>

<script type="text/javascript">
$(document).ready(function() {
	$("#demo-input-facebook-theme").tokenInput(<?=$tag?>, {
		prePopulate: <?=$prepopulate?>,
		theme: "facebook"
	});
});
</script>


<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
	function validate(frm)
	{
		var ans="0";
		typename="Email";
		if(document.getElementById('demo-input-facebook-theme').value=='')
		{
			alert("Please Enter To");
			document.getElementById('demo-input-facebook-theme').focus();
			return false;
		}
		
		if(document.getElementById('subject').value=='')
		{
			alert("Please Enter The Subject");
			document.getElementById('subject').focus();
			return false;
		}
	
		for(i=0; i<document.product.elements.length; i++)
		{
			if(document.product.elements[i].type=="checkbox"){
				if(document.product.elements[i].checked){
					ans="1";
					break;
				}
			}
		}
		if(ans=="0")
		{
			alert("Please select "+typename+" to be deleted");
			return false;
		}
		else
		{
			var answer = confirm('Are you sure you want to delete Product(s)');
			if(!answer)
				return false;
		}
		return true;
	}
	
	
	
	function addmail(eid)
	{
		emailadd=document.getElementById('emailid_'+eid).text;
		var emaillist=document.getElementById('emailid').value;
		if(emaillist!='')
		{
			emaillist=emaillist+','+emailadd;
		}
		else
		{
			emaillist=emailadd;
		}
		document.getElementById('divid_'+eid).style.display='none';
		
		document.getElementById('emailid').value=emaillist;
		return false;
	}
	

</SCRIPT>

<div class="add-charity-container">
    <h2>Message System</h2>
	<?php echo $this->Flash->render();?>
    <div class="white-bg">
        
        <div class="row" id="message_main_box_right">
                
                <ul>
                    <li><?php echo $this->Html->link('Inbox', array('controller'=>'gmails', 'action' => 'maillist'), array('escape' => false, 'class'=>'')); ?></li>
                    <li><?php echo $this->Html->link('Write Message', array('controller'=>'gmails', 'action' => 'writemail'), array('escape' => false, 'class'=>'active')); ?></li>
                    <li><?php echo $this->Html->link('Sent Message', array('controller'=>'gmails', 'action' => 'sentmails'), array('escape' => false, 'class'=>'')); ?></li>
                    <li><?php echo $this->Html->link('Draft', array('controller'=>'gmails', 'action' => 'draftmail'), array('escape' => false, 'class'=>'')); ?></li>
                </ul>
                
        </div>      
                
        <div id="message_box_title">
			<?php echo $this->Form->create('Gmail', array('name' => 'writemail','controller' => 'gmails','id'=>'form_draftmail','action' => 'writemail','onSubmit'=>'return validate(this)'));?>
			<?php echo $this->Form->hidden('id');?>
			
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
				
                
                <tr>
					<td align="left" valign="top" class="mail_text">To <span style="color:red;"> *</span></td>
					<td align="left" valign="top"><label>
					<?php echo $this->Form->text('email', array('id'=>'demo-input-facebook-theme','class'=>'field-sub2','style'=>'width:300px;')); ?>
						</label><div class="clr"></div><!--Separate users using commas(,)-->
					</td>
					
					
				</tr>
				<tr>
					<td valign="top" align="left" class="mail_text"></td>
					<td valign="top" align="left"><br></td>
				</tr>
				<tr>
					<td align="left" valign="top" class="mail_text">Subject <span style="color:red;"> *</span></td>
					<td align="left" valign="top"><label>
					<?php echo $this->Form->text('subject', array('id'=>'subject','class'=>'mail_field')); ?>
						
						</label>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left" class="mail_text"></td>
					<td valign="top" align="left"><br></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" class="mail_text">Message</td>
					<td align="left" valign="top">
						<?=$this->Form->textarea('message', array('cols'=>'60','rows'=>'3', 'id'=>'editor1'));?>
						<!--<textarea name="" cols="" rows="" class="mail_msg"></textarea>-->
					</td>
				</tr>
				<tr>
					<td valign="top" align="left" class="mail_text"></td>
					<td valign="top" align="left"><br></td>
				</tr>
				<tr>
					<td align="left" valign="top">&nbsp;</td>
					<td align="left" valign="top" width="300px;">
						
						<!--<?php echo $this->Html->image("prev.png",array('border' => '0','align'=>'absmiddle','style'=>'margin-top: -6px;','onclick'=>'history.back();')); ?>&nbsp;&nbsp;--> <?php //echo $this->Form->checkbox('draft',array('onclick'=>'frmsubmitgroup("draftmail")')); ?>
						<?php echo $this->Form->submit('Send',array('class'=>'btn', 'id'=>'sendBtn')); ?>&nbsp;
						<a href="javascript::void(0);" onclick="frmsubmitgroup('draftmail');" class="btn btn_sp">Save In Draft</a>
						<div id="message_display_draft"></div>
						<br /><?php //e($form->submit('Send', array('div' => false))); ?>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top">&nbsp;</td>
					<td align="left" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="top">&nbsp;</td>
					<td align="left" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="top">&nbsp;</td>
					<td align="left" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="top">&nbsp;</td>
					<td align="left" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="top">&nbsp;</td>
					<td align="left" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="top">&nbsp;</td>
					<td align="left" valign="top">&nbsp;</td>
				</tr>
			</table>
			
			<?php if($emailx['User']['emailId']!='') { 
			echo $this->Form->hidden('email', array('value'=>$emailx['User']['emailId']));
			}
			echo $this->Form->end();
			
			?>
      </div>
        
        
      <div class="cl"></div>  
    
    </div>
</div>
<script>
jQuery(document).ready(function(){
	CKEDITOR.replace('editor1');
	$("#editor1").addClass("required");
});
</script>

<script >
  function frmsubmitgroup(stepme){
	var editorText = CKEDITOR.instances.editor1.getData();
	document.getElementById('editor1').value=editorText;
	
    var step = stepme;
	var baseUrl = "<?php echo Configure::read('App.siteurl');?>";

	jQuery.ajax({
				url: baseUrl+"gmails/savedraft",
				type: "post",
				async: false,
				data:jQuery('#form_'+step).serialize(),
				dataType:"html",
				beforeSend: function(html) { 
					//jQuery("#results"+step).html('');
					jQuery("#sendBtn").hide();
				},
                success: function(html){
					//alert(html);
					//return false;
						if(html=='succuss'){
							window.location.href = baseUrl+'/gmails/draftmail';
                        } 
                }
                            
            });
		return false;
    }
  
</script>