<style>
    .error-message, #card-errors {
        color: red;
        font-size: 15px;
    }
    .myclasse::-webkit-input-placeholder {
        color: #ff0000;
    }
    #payB:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

<div class="col-md-8 col-sm-12">
    <?php echo $this->Flash->render(); ?>
    <!-- General Option  -->
    <form action="<?php echo $this->Url->build(['controller'=> 'products', 'action'=>'payment']);?>" id="payForm" method="post" autocomplete="off">
        <input type="hidden" name="saleTax" id="saleTax" value="">
        <input type="hidden" name="shipping" id="shipping" value="">
        <input type="hidden" name="total" id="total" value="">
        <div class="detail-wrapper">
            <div class="detail-wrapper-header">
                <h4><i class="ti-location-pin theme-cl mrg-r-10"></i>Shipping Address</h4>
            </div>
            <div class="detail-wrapper-body">
                <div class="row mrg-r-10 mrg-l-10">
                    <div class="col-sm-6">
                        <label>Name</label>
                        <input type="text" name="shippingName" id="shippingName" class="form-control" 
                            autocomplete="off"
                            pattern="^[a-zA-Z\s]+$" 
                            title="Name must contain only letters and spaces."
                            minlength="7" placeholder="Enter your name" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Mobile</label>
                        <input type="tel" name="ShippingMobile" class="form-control" id="ShippingMobile" 
                            autocomplete="off"
                            maxlength="17"
                            pattern="^\+1 \(\d{3}\) \d{3}-\d{4}$" 
                            title="Please enter a valid phone number (e.g., +16862612722)"
                            oninput="handleInput(event)"
                            placeholder="e.g., +16862612722" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Address</label>
                        <input type="text" name="shippingAddress" id="shippingAddress" class="form-control" autocomplete="off" minlength="10"
                           pattern="^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z0-9\s,.'-#]{10,}$" 
                           title="Please enter a valid address (must include at least one letter and one number, and cannot consist solely of letters or numbers. Letters, numbers, spaces, commas, periods, hyphens, apostrophes, and '#' for apartment numbers are allowed). Minimum length is 10 characters." 
                           placeholder="Enter your address"
                           oninput="this.setCustomValidity(''); this.checkValidity();" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Zipcode</label>
                        <input type="text" name="shippingZipcode" id="shippingZipcode" maxlength="5" class="form-control" 
                            autocomplete="off"
                            pattern="^(?!00000)\d{5}$" 
                            title="Zip code must be exactly 5 digits and cannot be all zeros." 
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                            placeholder="Enter your zipcode" required>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="detail-wrapper">-->
        <!--    <div class="detail-wrapper-header">-->
        <!--        <h4><i class="ti-package theme-cl mrg-r-10"></i>Package Specifications</h4>-->
        <!--    </div>-->
        <!--    <div class="detail-wrapper-body">-->
        <!--        <div class="row mrg-r-10 mrg-l-10">-->
        <!--            <div class="col-sm-6">-->
        <!--                <label>Weight (pounds)</label>-->
        <!--                <input type="number" name="packageWeight" id="packageWeight" class="form-control" -->
        <!--                    autocomplete="off" min="1" placeholder="Enter weight in pounds" required>-->
        <!--            </div>-->
        <!--            <div class="col-sm-6">-->
        <!--                <label>Length (inches)</label>-->
        <!--                <input type="number" name="packageLength" id="packageLength" class="form-control" -->
        <!--                    autocomplete="off" min="1" placeholder="Enter length in inches" required>-->
        <!--            </div>-->
        <!--            <div class="col-sm-6">-->
        <!--                <label>Width (inches)</label>-->
        <!--                <input type="number" name="packageWidth" id="packageWidth" class="form-control" -->
        <!--                    autocomplete="off" min="1" placeholder="Enter width in inches" required>-->
        <!--            </div>-->
        <!--            <div class="col-sm-6">-->
        <!--                <label>Height (inches)</label>-->
        <!--                <input type="number" name="packageHeight" id="packageHeight" class="form-control" -->
        <!--                    autocomplete="off" min="1" placeholder="Enter height in inches" required>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <div class="detail-wrapper">
            <div class="detail-wrapper-header">
                <h4><i class="ti-credit-card theme-cl mrg-r-5"></i>Payment Method</h4>
            </div>
            <div class="detail-wrapper-body">
                <!-- Payment Form -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="register-name">Name On Card <span>*</span></label>
                        <input type="text" name="name" class="form-control" id="name" 
                            autocomplete="off"
                            pattern="^[a-zA-Z\s]+$" 
                            title="Name must contain only letters and spaces."
                            placeholder="Enter card holder name" minlength="7" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="register-name">Card Details <span>*</span></label>
                        <div id="card-element" class="form-control"></div>
                        <div id="card-errors" role="alert"></div>
                    </div>
                </div>
                <!-- End Payment Form -->
            </div>
        </div>
        <div class="text-center">
            <button type="submit" id="payB" class="btn theme-btn" title="Submit">Pay Now</button>
        </div>
    </form>
</div>
<!-- Sidebar -->
<div class="col-md-4 col-sm-12">
    <div class="sidebar">
        <!-- Start: Search By Price -->
        <div class="widget-boxed padd-0">
            <!-- Booking listing or Space Price -->
            <div class="booking-price padd-15">
                <h4 class="mrg-bot-20">Payment Details</h4>
                <!-- your Dates -->
                <div class="booking-price-detail side-list no-border">
                    <h5>Your Date/Time</h5>
                    <ul>
                        <li>Date: <strong class="pull-right"><?php echo date("M jS, Y"); ?></strong></li>
                        <li>Time: <strong class="pull-right"><?php echo date("g:i A"); ?></strong></li>
                    </ul>
                </div>
                <!-- Your Stay -->
                <div class="booking-price-detail side-list no-border">
                    <h5>Receipt Details</h5>
                    <ul>
                        <?php $TOT = 0;
                        foreach ($carts as $VV) { ?>
                        <li>
                            <?php 
                            $price = ($VV->product->specialPrice != 0) ? $VV->product->specialPrice : $VV->product->unitPrice;
                            ?>
                            <?php echo $VV->product->productName; ?>
                            <strong class="pull-right">
                                $<?php echo $price; ?> X <?php echo $VV->quantity; ?> = $<?php echo ($VV->quantity * $price); ?>
                            </strong>
                        </li>
                        <?php
                            $TOT += ($VV->quantity * $price);
                        } ?>
                    </ul>
                </div>
                <?php
                    $adminFees   = $TOT * 0.16;
                    $productCost = $TOT + $adminFees ;
                    $saleTax  = 0;
                    $shipping = 0;
                    $TOTAL    = 0;
                    // $TOTAL = $productCost + $saleTax + $shipping ;
                ?>
                <!-- Total Cost -->
                <div class="booking-price-detail side-list no-border" style="border-top: 1px dashed #ccc;">
                    <ul>
                        <li>Product Cost + Admin Fees<strong class="theme-cl pull-right" id="productCostDisplay">$<?php echo ceil($productCost); ?></strong></li>
                    </ul>
                </div>
                <!-- Admin Fee -->
                <div class="booking-price-detail side-list no-border">
                    <ul>
                        <li>State Sale Tax<strong class="theme-cl pull-right" id="saleTaxDisplay">$<?php echo number_format($saleTax, 2); ?></strong></li>
                        <li>Shipping<strong class="theme-cl pull-right" id="shippingDisplay">$<?php echo number_format($shipping, 2); ?></strong></li>
                    </ul>
                </div>
                <!-- Total -->
                <div class="booking-price-detail side-list no-border" style="border-top: 1px dashed #ccc;">
                    <ul>
                        <li>Total<strong class="theme-cl pull-right" id="totalDisplay">$<?php echo number_format($TOTAL, 2); ?></strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<!--<script src="https://js.stripe.com/v3/"></script>-->
<script src="https://js.stripe.com/acacia/stripe.js"></script>
<script>
    const stripe = Stripe('pk_test_51N4UjxIccD3egFe4JHkgdV0K73MeRhylVlcdj9pbMvjSZWsoeWRUy5d3Rqj1JTWG2cf6LkE7QhyKyOYG0rli2OgZ00bFYsbANS'); // test public key
    // const stripe = Stripe('pk_live_51N4UjxIccD3egFe4Mul33BPLNENz6mU5y5wtxxgs3WjUiuOYU6rtTbZLsymzMwQUlxsmeEb94WZ78Stc7VuDMDh700hcYQvtEs'); // live public key
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    cardElement.on('change', function(event) {
        console.log(event)
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    $(document).ready(function () {
        $('#payForm').on('submit', function (event) {
            // Prevent default form submission
            event.preventDefault();

            // Check if the form is valid
            if (this.checkValidity()) {
                // Create a token using the card Element
                stripe.createToken(cardElement).then(function (result) {
                    if (result.error) {
                        // Show error in payment form
                        $('#card-errors').text(result.error.message);
                    } else {
                        // Send the token to your server
                        const token = result.token.id;
                        $('#payForm').append($('<input type="hidden" name="stripeToken">').val(token));
                        $('#payForm').off('submit').submit(); // Unbind any previous submit handlers and submit the form
                    }
                });
            } else {
                // If the form is invalid, show validation messages
                this.reportValidity();
            }
        });
    });
</script>
<script>
jQuery(document).ready(function($) {
    // Add error display element if not already in your HTML, now after shippingZipcode
    if ($('#ajaxErrorMessage').length === 0) {
        $('<div id="ajaxErrorMessage" style="color: red; margin: 10px 0; display: none;"></div>')
            .insertAfter('#shippingZipcode');
    }

    // Initially disable the button until valid data is received
    $('#payB').prop('disabled', true);

    $('#shippingZipcode').on('input', function() {
        var zipcode = $(this).val();
        var $errorMessage = $('#ajaxErrorMessage');
        var $payButton = $('#payB');

        // Clear previous error messages
        $errorMessage.hide().text('');

        // Check if the zipcode is valid (5 digits and not all zeros)
        if (zipcode.length === 5 && /^\d{5}$/.test(zipcode) && zipcode !== '00000') {
            // First API call to get the tax rate
            $.ajax({
                url: "<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'getTaxRate']);?>/" + zipcode,
                type: 'GET',
                // dataType: 'json',
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        
                        if (!data.rate || !data.rate.combined_rate) {
                            throw new Error('Invalid tax rate data received');
                        }

                        var saleTaxRate = parseFloat(data.rate.combined_rate) || 0;
                        var productCost = parseFloat($('#productCostDisplay').text().replace('$', '').replace(',', '')) || 0;
                        var saleTax = saleTaxRate * productCost;

                        $('#saleTaxDisplay').text('$' + saleTax.toFixed(2));
                        
                        // Second API call for shipping rates
                        $.ajax({
                            url: "<?php echo $this->Url->build(['controller' => 'Products', 'action' => 'getTotalShippingCost']);?>/" + zipcode,
                            type: 'GET',
                            success: function(shippingResponse) {
                                try {
                                    var shippingData = (shippingResponse);
                                    
                                    if (!shippingData.totalCost) {
                                        throw new Error('Invalid shipping cost data received');
                                    }

                                    var shippingRate = parseFloat(shippingData.totalCost) || 0;
                                    var total = productCost + saleTax + shippingRate;

                                    $('#shippingDisplay').text('$' + shippingRate.toFixed(2));
                                    $('#totalDisplay').text('$' + total.toFixed(2));

                                    // Set hidden inputs
                                    $('#saleTax').val(saleTax.toFixed(2));
                                    $('#shipping').val(shippingRate.toFixed(2));
                                    $('#total').val(total.toFixed(2));

                                    // Enable button only if all values are valid
                                    if (total > 0) {
                                        $payButton.prop('disabled', false);
                                        $errorMessage.hide(); // Hide error message on success
                                    } else {
                                        $payButton.prop('disabled', true);
                                        showError('Total amount is invalid');
                                    }

                                } catch (e) {
                                    showError('Error processing shipping information: ' + e.message);
                                    resetDisplays();
                                    $payButton.prop('disabled', true);
                                }
                            },
                            error: function(xhr, status, error) {
                                showError('Failed to get shipping rates: ' + getErrorMessage(xhr, status, error));
                                resetDisplays();
                                $payButton.prop('disabled', true);
                            }
                        });

                    } catch (e) {
                        showError('Error processing tax information: ' + e.message);
                        resetDisplays();
                        $payButton.prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    showError('Failed to get tax rates: ' + getErrorMessage(xhr, status, error));
                    resetDisplays();
                    $payButton.prop('disabled', true);
                }
            });
        } else {
            resetDisplays();
            $payButton.prop('disabled', true);
            if (zipcode.length > 0) {
                showError('Please enter a valid 5-digit ZIP code');
            }
        }
    });

    // Function to show error messages (no auto-hide)
    function showError(message) {
        $('#ajaxErrorMessage')
            .text(message)
            .show();
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
        $('#shippingDisplay').text('$0.00');
        $('#totalDisplay').text('$0.00');
        $('#saleTax').val('');
        $('#shipping').val('');
        $('#total').val('');
    }
});
</script>
<script>
    function formatPhoneNumber(input) {
        // Remove all non-digit characters except the leading '+'
        const cleaned = ('' + input).replace(/(?!^\+)\D/g, '');

        // Check if the cleaned number is valid
        if (cleaned.length < 11) {
            return cleaned; // Return as is if not enough digits
        }

        // Format the number
        const match = cleaned.match(/^(\+1)(\d{3})(\d{3})(\d{4})$/);
        if (match) {
            return `${match[1]} (${match[2]}) ${match[3]}-${match[4]}`;
        }

        return input; // Return original input if it doesn't match
    }

    function handleInput(event) {
        const input = event.target;
        const formattedValue = formatPhoneNumber(input.value);
        input.value = formattedValue;
    }
</script>