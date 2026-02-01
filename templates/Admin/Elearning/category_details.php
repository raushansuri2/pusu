<div class="mainwrapper">
    <div class="leftpanel">
        <?= $this->element('admin/sidebar'); ?>             
    </div><!-- leftpanel -->    
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-folder-open"></i>
                </div>
                <div class="media-body" style="width:80%;">
                    <ul class="breadcrumb">
                        <li><a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']); ?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li><a href="<?= $this->Url->build(['controller' => 'Elearning', 'action' => 'showCategories']); ?>">Manage Categories</a></li>
                        <li>Category Details</li>
                    </ul>
                    <h4>Category Details</h4>
                </div>
                <div class="search-body">
                    <a href="<?= $this->Url->build(['controller' => 'Elearning', 'action' => 'showCategories']); ?>" class="btn btn-primary mr5 ml10">Back</a>                    
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Category Details</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group row">
                                <label class="col-sm-2"><strong>ID:</strong></label>
                                <div class="col-sm-9"><?= h($category->id); ?></div>
                            </div>
                        
                            <div class="form-group row">
                                <label class="col-sm-2"><strong>Category Name:</strong></label>
                                <div class="col-sm-9"><?= h($category->name); ?></div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-2"><strong>Title:</strong></label>
                                <div class="col-sm-9"><?= h($category->title); ?></div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-2"><strong>Description:</strong></label>
                                <div class="col-sm-9"><?= wordwrap(h($category->description), 100, "<br>", true); ?></div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-2"><strong>Question Image:</strong></label>
                                <div class="col-sm-9">
                                    <?php if (!empty($category->questions_image)): ?>
                                        <img src="<?= $this->Url->build('/img/uploads/elearning_categories/' . $category->questions_image); ?>" alt="Question Image" style="max-width: 200px; max-height: 200px;" />
                                    <?php else: ?>
                                        No question image available.
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-2"><strong>Video Image:</strong></label>
                                <div class="col-sm-9">
                                    <?php if (!empty($category->videos_image)): ?>
                                        <img src="<?= $this->Url->build('/img/uploads/elearning_categories/' . $category->videos_image); ?>" alt="Video Image" style="max-width: 200px; max-height: 200px;" />
                                    <?php else: ?>
                                        No video image available.
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2"><strong>Article Image:</strong></label>
                                <div class="col-sm-9">
                                    <?php if (!empty($category->articles_image)): ?>
                                        <img src="<?= $this->Url->build('/img/uploads/elearning_categories/' . $category->articles_image); ?>" alt="Article Image" style="max-width: 200px; max-height: 200px;" />
                                    <?php else: ?>
                                        No Article image available.
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-2"><strong>Added On:</strong></label>
                                <div class="col-sm-9"><?= date('M jS, Y H:i', strtotime($category->created)); ?></div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-2"><strong>Status:</strong></label>
                                <div class="col-sm-9"><?= ($category->status == '1') ? 'Active' : 'Inactive'; ?></div>
                            </div>
                        </div>
                    </div><!-- panel -->                    
                </div>                
            </div><!-- row -->            
        </div>          
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->