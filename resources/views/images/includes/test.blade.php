<div class="row pt-3">
    <div class="col-12">
        <h3>Test valid image for convert</h3>
        <small class="text-muted">
            Here you can download files for testing the converter with positive answers
        </small>
        <div class="row">
            @foreach($files['valid'] as $item)
                <figure class="figure col-3">
                    <img src="{{ $item['url'] }}"
                         class="figure-img img-fluid rounded" alt="...">
                    <figcaption class="figure-caption">
                        <a href="{{ $item['url'] }}" class="btn-link" download="">{{ $item['name'] }}</a>
                        ({{ $item['size'] }})<br><br>
                        {{ $item['dimensions'] }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</div>
<div class="row pt-3">
    <div class="col-12">
        <h3>Test invalid image for convert</h3>
        <small class="text-muted">
            Here you can download files for testing the converter with negative answers
        </small>
        <div class="row">
            @foreach($files['invalid'] as $item)
                <figure class="figure col-3">
                    <img src="{{ $item['url'] }}"
                         class="figure-img img-fluid rounded" alt="...">
                    <figcaption class="figure-caption">
                        <a href="{{ $item['url'] }}" class="btn-link" download="">{{ $item['name'] }}</a>
                        ({{ $item['size'] }})<br><br>
                        {{ $item['dimensions'] }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</div>
