	<!-- Load jquery for ckeditor and ckfinder -->
	<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
	<?php echo $this->Html->script('ckfinder/ckfinder.js'); ?>
	
	<!-- End Load jquery for ckeditor and ckfinder -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<?php echo $this->Html->script('ajquery.tokeninput.js'); ?>
	<?php echo $this->Html->css('token-input-facebook.css'); ?>

<?php
//echo "<pre>"; print_r($emaillist);
	$i=1;
	$count=count($emaillist);
	$tag="[";
	foreach($emaillist as $email)
	{
		$tag.='{"name":"'.$email['User']['username'].'","id":"'.$email['User']['emailId'].'"}';
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
		
		//function validate(frm)
		//{
		
/*		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var address = document.getElementById('emailid').value;
		if(reg.test(address) == false)
		{
			alert("Sorry! we cannot complete your request, please enter valid Email Address");
			document.getElementById('emailid').focus();
			return false;
		}
		*/
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
	
		/*if(document.getElementById('PageContent').value=='')
		{
			alert("Please Enter message");
			document.getElementById('PageContent').focus();
			return false;
		}*/
		
		
		//}
		
		
		
		
		
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
	
	window.onload=function()
	{
		document.getElementById('searchmail').style.color='#CCC';
		document.getElementById('searchmail').value="Search";
		document.getElementById('searchmail').style.color='#CCC';
	}
	
	function clearsearch()
	{
		if(document.getElementById('searchmail').value=="" || document.getElementById('searchmail').value=="Search")
		{
			document.getElementById('searchmail').style.color='#000000';
			document.getElementById('searchmail').value="";
		}
		
	}
	
	function chksearch()
	{
		if(document.getElementById('searchmail').value=="" || document.getElementById('searchmail').value=="Search")
		{
			document.getElementById('searchmail').style.color='#CCC';
			document.getElementById('searchmail').value="Search";
			
			i=1;
			
			while(1)
			{
				if(document.getElementById('emailid_'+i))
				{
					
					document.getElementById('divid_'+i).style.display='';
					i++;
				}
				else
				{
					return false;
				}
			}
		}
	}
	
	function search()
	{
		if(document.getElementById('searchmail').value=="" || document.getElementById('searchmail').value=="Search")
		{
			
		}
		else
		{
			var searchtext=document.getElementById('searchmail').value;
			i=1;
			while(1)
			{
				if(document.getElementById('emailid_'+i))
				{
					var queuetext=document.getElementById('emailid_'+i).text;
					if(queuetext.search(searchtext) >= 0)
					{
						
					}
					else
					{
						document.getElementById('divid_'+i).style.display='none';
					}
					i++;
				}
				else
				{
					return false;
				}
			}
		}
	}

</SCRIPT>


<div id="desh_mid_profile2">
	<div><?php //echo $this->element('message'); ?></div> 
   <!--message main box-->
   <div id="message_main_box">
      <!--message top main-->
      <div id="message_top_main">
          
         <!--message main box left-->
         <div id="message_main_box_left">Write Message</div>
         <!--message main box left closed-->
              
      </div>
      <!--message top main closed-->
              
      <!--message box title-->
      <div id="message_box_title">
			<?php echo $this->Form->create('Gmail', array('name' => 'writemail','controller' => 'gmails','id'=>'form_draftmail','action' => 'writemail','onSubmit'=>'return validate(this)'));?>
			<?php echo $this->Form->hidden('id');?>
			
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
				
                
                <tr>
					<td align="left" valign="top" class="mail_text">To <span style="color:red;"> *</span></td>
					<td align="left" valign="top"><label>
					<?php echo $this->Form->text('email', array('id'=>'demo-input-facebook-theme','class'=>'field-sub2','style'=>'width:300px;')); ?>
						</label><div class="clr"></div>Separate users using commas(,)
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
						<?=$this->Form->textarea('message', array('cols'=>'60','rows'=>'3'));?>
						<?=$this->Fck->load('Gmail.message');?>
						<?=$this->Form->hidden('message1', array('id'=>'message1','value'=>''));?>
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
						
						<!--<?php echo $this->Html->image("prev.png",array('border' => '0','align'=>'absmiddle','style'=>'margin-top: -6px;','onclick'=>'history.back();')); ?>&nbsp;&nbsp;--> <?php //echo $this->Form->checkbox('draft',array('onclick'=>'frmsubmitgroup("draftmail")')); ?><?php echo $this->Form->submit('Send',array('class'=>'btn')); ?>&nbsp;<a href="javascript:frmsubmitgroup('draftmail');" class="btn btn_sp">Save In Draft</a><div id="message_display_draft"></div>
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
      <!--message box title closed-->
   </div>
   <!--message main box closed-->
</div>


<script >
  function frmsubmitgroup(stepme){
	//alert("sdsds
	var editorText = CKEDITOR.instances.GmailMessage.getData();
        
	document.getElementById('GmailMessage').value=editorText;
	
	
        var step = stepme;
	var baseUrl = "<?php echo $http1;?>";

	jQuery.ajax({
			     url: baseUrl+"gmails/"+step,
			     type: "post",
			     async: false,
			     data:jQuery('#form_'+step).serialize(),
			     dataType:"html",
			     beforeSend: function(html) { // this happens before actual call
                                jQuery("#results"+step).html('');
                                jQuery("#searchresults"+step).show();
                                //$(".word").html(searchString);
                            },
                            success: function(html){ // this happens after we get results
                                //alert('draft save sucessfully !');
				if(html=='succuss'){
					window.location.href = baseUrl+'/gmails/readdraftmail';
                                //jQuery("#step1").hide();
                                //jQuery("#message_display_draft").show();
                                    
                                } else if(html=='succussimage'){
					window.location.href = baseUrl+'/groups/index';
                                
                                //jQuery("#step1").hide();
                                //jQuery("#step2").show();step6
                                    
                                } else if(html=='step2'){
                                    
					jQuery("#step2").hide();
					jQuery("#step3").show();
                                    
                                } else if(html=='step3'){
                                
					jQuery("#step3").hide();
					jQuery("#step4").show();
                                    
                                } else if(html=='step4'){
                                    jQuery("#step4").hide();
                                    jQuery("#step5").show();

                                            
                                } else if(html=='step5'){
                                    jQuery("#step5").hide();
                                    jQuery("#step6").show();

                                            
                                } else if(html=='business'){
                                    jQuery("#step1").hide();
                                    jQuery("#step7").show();

                                            
                                } else if(html=='final'){
                                    jQuery("#step7").hide();
                                    jQuery("#step6").show();

                                            
                                } else {
                                
                                
                                jQuery("#results"+step).show();
                                jQuery("#searchresults"+step).hide();
                                jQuery("#results"+step).append(html);
                                
                                }
                                
                            }
                            
                          });
	return false;
      
    }
  
</script>
