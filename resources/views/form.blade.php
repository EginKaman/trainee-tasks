@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Task 4 - ReGex, AdminLTE and forms</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('form.store') }}" method="post">
                <x-adminlte-card title="Form Example" theme="lightblue" theme-mode="outline"
                                 header-class="rounded-bottom border-info">
                    <div class="row">
                        <div class="col-8">
                            <h4>Contact info</h4>
                            <div class="row">
                                <x-adminlte-input name="name" label="Name" placeholder="Name"
                                                  fgroup-class="col-md-6" disable-feedback/>
                            </div>
                            <div class="row">
                                <x-adminlte-input name="phone" label="Phone" placeholder="Phone"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        <span class="text-sm text-gray">
                                            +38 (xxx) xxx - xx - xx
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-input name="additional_phone" label="Phone" placeholder="Phone"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        <span class="text-sm text-gray">
                                            Enter your phone number
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <div class="row">
                                <x-adminlte-input name="email" label="Email" placeholder="Email"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        <span class="text-sm text-gray">
                                            Enter your email address
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-input name="email_rfc" label="Email RFC" placeholder="Phone"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        <span class="text-sm text-gray">
                                            Enter your email address
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <h4>Additional info</h4>
                            <div class="row">
                                <x-adminlte-input name="pincode" label="Pin code" placeholder="Pin code"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        <span class="text-sm text-gray">
                                            xxxx-xxxx
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-input name="id" label="Email RFC" placeholder="Phone"
                                                  fgroup-class="col-md-6" disable-feedback>
                                    <x-slot name="bottomSlot">
                                        <span class="text-sm text-gray">
                                            Enter your ID
                                        </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <h4>Comment</h4>
                            <div class="row">
                                <x-adminlte-textarea name="description" label="Description" placeholder="Description"
                                                     fgroup-class="col-md-12">
                                    <x-slot name="bottomSlot">
                                        <span class="text-sm text-gray">
                                            Add a short comment
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
