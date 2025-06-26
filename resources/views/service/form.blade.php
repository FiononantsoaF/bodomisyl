<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="id" value="{{ $service->id }}" id="id">
        <div class="form-group mb-2 mb20">
            <label for="title" class="form-label">{{ __('Title') }}</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $service?->title) }}" id="title" placeholder="Title">
            {!! $errors->first('title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $service?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="service_category_id" class="form-label">{{ __('Service Category') }}</label>
            <select name="service_category_id" id="service_category_id" class="form-control @error('service_category_id') is-invalid @enderror">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('service_category_id', $service->service_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            @error('service_category_id')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>

        <div class="form-group mb-2 mb20">
            <label for="price" class="form-label">{{ __('Price') }}</label>
            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $service?->price) }}" id="price" placeholder="Price">
            {!! $errors->first('price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="duration_minutes" class="form-label">{{ __('Duration Minutes') }}</label>
            <input type="text" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', $service?->duration_minutes) }}" id="duration_minutes" placeholder="Duration Minutes">
            {!! $errors->first('duration_minutes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="validity_days" class="form-label">{{ __('Validite') }}</label>
            <input type="text" name="validity_days" class="form-control @error('validity_days') is-invalid @enderror" value="{{ old('validity_days', $service?->validity_days) }}" id="validity_days" placeholder="Nombre de jour de validité">
            {!! $errors->first('validity_days', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>