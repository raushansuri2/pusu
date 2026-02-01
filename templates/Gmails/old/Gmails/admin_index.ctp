<?=$this->Element('admin/breadcrumbs');?>
<?php //echo "<pre>"; print_r($emaillist); die(); ?>
<style>
	#fancybox-title{display: none !important;}
</style>
<div>
	<article>
		<header>
			<h2>Gmail Manager</h2>
			<div style="float:right;">
				
				<!--<a href="javascript:" onClick="return formsubmit('Publish');" class="button">Publish</a>
				<a href="javascript:" onClick="return formsubmit('Unpublish');" class="button">Unpublish</a>-->
				<a href="javascript:" onClick="return formsubmit('Delete');" class="button">Delete</a>
				
				
			</div>
		</header>
	</article>
	<div class="input text" style="overflow:hidden;">
		Search: <?php echo $this->Form->input('search',array('label'=>false,'id'=>'search','div'=>false,'value'=>$search,'style'=>'margin-left:10px; margin-right:10px;')); ?>
		View:
		<?php echo $this->Form->input('view',array('options'=>array('20'=>'20','50'=>'50','100'=>'100','ALL'=>'All'),'id'=>'view','div'=>false, 'legend'=>false, 'label'=>false,'value'=>$limit,'style'=>'margin-left:10px;')); ?>
		<button type="button" style="margin-top:10px; margin-left:10px" onclick="change_url();">Search</button>
	
	</div>
	<br clear = "all" />
	<?php echo $this->element('admin/message');?>
	<?php echo $this->Form->create('Gmail', array('name'=>'Gmail','action' => 'delete','id'=>'GmailDeleteForm','onSubmit'=>'return validate(this)','class'=>'table-form'));?>
	<?php echo $this->Form->hidden('action',array('id'=>'action','value'=>'')); ?>
	
	<table >
		<tr>
			<th><?php echo $this->Form->checkbox('check',array('value'=>1,'onchange'=>"CheckAll(this.value)",'class'=>'check-all')); ?></th>
			<th>&nbsp;</th>
			<th>SNo.</th>
			<th>To Name</th>
			<th>From Name</th>
                       
			<th>Date</th>
			<!--<th>Actions</th>-->
		</tr>
		
		<?php $i = 1; ?>
		<?php foreach ($emaillist as $gallery){?>
		<tr>
			<td><?php echo $this->Form->checkbox($gallery['Gmail']['id'],array('value'=>$gallery['Gmail']['id'])); ?></td>
			<td>
				
			</td>
			<td><?php echo $i++; ?></td>
			<td><!--<a href="#touser<?php echo $gallery['Gmail']['id'];?>" id="touser<?php echo $gallery['Gmail']['id'];?>"  class="view" title="View User" rel="tooltip"><?php echo $gallery['User']['firstName']; ?></a>-->
				<?= $this->Html->link($gallery['User']['firstName'], array('controller' => 'gmails', 'action' => 'view', $gallery['Gmail']['id']), array('escape' => false, 'class' => 'view fancybox', 'title' => 'View Item', 'rel' => 'tooltip')) ?>
			</td>
                        <td>
				<?= $this->Html->link($gallery['FromUser']['User']['firstName'], array('controller' => 'gmails', 'action' => 'view', $gallery['Gmail']['id']), array('escape' => false, 'class' => 'view fancybox', 'title' => 'View Item', 'rel' => 'tooltip')) ?>
				<?php //echo $this->Html->link($gallery['FromUser']['User']['firstName'],array('controller'=>'galleries','action'=>'albumdetail',$gallery['Gmail']['id'])); ?>
			</td>
			<td>
				<?php //$image = ($gallery['Gmail']['status']==1)?'admin/icon_success.png':'admin/icon_notification_error.png';?>
				<?//=$this->Html->image($image);?>
				<?php echo date("F j, Y",$gallery['Gmail']['date']); ?>
			</td>
			
		</tr>
		
		<?php }?>
		 <tfoot>
			 <tr>
				 <td colspan="5">
					 <?php $search_keyword = '' ?>
					 <?php if(!$emaillist){?>
					<div style='color:#FF0000'>No Record Found</div>
						<?php } else{ ?>
						<ul class="pagination">
							
							<?php if($this->Paginator->first()){?>
							<li><?php echo $this->Paginator->first('« First',array('class'=>'button gray')); ?></li>
							<?php } ?>
							
							<?php if($this->Paginator->hasPrev()){?>
							<li><?php echo $this->Paginator->prev('< Previous',array('class'=>'button gray'), null, array('class'=>'disabled'));?>&nbsp;... &nbsp;</li>
							
							<?php } ?>
							
							<?=$this->Paginator->numbers(array('modulus'=>6,'tag'=>'li','class'=>'','separator'=>'')); ?>
							
							
                            
                          
                            
                            <?php if($this->Paginator->hasNext()){?>
                            
								<li>&nbsp;... &nbsp;<?php echo $this->Paginator->next('Next >',array('class'=>'button gray'));?></li>
							<?php } ?>
							<?php if($this->Paginator->last()){?>
							<li><?php echo $this->Paginator->last('Last »',array('class'=>'button gray')); ?></li>
							<?php } ?>
						</ul>
						<?php } ?>
					</td>
				</tr>
			</tfoot>
    						
		
	</table>
	</form>
</div>
<script type="text/javascript">	

function formsubmit(action)
{
	//alert(action);
	var flag=true;
//	if(action=='Delete')
		//flag=confirm('Are You Sure, You want to Delete this Product(s)!');
	if(flag)
	{
		document.getElementById('action').value=action;
		if(validate())
			document.getElementById('GmailDeleteForm').submit();
	}
}

function validate(){
		var ans="0";
		for(i=0; i<document.Gmail.elements.length; i++){
			if(document.Gmail.elements[i].type=="checkbox"){
				if(document.Gmail.elements[i].checked){
					ans="1";
					break;
				}
			}
		}
		if(ans=="0"){
			alert("Please select message to "+document.getElementById('action').value);
			return false;
		}else{
			var answer = confirm('Are you sure you want to '+document.getElementById('action').value+' Gmail(s)');
			if(!answer)
				return false;
		}
		return true;
	}	


function CheckAll(chk)
{
//alert(document.getElementById('PageCheck').checked);
//alert(document.getElementsByTagName('checkbox').length);
	var fmobj=document.getElementById('GmailDeleteForm');
	for (var i=0;i<fmobj.elements.length;i++) 
	{
		var e = fmobj.elements[i];
		if(e.type=='checkbox')
			fmobj.elements[i].checked=document.getElementById('GmailCheck').checked;
	}
	
}
function change_url(){
	var search = '';
	var limit = document.getElementById('view').value;
	
	if(document.getElementById('search').value==''){
		search = '_blank';
	}else{
		search = document.getElementById('search').value;
	}
	
	document.location.href = '<?=Configure::read('HTTP_PATH');?>/admin/galleries/index/'+search+'/'+limit;
}

</script>
