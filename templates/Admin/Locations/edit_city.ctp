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
                        <li>Edit City</li>
                    </ul>
                    <h4>Edit City</h4>
                </div>
                <div class="search-body">
                    <a href="<?= $this->Url->build(['controller' => 'Locations', 'action' => 'getCities']); ?>" class="btn btn-primary mr5 ml10">Back</a>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">      
            <?php echo $this->Flash->render(); ?>
            
            <?php echo $this->Form->create($city, ['url' => ['controller' => 'Locations', 'action' => 'editCity', $city->id], 'type' => 'post']); ?>
            
            <div class="form-group">
                <label>City Name <span class="asterisk">*</span></label>
                <?php
                echo $this->Form->control('name', [
                    'class' => 'form-control', 
                    'placeholder' => 'Enter City Name', 
                    'required' => true
                ]);
                ?>
            </div>
            
            <div class="form-group">
                <label>Select State <span class="asterisk">*</span></label>
                <?php
                echo $this->Form->control('state_id', [
                    'type' => 'select',
                    'options' => $states,
                    'empty' => 'Select State',
                    'class' => 'form-control',
                    'required' => true
                ]);
                ?>
            </div>
            
            <div class="form-group">
                <?php echo $this->Form->button(__('Update City'), ['class' => 'btn btn-primary']); ?>
            </div>
            
            <?php echo $this->Form->end(); ?>
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->