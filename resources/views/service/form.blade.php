<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="id" value="{{ $service->id }}" id="id">
        <div class="form-group mb-2 mb20">
            <label for="service_category_id" class="form-label">{{ __('Formule') }}</label>
            <select name="service_category_id" id="service_category_id" class="form-control @error('service_category_id') is-invalid @enderror" required>
                <option value="">---Choisir formule---</option>
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
            <label for="title" class="form-label">{{ __('Titre') }}</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $service?->title) }}" id="title" placeholder="Titre" required>
            {!! $errors->first('title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            {{-- <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $service?->description) }}" id="description" placeholder="Description"> --}}

            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="custdescription">
                {{ old('description', $service?->description) }}
            </textarea>
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="detail" class="form-label">{{ __('Detail') }}</label>
            {{-- <input type="text" name="detail" class="form-control @error('detail') is-invalid @enderror" value="{{ old('detail', $service?->detail) }}" id="detail" placeholder="detail"> --}}

            <textarea name="detail" class="form-control @error('detail') is-invalid @enderror" id="custdetail">
                {{ old('detail', $service?->detail) }}
            </textarea>
            {!! $errors->first('detail', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="remarque" class="form-label">{{ __('Remarque') }}</label>
            <input type="text" name="remarque" class="form-control @error('remarque') is-invalid @enderror" value="{{ old('remarque', $service?->remarque) }}" id="remarque" placeholder="remarque">
            {!! $errors->first('remarque', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="price" class="form-label">{{ __('Prix') }}</label>
            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $service?->price) }}" id="price" placeholder="Prix en Ar" required>
            {!! $errors->first('price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="duration_minutes" class="form-label">{{ __('Durée Minutes') }}</label>
            <input type="text" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', $service?->duration_minutes) }}" id="duration_minutes" placeholder="Durée Minutes">
            {!! $errors->first('duration_minutes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="validity_days" class="form-label">{{ __('Validité') }}</label>
            <input type="text" name="validity_days" class="form-control @error('validity_days') is-invalid @enderror" value="{{ old('validity_days', $service?->validity_days) }}" id="validity_days" placeholder="Nombre de jour de validité">
            {!! $errors->first('validity_days', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Insérer') }}</button>
        <a href="{{ route('servicedb') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium">
            <i class="fas fa-arrow-left me-2"></i> {{ __('Retour') }}
        </a>
    </div>
</div>