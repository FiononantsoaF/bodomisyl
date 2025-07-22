@extends('layouts.app')

@section('template_title')
    {{ $employeesCreneau->name ?? __('Show') . " " . __('Employees Creneau') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Employees Creneau</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('employees-creneaus.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Employee Id:</strong>
                            {{ $employeesCreneau->employee_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Creneau Id:</strong>
                            {{ $employeesCreneau->creneau_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Is Active:</strong>
                            {{ $employeesCreneau->is_active }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
