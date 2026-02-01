<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar');?>                
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-pencil-square-o"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']);?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Elearning', 'action' => 'showCategories']);?>"> Manage Category</a></li>
                        <li>Edit Category</li>
                    </ul>
                    <h4>Edit Category</h4>
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
                                            'placeholder' => 'Enter category name', 
                                            'div' => false, 
                                            'label' => false,
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
                                            'placeholder' => 'Enter title', 
                                            'div' => false, 
                                            'label' => false,
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
                                            'placeholder' => 'Enter description', 
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
                                    <label class="col-sm-3 control-label">Question Image <?php if (empty($category->questions_image)) { ?><span class="asterisk">*</span><?php } ?></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('questions_image', [
                                            'type' => 'file', 
                                            'class' => 'form-control',
                                            'required' => empty($category->questions_image), // Make required only if no image exists
                                            'div' => false, 
                                            'label' => false,
                                            'onchange' => 'previewImage(this, "questions-image-preview")' // Add onchange event
                                        ]); ?>
                                        <div id="questions-image-preview">
                                            <?php if (!empty($category->questions_image)): ?>
                                                <img src="<?php echo $this->Url->build('/img/uploads/elearning_categories/' . $category->questions_image); ?>" alt="Question Image" style="width: 150px; height: 150px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Video Image <?php if (empty($category->videos_image)) { ?><span class="asterisk">*</span><?php } ?></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('videos_image', [
                                            'type' => 'file', 
                                            'class' => 'form-control',
                                            'required' => empty($category->videos_image),
                                            'div' => false, 
                                            'label' => false,
                                            'onchange' => 'previewImage(this, "videos-image-preview")' // Add onchange event
                                        ]); ?>
                                        <div id="videos-image-preview">
                                            <?php if (!empty($category->videos_image)): ?>
                                                <img src="<?php echo $this->Url->build('/img/uploads/elearning_categories/' . $category->videos_image); ?>" alt="Video Image" style="width: 150px; height: 150px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Article Image <?php if (empty($category->articles_image)) { ?><span class="asterisk">*</span><?php } ?></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('articles_image', [
                                            'type' => 'file', 
                                            'class' => 'form-control',
                                            'required' => empty($category->articles_image),
                                            'div' => false, 
                                            'label' => false,
                                            'onchange' => 'previewImage(this, "articles-image-preview")' // Add onchange event
                                        ]); ?>
                                        <div id="articles-image-preview">
                                            <?php if (!empty($category->articles_image)): ?>
                                                <img src="<?php echo $this->Url->build('/img/uploads/elearning_categories/' . $category->articles_image); ?>" alt="Article Image" style="width: 150px; height: 150px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                    </div><!-- panel-body -->
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-9 col-sm-offset-3">
                                <?php echo $this->Form->submit('Update', ['class' => 'btn btn-primary mr5', 'div' => false, 'label' => false]); ?>
                            </div>
                        </div>
                    </div><!-- panel-footer -->  
                </div><!-- panel -->
                <?php echo $this->Form->end();?>                                
            </div><!-- row -->    
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->

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
            img.style.width = '150px'; // Set width
            img.style.height = '150px'; // Set height
            img.style.objectFit = 'cover'; // Maintain aspect ratio
            img.style.border = '1px solid #ddd'; // Optional: Add a border
            img.style.borderRadius = '4px'; // Optional: Add rounded corners
            previewContainer.appendChild(img);
        }
        reader.readAsDataURL(file);
    }
}
</script>