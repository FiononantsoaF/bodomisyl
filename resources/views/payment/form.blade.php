<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="appointment_id" class="form-label">{{ __('Appointment Id') }}</label>
            <input type="text" name="appointment_id" class="form-control @error('appointment_id') is-invalid @enderror" value="{{ old('appointment_id', $payment?->appointment_id) }}" id="appointment_id" placeholder="Appointment Id">
            {!! $errors->first('appointment_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="subscription_id" class="form-label">{{ __('Subscription Id') }}</label>
            <input type="text" name="subscription_id" class="form-control @error('subscription_id') is-invalid @enderror" value="{{ old('subscription_id', $payment?->subscription_id) }}" id="subscription_id" placeholder="Subscription Id">
            {!! $errors->first('subscription_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="total_amount" class="form-label">{{ __('Total Amount') }}</label>
            <input type="text" name="total_amount" class="form-control @error('total_amount') is-invalid @enderror" value="{{ old('total_amount', $payment?->total_amount) }}" id="total_amount" placeholder="Total Amount">
            {!! $errors->first('total_amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="deposit" class="form-label">{{ __('Deposit') }}</label>
            <input type="text" name="deposit" class="form-control @error('deposit') is-invalid @enderror" value="{{ old('deposit', $payment?->deposit) }}" id="deposit" placeholder="Deposit">
            {!! $errors->first('deposit', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="balance" class="form-label">{{ __('Balance') }}</label>
            <input type="text" name="balance" class="form-control @error('balance') is-invalid @enderror" value="{{ old('balance', $payment?->balance) }}" id="balance" placeholder="Balance">
            {!! $errors->first('balance', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $payment?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="paid_at" class="form-label">{{ __('Paid At') }}</label>
            <input type="text" name="paid_at" class="form-control @error('paid_at') is-invalid @enderror" value="{{ old('paid_at', $payment?->paid_at) }}" id="paid_at" placeholder="Paid At">
            {!! $errors->first('paid_at', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>