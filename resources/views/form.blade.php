@extends('adminlte::page')

@section('title', 'Task 4 - ReGex, AdminLTE and forms')

@section('content_header')
    <h1>Task 4 - ReGex, AdminLTE and forms</h1>
@endsection

@section('content')
    @if(session('success'))
        <x-adminlte-alert theme="success" title="Successfully">
            Your data has been sent successfully
        </x-adminlte-alert>
    @endif
    @if($errors->isNotEmpty())
        <x-adminlte-alert theme="danger" title="Danger">
            Please correct data in next fields: {{ implode(', ', Arr::map($errors->keys(), fn ($value) => __($value))) }}
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
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        @error('name')
                                            <span class="text-sm text-red">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <span class="text-sm text-gray float-right">
                                            <span id="length-name">0</span> / 128
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <div class="row">
                                <x-adminlte-input type="text" name="phone" label="Phone *" placeholder="Phone"
                                                  value="{{ old('phone') }}"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        @error('phone')
                                            <span class="text-sm text-red">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @else
                                            <span class="text-sm text-gray">
                                                +38 (xxx) xxx - xx - xx
                                            </span>
                                        @enderror

                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-input type="text" name="additional_phone" label="Phone" placeholder="Phone"
                                                  value="{{ old('additional_phone') }}"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        @error('additional_phone')
                                            <span class="text-sm text-red">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @else
                                            <span class="text-sm text-gray">
                                                Enter your phone number
                                            </span>
                                        @enderror

                                        <span class="text-sm text-gray float-right">
                                            <span id="length-additional_phone">0</span> / 256
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <div class="row">
                                <x-adminlte-input type="text" name="email" label="Email *" placeholder="Email"
                                                  value="{{ old('email') }}"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        @error('email')
                                            <span class="text-sm text-red">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @else

                                            <span class="text-sm text-gray">
                                                Enter your email address
                                            </span>
                                        @enderror

                                        <span class="text-sm text-gray float-right">
                                            <span id="length-email">0</span> / 254
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-input type="text" name="email_rfc" label="Email RFC" placeholder="Email RFC"
                                                  value="{{ old('email_rfc') }}"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        @error('email_rfc')
                                            <span class="text-sm text-red">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @else
                                            <span class="text-sm text-gray">
                                                Enter your email address
                                            </span>
                                        @enderror

                                        <span class="text-sm text-gray float-right">
                                            <span id="length-email_rfc">0</span> / 254
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <h4>Additional info</h4>
                            <div class="row">
                                <x-adminlte-input type="text" name="pincode" label="Pin code *" placeholder="Pin code"
                                                  value="{{ old('pincode') }}"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        @error('pincode')
                                            <span class="text-sm text-red">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @else
                                            <span class="text-sm text-gray">
                                                xxxx-xxxx
                                            </span>
                                        @enderror
                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-input type="text" name="id" label="ID" placeholder="ID"
                                                  value="{{ old('id') }}"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        @error('id')
                                            <span class="text-sm text-red">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @else
                                            <span class="text-sm text-gray">
                                            Enter your ID
                                        </span>
                                        @enderror
                                        <span class="text-sm text-gray float-right">
                                            <span id="length-id">0</span> / 128
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <h4>Comment</h4>
                            <div class="row">
                                <x-adminlte-textarea name="description" label="Description" placeholder="Description"
                                                     fgroup-class="col-md-12">{{ old('description') }}
                                    <x-slot name="bottomSlot">
                                        @error('description')
                                            <span class="text-sm text-red">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @else
                                            <span class="text-sm text-gray">
                                                Add a short comment
                                            </span>
                                        @enderror
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
