@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h2 class="h5 mb-0">Tableau de bord</h2>
    </div>
    <div class="card-body">
        <p>Bienvenue dans votre application Domisyl.</p>
        
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="w-25">Nom</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Date</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">John Doe</h6>
                                    <small class="text-muted">john@example.com</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success">Actif</span>
                        </td>
                        <td>15 Jan 2023</td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">Robert Johnson</h6>
                                    <small class="text-muted">robert@example.com</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-danger bg-opacity-10 text-danger">Inactif</span>
                        </td>
                        <td>10 Mar 2023</td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mt-4">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Suivant</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection