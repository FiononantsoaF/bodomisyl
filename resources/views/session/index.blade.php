@extends('layouts.app')

@section('template_title')
    Session
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <h5 id="card_title">
                                {{ __('Session') }}
                            </h5>

                             <div class="float-right">
                                <a href="{{ route('sessiondb.create') }}" " class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Ajouter') }}
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
                                        <th>Num</th>
                                        
										<th>Titre</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sessions as $session)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $session->title }}</td>

                                            <td>
                                                <form action="" method="POST">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('service_session.show',$serviceSession->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('service_session.edit',$serviceSession->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    <a class="btn btn-sm btn-primary " href=""><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    --}}
                                                    
                                                    <a class="btn btn-sm btn-success" href="{{ route('sessiondb.edit',$session->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Modifier') }}</a>
                                                    @csrf
                                                    {{-- 
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                    --}}
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{ $sessions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
