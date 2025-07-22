<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="client_id" class="form-label">{{ __('Client Id') }}</label>
            <input type="text" name="client_id" class="form-control @error('client_id') is-invalid @enderror" value="{{ old('client_id', $subscription?->client_id) }}" id="client_id" placeholder="Client Id">
            {!! $errors->first('client_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="services_id" class="form-label">{{ __('Services Id') }}</label>
            <input type="text" name="services_id" class="form-control @error('services_id') is-invalid @enderror" value="{{ old('services_id', $subscription?->services_id) }}" id="services_id" placeholder="Services Id">
            {!! $errors->first('services_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $subscription?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="total_session" class="form-label">{{ __('Total Session') }}</label>
            <input type="text" name="total_session" class="form-control @error('total_session') is-invalid @enderror" value="{{ old('total_session', $subscription?->total_session) }}" id="total_session" placeholder="Total Session">
            {!! $errors->first('total_session', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="used_session" class="form-label">{{ __('Used Session') }}</label>
            <input type="text" name="used_session" class="form-control @error('used_session') is-invalid @enderror" value="{{ old('used_session', $subscription?->used_session) }}" id="used_session" placeholder="Used Session">
            {!! $errors->first('used_session', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="period_start" class="form-label">{{ __('Period Start') }}</label>
            <input type="text" name="period_start" class="form-control @error('period_start') is-invalid @enderror" value="{{ old('period_start', $subscription?->period_start) }}" id="period_start" placeholder="Period Start">
            {!! $errors->first('period_start', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="period_end" class="form-label">{{ __('Period End') }}</label>
            <input type="text" name="period_end" class="form-control @error('period_end') is-invalid @enderror" value="{{ old('period_end', $subscription?->period_end) }}" id="period_end" placeholder="Period End">
            {!! $errors->first('period_end', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Valider') }}</button>
        <a href="{{ route('subscriptiondb') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium">
            <i class="fas fa-arrow-left me-2"></i> {{ __('Retour') }}
        </a>
        
    </div>
</div>