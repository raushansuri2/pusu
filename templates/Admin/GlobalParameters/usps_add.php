<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar');?>                
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-shield"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard']);?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li><a href="<?php echo $this->Url->build(['controller' => 'GlobalParameters', 'action' => 'usps']);?>"> Manage USPS</a></li>
                        <li>Add USPS</li>
                    </ul>
                    <h4>Add USPS</h4>
                </div>
                <div class="search-body">
                    <a href="<?= $this->Url->build(['controller' => 'GlobalParameters', 'action' => 'usps']); ?>" class="btn btn-primary mr5 ml10">Back</a>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Form->create($usps); ?>
                
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Environment <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php 
                                        // Define options for the environment select
                                        $environmentOptions = [
                                            'test' => 'Test',
                                            'live' => 'Live',
                                        ];
                                        echo $this->Form->input('environment', [
                                            'type' => 'select',
                                            'options' => $environmentOptions,
                                            'class' => 'form-control',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                        ]); 
                                        ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Grant Type <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php 
                                        // Define options for the grant type select
                                        $grantTypeOptions = [
                                            'client_credentials' => 'Client Credentials',
                                        ];
                                        echo $this->Form->input('grant_type', [
                                            'type' => 'select',
                                            'options' => $grantTypeOptions,
                                            'class' => 'form-control',
                                            'required' => true,
                                            'div' => false,
                                            'label' => false,
                                        ]); 
                                        ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Client ID <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('client_id', [
                                            'class' => 'form-control', 
                                            'type' => 'text', 
                                            'required' => true, 
                                            'div' => false, 
                                            'label' => false,
                                            'placeholder' => 'Enter client ID'
                                        ]); ?>
                                    </div>
                                </div>
                            </div><!-- col-sm-6 -->
                        </div><!-- row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Client Secret <span class="asterisk">*</span></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Form->input('client_secret', [
                                            'class' => 'form-control', 
                                            'type' => 'text', 
                                            'required' => true, 
                                            'div' => false, 
                                            'label' => false,
                                            'placeholder' => 'Enter client secret'
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