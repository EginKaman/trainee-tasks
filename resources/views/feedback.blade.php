@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Feedback') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert" data-cy="successAlert">
                                {{ __('Your feedback was sent successful.') }}
                            </div>
                        @endif
                        @if (session('failure'))
                            <div class="alert alert-danger" role="alert" data-cy="errorAlert">
                                {{ __('Something went wrong. Please, try again later.') }}
                            </div>
                        @endif
                        @error('g-recaptcha-response')
                            <div class="alert alert-danger" role="alert" data-cy="errorAlert">
                                <strong>{{ __('Couldn\'t verify recaptcha. Please, try again') }}</strong>
                            </div>
                        @enderror
                        @if ($errors->any() && !($errors->count() === 1 && $errors->has('g-recaptcha-response')))
                            <div class="alert alert-danger" role="alert" data-cy="errorAlert">
                                {{ __('Please, correct the mistakes in the fields:') }}
                                @foreach($errors->getMessages() as $key => $message)
                                    @if($key === 'g-recaptcha-response')
                                        @continue
                                    @endif
                                    <li>{{ $key }}</li>
                                @endforeach
                            </div>
                        @endif
                        <form method="POST" action="{{ route('feedback') }}" id="feedback-form">
                            @csrf
                            {!! RecaptchaV3::field('feedback') !!}
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           data-cy="nameField"
                                           placeholder="Enter your name"
                                           value="{{ old('name') }}" autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert" data-cy="errorMessage">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="text"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           data-cy="emailField"
                                           placeholder="Enter your email address"
                                           value="{{ old('email') }}" autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert" data-cy="errorMessage">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="text"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Message') }}</label>

                                <div class="col-md-6">
                                    <textarea id="text" data-cy="messageTextarea"
                                              placeholder="Enter your message"
                                              class="form-control @error('text') is-invalid @enderror" name="text"
                                              autocomplete="new-password">{{ old('text') }}</textarea>

                                    @error('text')
                                    <span class="invalid-feedback" role="alert" data-cy="errorMessage">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method"
                                               data-cy="smtp"
                                               value="smtp"
                                               id="send_method_1" @checked(old('method') === 'smtp' || old('method') === null)>
                                        <label class="form-check-label" for="send_method_1">
                                            SMTP
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method"
                                               value="sendgrid"
                                               data-cy="sendgrid"
                                               id="send_method_2" @checked(old('method') === 'sendgrid')>
                                        <label class="form-check-label" for="send_method_2">
                                            Sendgrid
                                        </label>
                                    </div>

                                    @error('method')
                                    <span class="invalid-feedback" role="alert" data-cy="errorMessage">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary"
                                            data-cy="sendButton"
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
    <script>
        function onSubmit(token) {
            document.getElementById("feedback-form").append('g-recaptcha-response', token).submit();
        }
    </script>
@endsection
