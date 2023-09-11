@extends('adminlte::page')

@section('title', 'Task 9 - Bots list')
@push('js')
    @vite([])
@endpush
@section('content_header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit job</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">{{ $job->bot->category->site->title }}</li>
                        <li class="breadcrumb-item">{{ $job->bot->category->title }}</li>
                        <li class="breadcrumb-item">{{ $job->bot->title }}</li>
                        <li class="breadcrumb-item">{{ $job->name }}</li>
                        <li class="breadcrumb-item active">Edit job</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('content')
    <form action="{{ route('jobs.update', ['job' => $job]) }}" method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <x-adminlte-card title="Job" theme="lightblue" theme-mode="outline"
                                 header-class="rounded-bottom border-info">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Job name</strong>
                            <p>{{ $job->name }}</p>
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
                                               value="{{ $type->value }}" disabled
                                            @checked($job->type === $type)>
                                        <label class="form-check-label"
                                               for="type-{{ $type->value }}}">
                                            {{ Str::title($type->name) }}job
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @if($job->type === \App\Enum\JobType::Cron)
                            @php($cron = explode(' ', $job->cron))
                            <div class="row settings" id="type-cron">
                                <div class="col">
                                    <x-adminlte-input name="cron[min]" label="Minute" placeholder="*"
                                                      :value="$cron[0]"
                                                      fgroup-class="col-md-12" enable-old-support/>
                                </div>
                                <div class="col">
                                    <x-adminlte-input name="cron[hour]" label="Hour" placeholder="*"
                                                      :value="$cron[1]"
                                                      fgroup-class="col-md-12" enable-old-support/>
                                </div>
                                <div class="col">
                                    <x-adminlte-input name="cron[day]" label="Day" placeholder="*"
                                                      :value="$cron[2]"
                                                      fgroup-class="col-md-12" enable-old-support/>
                                </div>
                                <div class="col">
                                    <x-adminlte-input name="cron[month]" label="Month" placeholder="*"
                                                      :value="$cron[3]"
                                                      fgroup-class="col-md-12" enable-old-support/>
                                </div>
                                <div class="col">
                                    <x-adminlte-input name="cron[week]" label="Week" placeholder="*"
                                                      :value="$cron[4]"
                                                      fgroup-class="col-md-12" enable-old-support/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="text-muted">At minute 0 past every 2nd hour on Wednesday</p>
                                </div>
                            </div>
                            @endif
                            <x-adminlte-input name="workers_count" label="Workers count" placeholder="123"
                                              :value="$job->workers_count"
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

                <x-adminlte-button class="btn-flat float-right" type="submit"
                                   label="Update" theme="success"></x-adminlte-button>
            </div>
        </div>
    </form>
@endsection

