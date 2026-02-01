<div class="panel panel-signin">
    <div class="panel-body">
        <div class="logo text-center">
            <?php echo $this->Html->image('admin/logo-black.png', ['style' => 'width:250px']); ?>
        </div>
        <br />
        <h4 class="text-center mb5">Forgot Password</h4>
        
        <div class="mb30"></div>
        <?php echo $this->Flash->render(); ?>
        
        <?php echo $this->Form->create(null, [
            'url' => ['controller' => 'Admins', 'action' => 'forgotpassword'],
            'novalidate' => 'novalidate',
            'id' => 'basicForm'
        ]); ?>
        
        <div class="input-group mb15" style="width: 100%">
            <?php 
            echo $this->Form->control('email', [
                'type' => 'email',
                'div' => false,
                'label' => false,
                'placeholder' => 'Email Address',
                'class' => 'form-control',
                'required' => true,
                'minlength' => 4,
                'maxlength' => 50
            ]); 
            ?>
        </div><!-- input-group -->
        
        <div class="clearfix">
            <div class="pull-left">
                <div class="ckbox ckbox-primary mt10">
                    <?php echo $this->Html->link('Login', ['controller' => 'Admins', 'action' => 'login']); ?>
                </div>
            </div>
            <div class="pull-right">
                <?php echo $this->Form->button('Submit', [
                    'class' => 'btn btn-primary mr5',
                    'div' => false
                ]); ?>
            </div>
        </div>                      
        <?php echo $this->Form->end(); ?>
    </div><!-- panel-body -->
    
    <div class="panel-footer">
        <!-- Uncomment if you want to add a signup link -->
        <!-- {{ HTML::link('admin/user/signup', 'Not yet a Member? Create Account Now', array('class' => 'btn btn-primary btn-block'))}} -->
    </div><!-- panel-footer -->
</div><!-- panel -->