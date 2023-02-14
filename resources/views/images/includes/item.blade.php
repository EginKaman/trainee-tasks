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
                <h5>{{ $items->first()->first()->type }}</h5>
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
                                    $col = 12 / $thumbs->count();
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
