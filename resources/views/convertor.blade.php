@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Task 2 - XML, CSV, JSON') }}</div>

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
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-converter" role="tabpanel"
                                 aria-labelledby="nav-converter-tab">
                                <div class="row pt-3">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="alert" data-cy=“successAlert”>
                                            {{ __('Your feedback was sent successful.') }}
                                        </div>
                                    @endif
                                    @if (session('failure'))
                                        <div class="alert alert-success" role="alert" data-cy=“errorAlert”>
                                            {{ __('Something went wrong. Please, try again later.') }}
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('convertor') }}" id="feedback-form">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="file"
                                                   class="col-md-4 col-form-label text-md-end">
                                                {{ __('File input') }}
                                            </label>

                                            <div class="col-md-6">
                                                <input class="form-control" type="file" id="file"
                                                       accept=".xml,.csv,.json">
                                                <div id="passwordHelpBlock" class="form-text">
                                                    Select file up to 1 Mb and formats: xml, csv, json
                                                </div>
                                                @error('file')
                                                <span class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-0">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="btn btn-primary">
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
                                            <a href="#">JSON Schema</a> <span>(941 kB)</span><br>
                                            <a href="#">XML Schema</a> <span>(671 kB)</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-12">
                                        <h3>Download test valid or invalid data for validation schemes</h3>
                                        <small class="text-muted">
                                            Click on the link to start downloading the file
                                        </small>
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col">Positive Data</th>
                                                <th scope="col">Negative Data</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><a href="#">correct-file-schema.json</a> <span>(511 kB)</span></td>
                                                <td><a href="#">wrong-file-schema.json</a> <span>(214 kB)</span></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">correct-file-min.xml</a> <span>(8 kB)</span></td>
                                                <td><a href="#">invalid-syntax.xml</a> <span>(571 kB)</span></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">correct-file-max.xml</a> <span>(942 kB)</span></td>
                                                <td><a href="#">wrong-file-min.csv</a> <span>(485 kB)</span></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">correct-file-max.csv</a> <span>(567 kB)</span></td>
                                                <td><a href="#">wrong-file-max</a> <span>(941 kB)</span></td>
                                            </tr>
                                            </tbody>
                                        </table>
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
