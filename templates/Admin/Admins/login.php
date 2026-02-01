<div class="panel panel-signin">
    <div class="panel-body">
        <div class="logo text-center">
            <?= $this->Html->image('admin/logo-black.png', ['style' => 'width:250px']) ?>
        </div>
        <br />
        <p class="text-center">Sign in to access admin panel</p>
        
        <div class="mb30"></div>
        <?= $this->Flash->render() ?>
        
        <!-- Form submits to /admin (AdminsController::login under Admin prefix) -->
        <?= $this->Form->create(null, [
            'url' => ['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin'],
            'novalidate' => true,
            'id' => 'basicForm'
        ]) ?>
        
        <div class="input-group mb15" style="width: 100%">
            <?= $this->Form->control('username', [
                'type' => 'text',
                'label' => false,
                'placeholder' => 'Username',
                'class' => 'form-control',
                'id' => 'username',
                'required' => true,
                'minlength' => 4,
                'maxlength' => 20
            ]) ?>
        </div><!-- input-group -->
        
        <div class="input-group mb15" style="width: 100%">
            <?= $this->Form->control('password', [
                'type' => 'password',
                'label' => false,
                'placeholder' => 'Password',
                'class' => 'form-control',
                'id' => 'password',
                'required' => true,
                'minlength' => 6,
                'maxlength' => 20
            ]) ?>
        </div><!-- input-group -->
        
        <div class="clearfix">
            <div class="pull-left">
                <div class="ckbox ckbox-primary mt10">
                    <?= $this->Html->link('Forgot Password', [
                        'controller' => 'Admins',
                        'action' => 'forgotpassword',
                        'prefix' => 'Admin'
                    ]) ?>
                </div>
            </div>
            <div class="pull-right">
                <?= $this->Form->submit('Sign in', ['class' => 'btn btn-primary mr5']) ?>
            </div>
        </div>                      
        
        <?= $this->Form->end() ?>
    </div><!-- panel-body -->
    
    <div class="panel-footer">
        <!-- Optional signup link if needed in the future -->
        <!-- <?= $this->Html->link('Not yet a Member? Create Account Now', ['controller' => 'Admins', 'action' => 'signup', 'prefix' => 'Admin'], ['class' => 'btn btn-primary btn-block']) ?> -->
    </div><!-- panel-footer -->
</div><!-- panel -->