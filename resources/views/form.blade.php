@extends('adminlte::page')

@section('title', 'Task 4 - ReGex, AdminLTE and forms')

@section('content_header')
    <h1>Task 4 - ReGex, AdminLTE and forms</h1>
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
