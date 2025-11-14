<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="code" class="form-label">{{ __('Code') }}</label>
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $carteCadeau?->code) }}" id="code" placeholder="Code">
            {!! $errors->first('code', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="beneficiaire" class="form-label">{{ __('Beneficiaire') }}</label>
            <input type="text" name="beneficiaire" class="form-control @error('beneficiaire') is-invalid @enderror" value="{{ old('beneficiaire', $carteCadeau?->beneficiaire) }}" id="beneficiaire" placeholder="Beneficiaire">
            {!! $errors->first('beneficiaire', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="contact" class="form-label">{{ __('Contact') }}</label>
            <input type="text" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact', $carteCadeau?->contact) }}" id="contact" placeholder="Contact">
            {!! $errors->first('contact', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="client_id" class="form-label">{{ __('Client Id') }}</label>
            <input type="text" name="client_id" class="form-control @error('client_id') is-invalid @enderror" value="{{ old('client_id', $carteCadeau?->client_id) }}" id="client_id" placeholder="Client Id">
            {!! $errors->first('client_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="service_id" class="form-label">{{ __('Service Id') }}</label>
            <input type="text" name="service_id" class="form-control @error('service_id') is-invalid @enderror" value="{{ old('service_id', $carteCadeau?->service_id) }}" id="service_id" placeholder="Service Id">
            {!! $errors->first('service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="montant" class="form-label">{{ __('Montant') }}</label>
            <input type="text" name="montant" class="form-control @error('montant') is-invalid @enderror" value="{{ old('montant', $carteCadeau?->montant) }}" id="montant" placeholder="Montant">
            {!! $errors->first('montant', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="date_emission" class="form-label">{{ __('Date Emission') }}</label>
            <input type="text" name="date_emission" class="form-control @error('date_emission') is-invalid @enderror" value="{{ old('date_emission', $carteCadeau?->date_emission) }}" id="date_emission" placeholder="Date Emission">
            {!! $errors->first('date_emission', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="validite_jours" class="form-label">{{ __('Validite Jours') }}</label>
            <input type="text" name="validite_jours" class="form-control @error('validite_jours') is-invalid @enderror" value="{{ old('validite_jours', $carteCadeau?->validite_jours) }}" id="validite_jours" placeholder="Validite Jours">
            {!! $errors->first('validite_jours', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="date_fin" class="form-label">{{ __('Date Fin') }}</label>
            <input type="text" name="date_fin" class="form-control @error('date_fin') is-invalid @enderror" value="{{ old('date_fin', $carteCadeau?->date_fin) }}" id="date_fin" placeholder="Date Fin">
            {!! $errors->first('date_fin', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="is_active" class="form-label">{{ __('Is Active') }}</label>
            <input type="text" name="is_active" class="form-control @error('is_active') is-invalid @enderror" value="{{ old('is_active', $carteCadeau?->is_active) }}" id="is_active" placeholder="Is Active">
            {!! $errors->first('is_active', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>