<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="client_id" class="form-label">{{ __('Client Id') }}</label>
            <input type="text" name="client_id" class="form-control @error('client_id') is-invalid @enderror" value="{{ old('client_id', $appointment?->client_id) }}" id="client_id" placeholder="Client Id">
            {!! $errors->first('client_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="employee_id" class="form-label">{{ __('Employee Id') }}</label>
            <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id', $appointment?->employee_id) }}" id="employee_id" placeholder="Employee Id">
            {!! $errors->first('employee_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="service_id" class="form-label">{{ __('Service Id') }}</label>
            <input type="text" name="service_id" class="form-control @error('service_id') is-invalid @enderror" value="{{ old('service_id', $appointment?->service_id) }}" id="service_id" placeholder="Service Id">
            {!! $errors->first('service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="start_times" class="form-label">{{ __('Start Times') }}</label>
            <input type="text" name="start_times" class="form-control @error('start_times') is-invalid @enderror" value="{{ old('start_times', $appointment?->start_times) }}" id="start_times" placeholder="Start Times">
            {!! $errors->first('start_times', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="end_times" class="form-label">{{ __('End Times') }}</label>
            <input type="text" name="end_times" class="form-control @error('end_times') is-invalid @enderror" value="{{ old('end_times', $appointment?->end_times) }}" id="end_times" placeholder="End Times">
            {!! $errors->first('end_times', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $appointment?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="comment" class="form-label">{{ __('Comment') }}</label>
            <input type="text" name="comment" class="form-control @error('comment') is-invalid @enderror" value="{{ old('comment', $appointment?->comment) }}" id="comment" placeholder="Comment">
            {!! $errors->first('comment', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>