@extends('layouts.app')

@section('template_title')
    {{ $appointment->name ?? __('Show') . " " . __('Appointment') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Appointment</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('appointments.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Client Id:</strong>
                            {{ $appointment->client_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Employee Id:</strong>
                            {{ $appointment->employee_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Service Id:</strong>
                            {{ $appointment->service_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Start Times:</strong>
                            {{ $appointment->start_times }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>End Times:</strong>
                            {{ $appointment->end_times }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Status:</strong>
                            {{ $appointment->status }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Comment:</strong>
                            {{ $appointment->comment }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
