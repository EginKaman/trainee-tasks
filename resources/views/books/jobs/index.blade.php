@extends('adminlte::page')

@section('title', 'Task 9 - Jobs list')
@push('js')
    @vite([])

    <script>
        const editJob = function (action, title, type, workersCount, cron) {
            console.log(type);
            $('#update-form').attr('action', action);
            $('#edit-job #job-name').text(title);
            $('#edit-job input#job-workers-count').val(workersCount);
            if (type === 'cron') {
                let cronArray = cron.split(' ');
                $('#edit-job #type-cron input[name="cron[min]"]').val(cronArray[0]);
                $('#edit-job #type-cron input[name="cron[hour]"]').val(cronArray[1]);
                $('#edit-job #type-cron input[name="cron[day]"]').val(cronArray[2]);
                $('#edit-job #type-cron input[name="cron[month]"]').val(cronArray[3]);
                $('#edit-job #type-cron input[name="cron[week]"]').val(cronArray[4]);
                $('#edit-job .settings').show();
            } else {
                $('#edit-job .settings').hide();
            }

            $('#edit-job').modal();
        }
        const deleteJob = function (action, title) {
            $('#delete-job #delete-form').attr('action', action);
            $('#delete-job #job-name').text(title);
            $('#delete-job').modal();
        }
    </script>
@endpush
@section('content_header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-22">
                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">{{ $bot->category->site->title }}</li>
                        <li class="breadcrumb-item">{{ $bot->category->title }}</li>
                        <li class="breadcrumb-item active">{{ $bot->title }}</li>
                    </ol>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $bot->title }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('bots.jobs.create', ['bot' => $bot]) }}" class="btn btn-primary float-sm-right">Create a job</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-jobs" role="tab" aria-controls="custom-tabs-three-jobs" aria-selected="true">Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-cron" role="tab" aria-controls="custom-tabs-three-cron" aria-selected="false">Cron jobs</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-three-jobs" role="tabpanel" aria-labelledby="custom-tabs-three-jobs-tab">
                            <h3>Jobs</h3>
                            <p>List of the all jobs which were created for selected bot</p>
                            <x:adminlte-datatable id="jobs-table" :config="$jobsConfig" :heads="$jobsHeads" head-theme="dark" triped hoverable bordered compressed/>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-three-cron" role="tabpanel" aria-labelledby="custom-tabs-three-cron-tab">
                            <h3>Cron Jobs</h3>
                            <p>List of the all cron jobs which were created for selected bot</p>
                            <x-adminlte-datatable id="cron-table" :config="$cronConfig" :heads="$cronHeads" head-theme="dark" triped hoverable bordered compressed/>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <form method="POST" style="display: inline-block" id="update-form">
        @csrf
        @method('PUT')
    <x-adminlte-modal id="edit-job" title="Edit job">
        <div class="row">
            <div class="col-md-12">
                <strong>Job name</strong>
                <p id="job-name"></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <x-adminlte-input name="workers_count" label="Workers count" placeholder="123"
                                  id="job-workers-count"
                                  fgroup-class="col-md-12">
                    <x-slot name="bottomSlot">
                                <span class="text-sm text-gray">
                                    The total quantity of workers for one job which will work in parallel and execute one scenario in parallel
                                </span>
                    </x-slot>
                </x-adminlte-input>
            </div>
        </div>
        <div class="row settings" id="type-cron" style="display: none;">
            <div class="col">
                <x-adminlte-input name="cron[min]" label="Minute" placeholder="*"
                                  fgroup-class="col-md-12" enable-old-support/>
            </div>
            <div class="col">
                <x-adminlte-input name="cron[hour]" label="Hour" placeholder="*"
                                  fgroup-class="col-md-12" enable-old-support/>
            </div>
            <div class="col">
                <x-adminlte-input name="cron[day]" label="Day" placeholder="*"
                                  fgroup-class="col-md-12" enable-old-support/>
            </div>
            <div class="col">
                <x-adminlte-input name="cron[month]" label="Month" placeholder="*"
                                  fgroup-class="col-md-12" enable-old-support/>
            </div>
            <div class="col">
                <x-adminlte-input name="cron[week]" label="Week" placeholder="*"
                                  fgroup-class="col-md-12" enable-old-support/>
            </div>
        </div>
        <x-slot name="footerSlot">


            <button class="btn btn-danger" type="submit">Update</button>

            <x-adminlte-button label="Cancel" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>
    </form>
    <x-adminlte-modal id="delete-job" title="Delete job">
        <p>Are you sure that you want to delete the job <span id="job-name"></span>? Current job will be deleted without the option of resuming and all workers will be removed too</p>
        <x-slot name="footerSlot">
            <form method="POST" style="display: inline-block" id="delete-form">
                @csrf
                @method('PUT')
                <button class="btn btn-danger">Delete</button>
            </form>
            <x-adminlte-button label="Cancel" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>
@endsection

