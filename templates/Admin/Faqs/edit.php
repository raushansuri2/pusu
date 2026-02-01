<?php echo $this->Html->script('ckeditor/ckeditor'); ?>
<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>                
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-question-circle"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><?= $this->Html->link('<i class="glyphicon glyphicon-home"></i> Dashboard', ['controller' => 'Admins', 'action' => 'dashboard'], ['escape' => false]); ?></li>
                        <li><?= $this->Html->link('Manage Faqs', ['controller' => 'Faqs', 'action' => 'index']); ?></li>
                        <li>Edit Faq</li>
                    </ul>
                    <h4>Edit Faq</h4>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Form->create($faqs, ['type' => 'file', 'novalidate' => 'novalidate']); ?>
                
                <div class="panel panel-default">
                    <div class="panel-heading">  
                        <h4 class="panel-title">Edit Faq</h4>
                        <p>Please provide all below details to edit faq.</p>
                    </div><!-- panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Faq Question <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('question', [
                                        'error' => ['minLength' => __('You can submit up to ')],
                                        'class' => 'form-control',
                                        'type' => 'text',
                                        'required' => false,
                                        'placeholder' => '',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 200
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Faq Answer</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('answer', [
                                        'error' => ['minLength' => __('You can submit up to ')],
                                        'class' => 'form-control',
                                        'type' => 'textarea',
                                        'required' => false,
                                        'placeholder' => '',
                                        'div' => false,
                                        'label' => false,
                                        'maxlength' => 200
                                    ]); ?>
                                </div>
                            </div><!-- form-group -->
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status <span class="asterisk">*</span></label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input('status', [
                                        'type' => 'select',
                                        'options' => [1 => 'Active', 0 => 'Inactive'],
                                        'class' => 'form-control',
                                        'label' => false,
                                        'div' => false,
                                        'required' => true
                                    ]); ?>
                                    <label class="error" for="status"></label> 
                                </div>                                                 
                            </div><!-- form-group -->               
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
                <?php echo $this->Form->end(); ?>                                
            </div><!-- row-->    
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->

<script>
jQuery(document).ready(function(){
    CKEDITOR.replace('answer');
});
</script>