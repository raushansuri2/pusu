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
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Elearning', 'action' => 'showQuestions']);?>"> Manage Questions</a></li>
                        <li>Add Question</li>
                    </ul>
                    <h4>Add Question</h4>
                </div>
                <div class="search-body">
                    <a href="<?= $this->Url->build(['controller' => 'Elearning', 'action' => 'showQuestions']); ?>" class="btn btn-primary mr5 ml10">Back</a>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Form->create($question); ?>
                
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
                                    <label class="col-sm-3 control-label">Question Text <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('question_text', [
                                            'class' => 'form-control',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                            'placeholder' => 'Enter the question text'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Option A <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('option_a', [
                                            'class' => 'form-control',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                            'placeholder' => 'Enter Option A'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Option B <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('option_b', [
                                            'class' => 'form-control',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                            'placeholder' => 'Enter Option B'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Option C <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('option_c', [
                                            'class' => 'form-control',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                            'placeholder' => 'Enter Option C'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Option D <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('option_d', [
                                            'class' => 'form-control',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                            'placeholder' => 'Enter Option D'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Correct Answer <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('correct_answer', [
                                            'type' => 'select',
                                            'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C', 'D' => 'Option D'],
                                            'empty' => 'Select the correct answer',
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