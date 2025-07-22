@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Appointment
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Création ') }} rendez-vous</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('appointmentsdb.creation')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row padding-1 p-1">
                                <div class="col-md-12">
                                    <div class="form-group mb-2 mb20">
                                        <label for="client_id" class="form-label">{{ __('Client') }}</label>
                                        <input type="text" class="form-control" value="{{ $client->name }}" readonly>
                                        <input type="hidden" name="client_id" value="{{ $subscription?->client_id }}" id="client_id" ">
                                        <input type="hidden" name="subscription_id" value="{{ $subscription?->id }}" id="subscription_id" ">
                                        {!! $errors->first('client_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                    <div class="form-group mb-2 mb20">
                                        <label for="employee_id" class="form-label">{{ __('Prestataire') }}</label>
                                        
                                        {{-- <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ $employee->id}}" id="session_id" placeholder="Employéé"> --}}
                                        <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror">
                                            <option>-- Choisir le prestataire --</option>
                                            @foreach($employee as $emp)
                                                <option value="{{ $emp->id }}" {{ $client->id ?? '' == $emp->id ? 'selected' : '' }}>
                                                    {{ $emp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                        {!! $errors->first('employee_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                    <div class="form-group mb-2 mb20">
                                        <label for="service_id" class="form-label">{{ __('Service') }}</label>
                                        <input type="text" class="form-control" value="{{ $service->title }}" readonly>
                                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                                        <input type="hidden" name="duration_minutes" value="{{ $service->duration_minutes }}">
                                        {!! $errors->first('service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                    <div class="form-group mb-2 mb20">
                                        <label for="start_times" class="form-label">{{ __('Date rendez-vous') }}</label>
                                        <input type="date" name="start_times" class="form-control @error('start_times') is-invalid @enderror" value="" id="start_times" placeholder="Start Times">
                                        {!! $errors->first('start_times', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                    <div class="form-group mb-2 mb20">
                                        <label for="creneau" class="form-label">{{ __('Horaire') }}</label>
                                        <select name="creneau" id="creneau" class="form-control @error('creneau') is-invalid @enderror">
                                            <option>-- Choisir heure --</option>
                                            @foreach($creneau as $cre)
                                                <option value="{{ $cre->creneau}}">
                                                    {{ $cre->creneau }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
        
                                    <div class="form-group mb-2 mb20">
                                        <label for="comment" class="form-label">{{ __('Comment') }}</label>
                                        <input type="text" name="comment" class="form-control @error('comment') is-invalid @enderror" value="" id="comment" placeholder="Comment">
                                        {!! $errors->first('comment', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>

                                </div>
                                <div class="col-md-12 mt20 mt-2">
                                    <button type="submit" class="btn btn-primary">{{ __('Valider') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection