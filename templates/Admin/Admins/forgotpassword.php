<div class="panel panel-signin" style="height: 100vh;">
    <div class="panel-body">
        <div class="row">
    <div class="col-md-7" style="background: url('https://www.vocecon.com/wp-content/uploads/pure-offset-calculation.jpg');height: 100%;min-height: 390px; background-repeat: no-repeat;
  background-size: cover; height: 100vh;"></div>
  
  <div class="col-md-5" style="padding: 41px;"><div class="logo text-center">
            <?php echo $this->Html->image('admin/logo-black.png', ['style' => 'width:400px']); ?>
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
        <div class="pull-right1">
        <div class="submit">
                <?php echo $this->Form->button('Submit', [
                    'class' => 'btn btn-primary mr5',
                    'div' => false
                ]); ?>
            </div></div>
            <div class="pull-left1">
                <div class="ckbox ckbox-primary mt10" style="text-align: center;font-size: 16px;color: #000;">
                    <?php echo $this->Html->link('Click Here', ['controller' => 'Admins', 'action' => 'login']); ?> to back Login.
                </div>
            </div>
            
        </div>   
        
         <div style="text-align:center"><p style="font-size: 14px;color: #555;margin-top: 50px;line-height: 27px;">Copyright © 2026<strong> ERISAQuote Pro.</strong> All rights reserved. <br>
            Reproduction in whole or in part in any form or medium without express written permission is prohibited.</p></div>                   
        <?php echo $this->Form->end(); ?>
</div>


</div>
            </div><!-- panel-body -->
    
    <div class="panel-footer">
        <!-- Uncomment if you want to add a signup link -->
        <!-- {{ HTML::link('admin/user/signup', 'Not yet a Member? Create Account Now', array('class' => 'btn btn-primary btn-block'))}} -->
    </div><!-- panel-footer -->
</div><!-- panel -->