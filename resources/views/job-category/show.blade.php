@extends('layouts.app')

@section('template_title')
    {{ $jobCategory->name ?? __('Show') . " " . __('Job Category') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Job Category</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('job-categories.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $jobCategory->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Service Category Id:</strong>
                            {{ $jobCategory->service_category_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
