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
                                                {{ __('FileHelper input') }}
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
                                                <div id="passwordHelpBlock" class="form-text">
                                                    Select a file up to 10 MB size, with a resolution higher than
                                                    500x500px and formats: WebP, JPG, PNG, GIF, BMP
                                                </div>
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
                                                weighing {{ $image->size_for_humans }}, size {{ $image->height }}
                                                x{{ $image->width }}px is
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
                                                                        ({{ $thumb->size_for_humans }})
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
                                            <img src="{{ Storage::url($image->path) }}"
                                                 class="figure-img img-fluid rounded" alt="...">
                                            <figcaption class="figure-caption">
                                                {{ $image->filename }}
                                                ({{ $image->size_for_humans }})
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
