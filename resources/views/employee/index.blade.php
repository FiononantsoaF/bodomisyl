@extends('layouts.app')

@section('template_title')
    Employee
@endsection

@section('content')
    <div class="container-fluid small">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Listes des employés</h5>
                            <a href="{{ route('employeedb.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                {{ __('Insertion employé') }}
                            </a>
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
                                        <th>Numero</th>
                                        
										<th>Nom</th>
										<th>Emploi</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Address</th>
                                        <th>Statut</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $employee->name }}</td>
											<td>{{ $employee->jobCategory->name ?? '-' }}</td>
											<td>{{ $employee->phone }}</td>
											<td>{{ $employee->email }}</td>
											<td>{{ $employee->address }}</td>
											<td >
                                                @if($employee->is_active)
                                                <span class="badge bg-success">Actif</span>
                                                @else
                                                    <span class="badge bg-danger">Inactif</span>
                                                @endif</td>
                                            </td>
                                            <td style="white-space: nowrap;" class="text-end">
                                                {{-- Bouton Modifier --}}
                                                <a class="btn btn-sm btn-success me-1" href="{{ route('employeedb.edit', $employee->id) }}" style="font-size: 0.6rem;">
                                                    <i class="fa fa-fw fa-edit"></i> {{ __('Modifier') }}
                                                </a>
                                            </td>
                                            <td>
                                                <form action="{{route('employeedb.desactiver', $employee->id) }}" 
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $employee->is_active ? 'btn-danger' : 'btn-success' }}" style="font-size: 0.6rem;">
                                                        <i class="fa fa-fw {{ $employee->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                        {{ $employee->is_active ? __('Désactiver') : __('Réactiver') }}
                                                    </button>
                                                </form>

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
