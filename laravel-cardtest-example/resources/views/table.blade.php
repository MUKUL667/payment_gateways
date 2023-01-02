<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

</head>
<body>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">user</th>
            <th scope="col">Stripe</th>

          </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="text-center" id="email"> {{$user->email}} </td>
                @if( App\Http\Controllers\HomeController::DoesCustomerExistAtStripe($user->email) == 2)
                <td class="text-center" id="isInStripeOrNot">
                        Account+PaymentMethod
                    </td>
                    <td class="text-center">
                        <div class="pl-1">
                           <input class="form-control btn-block btn-secondary savecard"
                                email="{{$user->email}}" type="submit" value="Card/User already in Stripe" disabled>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="pl-1">
                            <input class="form-control btn-block btn-warning chargecard"
                                email="{{$user->email}}" type="submit" value="Charge Card $10.00">
                        </div>
                    </td>
                    @elseif( App\Http\Controllers\HomeController::DoesCustomerExistAtStripe($user->email) == 1)
                    <td class="text-center" id="isInStripeOrNot">
                        Account "No" PaymentMethod
                    </td>
                    <td class="text-center">
                        <div class="pl-1">
                             <input class="form-control btn-block btn-warning savecard"
                                  email="{{$user->email}}" type="submit" value="User (no card) in Stripe">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="pl-1">
                              <input class="form-control btn-block btn-secondary chargecard"
                                   email="{{$user->email}}" type="submit" value="Charge Card $10.00" disabled>
                        </div>
                    </td>
                    @else
                    <td class="text-center" id="isInStripeOrNot">
                        NO Account
                    </td>
                    <td class="text-center">
                        <div class="pl-1">
                            <input class="form-control btn-block btn-warning savecard"
                                  email="{{$user->email}}" type="submit" value="Add user card to Stripe">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="pl-1">
                             <input class="form-control btn-block btn-secondary chargecard"
                                   email="{{$user->email}}" type="submit" value="Charge Card $10.00" disabled>
                             </div>
                    </td>
                    @endif
            </tr>
        @endforeach
        </tbody>
      </table>

</body>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Saving a Card sample</title>
    <meta name="description" content="A demo of Stripe Payment Intents" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="asset/css/normalize.css" />
    <link rel="stylesheet" href="asset/css/global.css" />
    <script src="https://js.stripe.com/v3/"></script>
    {{--  <script src="/script.js" defer></script>  --}}
  </head>

  <body>
    <div class="sr-root">
      <div class="sr-main">
        <div class="sr-payment-form card">
          <div class="sr-form-row">
            <label>
              Account details
            </label>
            <input type="text" id="email" placeholder="Email address" />
          </div>

          <div class="sr-form-row">
            <label>
              Payment details
            </label>
            <div class="sr-input sr-element sr-card-element" id="card-element">
              <!-- A Stripe card Element will be inserted here. -->
            </div>
          </div>
          <div class="sr-field-error" id="card-errors" role="alert"></div>
          <button id="submit">
            <div class="spinner hidden" id="spinner"></div>
            <span id="button-text">Link your card to your account</span>
          </button>
        </div>
        <div class="sr-result hidden">
          <p>Card setup completed<br /></p>
          <pre>
            <code></code>
          </pre>
        </div>
      </div>
    </div>
  </body>
</html>
{{--  <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js" async></script>  --}}

{{--  <script src="https://js.stripe.com/v3/"></script>  --}}
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>


<script type="text/javascript">
    $(document).on('click', '.savecard', function (event) {
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










        var stripeSetupIntent = function (publicKey, setupIntent)
         {

            // var stripe = Stripe('pk_test_51LNDUbSHUxJ44dN3vcRLRmI37vKRup3Db9BZYSzgZR0LMUmpA3P4IErCvUHy92NbuUDVW5lPVwwM6je4Ut36cWQm00W6AYGQGw');
            // // var stripe = Stripe(publicKey);
            // var elements = stripe.elements();
            var stripe = Stripe('{{ env("STRIPE_KEY") }}');
            // Create an instance of Elements
            var elements = stripe.elements();


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
                        "_token": "{{ csrf_token() }}",
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

            return fetch("/chargethecustomer/" + email + "/" + value2charge
            {
                    method: "POST",
                    headers: {
                        "_token": "{{ csrf_token() }}",
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

</script>

</html>
