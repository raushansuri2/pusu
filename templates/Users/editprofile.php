<style>
    /* General Styles */
    .input-container {
        position: relative;
        width: 100%;
    }
    .input-container input {
        width: 100%;
        padding-right: 40px;
        box-sizing: border-box;
        border: 1px solid #ced4da;
        border-radius: 4px;
        transition: border-color 0.3s;
    }
    .input-container input:focus {
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
        width: 100%;
        height: 2px;
        background-color: red;
        transform: rotate(45deg);
        top: 50%;
        left: 0;
        pointer-events: none;
        display: block; /* Show the slash by default */
    }
    @media (max-width: 768px) {
        .col-md-8, .col-md-4 {
            width: 100%;
        }
    }
</style>

<div class="col-md-10 col-sm-12 col-md-offset-1">
    <?= $this->Flash->render() ?>
    
    <!-- General Information -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'editprofile'],
        'class' => 'register-form',
        'enctype' => 'multipart/form-data'
    ]) ?>
        <div class="add-listing-box edit-info mrg-bot-25 padd-bot-30 padd-top-25">
            <div class="listing-box-header">
                <div class="avater-box">
                    <?php 
                    $UIMG = $user->profile_picture ? 
                        $this->Url->build('/img/uploads/users/' . $user->profile_picture) : 
                        $this->Url->build('/img/dummy.jpg');
                    ?>
                    <img src="<?= $UIMG ?>" class="img-responsive img-circle edit-avater" alt="">
                    <div class="upload-btn-wrapper">
                        <button class="btn theme-btn">Change Avatar</button>
                        <?= $this->Form->file('profile_picture') ?>
                    </div>
                </div>
                <h3><?= h($user->firstName) . ' ' . h($user->lastName) ?></h3>
            </div>
            <div class="row mrg-r-10 mrg-l-10">
                <div class="col-sm-6">
                    <label>First Name</label>
                    <?= $this->Form->control('firstName', [
                        'class' => 'form-control',
                        'placeholder' => 'First Name',
                        'autocomplete' => 'off',
                        'required' => true,
                        'pattern' => '^(?=.*[a-zA-Z])[a-zA-Z0-9]+$',
                        'title' => 'First name must contain at least one letter and can include numbers, but no spaces.',
                        'maxlength' => 20,
                        'value' => h($user->firstName),
                        'label' => false,
                        'oninput' => "this.value = this.value.replace(/\s/g, '')"
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Last Name</label>
                    <?= $this->Form->control('lastName', [
                        'class' => 'form-control',
                        'placeholder' => 'Last Name',
                        'autocomplete' => 'off',
                        'required' => true,
                        'pattern' => '^(?=.*[a-zA-Z])[a-zA-Z0-9]+$',
                        'title' => 'Last name must contain at least one letter and can include numbers, but no spaces.',
                        'maxlength' => 20,
                        'value' => h($user->lastName),
                        'label' => false,
                        'oninput' => "this.value = this.value.replace(/\s/g, '')"
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Email</label>
                    <?= $this->Form->control('email', [
                        'class' => 'form-control',
                        'disabled' => true,
                        'value' => h($user->email),
                        'autocomplete' => 'off',
                        'required' => true,
                        'label' => false
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Phone</label>
                    <?= $this->Form->control('contactNumber', [
                        'type' => 'tel',
                        'class' => 'form-control',
                        'placeholder' => 'Example: +16862612722',
                        'autocomplete' => 'off',
                        'maxlength' => 17,
                        'pattern' => '^\+1 \(\d{3}\) \d{3}-\d{4}$',
                        'title' => 'Please enter a valid phone number (e.g., +16862612722)',
                        'value' => h($user->contactNumber),
                        'required' => true,
                        'label' => false,
                        'oninput' => 'handleInput(event)'
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Profile ID Image (Seller)
                    <?php if ($user->profile_ID_image): ?>
                        <a target="_blank" href="<?= $this->Url->build('/img/uploads/users/usersIdImage/' . h($user->profile_ID_image)) ?>">View</a>
                    <?php endif; ?>
                    </label>
                    <?= $this->Form->file('profile_ID_image', ['class' => 'form-control']) ?>
                </div>
            </div>
        </div>

        <!-- Edit Location -->
        <div class="add-listing-box add-location mrg-bot-25 padd-bot-30 padd-top-25">
            <div class="listing-box-header">
                <i class="ti-location-pin theme-cl"></i>
                <h3>Edit Location</h3>
                <p>Write Address Information about your listing Location</p>
            </div>
            <div class="row mrg-r-10 mrg-l-10">
                <div class="col-sm-12">
                    <label>Address</label>
                    <?= $this->Form->control('address', [
                        'class' => 'form-control',
                        'value' => h($user->address),
                        'placeholder' => 'Enter Your Address',
                        'autocomplete' => 'off',
                        'minlength' => 10,
                        'maxlength' => 100,
                        'pattern' => '^(?=.*\d)[a-zA-Z0-9\s,.\'-#]+$',
                        'title' => 'Please enter a valid address (must include at least one number, letters, numbers, spaces, commas, periods, hyphens, apostrophes, and # allowed).',
                        'required' => true,
                        'label' => false
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Country</label>
                    <?= $this->Form->select('countryId', $countryList, [
                        'class' => 'form-control',
                        'value' => $user->countryId,
                        'empty' => 'Select Country',
                        'id' => 'countryId',
                        'required' => true,
                        'label' => false
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>State</label>
                    <?= $this->Form->select('stateId', $stateList, [
                        'class' => 'form-control',
                        'value' => $user->stateId,
                        'empty' => 'Select State',
                        'id' => 'stateId',
                        'required' => true,
                        'label' => false
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>City</label>
                    <?= $this->Form->select('cityId', $cityList, [
                        'class' => 'form-control',
                        'value' => $user->cityId,
                        'empty' => 'Select City',
                        'id' => 'cityId',
                        'required' => true,
                        'label' => false
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <label>Zipcode</label>
                    <?= $this->Form->control('zipCode', [
                        'class' => 'form-control',
                        'id' => 'zipCode',
                        'pattern' => '^(?!00000)\d{5}$',
                        'title' => 'Zip code must be exactly 5 digits and cannot be all zeros.',
                        'value' => h($user->zipCode),
                        'placeholder' => 'Enter a zipcode',
                        'autocomplete' => 'off',
                        'maxlength' => 5,
                        'required' => true,
                        'label' => false,
                        'oninput' => "this.value = this.value.replace(/[^0-9]/g, '');"
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <?= $this->Form->button('Update Profile', ['class' => 'btn theme-btn']) ?>
        </div>
    <?= $this->Form->end() ?>
    <br><br>

    <!-- Change Password Information -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'changepassword'],
        'class' => 'register-form'
    ]) ?>
        <div class="add-listing-box opening-day mrg-bot-25 padd-bot-30 padd-top-25">
            <div class="listing-box-header">
                <i class="ti-lock theme-cl"></i>
                <h3>Change Password</h3>
                <p>Remember, Your Password should not be easy and common.</p>
            </div>
            <div class="row mrg-r-10 mrg-l-10">
                <div class="col-sm-6">
                    <label>Old Password</label>
                    <div class="input-container" id="Password">
                        <?= $this->Form->control('oldpassword', [
                            'type' => 'password',
                            'id' => 'oldPassword',
                            'class' => 'form-control',
                            'placeholder' => '********',
                            'autocomplete' => 'off',
                            'required' => true,
                            'title' => 'Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one digit, and one special character (@$!%*?&).',
                            'label' => false
                        ]) ?>
                        <span class="eye-icon" id="toggleOldPassword">
                            <span id="eyeIconOld">👁️</span>
                            <span class="slash" id="slashOld"></span>
                        </span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>New Password</label>
                    <div class="input-container" id="CPassword">
                        <?= $this->Form->control('password', [
                            'type' => 'password',
                            'id' => 'newPassword',
                            'class' => 'form-control',
                            'placeholder' => '********',
                            'autocomplete' => 'off',
                            'required' => true,
                            'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$',
                            'title' => 'Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one digit, and one special character (@$!%*?&).',
                            'label' => false
                        ]) ?>
                        <span class="eye-icon" id="toggleNewPassword">
                            <span id="eyeIconNew">👁️</span>
                            <span class="slash" id="slashNew"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <?= $this->Form->button('Change Password', ['class' => 'btn theme-btn']) ?>
        </div>
    <?= $this->Form->end() ?>
</div>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#stateId").on('change', function() {
        $('.ajaxloader').fadeIn();
        $('#cityId').empty().append('<option value="">Select City</option>');
        var stateId = $(this).val();
        
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Users', 'action' => 'cityList']) ?>",
            type: 'POST',
            data: { stateId: stateId },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('.ajaxloader').fadeOut();
                $("#cityId").empty();
                $("#cityId").append(response.options);
            }
        });
    });

    // Toggle password visibility
    function togglePasswordVisibility(toggleId, passwordId, eyeIconId, slashId) {
        $('#' + toggleId).on('click', function() {
            const passwordInput = $('#' + passwordId);
            const eyeIcon = $('#' + eyeIconId);
            const slash = $('#' + slashId);
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            eyeIcon.html(type === 'password' ? '👁️' : '👁️');
            slash.css('display', type === 'password' ? 'block' : 'none');
        });
    }

    togglePasswordVisibility('toggleOldPassword', 'oldPassword', 'eyeIconOld', 'slashOld');
    togglePasswordVisibility('toggleNewPassword', 'newPassword', 'eyeIconNew', 'slashNew');

    function formatPhoneNumber(input) {
        const cleaned = ('' + input).replace(/(?!^\+)\D/g, '');
        if (cleaned.length < 11) return cleaned;
        const match = cleaned.match(/^(\+1)(\d{3})(\d{3})(\d{4})$/);
        if (match) return `${match[1]} (${match[2]}) ${match[3]}-${match[4]}`;
        return input;
    }

    window.handleInput = function(event) {
        const input = event.target;
        input.value = formatPhoneNumber(input.value);
    };
});
</script>