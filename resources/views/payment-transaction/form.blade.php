<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="payment_id" class="form-label">{{ __('Payment Id') }}</label>
            <input type="text" name="payment_id" class="form-control @error('payment_id') is-invalid @enderror" value="{{ old('payment_id', $paymentTransaction?->payment_id) }}" id="payment_id" placeholder="Payment Id">
            {!! $errors->first('payment_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="amount" class="form-label">{{ __('Amount') }}</label>
            <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $paymentTransaction?->amount) }}" id="amount" placeholder="Amount">
            {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="method" class="form-label">{{ __('Method') }}</label>
            <input type="text" name="method" class="form-control @error('method') is-invalid @enderror" value="{{ old('method', $paymentTransaction?->method) }}" id="method" placeholder="Method">
            {!! $errors->first('method', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="reference" class="form-label">{{ __('Reference') }}</label>
            <input type="text" name="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference', $paymentTransaction?->reference) }}" id="reference" placeholder="Reference">
            {!! $errors->first('reference', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="paid_at" class="form-label">{{ __('Paid At') }}</label>
            <input type="text" name="paid_at" class="form-control @error('paid_at') is-invalid @enderror" value="{{ old('paid_at', $paymentTransaction?->paid_at) }}" id="paid_at" placeholder="Paid At">
            {!! $errors->first('paid_at', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="notes" class="form-label">{{ __('Notes') }}</label>
            <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $paymentTransaction?->notes) }}" id="notes" placeholder="Notes">
            {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>