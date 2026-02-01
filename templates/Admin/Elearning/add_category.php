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
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Elearning', 'action' => 'showCategories']);?>"> Manage Categories</a></li>
                        <li>Add Category</li>
                    </ul>
                    <h4>Add Category</h4>
                </div>
                <div class="search-body">
                    <a href="<?= $this->Url->build(['controller' => 'Elearning', 'action' => 'showCategories']); ?>" class="btn btn-primary mr5 ml10">Back</a>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Form->create($category, ['type' => 'file']); ?>
                
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Category Name <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('name', [
                                            'class' => 'form-control', 
                                            'type' => 'text', 
                                            'required' => true, 
                                            'div' => false, 
                                            'label' => false,
                                            'placeholder' => 'Enter category name',
                                            'maxlength' => 50
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
                                            'type' => 'text', 
                                            'required' => true, 
                                            'div' => false, 
                                            'label' => false,
                                            'placeholder' => 'Enter title',
                                            'maxlength' => 100
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Description <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('description', [
                                            'class' => 'form-control', 
                                            'type' => 'textarea', 
                                            'required' => true, 
                                            'div' => false, 
                                            'label' => false,
                                            'placeholder' => 'Enter description'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Questions Image <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('questions_image', [
                                            'type' => 'file', 
                                            'class' => 'form-control', 
                                            'required' => true, 
                                            'div' => false, 
                                            'label' => false,
                                            'onchange' => 'previewImage(this, "questions-image-preview")'
                                        ]); ?>
                                        <div id="questions-image-preview" class="image-preview"></div>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Videos Image <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('videos_image', [
                                            'type' => 'file', 
                                            'class' => 'form-control', 
                                            'required' => true, 
                                            'div' => false, 
                                            'label' => false,
                                            'onchange' => 'previewImage(this, "videos-image-preview")'
                                        ]); ?>
                                        <div id="videos-image-preview" class="image-preview"></div>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Articles Image <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('articles_image', [
                                            'type' => 'file', 
                                            'class' => 'form-control', 
                                            'required' => true, 
                                            'div' => false, 
                                            'label' => false,
                                            'onchange' => 'previewImage(this, "articles-image-preview")'
                                        ]); ?>
                                        <div id="articles-image-preview" class="image-preview"></div>
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

<style>
.image-preview {
    margin-top: 10px; /* Space between input and preview */
}

.image-preview img {
    width: 150px; /* Set width to 150px */
    height: 150px; /* Set height to 150px */
    object-fit: cover; /* Maintain aspect ratio and cover the area */
    border: 1px solid #ddd; /* Optional: Add a border */
    border-radius: 4px; /* Optional: Add rounded corners */
}
</style>

<script>
function previewImage(input, previewId) {
    const file = input.files[0];
    const previewContainer = document.getElementById(previewId);
    previewContainer.innerHTML = ''; // Clear previous previews

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-responsive'; // Add any additional classes you want
            previewContainer.appendChild(img);
        }
        reader.readAsDataURL(file);
    }
}
</script>