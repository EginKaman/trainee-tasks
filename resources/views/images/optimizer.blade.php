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
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">
                            <svg class="" role="img" width="20px" height="20px" aria-label="Error:">
                                <use xlink:href="#exclamation-triangle-fill"/>
                            </svg>
                            {{ __('Validation error!') }}
                        </h4>
                        @error('image')
                        <p>{{ $message }}</p>
                        @enderror
                    </div>
                @endif
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
                                    <form method="POST" action="{{ route('optimizer') }}" id="convertor-form"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="file"
                                                   class="col-md-2 col-form-label text-md-end">
                                                {{ __('File input') }}
                                            </label>

                                            <div class="col-md-10">
                                                <input class="form-control @error('image') is-invalid @enderror"
                                                       type="file" id="file" name="image"
                                                       accept=".webp,.jpg,.png,.gif,.bmp">
                                                @error('image')
                                                <div class="invalid-feedback" role="alert" data-cy=“errorMessage”>
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                                @if($errors->isEmpty())
                                                    <div id="passwordHelpBlock" class="form-text">
                                                        Select a file up to 10 MB size, with a resolution higher than
                                                        500x500px and formats: WebP, JPG, PNG, GIF, BMP
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-0" id="processing" style="display:none;">
                                            <div class="col-md-6 col-sm-12">
                                                <label for="method"
                                                       class="col-form-label">
                                                    {{ __('Choose a validation method') }}
                                                </label>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="method"
                                                           id="method-2" value="native" checked>
                                                    <label class="form-check-label" for="method-2">
                                                        Native PHP
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="method"
                                                           id="method-1" value="library">
                                                    <label class="form-check-label" for="method-1">
                                                        Library
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
                                                <button type="submit" class="btn btn-primary" id="convert-button"
                                                        disabled>
                                                    {{ __('Convert') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    @if(!empty($image))
                                        <div class="row mt-4">
                                            <h4>Processing result</h4>
                                            <small class="text-muted">
                                                The processing image "{{ $image->filename }}"
                                                weighing {{ $image->size_for_humans }}, size {{ $image->dimension }} is
                                                protected by a watermark and converted in 5 formats - 4 others and 1 the
                                                same, but cropped
                                            </small>
                                            <div class="col-md-12 mt-3">
                                                @foreach($processing->all() as $ext => $items)
                                                    <div class="row">
                                                        @foreach($items as $size => $thumbs)
                                                            @foreach($thumbs->all() as $thumb)
                                                                @php
                                                                    switch ($thumb->original_width) {
                                                                        case 350:
                                                                            $col = 4;
                                                                            break;
                                                                        case 200:
                                                                            $col = 3;
                                                                            break;
                                                                        case 150:
                                                                            $col = 2;
                                                                            break;
                                                                        case 100:
                                                                            $col = 2;
                                                                            break;
                                                                        case 50:
                                                                            $col = 1;
                                                                            break;
                                                                        default:
                                                                            $col = 12  / $thumbs->count();

                                                                    }
                                                                @endphp
                                                                <figure class="figure col-{{ $col }}">
                                                                    <img src="{{ Storage::url($thumb->path) }}"
                                                                         class="figure-img img-fluid rounded" alt="...">
                                                                    <figcaption class="figure-caption">
                                                                        {{ $thumb->name }}
                                                                        ({{ $thumb->size_for_humans }}) <br>
                                                                        {{ $thumb->dimension }}
                                                                        @if($thumb->status === 'success')
                                                                            (optimized)
                                                                        @endif
                                                                    </figcaption>
                                                                </figure>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
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
                                        <h3>Test valid image for convert</h3>
                                        <small class="text-muted">
                                            Here you can download files for testing the converter with positive answers
                                        </small>
                                        <p class="text-start">
                                        </p>
                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-12">
                                        <h3>Test invalid image for convert</h3>
                                        <small class="text-muted">
                                            Here you can download files for testing the converter with negative answers
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
                                        <h3>Processing results for the last two days</h3>
                                        <small class="text-muted">
                                            You can see the conversion results
                                        </small>
                                    </div>
                                </div>
                                <div class="row pt-1">
                                    @foreach($images as $image)
                                        <figure class="figure col-3">
                                            <a href="{{ route('optimizer.show', $image) }}" class="btn-link">
                                                <img src="{{ Storage::url($image->path) }}"
                                                     class="figure-img img-fluid rounded" alt="...">
                                            </a>
                                            <figcaption class="figure-caption">
                                                <a href="{{ route('optimizer.show', $image) }}" class="btn-link">
                                                    {{ $image->filename }}
                                                </a>
                                                ({{ $image->size_for_humans }})<br><br>
                                                {{ $image->created_at->format('M j H:i') }}
                                            </figcaption>
                                        </figure>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
