<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>Early Access</title>
    <link rel="icon" href="<?php echo $this->Url->build('/');?>favicon.png" type="image/png">


    <!-- Icons font CSS-->
    <link href="<?php echo $this->Url->build('/');?>contact/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="<?php echo $this->Url->build('/');?>contact/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="<?php echo $this->Url->build('/');?>contact/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="<?php echo $this->Url->build('/');?>contact/vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="<?php echo $this->Url->build('/');?>contact/css/main.css" rel="stylesheet" media="all">
    
    <!-- Toast CSS-->
    <link href="<?php echo $this->Url->build('/');?>contact/toast/css/toastr.min.css" rel="stylesheet">
    
    <style>
        .btn {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="page-wrapper bg-gra-03 p-t-45 p-b-50">
        <div class="wrapper wrapper--w790">
            <div class="card card-5">
                <div class="card-heading">
                    <h2 class="title">Signing Up for Early Access Form</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="" onsubmit="return validate()" id="contactForm">
                        <input type="hidden" name="_csrfToken" value="<?= $this->request->getAttribute('csrfToken') ?>">
                        <div class="form-row m-b-55">
                            <div class="name">Name</div>
                            <div class="value">
                                <div class="row row-space">
                                    <div class="col-2">
                                        <div class="input-group-desc">
                                            <input class="input--style-5" type="text" name="first_name" id="first_name" required>
                                            <label class="label--desc">First Name</label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="input-group-desc">
                                            <input class="input--style-5" type="text" name="last_name" id="last_name" required>
                                            <label class="label--desc">Last Name</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="name">Email</div>
                            <div class="value">
                                <div class="input-group">
                                    <input class="input--style-5" type="email" name="email" id="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="name">Country</div>
                            <div class="value">
                                <div class="input-group">
                                    <input class="input--style-5" type="text" name="country" id="country" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="name">Role</div>
                            <div class="value">
                                <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="role" id="role" onchange="showDiv(this)" required>
                                            <option disabled="disabled" selected="selected">Choose option</option>
                                            <option value="Pet parent">Pet parent</option>
                                            <option value="Veterinarian">Veterinarian</option>
                                            <option value="Other pet service provider">Other pet service provider</option>
                                        </select>
                                        <div class="select-dropdown" id="rol"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row" style="visibility:hidden;" id="sub_role_div">
                            <div class="name">Sub Role</div>
                            <div class="value">
                                <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="sub_role" id="sub_role">
                                            <option disabled="disabled" selected="selected">Choose option</option>
                                            <option value="Pet Sitter">Pet Sitter</option>
                                            <option value="Dog Walker">Dog Walker</option>
                                            <option value="Pet Groomer">Pet Groomer</option>
                                            <option value="Pet Boarding">Pet Boarding</option>
                                            <option value="Pet Trainer">Pet Trainer</option>
                                        </select>
                                        <div class="select-dropdown" id="rol2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn--radius-2 btn--blue" type="submit" id="submitButton">Send</button>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="/" class="btn btn--radius-2 btn--red">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="<?php echo $this->Url->build('/');?>contact/vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="<?php echo $this->Url->build('/');?>contact/vendor/select2/select2.min.js"></script>
    <script src="<?php echo $this->Url->build('/');?>contact/vendor/datepicker/moment.min.js"></script>
    <script src="<?php echo $this->Url->build('/');?>contact/vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="<?php echo $this->Url->build('/');?>contact/js/global.js"></script>

    <script type="text/javascript">
        function showDiv(select) {
            if (select.value == 'Other pet service provider') {
                document.getElementById('sub_role_div').style.visibility = "visible";
            } else {
                document.getElementById('sub_role_div').style.visibility = "hidden";
                document.getElementById('sub_role').selectedIndex = 0; // Reset sub role selection
            }
        }

        function validate() {
            $(".contactresponse").remove();
            var Fname = $("#first_name").val();
            var Lname = $("#last_name").val();
            var email = $("#email").val();
            var country = $("#country").val();
            var role = $('#role').find(":selected").val();
            var sub_role = $("#sub_role").val();

            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            var reg = 0;

            if (Fname == '') {
                reg++;
                $("#first_name").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter first name.</span>');
            }
            if (Lname == '') {
                reg++;
                $("#last_name").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter last name.</span>');
            }
            if (!filter.test(email)) {
                reg++;
                $("#email").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter valid email address.</span>');
            }
            if (country == '') {
                reg++;
                $("#country").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter country.</span>');
            }
            if (!role) {
                reg++;
                $("#rol").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter role.</span>');
            }
            if (document.getElementById('sub_role_div').style.visibility == "visible" && !sub_role) {
                reg++;
                $("#rol2").after('<span class="contactresponse" style="color: rgb(255, 0, 0);">Please enter Sub role.</span>');
            }

            return reg < 1; // Return true if no errors were found
        }

        document.getElementById('contactForm').addEventListener('submit', function(event) {
            var submitButton = document.getElementById('submitButton');
            if (!validate()) {
                event.preventDefault(); // Prevent form submission if validation fails
                return;
            }
            submitButton.disabled = true; // Disable the submit button
            submitButton.textContent = 'Submitting...'; // Change button text
        });
    </script>
    <!-- Toast JS-->
    <script src="contact/toast/js/toastr.min.js"></script>
    <?php if (!empty($this->Flash->render())) { ?>
        <script>
            toastr.success('Thank you for signing up with us. We will contact you soon.');
        </script>
    <?php } ?>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>
</body>
</html>