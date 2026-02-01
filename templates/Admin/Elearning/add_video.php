<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar');?>                
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-plus"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']);?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Elearning', 'action' => 'showVideos']);?>"> Manage Videos</a></li>
                        <li>Add Video</li>
                    </ul>
                    <h4>Add Video</h4>
                </div>
                <div class="search-body">
                    <a href="<?= $this->Url->build(['controller' => 'Elearning', 'action' => 'showVideos']); ?>" class="btn btn-primary mr5 ml10">Back</a>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Form->create($video);?>
                
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Category<span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('category_id', [
                                            'type' => 'select',
                                            'options' => $categories,
                                            'empty' => 'Select a Category',
                                            'class' => 'form-control',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Title<span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('title', [
                                            'class' => 'form-control',
                                            'type' => 'text',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                            'placeholder' => 'Enter Video Title'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Description<span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('description', [
                                            'class' => 'form-control',
                                            'type' => 'textarea',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                            'placeholder' => 'Enter Video Description'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Video URL<span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('url', [
                                            'class' => 'form-control',
                                            'type' => 'url',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                            'placeholder' => 'Enter Video URL'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                    </div><!-- panel-body -->
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-9 col-sm-offset-3">
                                <?php echo $this->Form->submit('Submit', ['class' => 'btn btn-primary mr5', 'div' => false, 'label' => false]); ?>
                            </div>
                        </div>
                    </div><!-- panel-footer -->  
                </div><!-- panel -->
                <?php echo $this->Form->end();?>                                
            </div><!-- row-->    
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->