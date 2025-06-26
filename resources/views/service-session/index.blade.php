@extends('layouts.app')

@section('template_title')
    Service Session
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Service Session') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('service-session.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  Associer service / session
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
                                                <th>Services</th>
                                                <th>Session</th>
                                                <th>Session Per Period</th>
                                                <!--th>Period Type</th-->

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serviceSessions as $serviceSession)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
                                                <td>{{ $serviceSession->nomservice }}</td>
                                                <td>{{ $serviceSession->total_session }} {{ $serviceSession->nomsession }}</td>
                                                <td>{{ $serviceSession->session_per_period }}</td>

                                            <td>
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{ $serviceSessions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
