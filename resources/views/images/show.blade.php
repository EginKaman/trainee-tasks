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
                            <div class="tab-pane active" id="nav-previous" role="tabpanel"
                                 aria-labelledby="nav-test-tab">
                                <div class="row pt-3">
                                    <div class="col-12">
                                        <a href="{{ route('optimizer.previous') }}" class="btn btn-primary">Back</a>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <h4>Processing result</h4>
                                    <small class="text-muted">
                                        The processing image "{{ $image->filename }}"
                                        weighing {{ $image->size_for_humans }}, size {{ $image->dimension }} is
                                        protected by a watermark and converted in 5 formats - 4 others and 1 the
                                        same, but cropped
                                    </small>
                                    <p class="text-start">{{ $image->created_at->format('M j H:i') }}</p>
                                    <div class="col-md-12 mt-3">
                                        @foreach($processing->all() as $ext => $items)
                                            <div class="row">
                                                <h5>{{ $ext }}</h5>
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
                                                                case 100: case 150:
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
                                                                @if(\Illuminate\Support\Str::length($thumb->name) > 12)
                                                                    <span data-bs-toggle="tooltip"
                                                                          data-bs-title="{{ $thumb->name }}"
                                                                          title="{{ $thumb->name }}">
                                                                        {{ Str::limit($thumb->name, 12) }}
                                                                    </span>
                                                                @else
                                                                    {{ $thumb->name }}
                                                                @endif
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
