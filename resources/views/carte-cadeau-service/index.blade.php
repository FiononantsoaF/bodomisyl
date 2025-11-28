@extends('layouts.app')

@section('template_title')
    Liste des cartes cadeaux
@endsection

@section('content')
<div class="container-fluid small mb-2 py-1 p-0">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-1">
                <div class="d-flex card-header bg-white border-bottom-0 py-3 align-items-center">
                    <h5 class="mb-0 col-md-8">
                        <i class="fas fa-gift me-2 text-warning"></i> Liste des cartes cadeaux
                    </h5>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-sm btn-primary" id="openAddModal">
                            <i class="fa fa-plus-circle me-1"></i> Ajouter une prestation comme cadeau
                        </button>
                    </div>
                </div>
                <div class="card-body p-2 border-0">
                    <form method="GET" class="row g-2">
                        <div class="row align-items-end mb-1 g-1">
                            <div class="col-md-3 col-sm-6">
                                <label for="service_id" class="form-label small">Prestation</label>
                                <select name="service_id" id="service_id" class="form-select form-select-sm select2">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}" 
                                            {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label for="date" class="form-label small">Date d'ajout</label>
                                <input type="date" id="date" name="date" value="{{ request('date') }}" 
                                    class="form-control form-control-sm" autocomplete="off">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label for="date" class="form-label small"></label>
                                <div class="d-flex gap-2"> 
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-search me-1"></i> Rechercher
                                    </button>
                                    <a href="{{ route('cartecadeauservicedb') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eraser me-1"></i> Effacer
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="card-body p-0 border-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0" id="carteTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Prestation</th>
                                    <th>Réduction (%)</th>
                                    <th>Montant fixe</th>
                                    <th>Date d'ajout</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($carteCadeauServices as $carte)
                                    <tr data-id="{{ $carte->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $carte->service->title ?? '—' }}</td>
                                        <td class="reduction-cell">
                                            <span class="reduction-text">{{ $carte->reduction_percent ? $carte->reduction_percent . ' %' : '—' }}</span>
                                        </td>
                                        <td class="amount-cell"> <span class="amount-text">{{ $carte->amount ? number_format($carte->amount, 2, ',', ' ') . ' Ar' : '—' }}</span></td>
                                        <td>{{ $carte->created_at ? $carte->created_at->format('d-m-Y') : '—' }}</td>
                                        <td>
                                            @if ($carte->is_active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-center" >
                                            <button class="btn btn-sm btn-outline-primary edit-btn" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('cartecadeauservicedb.destroy', $carte->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger delete-btn" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>


                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            Aucune carte cadeau trouvée.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($carteCadeauServices->hasPages())
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $carteCadeauServices->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout -->
<div class="modal fade" id="addCarteModal" tabindex="-1" aria-labelledby="addCarteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="carteForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-plus-circle me-2"></i> Ajouter des prestations à la carte cadeau
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="alert-container"></div>
                    
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th style="width: 180px;">Réduction (%)</th>
                                <th style="width: 180px;">Montant fixe (Ar)</th>
                            </tr>
                        </thead>
                        <tbody id="services-container">
                            <tr><td colspan="3" class="text-center text-muted">Chargement...</td></tr>
                        </tbody>
                    </table>
                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Vous ne pouvez pas saisir à la fois une réduction et un montant fixe pour le même service.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success btn-sm" id="submitBtn">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="spinner"></span>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('addCarteModal'));
        const servicesContainer = document.getElementById('services-container');
        const openBtn = document.getElementById('openAddModal');
        const carteForm = document.getElementById('carteForm');
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('spinner');
        const alertContainer = document.getElementById('alert-container');

        function showAlert(message, type = 'danger') {
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            setTimeout(() => alertContainer.innerHTML = '', 5000);
        }

        // Ouverture du modal et chargement des services
        openBtn.addEventListener('click', () => {
            servicesContainer.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Chargement...</td></tr>';
            alertContainer.innerHTML = '';

            fetch('{{ route("cartecadeauservicedb.create") }}')
                .then(res => {
                    if (!res.ok) throw new Error('Erreur de chargement des services');
                    return res.json();
                })
                .then(data => {
                    // Récupérer le tableau des services non inclus
                    const services = data.servicesNonInclus || [];
                    
                    if (services.length === 0) {
                        servicesContainer.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tous les services sont déjà inclus.</td></tr>';
                    } else {
                        servicesContainer.innerHTML = '';
                        services.forEach(service => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>
                                    <input type="hidden" name="services[${service.id}][service_id]" value="${service.id}">
                                    ${escapeHtml(service.title)}
                                </td>
                                <td>
                                    <input type="number" 
                                        name="services[${service.id}][reduction_percent]" 
                                        class="form-control form-control-sm reduction" 
                                        data-service="${service.id}"
                                        min="0" 
                                        max="100" 
                                        step="0.1"
                                        placeholder="0-100">
                                </td>
                                <td>
                                    <input type="number" 
                                        name="services[${service.id}][amount]" 
                                        class="form-control form-control-sm amount" 
                                        data-service="${service.id}"
                                        min="0" 
                                        placeholder="Montant">
                                </td>
                            `;
                            servicesContainer.appendChild(row);
                        });

                        // Initialiser la logique de désactivation mutuelle
                        initMutualExclusion();
                    }
                    modal.show();
                })
                .catch(err => {
                    console.error(err);
                    servicesContainer.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Erreur lors du chargement des services.</td></tr>';
                });
        });

        // Fonction pour gérer la désactivation mutuelle des champs
        function initMutualExclusion() {
            const reductions = document.querySelectorAll('.reduction');
            const amounts = document.querySelectorAll('.amount');

            reductions.forEach((reduction, index) => {
                const amount = amounts[index];
                
                reduction.addEventListener('input', function() {
                    if (this.value) {
                        amount.disabled = true;
                        amount.value = '';
                    } else {
                        amount.disabled = false;
                    }
                });

                amount.addEventListener('input', function() {
                    if (this.value) {
                        reduction.disabled = true;
                        reduction.value = '';
                    } else {
                        reduction.disabled = false;
                    }
                });
            });
        }

        // Fonction pour échapper le HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Validation et soumission du formulaire
        carteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Préparer les données
            const formData = new FormData(this);
            const services = {};
            let hasValidData = false;

            // Construire l'objet services correctement
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('services[')) {
                    const match = key.match(/services\[(\d+)\]\[(\w+)\]/);
                    if (match) {
                        const serviceId = match[1];
                        const field = match[2];
                        
                        if (!services[serviceId]) {
                            services[serviceId] = {};
                        }
                        
                        if (value) {
                            services[serviceId][field] = value;
                            if (field === 'reduction_percent' || field === 'amount') {
                                hasValidData = true;
                            }
                        }
                    }
                }
            }

            // Validation
            if (!hasValidData) {
                showAlert('Veuillez saisir au moins une réduction ou un montant fixe.', 'warning');
                return;
            }

            // Désactiver le bouton et afficher le spinner
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            // Envoi AJAX
            fetch('{{ route("cartecadeauservicedb.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ services: services })
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => Promise.reject(err));
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    modal.hide();
                    // Afficher un message de succès
                    const mainAlert = document.createElement('div');
                    mainAlert.className = 'alert alert-success alert-dismissible fade show';
                    mainAlert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.querySelector('.container-fluid').prepend(mainAlert);
                    
                    // Recharger après un court délai
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert(data.message || 'Une erreur est survenue.', 'danger');
                }
            })
            .catch(err => {
                console.error('Erreur:', err);
                let errorMsg = 'Une erreur est survenue lors de l\'enregistrement.';
                if (err.message) {
                    errorMsg = err.message;
                } else if (err.errors) {
                    errorMsg = Object.values(err.errors).flat().join('<br>');
                }
                showAlert(errorMsg, 'danger');
            })
            .finally(() => {
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            });
        });

        

        // Réinitialiser le formulaire à la fermeture du modal
        document.getElementById('addCarteModal').addEventListener('hidden.bs.modal', function() {
            carteForm.reset();
            alertContainer.innerHTML = '';
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });

        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                if (row.classList.contains('updating')) return;
                row.classList.add('updating');

                const reductionCell = row.querySelector('.reduction-cell');
                const amountCell = row.querySelector('.amount-cell');
                const reductionText = reductionCell.querySelector('.reduction-text');
                const amountText = amountCell.querySelector('.amount-text');
                const actionsCell = row.querySelector('td.text-center');
                const editBtn = actionsCell.querySelector('.edit-btn');
                const deleteBtn = actionsCell.querySelector('.delete-btn');

                if (!reductionCell.querySelector('input') && !amountCell.querySelector('input')) {
                    const currentReduction = reductionText.textContent.replace(' %', '').trim() !== '—'
                        ? reductionText.textContent.replace(' %', '').trim()
                        : '';
                    const currentAmount = amountText.textContent.trim() !== '—'
                        ? amountText.textContent.replace(' Ar', '').replace(/\s/g, '').replace(',', '.').trim()
                        : '';
                    reductionCell.innerHTML = `<input type="number" class="form-control form-control-sm reduction-input" value="${currentReduction}" min="0" max="100" step="0.1">`;
                    amountCell.innerHTML = `<input type="number" class="form-control form-control-sm amount-input" value="${currentAmount}" min="0" step="100">`;

                    // Masquer edit/delete
                    editBtn.style.display = 'none';
                    deleteBtn.style.display = 'none';

                    // Créer les boutons save et cancel
                    const saveBtn = document.createElement('button');
                    saveBtn.className = 'btn btn-sm btn-success save-btn';
                    saveBtn.title = 'Enregistrer';
                    saveBtn.textContent = '✓';

                    const cancelBtn = document.createElement('button');
                    cancelBtn.className = 'btn btn-sm btn-secondary cancel-btn';
                    cancelBtn.title = 'Annuler';
                    cancelBtn.textContent = '✗';

                    actionsCell.appendChild(saveBtn);
                    actionsCell.appendChild(cancelBtn);

                    const reductionInput = reductionCell.querySelector('.reduction-input');
                    const amountInput = amountCell.querySelector('.amount-input');

                    // Mutuellement exclusif
                    reductionInput.addEventListener('input', () => {
                        if (reductionInput.value) {
                            amountInput.disabled = true;
                            amountInput.value = '';
                        } else {
                            amountInput.disabled = false;
                        }
                    });

                    amountInput.addEventListener('input', () => {
                        if (amountInput.value) {
                            reductionInput.disabled = true;
                            reductionInput.value = '';
                        } else {
                            reductionInput.disabled = false;
                        }
                    });

                    // Sauvegarde
                    saveBtn.addEventListener('click', () => {
                        const newReduction = reductionInput.value;
                        const newAmount = amountInput.value;
                        const carteId = row.getAttribute('data-id');

                        fetch(`/cartecadeauservice/update/${carteId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || ''
                            },
                            body: JSON.stringify({ reduction_percent: newReduction, amount: newAmount })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if(data.success){
                                reductionCell.innerHTML = `<span class="reduction-text">${newReduction ? newReduction + ' %' : '—'}</span>`;
                                amountCell.innerHTML = `<span class="amount-text">${newAmount ? parseFloat(newAmount).toLocaleString('fr-FR', {minimumFractionDigits:2}) + ' Ar' : '—'}</span>`;
                                saveBtn.remove();
                                cancelBtn.remove();
                                editBtn.style.display = 'inline-block';
                                deleteBtn.style.display = 'inline-block';
                                row.classList.remove('updating');
                            } else {
                                alert('Erreur lors de la mise à jour');
                            }
                        })
                        .catch(err => console.error(err));
                    });

                    // Annuler
                    cancelBtn.addEventListener('click', () => {
                        reductionCell.innerHTML = `<span class="reduction-text">${reductionText.textContent}</span>`;
                        amountCell.innerHTML = `<span class="amount-text">${amountText.textContent}</span>`;
                        saveBtn.remove();
                        cancelBtn.remove();
                        editBtn.style.display = 'inline-block';
                        deleteBtn.style.display = 'inline-block';
                        row.classList.remove('updating');
                    });
                }
            });
        });

    });


    $(document).ready(function() {
        $('#service_id').select2({
            placeholder: "-- Sélectionner un service --",
            allowClear: true,
            width: '100%' 
        });
    });
</script>
@endsection