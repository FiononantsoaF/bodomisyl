@extends('layouts.app')

@section('template_title')
    Service
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h5 class="mb-0" id="card_title">
                                {{ __('Préstation') }}
                            </h5>

                             <div class="float-right">
                                <a href="{{ route('servicedb.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                <thead class="thead small">
                                    <tr>           
										<th>Titre</th>
										<!-- <th>Description</th> -->
										<th>Formules</th>
										<th>Prix</th>
										<th>Durée/séance(min)</th>
                                        <!-- <th>Validité(jours)</th> -->
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($services as $service)
                                        <tr>
											<td>{{ $service->title }}</td>
						
											<td><span class="badge bg-light text-dark">{{ $service->serviceCategory->name }}</span></td>
											<td>{{ $service->price }} Ar</td>
                                            <td>{{ $service->duration_minutes ? $service->duration_minutes . ' min' : '-' }}</td>
                                            <td>
                                                <form action="" method="POST">
                                                    <!-- <a class="btn btn-sm btn-primary " href=""><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a> -->
                                                    <a class="btn btn-sm btn-success" href="{{ route('servicedb.edit',$service->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Modifier') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <!-- <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button> -->
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{ $services->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
