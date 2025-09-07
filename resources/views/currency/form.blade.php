<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="id" value="{{ old('money', $currency?->id) }}" >
        <div class="form-group mb-2 mb20">
            <label for="money" class="form-label">{{ __('Monnaie') }}</label>
            <input type="text" name="money" class="form-control @error('money') is-invalid @enderror" value="{{ old('money', $currency?->money) }}" id="money" placeholder="Exemple (EUR)">
            {!! $errors->first('money', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="value" class="form-label">{{ __('Valeur') }}</label>
            <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $currency?->value) }}" id="value" placeholder="Valeur en Ariary">
            {!! $errors->first('value', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Valider') }}</button>
    </div>
</div>