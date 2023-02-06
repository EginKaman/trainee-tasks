@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Task 3 - Image') }}</div>

                    <div class="card-body">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-converter-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-converter" type="button" role="tab"
                                        aria-controls="nav-converter" aria-selected="true">Converter
                                </button>
                                <button class="nav-link" id="nav-test-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-test" type="button" role="tab" aria-controls="nav-test"
                                        aria-selected="false">Test data
                                </button>
                                <button class="nav-link" id="nav-previous-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-previous" type="button" role="tab"
                                        aria-controls="nav-previous"
                                        aria-selected="false">Previous results
                                </button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-converter" role="tabpanel"
                                 aria-labelledby="nav-converter-tab">
                                <div class="row pt-3">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="alert" data-cy=“successAlert”>
                                            {{ __('Successfully!') }}
                                        </div>
                                    @endif
                                    @if (session('failure'))
                                        <div class="alert alert-danger" role="alert" data-cy=“errorAlert”>
                                            {{ __('Attention! An error has occurred, see the details below.') }}
                                        </div>
                                    @endif
                                    @if ($errors->any())
                                        <div class="alert alert-danger" role="alert" data-cy=“errorAlert”>
                                            {{ __('Attention! An error has occurred, see the details below.') }}
                                            @error('document')
                                            <div data-cy=“errorMessage”>
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @enderror
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('optimizer') }}" id="convertor-form"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="file"
                                                   class="col-md-2 col-form-label text-md-end">
                                                {{ __('File input') }}
                                            </label>

                                            <div class="col-md-10">
                                                <input class="form-control @error('name') is-invalid @enderror"
                                                       type="file" id="file" name="image"
                                                       onchange="change()"
                                                       accept=".webp,.jpg,.png,.gif,.bmp">
                                                @error('document')
                                                <div class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                                <div id="passwordHelpBlock" class="form-text">
                                                    Select a file up to 10 MB size, with a resolution higher than
                                                    500x500px and formats: WebP, JPG, PNG, GIF, BMP
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row mb-0">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="btn btn-primary" id="convert-button"
                                                        disabled>
                                                    {{ __('Convert') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-test" role="tabpanel"
                                 aria-labelledby="nav-test-tab">
                                <div class="row pt-3">
                                    <div class="col-12">
                                        <h3>File structure templates for test data</h3>
                                        <small class="text-muted">
                                            You can download the templates of data structure XML and JSON by clicking on
                                            the link below
                                        </small>
                                        <p class="text-start">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-test" role="tabpanel"
                                 aria-labelledby="nav-test-tab">
                                <div class="row pt-3">
                                    <div class="col-12">
                                        <h3>File structure templates for test data</h3>
                                        <small class="text-muted">
                                            You can download the templates of data structure XML and JSON by clicking on
                                            the link below
                                        </small>
                                        <p class="text-start">

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-previous" role="tabpanel"
                                 aria-labelledby="nav-test-tab">
                                <div class="row pt-3">
                                    <div class="col-12">
                                        <h3>File structure templates for test data</h3>
                                        <small class="text-muted">
                                            You can download the templates of data structure XML and JSON by clicking on
                                            the link below
                                        </small>
                                        <p class="text-start">

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
