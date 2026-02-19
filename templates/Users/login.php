<style>
    /* Basic styling for the form */
    body {
        font-family: Arial, sans-serif; /* Set a default font for better readability */
        background-color: #f8f9fa; /* Light background for contrast */
        color: #333; /* Dark text for readability */
    }

    .form-group {
        margin: 20px 0; /* Adjusted margin for better spacing */
    }

    .input-container {
        position: relative;
        width: 100%; /* Full width */
    }

    .input-container input {
        width: 100%; /* Full width for the input */
        padding-right: 40px; /* Space for the eye icon */
        box-sizing: border-box; /* Include padding in width */
        border: 1px solid #ced4da; /* Border color */
        border-radius: 4px; /* Rounded corners */
        transition: border-color 0.3s; /* Smooth transition for border color */
    }

    .input-container input:focus {
        border-color: #80bdff; /* Change border color on focus */
        outline: none; /* Remove default outline */
    }

    .eye-icon {
        position: absolute;
        right: 10px; /* Position the icon */
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #888; /* Icon color */
        font-size: 1.2em; /* Adjust icon size */
    }

    .slash {
        position: absolute;
        width: 100%;
        height: 2px; /* Thickness of the slash */
        background-color: red; /* Color of the slash */
        transform: rotate(45deg); /* Rotate to create a slash effect */
        top: 50%;
        left: 0;
        pointer-events: none; /* Prevent interaction with the slash */
        display: block; /* Initially show the slash */
    }

    .keep-signin-container {
        display: flex; /* Use flexbox for alignment */
        justify-content: space-between; /* Space between items */
        align-items: center; /* Center items vertically */
        margin-top: 10px; /* Add some margin on top */
    }

    .keep-signin-container label {
        margin: 0; /* Remove default margin */
        font-size: 14px; /* Adjust font size */
    }

    .forgot-password {
        font-size: 14px; /* Adjust font size */
        color: #007bff; /* Link color */
        text-decoration: none; /* Remove underline */
    }

    .forgot-password:hover {
        text-decoration: underline; /* Underline on hover */
    }

    .center {
        text-align: center; /* Center text */
    }

    .mrg-top-5 {
        margin-top: 20px; /* Increased margin for better spacing */
    }

    .bottom-login {
        font-size: 16px; /* Adjust font size */
        color: #555; /* Darker text color for better readability */
    }

    .bottom-login span {
        margin-right: 5px; /* Space between text and link */
    }

    .bottom-login a {
        color: #007bff; /* Link color */
        text-decoration: none; /* Remove underline */
        font-weight: bold; /* Make the link bold */
        transition: color 0.3s ease; /* Smooth transition for hover effect */
    }

    .bottom-login a:hover {
        color: #0056b3; /* Darker shade on hover */
        text-decoration: underline; /* Underline on hover */
    }

    /* Button Styles */
    .btn {
        background-color: #007bff; /* Button background color */
        color: white; /* Button text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Padding for button */
        border-radius: 4px; /* Rounded corners */
        cursor: pointer; /* Pointer cursor on hover */
        transition: background-color 0.3s; /* Smooth transition for background color */
    }

    .btn:hover {
        background-color: #0056b3; /* Darker background on hover */
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .col-md-6 {
            width: 100%; /* Full width on smaller screens */
        }
    }
</style>

<div class="row">
    <div class="col-md-6 col-sm-12">
        <?php echo $this->Flash->render(); ?>
        <div class="wel-back">
            <h2>Sign <span class="theme-cl">In</span></h2>
        </div>
        <?php echo $this->Form->create(null, ['url'=>['controller'=>'users','action'=>'login'], 'id' => 'loginForm']);?>
        <?php $submittedData = $this->request->getData();?>
            <input type="hidden" name="_csrfToken" value="<?php echo $this->request->getAttribute('csrfToken'); ?>">
            <div class="form-group">
                <label>Email Address<span class="mandatory">*</span></label>
                <input type="email" name="email" id="loginEmail" class="form-control" placeholder="Email Address" autocomplete="off" required value="<?php echo isset($submittedData['email']) ? h($submittedData['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="loginPassword">Password<span class="mandatory">*</span></label>
                <div class="input-container">
                    <input type="password" name="password" id="loginPassword" class="form-control" minlength="8" placeholder="********" autocomplete="off" required>
                    <span class="eye-icon" id="togglePassword">
                        <span id="eyeIcon">👁️</span> <!-- Default eye icon with slash -->
                        <span class="slash" id="slash"></span> <!-- Slash overlay -->
                    </span>
                </div>
            </div>
            <div class="form-group keep-signin-container">
                <label>
                    <input type="checkbox" name="keep_signed_in" id="keepSignedIn"> Keep me signed in
                </label>
                <a href="<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'forgotpassword']);?>" class="forgot-password">Forgot password?</a>
            </div>
            <div class="center">
                <button type="submit" id="loginSubmit" class="btn btn-midium theme-btn btn-radius width-200"> Sign In </button>
            </div>
        </form>
        <div class="center mrg-top-5">
            <div class="bottom-login text-center">
                <span>Don't have an account?</span>
                <a href="<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'signup']);?>" class="">Register Now</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12 hidden-xs">
        <img src="<?php echo $this->Url->build('/');?>assets/img/dog-doctor.jpg" style="width:100%; margin-top:5px;">
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    document.getElementById("loginForm").onsubmit = function(event) {
        // Prevent the default form submission
        event.preventDefault();

        // Clear previous error messages
        const errorMessages = document.querySelectorAll('.loginResponse');
        errorMessages.forEach(function(msg) {
            msg.remove();
        });

        // Get form values
        const loginEmail = document.getElementById("loginEmail").value;
        const loginPassword = document.getElementById("loginPassword").value;

        let isValid = true;

        // Validate email
        if (loginEmail.trim() === '') {
            isValid = false;
            const emailError = document.createElement("span");
            emailError.className = "loginResponse";
            emailError.style.color = "rgb(255, 0, 0)";
            emailError.textContent = "Please enter your email address.";
            document.getElementById("loginEmail").after(emailError);
        }

        // Validate password
        if (loginPassword.trim() === '') {
            isValid = false;
            const passwordError = document.createElement("span");
            passwordError.className = "loginResponse";
            passwordError.style.color = "rgb(255, 0, 0)";
            passwordError.textContent = "Please enter your password.";
            document.getElementById("loginPassword").after(passwordError);
        }

        // If the form is valid, append the timezone and submit
        if (isValid) {
            // Detect the user's timezone
            const userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            
            console.log("Timezone set successfully:", userTimeZone);
            
            // Create a hidden input for the timezone
            const timezoneInput = document.createElement("input");
            timezoneInput.type = "hidden";
            timezoneInput.name = "timezone";
            timezoneInput.value = userTimeZone;

            // Append the hidden input to the form
            this.appendChild(timezoneInput);

            // Submit the form
            this.submit();
        }
    };
</script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('loginPassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const slash = document.getElementById('slash');

        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Change the eye icon and display the slash based on visibility
        if (type === 'password') {
            eyeIcon.innerHTML = '👁️'; // Eye icon when password is hidden
            slash.style.display = 'block'; // Show the slash
        } else {
            eyeIcon.innerHTML = '👁️'; // Keep the eye icon
            slash.style.display = 'none'; // Hide the slash
        }
    });
</script>