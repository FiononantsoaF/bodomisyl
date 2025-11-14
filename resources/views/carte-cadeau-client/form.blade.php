<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="code" class="form-label">{{ __('Code') }}</label>
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $carteCadeauClient?->code) }}" id="code" placeholder="Code">
            {!! $errors->first('code', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="benef_name" class="form-label">{{ __('Benef Name') }}</label>
            <input type="text" name="benef_name" class="form-control @error('benef_name') is-invalid @enderror" value="{{ old('benef_name', $carteCadeauClient?->benef_name) }}" id="benef_name" placeholder="Benef Name">
            {!! $errors->first('benef_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="carte_cadeau_service_id" class="form-label">{{ __('Carte Cadeau Service Id') }}</label>
            <input type="text" name="carte_cadeau_service_id" class="form-control @error('carte_cadeau_service_id') is-invalid @enderror" value="{{ old('carte_cadeau_service_id', $carteCadeauClient?->carte_cadeau_service_id) }}" id="carte_cadeau_service_id" placeholder="Carte Cadeau Service Id">
            {!! $errors->first('carte_cadeau_service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="benef_contact" class="form-label">{{ __('Benef Contact') }}</label>
            <input type="text" name="benef_contact" class="form-control @error('benef_contact') is-invalid @enderror" value="{{ old('benef_contact', $carteCadeauClient?->benef_contact) }}" id="benef_contact" placeholder="Benef Contact">
            {!! $errors->first('benef_contact', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="client_id" class="form-label">{{ __('Client Id') }}</label>
            <input type="text" name="client_id" class="form-control @error('client_id') is-invalid @enderror" value="{{ old('client_id', $carteCadeauClient?->client_id) }}" id="client_id" placeholder="Client Id">
            {!! $errors->first('client_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="amount" class="form-label">{{ __('Amount') }}</label>
            <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $carteCadeauClient?->amount) }}" id="amount" placeholder="Amount">
            {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
            <input type="text" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $carteCadeauClient?->start_date) }}" id="start_date" placeholder="Start Date">
            {!! $errors->first('start_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="validy_days" class="form-label">{{ __('Validy Days') }}</label>
            <input type="text" name="validy_days" class="form-control @error('validy_days') is-invalid @enderror" value="{{ old('validy_days', $carteCadeauClient?->validy_days) }}" id="validy_days" placeholder="Validy Days">
            {!! $errors->first('validy_days', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="end_date" class="form-label">{{ __('End Date') }}</label>
            <input type="text" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $carteCadeauClient?->end_date) }}" id="end_date" placeholder="End Date">
            {!! $errors->first('end_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="is_active" class="form-label">{{ __('Is Active') }}</label>
            <input type="text" name="is_active" class="form-control @error('is_active') is-invalid @enderror" value="{{ old('is_active', $carteCadeauClient?->is_active) }}" id="is_active" placeholder="Is Active">
            {!! $errors->first('is_active', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>