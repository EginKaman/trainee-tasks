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
                                    <form method="POST" action="{{ route('convertor') }}" id="convertor-form"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="file"
                                                   class="col-md-4 col-form-label text-md-end">
                                                {{ __('File input') }}
                                            </label>

                                            <div class="col-md-6">
                                                <input class="form-control @error('name') is-invalid @enderror"
                                                       type="file" id="file" name="document"
                                                       onchange="change()"
                                                       accept=".xml,.csv,.json">
                                                @error('document')
                                                <div class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                                <div id="passwordHelpBlock" class="form-text">
                                                    Select file up to 1 Mb and formats: xml, csv, json
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row mb-0" id="xml-processing" style="display:none;">
                                            <label for="method"
                                                   class="col-md-4 col-form-label text-md-end">
                                                {{ __('Choose the method of processing XML') }}
                                            </label>

                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="reader"
                                                           id="method-1" value="xmlreader" checked>
                                                    <label class="form-check-label" for="method-1">
                                                        XMLReader
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="reader"
                                                           id="method-2" value="simplexml">
                                                    <label class="form-check-label" for="method-2">
                                                        SimpleXML
                                                    </label>
                                                </div>
                                                @error('method')
                                                <span class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-0">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="btn btn-primary" id="convert-button" disabled>
                                                    {{ __('Convert') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    @if(!empty($fileErrors))
                                        <div class="row">
                                            <div class="col-12">
                                                <p>
                                                <h3>The following errors were found in
                                                    the {{ $document->getClientOriginalName() }} file</h3>
                                                <small class="text-muted">
                                                    For a success conversion, upload the file without errors
                                                </small>
                                                </p>
                                                <table class="table table-bordered">
                                                    <tbody>
                                                    @foreach($fileErrors as $fileError)
                                                        <tr>
                                                            <td>Line {{ $fileError->line }}</td>
                                                            <td>{{ $fileError->message }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                    @if(!empty($results))
                                        <div class="row">
                                            <div class="col-12">
                                                <p>
                                                <h3>Processing result</h3>
                                                <small class="text-muted">
                                                    The tables show the converted data
                                                </small>
                                                </p>
                                                @foreach($results as $exrate)
                                                    <p><h4>Date: {{ $exrate->lastUpdate }}</h4></p>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Unit</th>
                                                            <th>Code</th>
                                                            <th>Country</th>
                                                            <th>Rate</th>
                                                            <th>Change</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($exrate->currency as $currency)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $currency->name }}</td>
                                                                <td>{{ $currency->unit }}</td>
                                                                <td>{{ $currency->currencyCode }}</td>
                                                                <td>{{ $currency->country }}</td>
                                                                <td>{{ $currency->rate }}</td>
                                                                <td>{{ $currency->change }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
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
                                            <a href="{{ route('convertor.json-schema') }}">JSON Schema</a>
                                            <span>({{ round($json->getSize() / 1024, 2) }} kB)</span><br>
                                            <a href="{{ route('convertor.xml-schema') }}">XML Schema</a>
                                            <span>({{ round($xml->getSize() / 1024, 2) }} kB)</span>
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
                                                <td>
                                                    <a href="{{ \Storage::url('examples/correct-file-schema.xml') }}">
                                                        correct-file-schema.xml
                                                    </a>
                                                    <span>(511 kB)</span>
                                                </td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-file-schema.xml') }}">
                                                        wrong-file-schema.xml
                                                    </a>
                                                    <span>(214 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/correct-file-schema.json') }}">
                                                        correct-file-schema.json
                                                    </a>
                                                    <span>(511 kB)</span>
                                                </td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-file-schema.json') }}">
                                                        wrong-file-schema.json
                                                    </a>
                                                    <span>(214 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/correct-file-schema.csv') }}">
                                                        correct-file-schema.csv
                                                    </a>
                                                    <span>(511 kB)</span>
                                                </td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-file-schema.csv') }}">
                                                        wrong-file-schema.csv
                                                    </a>
                                                    <span>(214 kB)</span>
                                                </td>
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
    <script>
        function isXML() {
            let type = document.getElementById('file').files[0].type;
            let splittedType = type.split('/');
            let radios = document.getElementById('xml-processing')
            if (splittedType[1] === 'xml') {
                radios.removeAttribute('style')
            } else {
                radios.style['display'] = 'none';
            }
        }
        function change() {
            let file = document.getElementById('file').files[0];
            if (file.length > 0) {
                document.getElementById('convert-button').removeAttribute('disabled');
            }
        }
    </script>
@endsection
