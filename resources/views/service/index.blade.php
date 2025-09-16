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
                                        <th>Statut</th>
                                        <th>Prestataire</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($services as $service)
                                        <tr>
											<td style="white-space: nowrap;" >{{ $service->title }}</td>
						
											<td style="white-space: nowrap;" ><span class="badge bg-light text-dark">{{ $service->serviceCategory->name }}</span></td>
											<td>{{ $service->price }} Ar</td>
                                            <td>{{ $service->duration_minutes ? $service->duration_minutes . ' min' : '-' }}</td>
                                            <td>@if($service->is_active)
                                                    <span class="badge bg-success">Activé</span>
                                                @else
                                                    <span class="badge bg-danger">Désactivé</span>
                                                @endif</td>
                                            
                                            <td>
                                                <div data-bs-toggle="modal" data-bs-target="#employeesModal-{{ $service->id }}" style="cursor:pointer;">
                                                    <ul>
                                                        @forelse ($service->employees as $employee)
                                                            <li>{{ $employee->nom }}</li>
                                                        @empty
                                                            <li>Aucun employé assigné</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </td>

                                            <!-- Modal -->
                                            <div class="modal fade" id="employeesModal-{{ $service->id }}" tabindex="-1" aria-labelledby="employeesModalLabel-{{ $service->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="employeesModalLabel-{{ $service->id }}">
                                                        Prestataire de  : {{ $service->title }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <label for="employees">Sélectionnez des employés :</label>
                                                        <select name="employee_ids[]" class="form-control" multiple>
                                                            @foreach($employees as $employee)
                                                                <option value="{{ $employee->id }}"
                                                                    {{ $service->employees->contains($employee->id) ? 'selected' : '' }}>
                                                                    {{ $employee->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <div class="mt-3">
                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                            </div>

                                            <td>
                                                <form action="{{ route('servicedb.destroy',$service->id) }}" method="POST" class="small">
                                                    <!-- <a class="btn btn-sm btn-primary " href=""><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a> -->
                                                    <a class="btn btn-sm btn-success" href="{{ route('servicedb.edit',$service->id) }}" style="font-size: 0.6rem"><i class="fa fa-fw fa-edit"></i> {{ __('Modifier') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <td>
                                                        <button style="white-space: nowrap; font-size: 0.6rem;"  type="submit" onclick="return confirm('Voulez vous vraiement effectuer cette action')" class="btn btn-danger btn-sm text-inline" style="font-size: 0.7rem">{{ __('Activer / Desactiver') }}</button>
                                                    </td>
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
