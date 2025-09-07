<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="services_id" class="form-label">{{ __('Service') }}</label>
            {{-- <input type="text" name="services_id" class="form-control @error('services_id') is-invalid @enderror" value="{{ old('services_id', $serviceSession?->services_id) }}" id="services_id" placeholder="Services"> --}}
            
            <select name="services_id" id="services_id" class="form-control @error('services_id') is-invalid @enderror">
                <option>-- Choisir --</option>
                @foreach($services as $serv)
                    <option value="{{ $serv->id }}" {{ old('services_id', $serviceSession->services_id ?? '') == $serv->id ? 'selected' : '' }}>
                        {{ $serv->title }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('services_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="session_id" class="form-label">{{ __('Session') }}</label>
            
            {{-- <input type="text" name="session_id" class="form-control @error('session_id') is-invalid @enderror" value="{{ old('session_id', $serviceSession?->session_id) }}" id="session_id" placeholder="Session"> --}}
            <select name="session_id" id="session_id" class="form-control @error('session_id') is-invalid @enderror">
                <option>-- Choisir --</option>
                @foreach($sessions as $serv)
                    <option value="{{ $serv->id }}" {{ old('session_id', $serviceSession->session_id ?? '') == $serv->id ? 'selected' : '' }}>
                        {{ $serv->title }}
                    </option>
                @endforeach
            </select>
            
            {!! $errors->first('session_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="total_session" class="form-label">{{ __('Total Session') }}</label>
            <input type="text" name="total_session" class="form-control @error('total_session') is-invalid @enderror" value="{{ old('total_session', $serviceSession?->total_session) }}" id="total_session" placeholder="Total Sessions">
            {!! $errors->first('total_session', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="session_per_period" class="form-label">{{ __('Session par semaine') }}</label>
            <input type="text" name="session_per_period" class="form-control @error('session_per_period') is-invalid @enderror" value="{{ old('session_per_period', $serviceSession?->session_per_period) }}" id="session_per_period" placeholder="Nombre session par période">
            {!! $errors->first('session_per_period', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        {{-- <div class="form-group mb-2 mb20">
            <label for="period_type" class="form-label">{{ __('Type période') }}</label>
            <input type="text" name="period_type" class="form-control @error('period_type') is-invalid @enderror" value="{{ old('period_type', $serviceSession?->period_type) }}" id="period_type" placeholder="Type période">
            {!! $errors->first('period_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>--}}

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Insérer') }}</button>
        <a href="{{ route('service-session') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium">
            <i class="fas fa-arrow-left me-2"></i> {{ __('Retour') }}
        </a>
    </div>
</div>