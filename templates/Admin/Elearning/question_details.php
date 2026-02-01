<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar');?>             
    </div><!-- leftpanel -->	
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-question-circle"></i>
                </div>
                <div class="media-body" style="width:80%;">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']);?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Elearning', 'action' => 'showQuestions']);?>">Manage Questions</a></li>
                        <li>Question Details</li>
                    </ul>
                    <h4>Question Details</h4>
                </div>
                <div class="search-body">
                    <a href="<?php echo $this->Url->build(['controller' => 'Elearning', 'action' => 'showQuestions']);?>" class="btn btn-primary mr5 ml10">Back</a>                    
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Question Details</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2"><strong>ID:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->id); ?></div>
                            </div>
                        
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Question Text:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->question_text); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Option A:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->option_a); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Option B:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->option_b); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Option C:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->option_c); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Option D:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->option_d); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Correct Answer:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->correct_answer); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Category:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->elearning_category->name); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Added On:</strong></label>
                                <div class="col-sm-9"><?php echo date('M jS, Y H:i', strtotime($details->created)); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Status:</strong></label>
                                <div class="col-sm-9"><?php echo ($details->status == '1') ? 'Active' : 'Inactive'; ?></div>
                            </div>
                        </div>
                    </div><!-- panel -->                    
                </div>                
            </div><!-- row -->            
        </div>			
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->