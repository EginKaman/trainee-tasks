@extends('adminlte::page')

@section('title', 'Task 9 - Bots list')
@push('js')
    @vite([])
    <script>
        const deleteBot = function (action, title) {
            $('#delete-bot #delete-form').attr('action', action);
            $('#delete-bot #bot-name').text(title);
            $('#delete-bot').modal();
        }
    </script>
@endpush
@section('content_header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Bots list</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">{{ $site->title }}</li>
                        <li class="breadcrumb-item active">{{ $category->title }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-adminlte-card title="Bots" theme="lightblue" theme-mode="outline"
                             header-class="rounded-bottom border-info">
                <p>List of the scenarios (bots) which were recorded in the extension for the selected site and event</p>
                <x-adminlte-datatable id="bots-table" :config="$config" :heads="$heads" head-theme="dark" triped hoverable bordered compressed/>
            </x-adminlte-card>
        </div>
    </div>
    <x-adminlte-modal id="delete-bot" title="Delete bot">
        <p>Are you sure that you want to delete the <span id="bot-name"></span>? All the running jobs, cronjobs, and workers will be stopped and deleted too</p>
        <x-slot name="footerSlot">
            <form method="POST" style="display: inline-block" id="delete-form">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
            <x-adminlte-button label="Cancel" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>
@endsection

