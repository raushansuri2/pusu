<div class="panel panel-signin"style="height: 100vh;">
    <div class="panel-body">
    <div class="row">
    <div class="col-md-7" style="background: url('https://www.vocecon.com/wp-content/uploads/pure-offset-calculation.jpg');height: 100%;min-height: 390px; background-repeat: no-repeat;
  background-size: cover; height: 100vh;"></div>
    <div class="col-md-5" style="padding: 41px;"><div class="logo text-center">
            <?= $this->Html->image('admin/logo-black.png', ['style' => 'width:400px']) ?>
        </div>
        <br />
        <p class="text-center" style="text-align: center;
  font-size: 20px;
  color: #000;">Sign in to access admin panel</p>
        
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
        <div class="pull-right1">
                <?= $this->Form->submit('Sign in', ['class' => 'btn btn-primary mr5']) ?>
            </div>
            <div class="pull-left1">
                <div class="ckbox ckbox-primary mt10" style="text-align: center;font-size: 16px;color: #000;">
                    Forgot password? <?= $this->Html->link('Click here to recover access', [
                        'controller' => 'Admins',
                        'action' => 'forgotpassword',
                        'prefix' => 'Admin'
                    ]) ?>
                </div>
            </div>
            
        </div>   
        <div style="text-align:center"><p style="font-size: 14px;color: #555;margin-top: 50px;line-height: 27px;">Copyright © 2026<strong> ERISAQuote Pro.</strong> All rights reserved. <br>
            Reproduction in whole or in part in any form or medium without express written permission is prohibited.</p></div>                 
        
        <?= $this->Form->end() ?></div>
    </div>
        
    </div><!-- panel-body -->
    
    <div class="panel-footer" style="display:none;">
        <!-- Optional signup link if needed in the future -->
        <!-- <?= $this->Html->link('Not yet a Member? Create Account Now', ['controller' => 'Admins', 'action' => 'signup', 'prefix' => 'Admin'], ['class' => 'btn btn-primary btn-block']) ?> -->
    </div><!-- panel-footer -->
</div><!-- panel -->