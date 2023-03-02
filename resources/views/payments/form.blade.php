<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Accept a card payment</title>
    <meta name="description" content="A demo of a card payment on Stripe"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <style>
        /* Variables */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            display: flex;
            justify-content: center;
            align-content: center;
            height: 100vh;
            width: 100vw;
        }

        form {
            width: 30vw;
            min-width: 500px;
            align-self: center;
            box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
            0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
            border-radius: 7px;
            padding: 40px;
        }

        input {
            border-radius: 6px;
            margin-bottom: 6px;
            padding: 12px;
            border: 1px solid rgba(50, 50, 93, 0.1);
            height: 44px;
            font-size: 16px;
            width: 100%;
            background: white;
        }

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
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
<!-- Display a payment form -->
<form id="payment-form">
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
</form>
<script>
    // This is your test publishable API key.
    var stripe = Stripe("{{config('services.stripe.api_key')}}");

    // The items the customer wants to buy
    var purchase = {
        order_id: 4,
        method: 'stripe'
    };

    // Disable the button until we have Stripe set up on the page
    document.querySelector("button").disabled = true;
    fetch("/api/v1/payments", {
        method: "POST",
        headers: {
            "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2FwaS92MS92ZXJpZnkiLCJpYXQiOjE2Nzc3NTkxNDUsImV4cCI6MTY3Nzc2Mjc0NSwibmJmIjoxNjc3NzU5MTQ1LCJqdGkiOiJzZmJuSHVnSzRmWk5vb1drIiwic3ViIjoiNiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.FdGkGTKpIvMm_No66q-UW70Z3GIS4k0TF0Se6Zlp8ztwWb4BZHiAAIu5nthu2IfJ8SBnoNmVBg6k_xyY2jCLJLEUxiouB7qSW7DlG-oylgllgOmxhW5Nnu9Xx9pBnEyDNT7DlQBh8QfeolrOF9ERCqPod_0Z4mLD_e485exisnh5mTgPHend8uc4KzRxUeSZCxam2j9ITjF-xoc33FqydSihn_Uvaki7G-7pDWwqF-1PD9c-PijjtviuhkV5_hrstnf5rsCHiIHpU_15JaYi2F5dcDVJN_yVFZYn4T2U9mQMHm6fIHwhUSGDwbn-bnFAwteahy8zy34ALqeJHEn1jxwFJlYjOzYHR0Ynbh-ztlhE7m3nPntLpc-xdFFCiAiaxXNiBbJNNJaw44ZDPW8jWk3noF2nCY5sqHRo2pJ3i8T-pF7I7KFzKkUsdKGJ3XirRGE3I0YRGw03Ng_kwb3rQwC1DRyFkEBSbMgByj3GYzEZnbCefhygFDJn_VA4QvUz6a3bG-4ux9D27AxULYE7uiVfnH0Betj_471Sg28MiRMAlu7jQVPi6W6IsiUT-jCDQv82pVz7bM54_2oyx6cpJM65v4s_qb8vFhzKvjxVaj8P-zvneFH56OLExn2a745mdkqZKBlCU6NNNB1DnAup0GCgP4lQjqZZA4FVTFJ4mAo",
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
                payWithCard(stripe, card, data.client_secret);
            });
        });

    // Calls stripe.confirmCardPayment
    // If the card requires authentication Stripe shows a pop-up modal to
    // prompt the user to enter authentication details without leaving your page.
    var payWithCard = function (stripe, card, clientSecret) {
        loading(true);
        stripe
            .confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card
                }
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
</script>
</body>
</html>

@extends('adminlte::page')

@section('title', 'Task 4 - ReGex, AdminLTE and forms')

@section('content_header')
    <h1>Task 7 - Payments</h1>
@endsection
@push('js')
    @vite(['resources/js/form.js'])
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
            <form action="{{ route('form.store') }}" method="post" id="info-form">
                @csrf
                <x-adminlte-card title="Form Example" theme="lightblue" theme-mode="outline"
                                 header-class="rounded-bottom border-info">
                    <div class="row">
                        <div class="col-8">
                            <h4>Contact info</h4>
                            <div class="row">
                                <x-adminlte-input type="text" name="name" label="Name *" placeholder="Name"
                                                  value="{{ old('name') }}"
                                                  fgroup-class="col-md-6" enable-old-support>
                                    <x-slot name="bottomSlot">
                                        <span class="text-sm text-gray float-right">
                                            <span id="length-name">0</span> / 128
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <div class="row">
                                <x-adminlte-input type="text" name="phone" label="Phone *" placeholder="Phone"
                                                  error-key="phone"
                                                  fgroup-class="col-md-6" enable-old-support>
                                    @if(!$errors->has('phone'))
                                        <x-slot name="bottomSlot">
                                            <span class="text-sm text-gray">
                                                +38 (xxx) xxx - xx - xx
                                            </span>
                                        </x-slot>
                                    @endif
                                </x-adminlte-input>
                                <x-adminlte-input type="text" name="additional_phone" label="Phone" placeholder="Phone"
                                                  error-key="additional_phone"
                                                  fgroup-class="col-md-6" enable-old-support>
                                    <x-slot name="bottomSlot">
                                        @if(!$errors->has('additional_phone'))
                                            <span class="text-sm text-gray">
                                                Enter your phone number
                                            </span>
                                        @endif

                                        <span class="text-sm text-gray float-right">
                                            <span id="length-additional_phone">0</span> / 256
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <div class="row">
                                <x-adminlte-input type="text" name="email" label="Email *" placeholder="Email"
                                                  error-key="email"
                                                  fgroup-class="col-md-6" enable-old-support>
                                    <x-slot name="bottomSlot">
                                        @if(!$errors->has('email'))
                                            <span class="text-sm text-gray">
                                                Enter your email address
                                            </span>
                                        @endif

                                        <span class="text-sm text-gray float-right">
                                            <span id="length-email">0</span> / 254
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-input type="text" name="email_rfc" label="Email RFC" placeholder="Email RFC"
                                                  error-key="email_rfc"
                                                  fgroup-class="col-md-6" enable-old-support>
                                    <x-slot name="bottomSlot">
                                        @if(!$errors->has('email_rfc'))
                                            <span class="text-sm text-gray">
                                                Enter your email address
                                            </span>
                                        @endif

                                        <span class="text-sm text-gray float-right">
                                            <span id="length-email_rfc">0</span> / 254
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <h4>Additional info</h4>
                            <div class="row">
                                <x-adminlte-input type="text" name="pincode" label="Pin code *" placeholder="Pin code"
                                                  error-key="pincode"
                                                  fgroup-class="col-md-6" enable-old-support>
                                    <x-slot name="bottomSlot">
                                        @if(!$errors->has('pincode'))
                                            <span class="text-sm text-gray">
                                                xxxx-xxxx
                                            </span>
                                        @endif
                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-input type="text" name="id" label="ID" placeholder="ID"
                                                  error-key="id"
                                                  fgroup-class="col-md-6" enable-old-support>
                                    <x-slot name="bottomSlot">
                                        @if(!$errors->has('id'))
                                            <span class="text-sm text-gray">
                                            Enter your ID
                                        </span>
                                        @endif
                                        <span class="text-sm text-gray float-right">
                                            <span id="length-id">0</span> / 128
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <h4>Comment</h4>
                            <div class="row">
                                <x-adminlte-textarea name="description" label="Description" placeholder="Description"
                                                     error-key="description"
                                                     fgroup-class="col-md-12">{{ old('description') }}
                                    <x-slot name="bottomSlot">
                                        @if(!$errors->has('description'))
                                            <span class="text-sm text-gray">
                                                Add a short comment
                                            </span>
                                        @endif
                                        <span class="text-sm text-gray float-right">
                                            <span id="length-description">0</span> / 500
                                        </span>
                                    </x-slot>
                                </x-adminlte-textarea>
                            </div>
                        </div>
                    </div>
                </x-adminlte-card>
                <div class="row">
                    <div class="col-12">
                        <x-adminlte-button label="Continue" class="float-right" type="submit"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

