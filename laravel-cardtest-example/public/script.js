$(document).on('click', '.savecard', function (event) {
alert("mukul");
    //get the email of the user for the selected row
    var email = $(this).attr('email');
    //update the modal so the user doesn't have to retype the email address
    $('#modalemail').val(email)

    fetch("/public-key", {
            method: "get",
            headers: {
                "_token": "{{ csrf_token() }}",
            }
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (response) {
            getSetupIntent(response.publicKey, email);
        });

});


var getSetupIntent = function (publicKey, email) {
    return fetch("/createcustomerinstripe/" + email, {
            method: "post",
            headers: {
                "_token": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (setupIntent) {
            stripeSetupIntent(publicKey, setupIntent);
        });
};










var stripeSetupIntent = function (publicKey, setupIntent) {
    // var stripe = Stripe('pk_test_51LNDUbSHUxJ44dN3vcRLRmI37vKRup3Db9BZYSzgZR0LMUmpA3P4IErCvUHy92NbuUDVW5lPVwwM6je4Ut36cWQm00W6AYGQGw');
    // // var stripe = Stripe(publicKey);
    // var elements = stripe.elements();
    var stripe = Stripe('pk_test_51LNDUbSHUxJ44dN3vcRLRmI37vKRup3Db9BZYSzgZR0LMUmpA3P4IErCvUHy92NbuUDVW5lPVwwM6je4Ut36cWQm00W6AYGQGw');
    // Create an instance of Elements
    var elements = stripe.elements();

    $('#modal-block-entercard').modal();

    // Element styles
    var style = {
        base: {
            fontSize: "16px",
            color: "#32325d",
            fontFamily: "-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif",
            fontSmoothing: "antialiased",
            "::placeholder": {
                color: "rgba(0,0,0,0.4)"
            }
        }
    };

    var card = elements.create("card", {
        style: style
    });

    card.mount("#card-element");

    // Element focus ring
    card.on("focus", function () {
        var el = document.getElementById("card-element");
        el.classList.add("focused");
    });

    card.on("blur", function () {
        var el = document.getElementById("card-element");
        el.classList.remove("focused");
    });

    // Handle payment submission when user clicks the pay button.
    var button = document.getElementById("submit");
    button.addEventListener("click", function (event) {

        event.preventDefault();
        changeLoadingState(true);
        var email = document.getElementById("email").value;

        stripe
            .confirmCardSetup(setupIntent.client_secret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        email: email
                    }
                }
            })
            .then(function (result) {
                if (result.error) {
                    changeLoadingState(false);
                    var displayError = document.getElementById("card-errors");
                    displayError.textContent = result.error.message;
                } else {
                    // The PaymentMethod was successfully set up
                    orderComplete(stripe, setupIntent.client_secret);
                }
            });
    });
};
$(document).on('click', '.chargecard', function (event) {

    //get the email of the user for the selected row
    var email = $(this).attr('email');
    var value2charge = 1000;

    fetch("/public-key", {
            method: "get",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                "Content-Type": "application/json"
            }
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (response) {
            getPaymentIntent(response.publicKey, email, value2charge);
        });

});
var getPaymentIntent = function (publicKey, email, value2charge) {
    return fetch("/chargethecustomer/" + email + "/" + value2charge, {
            method: "post",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                "Content-Type": "application/json"
            }
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (paymentIntent) {
            stripePaymentIntent(publicKey, email, paymentIntent);
        });
};


var stripePaymentIntent = function (publicKey, email, paymentIntent) {
    var stripe = Stripe(publicKey);
    var elements = stripe.elements();
    var cardElement = elements.getElement('card'); //from https://stripe.com/docs/js/elements_object/get_element

    $('#modal-block-chargecardresponse').modal();

    console.log("PAYEMENT STATUS: ", paymentIntent.status);

    if (paymentIntent.status === "succeeded") {
        $('#modalchargecardresponse_data').html("Payment was successful");
    } else {
        //see better error handeling on step 5 here: https://stripe.com/docs/payments/save-and-reuse#web-create-payment-intent-off-session
        stripe
            .confirmCardPayment(paymentIntent.client_secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: email
                    },
                },
            })
            .then(function (result) {
                if (result.error) {
                    $('#modalchargecardresponse_data').html("Payment had an error: " + result.error.message);
                } else {
                    // The Payment was successful
                    $('#modalchargecardresponse_data').html("Payment was successful");
                }
            });
    }

};
