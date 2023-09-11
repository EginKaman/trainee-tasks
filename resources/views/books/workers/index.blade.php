@extends('adminlte::page')

@section('title', 'Task 9 - Bots list')
@push('js')
    @vite([])
    <script>
        const stopWorker = function (action, title) {
            $('#stop-worker #update-form').attr('action', action);
            $('#stop-worker #worker-name').text(title);
            $('#stop-worker').modal();
        }
    </script>
@endpush
@section('content_header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $job->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">{{ $job->bot->category->site->title }}</li>
                        <li class="breadcrumb-item">{{ $job->bot->category->title }}</li>
                        <li class="breadcrumb-item">{{ $job->bot->title }}</li>
                        <li class="breadcrumb-item active">Job detail view</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-adminlte-card title="Job workers" theme="lightblue" theme-mode="outline"
                             header-class="rounded-bottom border-info">
                <p>One worker is one scenario which is performed. If the job has flag Loop which is equal Yes then the worker will repeat a scenario "Number of repetitions" times which was selected during the creation of the job. If "Number of repetitions" is equal 0 or not filled and Loop is Yes then a worker will repeat scenario until a job is stopped</p>
                <x-adminlte-datatable id="workers-table" :config="$config" :heads="$heads" head-theme="dark" triped hoverable bordered compressed/>
            </x-adminlte-card>
        </div>
    </div>

    <x-adminlte-modal id="stop-worker" title="Stop worker">
        <p>Are you sure that you want to stop <span id="worker-name"></span> worker?</p>
        <x-slot name="footerSlot">
            <form method="POST" style="display: inline-block" id="update-form">
                @csrf
                @method('PUT')
                <button class="btn btn-danger">Stop</button>
            </form>
            <x-adminlte-button label="Cancel" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>
@endsection

