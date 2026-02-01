<?php 
// echo $logginUserZipCode;exit;
?>
<style>
    .errornew {
        border-color: #ff0000 !important;
    }
    .msg-error {
        color: red;
    }
    #card-element {
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    #pay:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

<div class="col-md-8 col-sm-12">
    <?php echo $this->Flash->render(); ?>
    <div class="wel-back">
        <h2>Payment <span class="theme-cl">Process</span></h2>
    </div>
    <?php echo $this->Form->create(null, [
        'url' => [
            'controller' => 'users',
            'action' => 'payment',
            base64_encode($service_provider_id),
            base64_encode($user_id),
            base64_encode($utype),
            base64_encode($service_id),
            base64_encode($service_name),
            base64_encode($adminFee),
            base64_encode($service_fee),
            base64_encode($TOTALAMOUNT),
            base64_encode($bookDate)
        ],
        'id' => 'payForm',
        'autocomplete' => 'off'
    ]); ?>
    
    <input type="hidden" name="saleTax" id="saleTax" value="">
    <input type="hidden" name="total" id="total" value="">
    <div class="form-group">
        <label>Card Holder Name <span>*</span></label>
        <input type="text" name="name" id="name" class="form-control" placeholder="Card Holder Name" autocomplete="off">
        <span id="name-error" class="error-message" style="color: red;"></span>
    </div>

    <div class="form-group">
        <label for="card-element">Card Details <span>*</span></label>
        <div id="card-element"></div>
        <div id="card-errors" role="alert" style="color: red;"></div>
    </div>

    <div class="center">
        <button type="submit" id="pay" class="btn btn-midium theme-btn btn-radius width-200" disabled>Pay Now</button>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

<div class="col-md-4 col-sm-12">
    <div class="sidebar">
        <div class="widget-boxed padd-0">
            <div class="booking-price padd-15">
                <h4 class="mrg-bot-20">Payment Details</h4>
                <div class="booking-price-detail side-list no-border">
                    <h5>Your Date/Time</h5>
                    <ul>
                        <li>Date: <strong class="pull-right"><?php echo date("M jS, Y"); ?></strong></li>
                        <li>Time: <strong class="pull-right">
                            <?php
                                if ($this->request->getSession()->check('Config.timezone')) {
                                    date_default_timezone_set($this->request->getSession()->read('Config.timezone'));
                                } else {
                                    date_default_timezone_set('America/New_York');
                                }
                                echo date('g:i A');
                            ?>
                        </strong></li>
                    </ul>
                </div>
                <div class="booking-price-detail side-list no-border">
                    <?php if ($utype == 3) { ?>
                        <h5>Service Name</h5>
                    <?php } elseif ($utype == 2) { ?>
                        <h5>Business Name</h5>
                    <?php } ?>
                    <ul>
                        <li><?php echo $service_name; ?></li>
                    </ul>
                </div>
                <div class="booking-price-detail side-list no-border">
                    <?php if ($utype == 3) { ?>
                        <h5>Receipt Details</h5>
                    <?php } elseif ($utype == 2) { ?>
                        <h5>Business Cost Details</h5>
                    <?php } ?>
                    <ul>
                        <?php if ($utype == 3) { ?>
                            <li>Service Cost + Admin Fees<strong class="pull-right" id="serviceCostDisplay">$<?php echo ceil($TOTALAMOUNT); ?></strong></li>
                        <?php } elseif ($utype == 2) { ?>
                            <li>Business Cost + Admin Fees<strong class="pull-right" id="serviceCostDisplay">$<?php echo ceil($TOTALAMOUNT); ?></strong></li>
                        <?php } ?>
                        <?php 
                            $saleTax  = 0;
                            $TOTAL    = 0;
                        ?>
                        <li>State Sale Tax<strong class="pull-right" id="saleTaxDisplay">$<?php echo number_format($saleTax, 2); ?></strong></li>
                    </ul>
                </div>
                <div class="booking-price-detail side-list no-border" style="border-top: 1px dashed #ccc;">
                    <ul>
                        <li>Total Cost<strong class="theme-cl pull-right" id="totalDisplay">$<?php echo number_format($TOTAL, 2); ?></strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
    const stripe = Stripe('pk_test_51N4UjxIccD3egFe4JHkgdV0K73MeRhylVlcdj9pbMvjSZWsoeWRUy5d3Rqj1JTWG2cf6LkE7QhyKyOYG0rli2OgZ00bFYsbANS'); // test public key
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    jQuery(document).ready(function($) {
        var $payButton = $('#pay');
        var $errorMessage = null;

        // Initialize error message div
        if ($('#ajaxErrorMessage').length === 0) {
            $('<div id="ajaxErrorMessage" style="color: red; margin: 10px 0; display: none;"></div>')
                .insertAfter('#name');
        }
        $errorMessage = $('#ajaxErrorMessage');

        // Function to check if all conditions are met to enable the button
        function updateButtonState() {
            var nameValid = $('#name').val().length >= 7;
            var totalValid = parseFloat($('#total').val()) > 0;
            var hasError = $errorMessage.is(':visible');

            if (nameValid && totalValid && !hasError) {
                $payButton.prop('disabled', false);
            } else {
                $payButton.prop('disabled', true);
            }
        }

        // Stripe payment handling
        $('#pay').on('click', function(event) {
            event.preventDefault();

            let errorCount = 0;
            let focus = '';
            $("#name-error").text('');
            
            if ($("#name").val().length < 7) {
                $("#name-error").text("Please enter at least 7 letters for the name");
                errorCount++;
                if (focus == '') {
                    focus = 'name';
                }
            }

            if (errorCount < 1) {
                stripe.createToken(cardElement).then(function(result) {
                    if (result.error) {
                        $('#card-errors').text(result.error.message);
                        $payButton.prop('disabled', true);
                        updateButtonState();
                    } else {
                        const token = result.token.id;
                        $('#payForm').append($('<input type="hidden" name="stripeToken">').val(token));
                        $('#payForm').submit();
                    }
                });
            } else {
                $("#" + focus).focus();
                $payButton.prop('disabled', true);
                updateButtonState();
                return false;
            }
        });

        // Listen for changes to re-evaluate button state
        $('#name').on('input', function() {
            $("#name-error").text('');
            updateButtonState();
        });

        cardElement.on('change', function(event) {
            if (event.error) {
                $('#card-errors').text(event.error.message);
            } else {
                $('#card-errors').text('');
            }
            updateButtonState();
        });

        // Initial tax rate handling
        var zipCode = "<?php echo isset($logginUserZipCode) ? $logginUserZipCode : ''; ?>";
        var requestId = "<?php echo isset($request->id) ? $request->id : ''; ?>";

        // Clear previous error messages
        $errorMessage.hide().text('');

        if (zipCode.length === 5 && /^\d{5}$/.test(zipCode) && zipCode !== '00000') {
            $.ajax({
                url: "<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'getTaxRate']);?>/" + zipCode + "/" + requestId,
                type: 'GET',
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        
                        if (!data.rate || !data.rate.combined_rate) {
                            throw new Error('Invalid tax rate data received');
                        }

                        var saleTaxRate = parseFloat(data.rate.combined_rate) || 0;
                        var serviceCost = parseFloat($('#serviceCostDisplay').text().replace('$', '').replace(',', '')) || 0;
                        var saleTax = saleTaxRate * serviceCost;

                        $('#saleTaxDisplay').text('$' + saleTax.toFixed(2));
                        var total = serviceCost + saleTax;

                        $('#totalDisplay').text('$' + total.toFixed(2));
                        $('#saleTax').val(saleTax.toFixed(2));
                        $('#total').val(total.toFixed(2));

                        if (total > 0) {
                            $errorMessage.hide();
                        } else {
                            showError('Total amount is invalid');
                        }
                        updateButtonState();

                    } catch (e) {
                        showError('Error processing tax information: ' + e.message);
                        resetDisplays();
                        updateButtonState();
                    }
                },
                error: function(xhr, status, error) {
                    showError('Failed to get tax rates: ' + getErrorMessage(xhr, status, error));
                    resetDisplays();
                    updateButtonState();
                }
            });
        } else {
            resetDisplays();
            if (zipCode.length > 0) {
                showError('Please provide a valid 5-digit ZIP code');
            }
            updateButtonState();
        }

        // Function to show error messages
        function showError(message) {
            $errorMessage.text(message).show();
        }

        // Function to get detailed error message from AJAX response
        function getErrorMessage(xhr, status, error) {
            let message = status + ' - ' + error;
            if (xhr.responseText) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        message += ': ' + response.message;
                    }
                } catch (e) {
                    message += ': ' + xhr.responseText;
                }
            }
            return message;
        }

        // Function to reset displays and hidden inputs
        function resetDisplays() {
            $('#saleTaxDisplay').text('$0.00');
            $('#totalDisplay').text('$0.00');
            $('#saleTax').val('');
            $('#total').val('');
        }
    });
</script>