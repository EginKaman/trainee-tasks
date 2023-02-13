@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Task 3 - Image') }}</div>
                    <div class="card-body">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-link" href="{{ route('optimizer') }}">Converter</a>
                                <a class="nav-link" href="{{ route('optimizer.test') }}">Test data</a>
                                <a class="nav-link active" href="{{ route('optimizer.previous') }}">Previous results</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane show active" id="nav-previous" role="tabpanel"
                                 aria-labelledby="nav-previous-tab">
                                @include('images.includes.previous')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
