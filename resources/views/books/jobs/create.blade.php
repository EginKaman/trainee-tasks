@extends('adminlte::page')

@section('title', 'Task 9 - Bots list')
@push('js')
    @vite(['resources/js/books/jobs/create.js'])
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
                        <li class="breadcrumb-item">{{ $bot->title }}</li>
                        <li class="breadcrumb-item active">Create job</li>
                    </ol>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create job</h1>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('content')
    <form action="{{ route('bots.jobs.store', ['bot' => $bot]) }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <x-adminlte-card title="Job" theme="lightblue" theme-mode="outline"
                                 header-class="rounded-bottom border-info">
                    <div class="row">
                        <div class="col-md-12">
                            <x-adminlte-input name="name" label="Job name" placeholder="job-name"
                                              fgroup-class="col-md-12" enable-old-support/>
                        </div>
                    </div>
                </x-adminlte-card>
                <x-adminlte-card title="Settings" theme="lightblue" theme-mode="outline"
                                 header-class="rounded-bottom border-info">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                @foreach(\App\Enum\JobType::cases() as $type)
                                    <div class="form-check form-check-inline">
                                        <input id="type-single" class="form-check-input" type="radio" name="type"
                                               value="{{ $type->value }}"
                                            @checked(old('type') === \App\Enum\JobType::Cron->value || $loop->first)>
                                        <label class="form-check-label"
                                               for="type-{{ $type->value }}}">
                                            {{ Str::title($type->name) }}job
                                        </label>
                                    </div>
                                    @error('type')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }} }}</strong>
                                    </span>
                                    @enderror
                                @endforeach
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
                            <div class="row">
                                <div class="col">
                                    <p class="text-muted">At minute 0 past every 2nd hour on Wednesday</p>
                                </div>
                            </div>
                            <x-adminlte-input name="workers_count" label="Workers count" placeholder="123"
                                              fgroup-class="col-md-12" enable-old-support>
                                <x-slot name="bottomSlot">
                                <span class="text-sm text-gray">
                                    The total quantity of workers for one job which will work in parallel and execute one scenario in parallel
                                </span>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                    </div>
                </x-adminlte-card>
                <x-adminlte-card title="Scenario settings" theme="lightblue" theme-mode="outline"
                                 header-class="rounded-bottom border-info">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input id="is-loop" class="form-check-input" type="checkbox" name="is_loop"
                                           value="1" @checked(old('is_loop'))/>
                                    <label class="form-check-label" for="is-loop">Launch the scenario in the
                                        loop</label>
                                </div>
                            </div>
                            <div class="row" id="loop-active" style="display: none;">
                                <div class="col">
                                    <x-adminlte-input name="pause" label="Pause in seconds" placeholder="123"
                                                      fgroup-class="col-md-12" enable-old-support>
                                        <x-slot name="bottomSlot">
                                            <span class="text-sm text-gray">
                                                A worker(s) will repeat the scenario after this pause
                                            </span>
                                        </x-slot>
                                    </x-adminlte-input>
                                    <x-adminlte-input name="repetitions" label="Number of repetitions" placeholder="123"
                                                      fgroup-class="col-md-12" enable-old-support>
                                        <x-slot name="bottomSlot">
                                            <span class="text-sm text-gray">
                                                One worker will repeat the scenario as many times as indicated in this field. If the number is equal 0 or empty a worker will repeat the scenario until the job is stopped
                                            </span>
                                        </x-slot>
                                    </x-adminlte-input>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-adminlte-card>

                <x-adminlte-button class="btn-flat float-right" type="submit"
                                   label="Create" theme="success"></x-adminlte-button>
            </div>
        </div>
    </form>
@endsection

