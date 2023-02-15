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
                <input type="hidden" name="timezone" value="">
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
            <div class="col-md-6 offset-md-2">
                <button type="submit" class="btn btn-primary" id="convert-button"
                        disabled>
                    {{ __('Convert') }}
                </button>
            </div>
        </div>
    </form>
    @if(!empty($image))
       @include('images.includes.item')
    @endif
</div>
