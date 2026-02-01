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
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Elearning', 'action' => 'showArticles']);?>"> Manage Articles</a></li>
                        <li>Add Article</li>
                    </ul>
                    <h4>Add Article</h4>
                </div>
                <div class="search-body">
                    <a href="<?= $this->Url->build(['controller' => 'Elearning', 'action' => 'showArticles']); ?>" class="btn btn-primary mr5 ml10">Back</a>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Form->create($article, ['type' => 'file']); ?>
                
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Category <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php
                                        echo $this->Form->input('category_id', [
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
                                    <label class="col-sm-3 control-label">Title <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('title', [
                                            'class' => 'form-control',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                            'placeholder' => 'Enter the article title'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Content <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('content', [
                                            'type' => 'textarea',
                                            'class' => 'form-control',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                            'placeholder' => 'Enter the article content'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Image <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('image', [
                                            'type' => 'file',
                                            'class' => 'form-control',
                                            'div' => false,
                                            'label' => false,
                                            'required' => true,
                                            'id' => 'imageUpload'
                                        ]); ?>
                                        <div id="imagePreview" style="margin-top: 10px;">
                                            <img id="previewImg" src="#" alt="Image Preview" style="display: none; max-width: 150px; height: 150px;" />
                                        </div>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-12 -->
                        </div><!-- row -->
                    </div><!-- panel-body -->
                </div><!-- panel -->
                <?php echo $this->Form->end(); ?>
            </div><!-- row -->
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->

<script>
    document.getElementById('imageUpload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.getElementById('previewImg');
            img.src = e.target.result;
            img.style.display = 'block'; // Show the image
        }
        
        if (file) {
            reader.readAsDataURL(file); // Convert the file to a data URL
        }
    });
</script>