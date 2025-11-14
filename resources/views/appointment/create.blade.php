@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Appointment
@endsection

@section('content')
    <div class="container-fluid small mb-2 py-1 p-0">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-1">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-search me-2"></i> Nouveau rendez-vous pour : {{ $client->name }}
                        </h5>
                    </div>
                </div>
                <div class="card shadow-sm mb-1"> 
                        <form method="POST" action="{{route('appointmentsdb/save')}}"  role="form" enctype="multipart/form-data" class="row g-1">
                            @csrf

                            @include('appointment.form')
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection
