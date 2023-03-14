@extends('adminlte::page')

@section('title', 'Task 7 - Payment gateways')

@section('content_header')
    <h1>Task 7 - Payment gateways</h1>
@endsection
@push('js')
    @vite(['resources/js/form.js'])
    <style>
        /* Variables */

        .result-message {
            line-height: 22px;
            font-size: 16px;
        }

        .result-message a {
            color: rgb(89, 111, 214);
            font-weight: 600;
            text-decoration: none;
        }

        .hidden {
            display: none;
        }

        #card-error {
            color: rgb(105, 115, 134);
            text-align: left;
            font-size: 13px;
            line-height: 17px;
            margin-top: 12px;
        }

        #card-element {
            border-radius: 4px 4px 0 0;
            padding: 12px;
            border: 1px solid rgba(50, 50, 93, 0.1);
            height: 44px;
            width: 100%;
            background: white;
        }

        #payment-request-button {
            margin-bottom: 32px;
        }

        /* Buttons and links */
        button {
            background: #5469d4;
            color: #ffffff;
            font-family: Arial, sans-serif;
            border-radius: 0 0 4px 4px;
            border: 0;
            padding: 12px 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: block;
            transition: all 0.2s ease;
            box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
            width: 100%;
        }

        button:hover {
            filter: contrast(115%);
        }

        button:disabled {
            opacity: 0.5;
            cursor: default;
        }

        /* spinner/processing state, errors */
        .spinner,
        .spinner:before,
        .spinner:after {
            border-radius: 50%;
        }

        .spinner {
            color: #ffffff;
            font-size: 22px;
            text-indent: -99999px;
            margin: 0px auto;
            position: relative;
            width: 20px;
            height: 20px;
            box-shadow: inset 0 0 0 2px;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
        }

        .spinner:before,
        .spinner:after {
            position: absolute;
            content: "";
        }

        .spinner:before {
            width: 10.4px;
            height: 20.4px;
            background: #5469d4;
            border-radius: 20.4px 0 0 20.4px;
            top: -0.2px;
            left: -0.2px;
            -webkit-transform-origin: 10.4px 10.2px;
            transform-origin: 10.4px 10.2px;
            -webkit-animation: loading 2s infinite ease 1.5s;
            animation: loading 2s infinite ease 1.5s;
        }

        .spinner:after {
            width: 10.4px;
            height: 10.2px;
            background: #5469d4;
            border-radius: 0 10.2px 10.2px 0;
            top: -0.1px;
            left: 10.2px;
            -webkit-transform-origin: 0px 10.2px;
            transform-origin: 0px 10.2px;
            -webkit-animation: loading 2s infinite ease;
            animation: loading 2s infinite ease;
        }

        @-webkit-keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @media only screen and (max-width: 600px) {
            form {
                width: 80vw;
            }
        }
    </style>

@endpush
@section('content')
    @if(session('success'))
        <x-adminlte-alert theme="success" title="Successfully!">
            Your data has been sent successfully
        </x-adminlte-alert>
    @endif
    @if($errors->isNotEmpty())
        <x-adminlte-alert theme="danger" title="Validation error!">
            Please correct data in next
            fields: {{ implode(', ', Arr::map($errors->keys(), fn ($value) => __($value))) }}
        </x-adminlte-alert>
    @endif
    <div class="row">
        <div class="col-12">
            <form id="payment-form">
                <x-adminlte-card title="Stripe Payment" theme="lightblue" theme-mode="outline"
                                 header-class="rounded-bottom border-info">
                    <div class="row">
                        <div class="col-6">
                            <x-adminlte-input type="text" name="token" label="Bearer token *" placeholder="Bearer token"
                                              error-key="token"
                                              fgroup-class="col-md-6" enable-old-support>
                            </x-adminlte-input>
                            <x-adminlte-input type="text" name="order_id" label="Order ID *" placeholder="Order ID"
                                              error-key="order_id"
                                              fgroup-class="col-md-6" enable-old-support>
                            </x-adminlte-input>
                            <x-adminlte-input type="text" name="card_id" label="Card ID *" placeholder="Card ID"
                                              error-key="card_id"
                                              fgroup-class="col-md-6" enable-old-support>
                            </x-adminlte-input>
                            <x-adminlte-input-switch type="checkbox" name="save_card" label="Save Card? *"
                                                     value="1"
                                                     error-key="save_card" enable-old-support>
                            </x-adminlte-input-switch>
                            <x-adminlte-button onclick="event.preventDefault();stripe()" class="btn-flat" id="submit-stripe" type="submit"
                                               label="Submit" theme="success"></x-adminlte-button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6" id="card-form" style="display: none">
                            <div id="card-element"><!--Stripe.js injects the Card Element--></div>
                            <button id="submit">
                                <div class="spinner hidden" id="spinner"></div>
                                <span id="button-text">Pay now</span>
                            </button>
                            <p id="card-error" role="alert"></p>
                            <p class="result-message hidden">
                                Payment succeeded, see the result in your
                                <a href="" target="_blank">Stripe dashboard.</a> Refresh the page to pay again.
                            </p>
                        </div>
                        <script src="https://js.stripe.com/v3/"></script>
                        <script>
                            function stripe() {
                                document.querySelector("#card-form").style.display = '';
                                // This is your test publishable API key.
                                var stripe = Stripe("{{config('services.stripe.api_key')}}");

                                // The items the customer wants to buy
                                var purchase = {
                                    order_id: document.querySelector("input#order_id").value,
                                    save_card: Boolean(document.querySelector("input#save_card").value),
                                    card_id: document.querySelector("input#card_id").value,
                                    type_payment: 'stripe'
                                };

                                // Disable the button until we have Stripe set up on the page
                                document.querySelector("button#submit").disabled = false;
                                fetch("/api/v1/payments", {
                                    method: "POST",
                                    headers: {
                                        "Authorization": "Bearer " + document.querySelector("input#token").value,
                                        "Content-Type": "application/json",
                                        "Accept": "application/json"
                                    },
                                    body: JSON.stringify(purchase)
                                })
                                    .then(function (result) {
                                        return result.json();
                                    })
                                    .then(function (data) {
                                        var elements = stripe.elements();
                                        var style = {
                                            base: {
                                                color: "#32325d",
                                                fontFamily: 'Arial, sans-serif',
                                                fontSmoothing: "antialiased",
                                                fontSize: "16px",
                                                "::placeholder": {
                                                    color: "#32325d"
                                                }
                                            },
                                            invalid: {
                                                fontFamily: 'Arial, sans-serif',
                                                color: "#fa755a",
                                                iconColor: "#fa755a"
                                            }
                                        };

                                        var card = elements.create("card", {style: style});
                                        // Stripe injects an iframe into the DOM
                                        card.mount("#card-element");

                                        card.on("change", function (event) {
                                            // Disable the Pay button if there are no card details in the Element
                                            document.querySelector("button").disabled = event.empty;
                                            document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
                                        });

                                        var form = document.getElementById("payment-form");
                                        form.addEventListener("submit", function (event) {
                                            event.preventDefault();
                                            // Complete payment when the submit button is clicked
                                            payWithCard(stripe, card, data.client_secret, data.paymentMethod);
                                        });
                                    });

                                // Calls stripe.confirmCardPayment
                                // If the card requires authentication Stripe shows a pop-up modal to
                                // prompt the user to enter authentication details without leaving your page.
                                var payWithCard = function (stripe, card, clientSecret, paymentMethod) {
                                    loading(true);
                                    if (!paymentMethod) {
                                        paymentMethod = {
                                            card: card
                                        };
                                    }
                                    stripe
                                        .confirmCardPayment(clientSecret, {
                                            payment_method: paymentMethod
                                        })
                                        .then(function (result) {
                                            if (result.error) {
                                                // Show error to your customer
                                                showError(result.error.message);
                                            } else {
                                                // The payment succeeded!
                                                orderComplete(result.paymentIntent.id);
                                            }
                                        });
                                };

                                /* ------- UI helpers ------- */

                                // Shows a success message when the payment is complete
                                var orderComplete = function (paymentIntentId) {
                                    loading(false);
                                    document
                                        .querySelector(".result-message a")
                                        .setAttribute(
                                            "href",
                                            "https://dashboard.stripe.com/test/payments/" + paymentIntentId
                                        );
                                    document.querySelector(".result-message").classList.remove("hidden");
                                    document.querySelector("button").disabled = true;
                                };

                                // Show the customer the error from Stripe if their card fails to charge
                                var showError = function (errorMsgText) {
                                    loading(false);
                                    var errorMsg = document.querySelector("#card-error");
                                    errorMsg.textContent = errorMsgText;
                                    setTimeout(function () {
                                        errorMsg.textContent = "";
                                    }, 4000);
                                };

                                // Show a spinner on payment submission
                                var loading = function (isLoading) {
                                    if (isLoading) {
                                        // Disable the button and show a spinner
                                        document.querySelector("button").disabled = true;
                                        document.querySelector("#spinner").classList.remove("hidden");
                                        document.querySelector("#button-text").classList.add("hidden");
                                    } else {
                                        document.querySelector("button").disabled = false;
                                        document.querySelector("#spinner").classList.add("hidden");
                                        document.querySelector("#button-text").classList.remove("hidden");
                                    }
                                };
                            }
                        </script>
                        <div class="col-6">

                        </div>
                    </div>
                </x-adminlte-card>
                <x-adminlte-card title="Paypal Payment" theme="lightblue" theme-mode="outline" id="paypal"
                                 header-class="rounded-bottom border-info">
                    <div class="row">
                        <div class="col-6">
                            <x-adminlte-input type="text" name="token" label="Bearer token *" placeholder="Bearer token"
                                              error-key="token"
                                              fgroup-class="col-md-6" enable-old-support>
                            </x-adminlte-input>
                            <x-adminlte-input type="text" name="order_id" label="Order ID *" placeholder="Order ID"
                                              error-key="order_id"
                                              fgroup-class="col-md-6" enable-old-support>
                            </x-adminlte-input>
                            <x-adminlte-button onclick="event.preventDefault();paypal()" class="btn-flat" id="submit-paypal" type="submit"
                                               label="Submit" theme="success"></x-adminlte-button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <a href="#" target="_blank" class="btn btn-primary btn-flat" style="display: none;" id="paypal-link">
                                Enter the data
                            </a>
                        </div>
                    </div>
                    <script>
                        function paypal() {

                            // The items the customer wants to buy
                            var purchase = {
                                order_id: document.querySelector("#paypal input#order_id").value,
                                type_payment: 'paypal'
                            };
                            fetch("/api/v1/payments", {
                                method: "POST",
                                headers: {
                                    "Authorization": "Bearer " + document.querySelector("#paypal input#token").value,
                                    "Content-Type": "application/json",
                                    "Accept": "application/json"
                                },
                                body: JSON.stringify(purchase)
                            })
                                .then(function (result) {
                                    return result.json();
                                })
                                .then(function (data) {
                                    document.querySelector('#paypal-link').href = data.url;
                                    document.querySelector('#paypal-link').style.display = '';
                                    document.querySelector('#paypal-link').text = 'Pay ' + data.amount + '$';
                                });
                        }
                    </script>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@endsection

