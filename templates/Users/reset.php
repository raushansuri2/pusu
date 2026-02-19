<div class="row">
    <div class="col-md-3 col-sm-12"></div>
    <div class="col-md-6 col-sm-12">
        <?php echo $this->Flash->render(); ?>

        <?php echo $this->Form->create(null, ['url' => ['controller' => 'users', 'action' => 'reset', $token]]); ?>
        
        <div class="form-group">
            <label>Password</label>
            <?php echo $this->Form->input("password", [
                "type" => "password", 
                "div" => false, 
                "label" => false,
                'placeholder' => "Password",
                'class' => 'form-control', 
                'validate' => true, 
                'required' => true, 
                'minlength' => '8', 
                'maxlength' => 16
            ]); ?>
        </div>
        
        <div class="form-group">
            <label>Confirm Password</label>
            <?php echo $this->Form->input("Cpassword", [
                "type" => "password", 
                "div" => false, 
                "label" => false,
                'placeholder' => "Confirm Password",
                'class' => 'form-control', 
                'validate' => true, 
                'required' => true, 
                'minlength' => '8', 
                'maxlength' => 16
            ]); ?>
        </div>
        
        <div class="center">
            <button type="submit" id="login-btn" class="btn btn-midium theme-btn btn-radius width-200">Reset</button>
        </div>
        
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="col-md-3 col-sm-12"></div>
</div>
