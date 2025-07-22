<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="client_id" class="form-label">{{ __('Client Id') }}</label>
            <input type="text" name="client_id" class="form-control @error('client_id') is-invalid @enderror" value="{{ old('client_id', $ficheClient?->client_id) }}" id="client_id" placeholder="Client Id">
            {!! $errors->first('client_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="objectifs" class="form-label">{{ __('Objectifs') }}</label>
            <input type="text" name="objectifs" class="form-control @error('objectifs') is-invalid @enderror" value="{{ old('objectifs', $ficheClient?->objectifs) }}" id="objectifs" placeholder="Objectifs">
            {!! $errors->first('objectifs', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="indications" class="form-label">{{ __('Indications') }}</label>
            <input type="text" name="indications" class="form-control @error('indications') is-invalid @enderror" value="{{ old('indications', $ficheClient?->indications) }}" id="indications" placeholder="Indications">
            {!! $errors->first('indications', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="observations" class="form-label">{{ __('Observations') }}</label>
            <input type="text" name="observations" class="form-control @error('observations') is-invalid @enderror" value="{{ old('observations', $ficheClient?->observations) }}" id="observations" placeholder="Observations">
            {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>