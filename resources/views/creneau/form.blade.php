<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="creneau" class="form-label">{{ __('Creneau') }}</label>
            <input type="time" name="creneau" class="form-control @error('creneau') is-invalid @enderror" value="{{ old('creneau', $creneau?->creneau) }}" id="creneau" placeholder="Creneau">
            {!! $errors->first('creneau', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}

        </div>
        @error('creneau')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Insérer') }}</button>
        <a href="{{ route('creneaudb') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium">
            <i class="fas fa-arrow-left me-2"></i> {{ __('Retour') }}
        </a>
    </div>
</div>