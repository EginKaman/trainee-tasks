@extends('layouts.app')

@section('content')
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" viewBox="0 0 16 16">
            <path
                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </symbol>
        <symbol id="info-fill" viewBox="0 0 16 16">
            <path
                d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
        </symbol>
        <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
            <path
                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </symbol>
    </svg>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if ($errors->isEmpty() && empty($fileErrors) && !empty($results))
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">
                            <svg class="" role="img" width="20px" height="20px" aria-label="Success:">
                                <use xlink:href="#check-circle-fill"/>
                            </svg>
                            {{ __('Successfully!') }}</h4>
                        <p>The data of the "{{ $document->getClientOriginalName() }}" has been successfully converted
                            and displayed bellow</p>
                    </div>
                @endif
                @if (!empty($fileErrors))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">
                            <svg class="" role="img" width="20px" height="20px" aria-label="Error:">
                                <use xlink:href="#exclamation-triangle-fill"/>
                            </svg>
                            {{ __('Validation error!') }}</h4>
                        <p>{{ __('Attention! An error has occurred, see the details below.') }}</p>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">
                            <svg class="" role="img" width="20px" height="20px" aria-label="Error:">
                                <use xlink:href="#exclamation-triangle-fill"/>
                            </svg>
                            {{ __('Validation error!') }}
                        </h4>
                        @error('document')
                        <p>{{ $message }}</p>
                        @enderror
                    </div>
                @endif
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
                                    <form method="POST" action="{{ route('convertor') }}" id="convertor-form"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-md-8 col-sm-12">
                                                <label for="file"
                                                       class="col-form-label">
                                                    {{ __('File input') }}
                                                </label>

                                                <input
                                                    class="form-control @error('document') is-invalid @enderror @if(!empty($fileErrors)) is-invalid @endif"
                                                    type="file" id="file" name="document"
                                                    onchange="change()"
                                                    accept=".xml,.csv,.json">
                                                @error('document')
                                                <div class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                                @if(!empty($fileErrors))
                                                    <div class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                                        <strong>{{ __('File has errors, upload file without errors') }}</strong>
                                                    </div>
                                                @endif
                                                @if(empty($fileErrors) && $errors->isEmpty())
                                                    <div id="passwordHelpBlock" class="form-text">
                                                        Select file up to 1 Mb and formats: xml, csv, json
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-0" id="xml-processing" style="display:none;">
                                            <div class="col-md-6 col-sm-12">
                                                <label for="method"
                                                       class="col-form-label">
                                                    {{ __('Choose the method of processing XML') }}
                                                </label>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="reader"
                                                           id="method-2" value="simplexml" checked>
                                                    <label class="form-check-label" for="method-2">
                                                        SimpleXML
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="reader"
                                                           id="method-1" value="xmlreader">
                                                    <label class="form-check-label" for="method-1">
                                                        XMLReader
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
                                            <div class="col-md-6 col-sm-12">
                                                <button type="submit" class="btn btn-primary btn-bl" id="convert-button"
                                                        disabled>
                                                    {{ __('Convert') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    @if(!empty($fileErrors))
                                        <div class="row">
                                            <div class="col-12">
                                                <h3>The following errors were found in
                                                    the {{ $document->getClientOriginalName() }} file</h3>
                                                <small class="text-muted">
                                                    For a success conversion, upload the file without errors
                                                </small>
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
                                                <h3>Processing result</h3>
                                                <small class="text-muted">
                                                    The tables show the converted data
                                                </small>
                                                @foreach($results as $exrate)
                                                    <h4>
                                                        Date: {{ \Illuminate\Support\Facades\Date::createFromFormat('Y-m-d', $exrate->lastUpdate)->format('Y.m.d') }}
                                                    </h4>
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
                                        <div class="row">
                                            <div class="col-12">
                                                <h3>Processing results for download</h3>
                                                <small class="text-muted">
                                                    Download the results of processing the
                                                    file "{{ $document->getClientOriginalName() }}" by clicking on the
                                                    link below
                                                </small>
                                                <table class="table">
                                                    <tbody>
                                                    <tr>
                                                        <td><a href="{{ $urls['processing_results_simple'] }}" download>processing
                                                                results.xml
                                                                (Simple)</a>
                                                            ({{ round($files['processing_results_simple']->getSize() / 1024, 2) }}
                                                            kB)
                                                        </td>
                                                        <td><a href="{{ $urls['processing_results_writer'] }}" download>processing
                                                                results.xml
                                                                (XmlWriter)</a>
                                                            ({{ round($files['processing_results_writer']->getSize() / 1024, 2) }}
                                                            kB)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="{{ $urls['processing_results_json'] }}" download>processing
                                                                results.json</a>
                                                            ({{ round($files['processing_results_json']->getSize() / 1024, 2) }}
                                                            kB)
                                                        </td>
                                                        <td><a href="{{ $urls['processing_results_csv'] }}">processing
                                                                results.csv</a>
                                                            ({{ round($files['processing_results_csv']->getSize() / 1024, 2) }}
                                                            kB)
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
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
                                            <a href="{{ route('convertor.json-schema') }}" target="_blank" download="">JSON
                                                Schema</a>
                                            <span>({{ round($json->getSize() / 1024, 2) }} kB)</span><br>
                                            <a href="{{ route('convertor.xml-schema') }}" target="_blank" download="">XML
                                                Schema</a>
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
                                                    <a href="{{ \Storage::url('examples/correct-file-schema.xml') }}"
                                                       target="_blank" download="">
                                                        correct-file-schema.xml
                                                    </a>
                                                    <span>(511 kB)</span>
                                                </td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-file-schema.xml') }}"
                                                       target="_blank" download="">
                                                        wrong-file-schema.xml
                                                    </a>
                                                    <span>(214 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/correct-file-schema.json') }}"
                                                       target="_blank" download="">
                                                        correct-file-schema.json
                                                    </a>
                                                    <span>(511 kB)</span>
                                                </td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-file-schema.json') }}"
                                                       target="_blank" download="">
                                                        wrong-file-schema.json
                                                    </a>
                                                    <span>(214 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/correct-file-schema.csv') }}"
                                                       target="_blank" download="">
                                                        correct-file-schema.csv
                                                    </a>
                                                    <span>(511 kB)</span>
                                                </td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-file-schema.csv') }}"
                                                       target="_blank" download="">
                                                        wrong-file-schema.csv
                                                    </a>
                                                    <span>(214 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/correct-file-schema-header.csv') }}"
                                                       target="_blank" download="">
                                                        correct-file-schema-header.csv
                                                    </a>
                                                    <span>(511 kB)</span>
                                                </td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-file-schema-header.csv') }}"
                                                       target="_blank" download="">
                                                        wrong-file-schema-header.csv
                                                    </a>
                                                    <span>(214 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/correct-file-min.xml') }}"
                                                       target="_blank" download="">correct-file-min.xml</a>
                                                    <span>(8 kB)</span>
                                                </td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/invalid-syntax.xml') }}"
                                                       target="_blank" download="">invalid-syntax.xml</a>
                                                    <span>(571 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/correct-file-max.xml') }}"
                                                       target="_blank" download="">correct-file-max.xml</a>
                                                    <span>(942 kB)</span>
                                                </td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-file-max.xml') }}"
                                                       target="_blank" download="">wrong-file-max.xml</a>
                                                    <span>(485 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-file-min.xml') }}"
                                                       target="_blank" download="">wrong-file-min.xml</a>
                                                    <span>(485 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-last-update.xml') }}"
                                                       target="_blank" download="">
                                                        wrong-data-last-update.xml
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-name.xml') }}"
                                                       target="_blank" download="">
                                                        wrong-data-name.xml
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-unit.xml') }}"
                                                       target="_blank" download="">
                                                        wrong-data-unit.xml
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-country.xml') }}"
                                                       target="_blank" download="">
                                                        wrong-data-country.xml
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-currency-code.xml') }}"
                                                       target="_blank" download="">
                                                        wrong-data-currency-code.xml
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-change.xml') }}"
                                                       target="_blank" download="">
                                                        wrong-data-change.xml
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-rate.xml') }}"
                                                       target="_blank" download="">
                                                        wrong-data-rate.xml
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-last-update.json') }}"
                                                       target="_blank" download="">
                                                        wrong-data-last-update.json
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-name.json') }}"
                                                       target="_blank" download="">
                                                        wrong-data-name.json
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-unit.json') }}"
                                                       target="_blank" download="">
                                                        wrong-data-unit.json
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-country.json') }}"
                                                       target="_blank" download="">
                                                        wrong-data-country.json
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-currency-code.json') }}"
                                                       target="_blank" download="">
                                                        wrong-data-currency-code.json
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-change.json') }}"
                                                       target="_blank" download="">
                                                        wrong-data-change.json
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-rate.json') }}"
                                                       target="_blank" download="">
                                                        wrong-data-rate.json
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-last-update.csv') }}"
                                                       target="_blank" download="">
                                                        wrong-data-last-update.csv
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><a href="{{ \Storage::url('examples/wrong-data-name.csv') }}"
                                                       target="_blank" download="">
                                                        wrong-data-name.csv
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-unit.csv') }}"
                                                       target="_blank" download="">
                                                        wrong-data-unit.csv
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-country.csv') }}"
                                                       target="_blank" download="">
                                                        wrong-data-country.csv
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-currency-code.csv') }}"
                                                       target="_blank" download="">
                                                        wrong-data-currency-code.csv
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-change.csv') }}"
                                                       target="_blank" download="">
                                                        wrong-data-change.csv
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="{{ \Storage::url('examples/wrong-data-rate.csv') }}"
                                                       target="_blank" download="">
                                                        wrong-data-rate.csv
                                                    </a>
                                                    <span>(941 kB)</span>
                                                </td>
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
            isXML();
            let file = document.getElementById('file').files;
            if (file.length > 0) {
                document.getElementById('convert-button').removeAttribute('disabled');
            }
        }
    </script>
@endsection
