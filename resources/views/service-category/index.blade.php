@extends('layouts.app')

@section('template_title')
    Service Category
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h5 id="card_title">
                                {{ __('Formules') }}
                            </h5>

                             <div class="float-right">
                                <a href="{{ route('service-categorydb.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                            <table class="table table-hover align-middle">
                                <thead class="table-light small">
                                    <tr>
										<th>Name</th>
										<th>Description</th>
                                        <th>Statut</th>
                                        <th>Image</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($serviceCategories as $serviceCategory)
                                        <tr>
											<td style="white-space: nowrap;" >{{ $serviceCategory->name }}</td>
											<td>{{ $serviceCategory->description }}</td>
                                            <td>@if($serviceCategory->is_active)
                                                    <span class="badge bg-success">Activé</span>
                                                @else
                                                    <span class="badge bg-danger">Désactivé</span>
                                                @endif</td>
                                            <td><img style="width: 10%;" src="{{ asset('imageformule') }}/{{ $serviceCategory->image_url }}" alt="tag"></td>
                                            <td>
                                        <form  method="POST" action="{{ route('service-categorydb.destroy',$serviceCategory->id) }}">
                                            <td style="white-space: nowrap;" >
                                                <a class="btn btn-sm btn-success" href="{{ route('service-categorydb.edit',$serviceCategory->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Modifier') }}</a>
                                            </td>
                                            @csrf
                                            @method('DELETE')
                                            <td>
                                                <button style="white-space: nowrap;" type="submit" onclick="return confirm('Voulez vous vraiement effectuer cette action')" class="btn btn-danger btn-s text-inline" style="font-size: 0.7rem">{{ __('Activer / Desactiver') }}</button>
                                            </td>
                                        </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{ $serviceCategories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
