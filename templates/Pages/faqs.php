<div class="col-md-12 col-sm-12">
						<div class="panel-group style-1" id="accordion" role="tablist" aria-multiselectable="true">
                                          <?php foreach($faqs as $faq){ ?>
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="designing">
									<h4 class="panel-title">
										<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?php echo $faq->id;?>" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
											<?php echo $faq->question;?>
										</a>
									</h4>
								</div>
								<div id="collapseOne_<?php echo $faq->id;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="designing" aria-expanded="false" style="height: 0px;">
									<div class="panel-body">
										<p><?php echo $faq->answer;?></p>
									</div>
								</div>
							</div>
                                          <?php } ?>
							
						</div>
					</div>




