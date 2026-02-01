<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar');?>             
    </div><!-- leftpanel -->	
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-file-text"></i>
                </div>
                <div class="media-body" style="width:80%;">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']);?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Elearning', 'action' => 'showArticles']);?>">Manage Articles</a></li>
                        <li>Article Details</li>
                    </ul>
                    <h4>Article Details</h4>
                </div>
                <div class="search-body">
                    <a href="<?php echo $this->Url->build(['controller' => 'Elearning', 'action' => 'showArticles']);?>" class="btn btn-primary mr5 ml10">Back</a>                    
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Article Details</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2"><strong>ID:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->id); ?></div>
                            </div>
                        
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Title:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->title); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Content:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->content); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Category:</strong></label>
                                <div class="col-sm-9"><?php echo h($details->elearning_category->name); ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2"><strong>Image:</strong></label>
                                <div class="col-sm-9">
                                    <?php if (!empty($details->image)): ?>
                                        <img src="<?= $this->Url->build('/img/uploads/elearning_articles/' . $details->image); ?>" alt="Article Image" style="max-width: 150px; height: 150px;" />
                                    <?php else: ?>
                                        No image available.
                                    <?php endif; ?>
                                </div>
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