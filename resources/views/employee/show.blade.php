@extends('layouts.app')

@section('template_title')
    {{ $employee->name ?? __('Show') . " " . __('Employee') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Employee</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('employees.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $employee->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Job Categ Id:</strong>
                            {{ $employee->job_categ_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Specialty:</strong>
                            {{ $employee->specialty }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Is Active:</strong>
                            {{ $employee->is_active }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Phone:</strong>
                            {{ $employee->phone }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Email:</strong>
                            {{ $employee->email }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Address:</strong>
                            {{ $employee->address }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
