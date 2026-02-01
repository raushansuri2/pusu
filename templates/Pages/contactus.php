<div class="container">
    <div class="col-md-10 col-md-offset-1 col-sm-12 translateY-60">
        <div class="col-md-12 col-sm-12">
            <div class="detail-wrapper text-center padd-top-40 mrg-bot-10 padd-bot-40 light-bg">
                <i class="theme-cl font-30 ti-location-pin"></i>
                <h4>USA Office</h4>
                <p>16445 Tomahawk Drive, Gaithersburg, MD 20878</p>
            </div>
        </div>
    </div>
</div>

<section class="padd-top-0">
    <div class="container">
        <div class="col-md-6 col-sm-6">
            <?php echo $this->Flash->render(); ?>
            <?php echo $this->Form->create(null, ['url' => ['controller' => 'Pages', 'action' => 'contactus'], 'onsubmit' => 'return validate();']); ?>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea name="message" id="message" class="form-control height-120" placeholder="Message" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn theme-btn form-control" name="submit" id="submitBtn">SEND MESSAGE</button>
                </div>
            <?php echo $this->Form->end(); ?>
        </div>
        <div class="col-md-6 col-sm-6">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.312031449958!2d-73.46106868506354!3d40.71114697933196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89e9d550d3e6818d%3A0xe9c0276734feb241!2sN%20New%20York%20Dr%2C%20North%20Massapequa%2C%20NY%2011758%2C%20USA!5e0!3m2!1sen!2sin!4v1571653902390!5m2!1sen!2sin" width="550" height="400" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script type="text/javascript">
    function validate() {
        $(".contactresponse").remove();
        var REname = $("#name").val();
        var REemail = $("#email").val();
        var RMessage = $("#message").val();
    
        var emailPattern = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
        var errorCount = 0;
    
        if (REname === '') {
            errorCount++;
            $("#name").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter your name.</span>');
        }
        if (!emailPattern.test(REemail)) {
            errorCount++;
            $("#email").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter a valid email address.</span>');
        }
        if (RMessage === '') {
            errorCount++;
            $("#message").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter your message.</span>');
        }

        // If there are no errors, disable the button and show loading
        if (errorCount === 0) {
            $("#submitBtn").prop("disabled", true ).html('Sending...');
        }
    
        return errorCount === 0; // Return true if no errors
    }
</script>