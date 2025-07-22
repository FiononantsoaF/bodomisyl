<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <input type="hidden" name="id" value="{{ $serviceCategory->id }}" id="id">
            <label for="name" class="form-label">{{ __('Nom') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $serviceCategory?->name) }}" id="name" placeholder="nom ">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $serviceCategory?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="remarque" class="form-label">{{ __('remarque') }}</label>
            <input type="text" name="remarque" class="form-control @error('remarque') is-invalid @enderror" value="{{ old('remarque', $serviceCategory?->remarque) }}" id="remarque" placeholder="remarque">
            {!! $errors->first('remarque', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="mb-3">

            <label class="form-label" for="inputFile">Image:</label>

            <input 
                type="file" 
                name="image_url" 
                id="image_url"
                class="form-control @error('image_url') is-invalid @enderror" value="{{ old('image_url', $serviceCategory?->image_url) }}" 
                >
            {!! $errors->first('image_url', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}

        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Valider') }}</button>
        <a href="{{ route('service-categorydb') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium">
            <i class="fas fa-arrow-left me-2"></i> {{ __('Retour') }}
        </a>

    </div>
</div>