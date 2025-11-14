@extends('layouts.app')

@section('template_title')
    {{ $testimonial->name ?? __('Show') . " " . __('Testimonial') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Testimonial</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('testimonials.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>File Path:</strong>
                            {{ $testimonial->file_path }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Is Active:</strong>
                            {{ $testimonial->is_active }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
