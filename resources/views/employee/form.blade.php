<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Nom') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $employee?->name) }}" id="name" placeholder="Nom complet">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="job_categ_id" class="form-label">{{ __('Emploi') }}</label>
            <select name="job_categ_id" id="job_categ_id" class="form-control @error('job_categ_id') is-invalid @enderror">
                <option value="">---Choisir emploi---</option>
                @foreach($job as $cat)
                    <option value="{{ $cat->id }}" {{ old('job_categ_id', $employee->job_categ_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            @error('job_categ_id')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
        <div class="form-group mb-2 mb20">
            <label for="specialty" class="form-label">{{ __('Spécialité') }}</label>
            <input type="text" name="specialty" class="form-control @error('specialty') is-invalid @enderror" value="{{ old('specialty', $employee?->specialty) }}" id="specialty" placeholder="Spécialité">
            {!! $errors->first('specialty', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            {{--<label for="is_active" class="form-label">{{ __('Is Active') }}</label>--}}
            <input type="hidden" name="is_active" class="form-control @error('is_active') is-invalid @enderror" value="{{ old('is_active', $employee?->is_active) }}" id="is_active" placeholder="">
            {!! $errors->first('is_active', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="phone" class="form-label">{{ __('Télephone') }}</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $employee?->phone) }}" id="phone" placeholder="">
            {!! $errors->first('phone', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $employee?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="address" class="form-label">{{ __('Adresse') }}</label>
            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $employee?->address) }}" id="address" placeholder="Adresse">
            {!! $errors->first('address', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Valider') }}</button>
        <a href="{{ route('employeedb') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium">
            <i class="fas fa-arrow-left me-2"></i> {{ __('Retour') }}
        </a>
    </div>
</div>