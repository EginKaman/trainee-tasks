@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Feedback') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert" data-cy=“successAlert”>
                                {{ __('Your feedback was sent successful.') }}
                            </div>
                        @endif
                        @if (session('failure'))
                            <div class="alert alert-danger" role="alert" data-cy=“errorAlert”>
                                {{ __('Something went wrong. Please, try again later.') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert" data-cy=“errorAlert”>
                                {{ __('Please, correct the mistakes in the fields:') }}
                                @foreach($errors->getMessages() as $key => $message)
                                    <li>{{ $key }}</li>
                                @endforeach
                            </div>
                        @endif
                        <form method="POST" action="{{ route('feedback') }}" id="feedback-form">
                            @csrf
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           data-cy=“nameField”
                                           placeholder="Enter your name"
                                           required="required"
                                           value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           data-cy=“emailField”
                                           placeholder="Enter your email address"
                                           required="required"
                                           value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="text"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Message') }}</label>

                                <div class="col-md-6">
                                    <textarea id="text" data-cy=“messageTextarea”
                                              required="required"
                                              placeholder="Enter your message"
                                              class="form-control @error('text') is-invalid @enderror" name="text"
                                              required autocomplete="new-password">{{ old('text') }}</textarea>

                                    @error('text')
                                    <span class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method"
                                               data-cy=“smtp”
                                               value="smtp"
                                               id="send_method_1" @checked(old('method') === 'smtp' || old('method') === null)>
                                        <label class="form-check-label" for="send_method_1">
                                            SMTP
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method"
                                               value="sendgrid"
                                               data-cy=“sendgrid”
                                               id="send_method_2" @checked(old('method') === 'sendgrid')>
                                        <label class="form-check-label" for="send_method_2">
                                            Sendgrid
                                        </label>
                                    </div>

                                    @error('method')
                                    <span class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            @error('g-recaptcha-response')
                            <span class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary"
                                            data-cy=“sendButton”
                                            class="g-recaptcha"
                                            data-sitekey="{{ config('recaptcha.site_key') }}"
                                            data-callback="onSubmit"
                                            data-action="submit">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
