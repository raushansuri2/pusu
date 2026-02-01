<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar');?>             
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']);?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li>Add State</li>
                    </ul>
                    <h4>Add State</h4>
                </div>
                <div class="search-body">
                    <a href="<?= $this->Url->build(['controller' => 'Locations', 'action' => 'getStates']); ?>" class="btn btn-primary mr5 ml10">Back</a>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">      
            <?php echo $this->Flash->render(); ?>
            
            <?php echo $this->Form->create($state, ['url' => ['controller' => 'Locations', 'action' => 'addState'], 'type' => 'post']); ?>
            
            <div class="form-group">
                <label>State Code <span class="asterisk">*</span></label>
                <?php
                    echo $this->Form->control('code', ['class' => 'form-control', 'placeholder' => 'Enter State Code', 'required' => true]);
                ?>
            </div>
            
            <div class="form-group">
                <label>State Name <span class="asterisk">*</span></label>
                <?php
                echo $this->Form->control('name', ['class' => 'form-control', 'placeholder' => 'Enter State Name', 'required' => true]);
                ?>
            </div>
            
            <div class="form-group">
                <label>Select Country <span class="asterisk">*</span></label>
                <?php
                echo $this->Form->control('country_id', [
                    'type' => 'select',
                    'options' => $countries,
                    'empty' => 'Select Country',
                    'class' => 'form-control',
                    'required' => true
                ]);
                ?>
            </div>
            
            <div class="form-group">
                <label>State Tax (%) <span class="asterisk">*</span></label>
                <?php
                echo $this->Form->control('state_tax', ['class' => 'form-control', 'placeholder' => 'Enter State Tax', 'type' => 'number', 'step' => '0.01', 'required' => true]);
                ?>
            </div>
            
            <div class="form-group">
                <?php echo $this->Form->button(__('Add State'), ['class' => 'btn btn-primary']); ?>
            </div>
            
            <?php echo $this->Form->end(); ?>
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->