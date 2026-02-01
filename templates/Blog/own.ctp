
					<div class="col-md-12 col-sm-12">
						<div class="row">
							<?php echo $this->Flash->render(); ?>
						<div class="search-body" style="width: 100%;">
                        <?php 
								echo $this->Form->create('Contacts',  ['type' => 'get', 'novalidate' => 'novalidate','style'=>"",'class'=>"bicky"]); ?>
                        <div class="row1">
                                    <div class="col-md-8 col-sm-5 min-pad">
										<div class="form-box">
											<?php echo $this->Form->input('keyword', ['templates' => ['inputContainer' => '{{content}}'],'value'=>$this->request->query('keyword'), 'class'=>'form-control width200','placeholder'=>'Enter Keyword to Search', 'style' => '', 'div'=>false, 'label'=>false, 'autocomplete'=>'off']); ?>
										</div>
									</div>
									<div class="col-md-2 col-sm-5 min-pad">
										<div class="form-box">
											<?php $this->Form->templates(['submitContainer' => '{{content}}']);                    
								echo $this->Form->submit('Search', ['style'=>"width:100%",'class' => 'btn btn-primary mr5 ml10',  'div' => false, 'label' =>false]); ?>
										</div>
									</div>
									

									<div class="col-md-2 col-sm-2 min-pad">
										<a href="<?php echo $this->Url->build(['controller' => 'Freestaffs', 'action' => 'add']);?>" class="btn btn-primary1 mr5 ml10" style="width:100%">Add</a>
									</div></div>
                                    </form>
                            
                            <!--
							
							<?php 
								echo $this->Form->create('Contacts',  ['type' => 'get', 'novalidate' => 'novalidate','style'=>"float:left;width:70%;",'class'=>"bicky"]);
								echo $this->Form->input('keyword', ['templates' => ['inputContainer' => '{{content}}'],'value'=>$this->request->query('keyword'), 'class'=>'form-control width200','placeholder'=>'Enter Keyword to Search', 'style' => 'float:left; width:75%;', 'div'=>false, 'label'=>false, 'autocomplete'=>'off']);
								
								$this->Form->templates(['submitContainer' => '{{content}}']);                    
								echo $this->Form->submit('Search', ['style'=>"float:right;width:20%;",'class' => 'btn btn-primary mr5 ml10',  'div' => false, 'label' =>false]);
								echo $this->Form->end();
							?>-->	
						</div>	<br style="clear:both">
                        <div class="responsive-table">
						<table id="basicTable" class="table table-striped table-bordered responsive">
							<thead class="table-heading">
								<tr>
									<th>#ID</th>
									<th>Category</th>
									<th>Post Title</th>
									<th>Image-1</th>
									<th>Image-2</th>
									<th>Image-3</th>
									<th>Image-4</th>
									<th>Image-5</th>
									<th>Video</th>
									<th>Video Link</th>
									<th>Description</th>
									<th>Total Comment</th>
									<th>Total View</th>
									<th>Created</th>
									<th>Status</th>
									<th class="table-action" style="width: 10%">Action</th>
								</tr>
							</thead>                         
							<tbody>									
						<?php
                        if(count($freestuffs)){
							foreach ($freestuffs as $freestuff):
								?>
								<tr>
									<td><?php echo $freestuff->id; ?></td>
									<td><?php echo @$freestuff->category->name; ?></td>
									<td><div class="text-data"><?php echo @$freestuff->postTitle; ?></div></td>
									<?php $IMG1 = ($freestuff->image_1 !='') ? $this->Url->build('/').'img/uploads/freestaff/'.$freestuff->image_1 : $this->Url->build('/').'img/dummy.jpg'; ?>
									<td><img src="<?php echo $IMG1;?>" class="inner img-responsive" alt="Berry Lace Dress" width="75"></td>
									<?php $IMG2 = ($freestuff->image_2 !='') ? $this->Url->build('/').'img/uploads/freestaff/'.$freestuff->image_2 : $this->Url->build('/').'img/dummy.jpg'; ?>
									<td><img src="<?php echo $IMG2;?>" class="inner img-responsive" alt="Berry Lace Dress" width="75"></td>
									<?php $IMG3 = ($freestuff->image_3 !='') ? $this->Url->build('/').'img/uploads/freestaff/'.$freestuff->image_3 : $this->Url->build('/').'img/dummy.jpg'; ?>
									<td><img src="<?php echo $IMG3;?>" class="inner img-responsive" alt="Berry Lace Dress" width="75"></td>
									<?php $IMG4 = ($freestuff->image_4 !='') ? $this->Url->build('/').'img/uploads/freestaff/'.$freestuff->image_4 : $this->Url->build('/').'img/dummy.jpg'; ?>
									<td><img src="<?php echo $IMG4;?>" class="inner img-responsive" alt="Berry Lace Dress" width="75"></td>
									<?php $IMG5 = ($freestuff->image_5 !='') ? $this->Url->build('/').'img/uploads/freestaff/'.$freestuff->image_5 : $this->Url->build('/').'img/dummy.jpg'; ?>
									<td><img src="<?php echo $IMG5;?>" class="inner img-responsive" alt="Berry Lace Dress" width="75"></td>
									
									<td>
										<?php if($freestuff->video !='') { ?>
										<a href="<?php echo $this->Url->build('/').'img/uploads/freestaff/'.$freestuff->video;?>" target="_blank">View Video</a>
										<?php }else{ echo "-";  }?>
									</td>
									<td><?php echo h($freestuff->videolink); ?></td>
									<td><div class="text-data"><?php echo h($freestuff->description); ?></div></td>
									<td><?php echo ($freestuff->totalComment)? $freestuff->totalComment : '0'; ?></td>
									<td><?php echo ($freestuff->totalView) ? $freestuff->totalView : '0'; ?></td>
									<td><?php echo h($freestuff->created); ?></td>
									<td>
										<?php if($freestuff->status == '1'){?>
                                            <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'status', $freestuff->id]);?>" onclick="return confirm('Are you sure want to deactivate this freestuff?')">Active</a>
                                        <?php }else{ ?>
                                            <a href="<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'status', $freestuff->id]);?>" onclick="return confirm('Are you sure want to active this freestuff?')">Inactive</a>
                                        <?php } ?>
									</td>
									<td class="table-action"  style="width: 10%;">
										<a href="<?php echo $this->Url->build(['controller' => 'Freestaffs', 'action' => 'edit', $freestuff->id]);?>" data-toggle="tooltip" title="Edit" class="tooltips"><i class="fa fa-pencil"></i></a>
										<a href="<?php echo $this->Url->build(['controller' => 'Freestaffs', 'action' => 'delete', $freestuff->id]);?>" data-toggle="tooltip" onclick="return confirm('Are you sure you want to delete freestuff?.')" title="Delete" class="delete-row tooltips"><i class="fa fa-trash-o"></i></a>
									</td>
								</tr>
								<?php
							endforeach;
						}else {
							echo "<tr><td colspan='15' class='error'>No Record Found...</td></tr>";
						}
                        ?> 
					</tbody>
				</table>
							</div>
							
							
						<!-- End All Listing List -->
						
						
			<div class="paging-container">
				<?php
				//------Paging---------
				if($this->Paginator->counter(array('format' => __('{{count}}'))) !=0) {?> 
						<p>
							<?php
							echo $this->Paginator->counter(array('format' => __('<p class="records-showing">Showing {{start}} - {{end}} of {{count}} Records</p>')));
							?>
						</p>
                        <?php if($this->Paginator->counter(array('format' => __('{{pages}}'))) > 1) {?>
						<ul class="pagination">
						<?php		
							echo $this->Paginator->prev(__('Previous'), array('tag' => 'li','escape' => false), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a','escape' => false));
							echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'ellipsis'=>'','currentClass' => 'active','tag' => 'li','first' => 1));
							echo $this->Paginator->next(__('Next'), array('tag' => 'li','escape' => false,'currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a','escape' => false));			   
						?>
						</ul>
                        <?php } ?>
						<div class="cl"></div>	
					<?php 
				}
				?>
			</div>
						
						
					</div>
				