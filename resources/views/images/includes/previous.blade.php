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
            <a href="{{ route('optimizer.previous', $image) }}" class="btn-link">
                <img src="{{ Storage::url($image->path) }}"
                     class="figure-img img-fluid rounded" alt="...">
            </a>
            <figcaption class="figure-caption">
                @if(\Illuminate\Support\Str::length($image->filename) > 24)
                    <a href="{{ route('optimizer.show', $image) }}" class="btn-link"
                       data-bs-placement="top"
                       data-bs-toggle="tooltip"
                       data-bs-title="{{ $image->filename }}" title="{{ $image->filename }}">
                        {{ Str::limit($image->filename, 24) }}
                    </a>
                @else
                    <a href="{{ route('optimizer.show', $image) }}" class="btn-link">
                        {{ $image->filename }}
                    </a>
                @endif
            ({{ $image->size_for_humans }})<br><br>
                {{ $image->created_at->format('M j H:i') }}
            </figcaption>
        </figure>
    @endforeach
</div>
