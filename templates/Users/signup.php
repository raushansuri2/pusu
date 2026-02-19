<style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }
    .input-container {
        position: relative;
        width: 100%;
    }
    .input-container input,
    .form-control {
        width: 100%;
        padding: 10px;
        padding-right: 40px;
        box-sizing: border-box;
        border: 1px solid #ced4da;
        border-radius: 4px;
        transition: border-color 0.3s;
        height: 40px;
    }
    .input-container input:focus,
    .form-control:focus {
        border-color: #80bdff;
        outline: none;
    }
    .eye-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #888;
        font-size: 1.2em;
    }
    .slash {
        position: absolute;
        width: 20px;
        height: 2px;
        background-color: red;
        transform: rotate(45deg);
        top: 50%;
        left: 50%;
        margin-left: -10px;
        pointer-events: none;
        display: block;
    }
    .bottom-login {
        font-weight: bold;
        font-size: 16px;
        color: #555;
    }
    .center {
        text-align: center;
    }
    .mrg-top-5 {
        margin-top: 20px;
    }
    .bottom-login span {
        margin-right: 5px;
    }
    .bottom-login a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s ease;
    }
    .bottom-login a:hover {
        color: #0056b3;
        text-decoration: underline;
    }
    /* Button Styles */
    .btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
    }
    .btn:hover {
        background-color: #0056b3;
    }
    /* Checkbox Styles */
    .custom-checkbox {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }
    .custom-checkbox input {
        margin-right: 10px;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    .custom-checkbox a {
        color: #007bff;
    }
    .custom-checkbox a:hover {
        text-decoration: underline;
    }
    /* Mandatory Field */
    .mandatory {
        color: red;
        margin-left: 5px;
    }
    /* Form Group */
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    /* Responsive Styles */
    @media (max-width: 768px) {
        .col-md-8, .col-md-4, .col-sm-12 {
            width: 100%;
        }
        .row {
            margin: 0;
        }
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- HTML Structure -->
<div class="col-md-12 col-sm-12">
    <div class="wel-back">
        <h2>Sign <span class="theme-cl">Up</span></h2>
    </div>
</div>
<div class="col-md-8 col-sm-12">
    <?php echo $this->Flash->render(); ?>
    <?php echo $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'signup'], 'onSubmit' => 'return checksignup();', 'autocomplete' => 'off']); ?>
        <input type="hidden" name="_csrfToken" value="<?php echo $this->request->getAttribute('csrfToken'); ?>">
        <?php $submittedData = $this->request->getData(); ?>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>First Name<span class="mandatory">*</span></label>
                    <input type="text" name="firstName" id="firstName" class="form-control" placeholder="First Name" autocomplete="off" required
                        pattern="^(?=.*[a-zA-Z])[a-zA-Z0-9]+$"
                        title="First name must contain at least one letter and can include numbers, but no spaces."
                        maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')"
                        value="<?php echo isset($submittedData['firstName']) ? h($submittedData['firstName']) : ''; ?>"
                        aria-required="true">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Last Name<span class="mandatory">*</span></label>
                    <input type="text" name="lastName" id="lastName" class="form-control" placeholder="Last Name" autocomplete="off" required
                        pattern="^(?=.*[a-zA-Z])[a-zA-Z0-9]+$"
                        title="Last name must contain at least one letter and can include numbers, but no spaces."
                        maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')"
                        value="<?php echo isset($submittedData['lastName']) ? h($submittedData['lastName']) : ''; ?>"
                        aria-required="true">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Email<span class="mandatory">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" autocomplete="off" required
                        value="<?php echo isset($submittedData['email']) ? h($submittedData['email']) : ''; ?>"
                        aria-required="true">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Gender<span class="mandatory">*</span></label>
                    <select id="gender" class="form-control" name="gender" required aria-required="true">
                        <option value="">Select Gender</option>
                        <option value="Male" <?php echo (isset($submittedData['gender']) && $submittedData['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (isset($submittedData['gender']) && $submittedData['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Phone<span class="mandatory">*</span></label>
                    <input type="tel" name="contactNumber" id="contactNumber" class="form-control"
                        placeholder="Example: +1 (123) 456-7890"
                        autocomplete="off"
                        maxlength="17"
                        pattern="^\+1 \(\d{3}\) \d{3}-\d{4}$"
                        title="Please enter a valid phone number (e.g., +1 (123) 456-7890)"
                        required
                        value="<?php echo isset($submittedData['contactNumber']) ? h($submittedData['contactNumber']) : ''; ?>"
                        oninput="handleInput(event)"
                        aria-required="true">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Date of Birth<span class="mandatory">*</span></label>
                    <input type="text" id="dob" name="dob" class="form-control" placeholder="mm-dd-yyyy" autocomplete="off" required
                        value="<?php echo isset($submittedData['dob']) ? h($submittedData['dob']) : ''; ?>"
                        aria-required="true">
                </div>
            </div>
            <div class="col-sm-6">
                <label>Password<span class="mandatory">*</span></label>
                <div class="input-container" id="PAssword">
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="********" autocomplete="off" required aria-required="true">
                    <span class="eye-icon" id="togglePassword">
                        <span id="eyeIconPass">👁️</span>
                        <span class="slash" id="slashPass"></span>
                    </span>
                </div>
            </div>
            <div class="col-sm-6">
                <label>Confirm Password<span class="mandatory">*</span></label>
                <div class="input-container" id="CPAssword">
                    <input type="password" name="Cpassword" id="Cpassword" class="form-control"
                        placeholder="********" autocomplete="off" required aria-required="true">
                    <span class="eye-icon" id="toggleCPassword">
                        <span id="eyeIconCPass">👁️</span>
                        <span class="slash" id="slashCPass"></span>
                    </span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Address<span class="mandatory">*</span></label>
                    <input type="text" name="address" id="address" class="form-control" placeholder="Enter Your Address" autocomplete="off"
                        minlength="10" maxlength="100"
                        pattern="^(?=.*\d)[a-zA-Z0-9\s,.'-#]+$"
                        title="Please enter a valid address (must include at least one number; letters, numbers, spaces, commas, periods, hyphens, apostrophes, and '#' are allowed)."
                        required
                        value="<?php echo isset($submittedData['address']) ? h($submittedData['address']) : ''; ?>"
                        aria-required="true">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="countryId">Country<span class="mandatory">*</span></label>
                    <select name="countryId" id="countryId" class="form-control" required aria-required="true">
                        <option value="">Select Country</option>
                        <?php foreach ($countryList as $key => $value): ?>
                            <option value="<?php echo h($key); ?>" <?php echo (isset($submittedData['countryId']) && $submittedData['countryId'] == $key) ? 'selected' : ''; ?>>
                                <?php echo h($value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="stateId">State<span class="mandatory">*</span></label>
                    <select name="stateId" id="stateId" class="form-control" required aria-required="true">
                        <option value="">Select State</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="cityId">City<span class="mandatory">*</span></label>
                    <select name="cityId" id="cityId" class="form-control" required aria-required="true">
                        <option value="">Select City</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Zipcode<span class="mandatory">*</span></label>
                    <input type="text" name="zipCode" id="zipCode" class="form-control"
                        minlength="5" maxlength="5"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        placeholder="Enter a zipcode"
                        autocomplete="off"
                        required
                        value="<?php echo isset($submittedData['zipCode']) ? h($submittedData['zipCode']) : ''; ?>"
                        aria-required="true">
                </div>
            </div>
        </div>
        <div class="custom-checkbox">
            <input type="checkbox" id="select1" aria-required="true">
            <label for="select1"></label>
            <a href="<?php echo $this->Url->build('/'); ?>terms.html" target="_blank" id="tlink">I agree to the Terms & Conditions.</a>
        </div>
        <div class="center">
            <button type="submit" class="btn">Sign Up</button>
        </div>
    <?php echo $this->Form->end(); ?>

    <div class="center mrg-top-5">
        <div class="bottom-login text-center">
            <span>Already have an account?</span>
            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'login']); ?>">Sign In</a>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-12 hidden-xs">
    <div class="wel-back"></div><br>
    <img src="<?php echo $this->Url->build('/'); ?>assets/img/dog.png" style="width:100%; margin-top:5px;" alt="Dog Image">
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript">
    function checksignup() {
        $(".contactresponse").remove(); // Clear previous error messages
        let reg = 0;

        const firstName = $("#firstName").val().trim();
        const lastName = $("#lastName").val().trim();
        const email = $("#email").val().trim();
        const gender = $("#gender").val().trim();
        const dob = $("#dob").val().trim();
        const phone = $("#contactNumber").val().trim();
        const address = $("#address").val().trim();
        const country = $("#countryId").val().trim();
        const state = $("#stateId").val().trim();
        const city = $("#cityId").val().trim();
        const zipcode = $("#zipCode").val().trim();
        const password = $("#password").val().trim();
        const confirmPass = $("#Cpassword").val().trim();
        const termsChecked = $("#select1").prop("checked");

        const emailPattern = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/;
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (firstName === '') {
            reg++;
            $("#firstName").after('<span class="contactresponse" style="color: red;">Please enter first name.</span>');
        }
        if (lastName === '') {
            reg++;
            $("#lastName").after('<span class="contactresponse" style="color: red;">Please enter last name.</span>');
        }
        if (!emailPattern.test(email)) {
            reg++;
            $("#email").after('<span class="contactresponse" style="color: red;">Please enter a valid email address.</span>');
        }
        if (gender === '') {
            reg++;
            $("#gender").after('<span class="contactresponse" style="color: red;">Please select a gender.</span>');
        }
        if (dob === '') {
            reg++;
            $("#dob").after('<span class="contactresponse" style="color: red;">Please select date of birth.</span>');
        }
        if (phone === '') {
            reg++;
            $("#contactNumber").after('<span class="contactresponse" style="color: red;">Please enter a phone number.</span>');
        }
        if (address === '') {
            reg++;
            $("#address").after('<span class="contactresponse" style="color: red;">Please enter an address.</span>');
        }
        if (country === '') {
            reg++;
            $("#countryId").after('<span class="contactresponse" style="color: red;">Please select a country.</span>');
        }
        if (state === '') {
            reg++;
            $("#stateId").after('<span class="contactresponse" style="color: red;">Please select a state.</span>');
        }
        if (city === '') {
            reg++;
            $("#cityId").after('<span class="contactresponse" style="color: red;">Please select a city.</span>');
        }
        if (zipcode === '') {
            reg++;
            $("#zipCode").after('<span class="contactresponse" style="color: red;">Please enter a zipcode.</span>');
        }
        if (password === '') {
            reg++;
            $("#PAssword").after('<span class="contactresponse" style="color: red;">Please enter a password.</span>');
        } else if (!passwordPattern.test(password)) {
            reg++;
            $("#PAssword").after('<span class="contactresponse" style="color: red;">Password must be at least 8 characters, with one uppercase, one lowercase, one number, and one special character.</span>');
        }
        if (confirmPass !== password) {
            reg++;
            $("#CPAssword").after('<span class="contactresponse" style="color: red;">Confirm password must match the password.</span>');
        }
        if (!termsChecked) {
            reg++;
            $("#tlink").after('<span class="contactresponse" style="color: red;">Please agree to the Terms & Conditions.</span>');
        }

        return reg === 0; // Return true if no errors, false otherwise
    }
</script>
<!-- States Script -->
<script type="text/javascript">
    jQuery(document).ready(function() {
        const csrfToken = $('input[name="_csrfToken"]').val();
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-Token': csrfToken
            }
        });

        function loadStates(countryId) {
            $('#stateId').empty().append('<option value="">Select State</option>');
            $('#cityId').empty().append('<option value="">Select City</option>');
            if (!countryId) return;

            $.ajax({
                data: { countryId: countryId },
                type: 'POST',
                url: "<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'stateList']); ?>",
                beforeSend: function() {
                    $('.ajaxloader').fadeIn();
                },
                success: function(response) {
                    $('.ajaxloader').fadeOut();
                    if (response.options) {
                        $("#stateId").append(response.options);
                    }
                },
                error: function(xhr, status, error) {
                    $('.ajaxloader').fadeOut();
                    console.error("State AJAX Error:", error);
                }
            });
        }

        $("#countryId").on('change', function() {
            loadStates($(this).val());
        });

        // Load states for pre-selected country
        const countryId = $("#countryId").val();
        if (countryId) loadStates(countryId);
    });
</script>
<!-- Cities Script -->
<script type="text/javascript">
    jQuery("#stateId").on('change', function() {
        const stateId = $(this).val();
        $('#cityId').empty().append('<option value="">Select City</option>');
        if (!stateId) return;

        $.ajax({
            data: { stateId: stateId },
            type: 'POST',
            url: "<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'cityList']); ?>",
            beforeSend: function() {
                $('.ajaxloader').fadeIn();
            },
            success: function(response) {
                $('.ajaxloader').fadeOut();
                if (response.options) {
                    $("#cityId").append(response.options);
                }
            },
            error: function(xhr, status, error) {
                $('.ajaxloader').fadeOut();
                console.error("City AJAX Error:", error);
            }
        });
    });
</script>
<!-- Flatpickr for DOB -->
<script>
    flatpickr("#dob", {
        mode: "single",
        dateFormat: "m-d-Y",
        allowInput: true,
        maxDate: "today",
        defaultDate: $("#dob").val() || null
    });
</script>
<!-- Password Toggle -->
<script>
    // Password toggle
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIconPass');
        const slash = document.getElementById('slashPass');

        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        slash.style.display = type === 'password' ? 'block' : 'none';
    });

    // Confirm password toggle
    document.getElementById('toggleCPassword').addEventListener('click', function() {
        const cPasswordInput = document.getElementById('Cpassword');
        const eyeIcon = document.getElementById('eyeIconCPass');
        const slash = document.getElementById('slashCPass');

        const type = cPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        cPasswordInput.setAttribute('type', type);
        slash.style.display = type === 'password' ? 'block' : 'none';
    });
</script>
<!-- Phone Number Formatting -->
<script>
    function formatPhoneNumber(input) {
        const cleaned = ('' + input).replace(/(?!^\+)\D/g, '');
        if (cleaned.length < 11) return cleaned;

        const match = cleaned.match(/^(\+1)(\d{3})(\d{3})(\d{4})$/);
        if (match) {
            return `${match[1]} (${match[2]}) ${match[3]}-${match[4]}`;
        }
        return input;
    }

    function handleInput(event) {
        const input = event.target;
        input.value = formatPhoneNumber(input.value);
    }
</script>