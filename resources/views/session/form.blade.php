<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="id" value="{{ $session->id }}" id="id">
        <div class="form-group mb-2 mb20">
            <label for="title" class="form-label">{{ __('Titre') }}</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $session?->title) }}" id="title" placeholder="Titre">
            {!! $errors->first('title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="category_id" class="form-label">{{ __('Catégories') }}</label>
            
            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                <option>-- Choisir --</option>
                @foreach($category as $categ)
                    <option value="{{ $categ->id }}" {{ old('category_id') == $categ->id ? 'selected' : '' }}>
                        {{ $categ->title }}
                    </option>
                @endforeach
            </select>

            {!! $errors->first('category_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Valider') }}</button>
        <a href="{{ route('sessiondb') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium">
            <i class="fas fa-arrow-left me-2"></i> {{ __('Retour') }}
        </a>
    </div>
</div>