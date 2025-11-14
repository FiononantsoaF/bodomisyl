<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="service_id" class="form-label">{{ __('Service Id') }}</label>
            <input type="text" name="service_id" class="form-control @error('service_id') is-invalid @enderror" value="{{ old('service_id', $carteCadeauService?->service_id) }}" id="service_id" placeholder="Service Id">
            {!! $errors->first('service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="reduction_percent" class="form-label">{{ __('Reduction Percent') }}</label>
            <input type="text" name="reduction_percent" class="form-control @error('reduction_percent') is-invalid @enderror" value="{{ old('reduction_percent', $carteCadeauService?->reduction_percent) }}" id="reduction_percent" placeholder="Reduction Percent">
            {!! $errors->first('reduction_percent', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="amount" class="form-label">{{ __('Amount') }}</label>
            <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $carteCadeauService?->amount) }}" id="amount" placeholder="Amount">
            {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="is_active" class="form-label">{{ __('Is Active') }}</label>
            <input type="text" name="is_active" class="form-control @error('is_active') is-invalid @enderror" value="{{ old('is_active', $carteCadeauService?->is_active) }}" id="is_active" placeholder="Is Active">
            {!! $errors->first('is_active', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>