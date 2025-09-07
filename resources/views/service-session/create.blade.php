@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Service Session
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <h5 class="card-title">{{ __('Création') }} Service Session</h5>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('service-session.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            @include('service-session.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
