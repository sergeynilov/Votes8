var this_frontend_home_url
var this_csrf_token
var this_service_subscription_id
var this_stripe_public_key
var this_userProfile


function profileSelectServiceSubscription(page, paramsArray) {  // constructor of frontend Profile's editor - set all params from server
// alert( "page::"+page+"  profileSelectServiceSubscription paramsArray::"+var_dump(paramsArray) )
    this_frontend_home_url = paramsArray.frontend_home_url;
    this_csrf_token = paramsArray.csrf_token;
    this_service_subscription_id = paramsArray.service_subscription_id;
    this_stripe_public_key = paramsArray.stripe_public_key;
    this_userProfile = paramsArray.userProfile;

} // function profileSelectServiceSubscription(Params) {  constructor of frontend Profile's editor - set all params from server


profileSelectServiceSubscription.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    // alert( "profileSelectServiceSubscription  onFrontendPageInit page::" + page)
    frontendInit()

    if (page == "confirm_selected") {
        // this.getRelatedUsersSiteSubscriptions()
        this.initStripe();
    }
    if (page == "payments") {
        // this.loadPaymentAgreements()
        // this.showPaymentsRows(1)
    }


/*
    $('body').on('click', '.pagination a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        // alert( "++url::"+(url) )

        var page_to_load = getSplitted(url, 'get-profile_select_service_subscription-payment-items-rows', 1)
        // alert( "+ 2 page_to_load::"+var_dump(page_to_load) )
        if ( page_to_load != '' ) {

            if (!checkInteger(page_to_load)) {
                var page_to_load = getSplitted(page_to_load, '=', 1)
            }

            if (!checkInteger(page_to_load)) page_to_load = 1
            profileSelectServiceSubscription.showPaymentsRows(page_to_load)
            return;
        }

        return false;
    }); // $('body').on('click', '.pagination a', function (e) {
*/


} // profileSelectServiceSubscription.prototype.onFrontendPageInit= function(page) {


profileSelectServiceSubscription.prototype.initStripe = function (page) {  // all vars/objects init
    // Create a Stripe client.
    var stripe = Stripe(this_stripe_public_key);

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            // fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '18px',
            '::placeholder': {
                color: '#636b6f'
            }
        },
        invalid: {
            color: '#ff0000',
            iconColor: '#ff0000'
        }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function (event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');
    self= this
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        stripe.createPaymentMethod('card', card, {
            // this_userProfile
            billing_details: {
                name: this_userProfile.full_name,
                email: this_userProfile.email,
                address: {
                    city: this_userProfile.address_city,
                    state: this_userProfile.address_state,
                    postal_code: this_userProfile.address_postal_code,
                    country: this_userProfile.address_country_code,
                    line1: this_userProfile.address_line1
                }

            },
        }).then(function(result) { // Handle result.error or result.paymentMethod
            if (result.error) { // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                popupErrorMessage("Validation error !")
                // popupErrorMessage(errorElement.textContent)
                console.log(errorElement.textContent);
            } else {  // Send the token to your server.
                console.log("++ result::")
                console.log( result )
                self.stripeTokenHandler(result.paymentMethod.id);
            }

        });

    });

} // initStripe() {


// Submit the form with the token ID.
// stripeTokenHandler(token) {
profileSelectServiceSubscription.prototype.stripeTokenHandler = function (token) {  // all vars/objects init    // Insert the token ID into the form so it gets submitted to the server
    // alert( "stripeTokenHandler token::"+(token)+"  this_service_subscription_id::"+this_service_subscription_id )
    let purchaseDetails= {
        source_service_subscription_id : this_service_subscription_id,
        payment_token : token,
        logged_user_id : this_userProfile.id,
        address : this_userProfile.address_line1,
        city : this_userProfile.address_city,
        state : this_userProfile.address_state,
        zipcode : this_userProfile.address_postal_code,
    }

    console.log("purchaseDetails::")
    console.log( purchaseDetails )

    var href = this_frontend_home_url + "/profile/profile_subscribe_to_service_subscription";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: {"_token": this_csrf_token, purchaseDetails : purchaseDetails },
        success: function (response) {
            popupAlert("You are subscribed to selected package successfully !", 'success')
            setTimeout(function tick() {
                document.location = this_frontend_home_url + "/profile/view";
            }, 3000);

        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

} // profileSelectServiceSubscription.prototype.stripeTokenHandler = function (token) {


profileSelectServiceSubscription.prototype.makeCancelSubscription = function (service_subscription_id, service_subscription_name ) {
    // alert( "makeCancelSubscription  service_subscription_id::"+var_dump(service_subscription_id) + "  service_subscription_name::"+service_subscription_name )
    confirmMsg('Do you want to cancel "' + service_subscription_name + '" subscription ?', function () {
            var href = this_frontend_home_url + "/profile/profile_cancel_service_subscription_subscription";
            $.ajax({
                type: "POST",
                dataType: "json",
                url: href,
                data: {"_token": this_csrf_token, source_service_subscription_id : service_subscription_id },
                success: function (response) {
                    popupAlert("Your subscription to selected package was successfully cancelled!", 'success')
                    setTimeout(function tick() {
                        document.location = this_frontend_home_url + "/profile/view";
                    }, 3000);

                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );

} // makeCancelSubscription( {{ $nextSiteSubscription['id'] }}, '{{ $nextSiteSubscription['name'] }}' )


profileSelectServiceSubscription.prototype.onSelectPaymentMethod = function( selected_service_subscription_id ) {
    var payment_type= $('input[name=payment_type]:checked').val()
    alert( "payment_type::"+(payment_type) +"  selected_service_subscription_id::"+selected_service_subscription_id)
    if ( payment_type == 'paypal') {

        var theForm = $("#stripe_confirm_subscribe_payment_form");
        theForm.submit();

        //
    } // if ( payment_type == 'paypal') {


    if ( payment_type == 'stripe') {
        $("#div_payment_form_stripe").css("display", "block")
    } // if ( payment_type == 'stripe') {



    // alert( "makeCancelSubscription  service_subscription_id::"+var_dump(service_subscription_id) + "  service_subscription_name::"+service_subscription_name )
/*
    confirmMsg('Do you want to cancel "' + service_subscription_name + '" subscription ?', function () {
            var href = this_frontend_home_url + "/profile/profile_cancel_service_subscription_subscription";
            $.ajax({
                type: "POST",
                dataType: "json",
                url: href,
                data: {"_token": this_csrf_token, source_service_subscription_id : service_subscription_id },
                success: function (response) {
                    popupAlert("Your subscription to selected package was successfully cancelled!", 'success')
                    setTimeout(function tick() {
                        document.location = this_frontend_home_url + "/profile/view";
                    }, 3000);

                },
                error: function (error) {
                    popupErrorMessage(error.responseJSON.message)
                }
            });

        }
    );
*/

} // makeCancelSubscription( {{ $nextSiteSubscription['id'] }}, '{{ $nextSiteSubscription['name'] }}' )
