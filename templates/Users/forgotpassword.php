<div class="col-md-3 col-sm-12"></div>
<div class="col-md-6 col-sm-12">
    <?php echo $this->Flash->render(); ?>
    <div class="wel-back">
        <h2>Forgot <span class="theme-cl">Password</span></h2>
    </div>
    <?php echo $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'forgotpassword']]); ?>
        <div class="form-group">
            <label>Email Address <span class="mandatory">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="Enter your registered email" required autocomplete="off">
        </div>
        <div class="center">
            <button type="submit" id="login-btn" class="btn btn-medium theme-btn btn-radius width-200">Send</button>
        </div>
    <?php echo $this->Form->end(); ?>
</div>
<div class="col-md-3 col-sm-12"></div>
