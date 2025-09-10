@extends('layouts.app')

@section('template_title')
    Fiche Client
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Fiche Client') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('fiche-clients.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Création de nouveau fiche') }}
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
                                <thead class="thead small">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Client Id</th>
										<th>Objectifs</th>
										<th>Indications</th>
										<th>Observations</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($ficheClients as $ficheClient)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $ficheClient->client_id }}</td>
											<td>{{ $ficheClient->objectifs }}</td>
											<td>{{ $ficheClient->indications }}</td>
											<td>{{ $ficheClient->observations }}</td>

                                            <td>
                                                <form action="{{ route('fiche-clients.destroy',$ficheClient->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('fiche-clients.show',$ficheClient->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('fiche-clients.edit',$ficheClient->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $ficheClients->links() !!}
            </div>
        </div>
    </div>
@endsection
