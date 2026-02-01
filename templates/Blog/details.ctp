<?php $pagingDiv = $this->Paginator->counter('{{count}}');?>
						<!-- =============== Blog Detail ================= -->
						<div class="col-md-8 col-sm-12">
							
							<!-- /.Article -->
							<article class="blog-news detail-wrapper">
								<div class="full-blog">
								
									<!-- Featured Image -->
									<figure class="img-holder">
										<?php $IMG = ($freestaffs->image_1 !='') ? $this->Url->build('/').'img/uploads/freestaff/'.$freestaffs->image_1 : $this->Url->build('/').'img/dummy.jpg'; ?>
										<a href="blog-detail.html"><img src="<?php echo $IMG;?>" class="img-responsive" alt="News"></a>
										<div class="blog-post-date theme-bg">
											<?php echo date("F jS, Y", strtotime($freestaffs->created));?>
										</div>
									</figure>
									
									<!-- Blog Content -->
									<div class="full blog-content">
										<div class="post-meta">
											<span class="author"><i class="ti-user"></i><a href="#" title="Posts by admin"><?php echo @$freestaffs->user->fullName;?></a></span>
											<span class="author"><i class="ti-calendar"></i><?php echo date("F jS, Y", strtotime($freestaffs->created));?></span>
											<span class="author"><i class="ti-comment-alt"></i><?php echo $pagingDiv;?> Comments</span>
											<span class="author"><i class="ti-eye-alt"></i><?php echo ($freestaffs->totalView) ? $freestaffs->totalView : '0';?> View</span>
											<span class="author"><a href="<?php echo $this->Url->build(['controller'=>'Freestaffs','action'=>'likes',$freestaffs->id,1]);?>"><i class="fa fa-thumbs-o-up"></i><?php echo (@$likeStatus->status==1) ?"Liked" :"Like";?></a></span>
											<span class="author"><a href="<?php echo $this->Url->build(['controller'=>'Freestaffs','action'=>'likes',$freestaffs->id,2]);?>"><i class="fa fa-thumbs-o-down"></i> <?php echo (@$likeStatus->status==2) ?"Disliked" :"Dislike";?></a></span>
											<?php if($this->request->session()->read('RitevetUsers.id') == $freestaffs->userId){ ?>
											<span class="author"><a href="<?php echo $this->Url->build(['controller'=>'Freestaffs','action'=>'delete',$freestaffs->id]);?>" onclick="return confirm('Are you sure want to delete this stuff?')"><i class="fa fa-trash"></i> Delete</a></span>
											<?php } ?>
											<!--<span class="author"><a href=""><i class="fa fa-share"></i> Share</a></span>-->
										</div>
										<h3 class="bl-title"><?php echo $freestaffs->postTitle;?></h3>
										<div class="blog-text">
											<?php echo $freestaffs->description;?>
											<!--<div class="post-tags">
												<strong>Tags:</strong>
												<a href="#">Lorem ipsum</a>
												<a href="#">Lorem ipsum</a>
												<a href="#">Lorem ipsum</a>
												<a href="#">Lorem ipsum</a>
											</div>-->
										</div>
										
										<!-- Blog Share Option -->
										<!--<div class="row no-mrg">
											<div class="blog-footer-social">
												<span>Share <i class="fa fa-share-alt"></i></span>
												<ul class="list-inline social">
													<li><a href="#"><i class="fa fa-facebook"></i></a></li>
													<li><a href="#"><i class="fa fa-twitter"></i></a></li>
													<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
													<li><a href="#"><i class="fa fa-pinterest"></i></a></li>
												</ul>
											</div>
										</div>-->
										
									</div>
									<!-- Blog Content -->
									
								</div>
							</article>
							<!-- /.Article -->
							
							<!-- User Comments -->
							<div class="row no-mrg">
								<div class="col-md-12 col-sm-12">
									<div class="comments">      
										<div class="comments-title">
											<h4>Comments (<?php echo $pagingDiv;?>)</h4>
										</div>
										
										<!-- Single Comment -->
										<?php if($freestaffcomments){
											foreach($freestaffcomments as $freestaffcomment){ ?>
										<div class="single-comment">
											<?php if($this->request->session()->read('RitevetUsers.id') == $freestaffcomment->user->id ){ ?>
												<a href="<?php echo $this->Url->build(['controller' => 'Freestaffs', 'action' => 'commentdelete', $freestaffcomment->freestaffId,$freestaffcomment->id]);?>" style="float: right;padding: 10px; color: #ff0000;" onclick="return confirm('Are you sure you want to delete comment?.')"><i class="fa fa-trash-o"></i></a>
											<?php } ?>
											<div class="img-holder">
												<?php $UIMG = (@$freestaffcomment->user->profile_picture !='') ? $this->Url->build('/').'img/uploads/users/'.@$freestaffcomment->user->profile_picture : $this->Url->build('/').'img/dummy.jpg'; ?>
												<img src="<?php echo $UIMG;?>" class="img-responsive" alt="">
											</div>
											<div class="text-holder">
												<div class="top">
													<div class="name pull-left">
														<h4><?php echo @$freestaffcomment->user->fullName;?></h4>
													</div>
													<!--<div class="rating pull-right">
														<ul>
															<li><i class="fa fa-star active"></i></li>
															<li><i class="fa fa-star active"></i></li>
															<li><i class="fa fa-star active"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
														</ul>
													</div>-->
												</div>
												
												<div class="text">
													<p><?php echo $freestaffcomment->comment;?></p>
												</div>
												
												<span class="small"><?php echo date("M jS, Y", strtotime($freestaffcomment->created));?></span>
											</div>
										</div>
										<?php }
										}else{?>
										<div class="single-comment">
											<p>No Comment</p>
										</div>
										<?php } ?>
										<!-- Single Comment -->
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
																<ul>
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
								</div>
							</div>
							<!-- /.User Comments -->
							
							<!-- Start Blog Comment -->
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="comments-form"> 
										<div class="section-title2">
											<h4>Leave a Reply</h4>
										</div>
										
										<?php 	echo $this->Form->create('Users',  ['url'=>['controller'=>'Freestaffs','action'=>'postcomment'],'type' => 'post', 'onsubmit'=>'return validatecomment();', 'novalidate' => 'novalidate']); ?>
											<!--<div class="col-md-6 col-sm-6">
												<input type="text" name="commentName" id="commentName" class="form-control" placeholder="Your Name">
											</div>
											
											<div class="col-md-6 col-sm-6">
												<input type="email" name="commentEmail" id="commentEmail" class="form-control" placeholder="Your Email">
											</div>-->
											<input type="hidden" name="freestaffId" id="freestaffId" value="<?php echo $freestaffs->id;?>">
											<div class="col-md-12 col-sm-12">
												<textarea class="form-control" name="comment" id="commentMessage" placeholder="Comment"></textarea>
											</div>
											
											<div class="col-md-12 col-sm-12">
												<button class="btn theme-btn width-200" type="submit">submit now</button>
											</div>
										</form>
										
									</div>
								
								</div>
							</div>
							<!-- End Blog Comment -->
							
						</div>
						<!-- /.col-md-8 -->
						
						<!-- ===================== Blog Sidebar ==================== -->
						<div class="col-md-4 col-sm-12">
							<div class="sidebar">
							
								<!-- Search Bar -->
								<div class="widget-boxed">
									<div class="widget-boxed-header border-0">
										<h4><i class="ti-search padd-r-10"></i>Search Here</h4>
									</div>
									
									<div class="widget-boxed-body padd-top-5">
										<div class="input-group">
											<?php 	echo $this->Form->create('Users',  ['url'=>['controller'=>'Freestaffs','action'=>'index'],'type' => 'get', 'novalidate' => 'novalidate']); ?>
											<input type="text" name="keyword" class="form-control" placeholder="Search…">
											<span class="input-group-btn">
												<button type="submit" class="btn height-50 theme-btn">Go</button>
											</span>
											<?php echo $this->Form->end(); ?>
										</div>
									</div>
								
								</div>
				
								<!-- Start: Latest Blogs -->
								<div class="widget-boxed">
									<div class="widget-boxed-header">
										<h4><i class="ti-check-box padd-r-10"></i>Latest Stuff</h4>
									</div>
									<div class="widget-boxed-body padd-top-5">
										<div class="side-list">
											<ul class="side-blog-list">
												<?php foreach($latests as $latest){ ?>
												<li>
													<a href="#">
														<div class="blog-list-img">
															<?php $LIMG = ($latest->image_1 !='') ? $this->Url->build('/').'img/uploads/freestaff/'.$latest->image_1 : $this->Url->build('/').'img/dummy.jpg'; ?>
															<img src="<?php echo $LIMG;?>" class="img-responsive" alt="">
														</div>
													</a>
													<div class="blog-list-info">
														<h5><a href="#" title="blog"><?php echo $latest->postTitle;?></a></h5>
														<div class="blog-post-meta">
															<span class="updated"><?php echo date("M jS, Y", strtotime($latest->created));?></span> | <a href="<?php echo $this->Url->build(['controller'=>'freestaffs', 'action'=>'details', base64_encode($latest->id)]);?>" rel="tag"><?php echo substr(strip_tags($latest->description), 0, 17);?></a>					
														</div>
													</div>
												</li>
												<?php } ?>

											</ul>
										</div>
									</div>
								</div>
								<!-- End: Latest Blogs -->
								
								<!-- Start: Listing Category -->
								<div class="widget-boxed">
									<div class="widget-boxed-header">
										<h4><i class="ti-briefcase padd-r-10"></i>Stuff Categories</h4>
									</div>
									<div class="widget-boxed-body padd-top-10 padd-bot-0">
										<div class="side-list">
											<ul class="category-list">
												<?php foreach($CATEArray as $val){ ?>
												<li><a href="<?php echo $this->Url->build(['controller'=>'Freestaffs', 'action'=>'index/?categoryid='.$val['id']]);?>"><?php echo $val['name'];?> <span class="badge bg-d"><?php echo $val['totalProduct'];?></span></a></li>
												<?php } ?>
											</ul>

										</div>
									</div>
								</div>
								<!-- End: Listing Category -->
								
							</div>
						</div>
						

<script>
	function validatecomment(){
		if($("#commentMessage").val() ==''){
			alert('Please fill the comment');
			return false;
		}else{
			return true;
		}
	}
</script>

						
				