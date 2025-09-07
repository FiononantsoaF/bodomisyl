    @if ($message = Session::get('error'))
        <div class="alert alert-danger m-4">
            <p>{{ $message }}</p>
        </div>
    @endif 

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-1">             
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="code_promo" class="form-label fw-bold">{{ __('Libellé Promo') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" name="code_promo" class="form-control @error('code_promo') is-invalid @enderror" 
                                    value="{{ old('code_promo', $promotion?->code_promo) }}" id="code_promo" 
                                    placeholder="Promotion domisyl" required>
                        </div>
                        {!! $errors->first('code_promo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label class="form-label fw-bold">{{ __('Type de Réduction') }} <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="form-check-input" name="discount_type" value="percentage" 
                                    id="discount_percentage" {{ old('discount_type', $promotion?->discount_type ?? 'percentage') == 'percentage' ? 'checked' : '' }}>
                            <label class="col-md-5" for="discount_percentage">
                                <i></i>{{ __('Pourcentage') }}
                            </label>

                            <input type="radio" class="form-check-input" name="discount_type" value="amount" 
                                    id="discount_amount" {{ old('discount_type', $promotion?->discount_type) == 'amount' ? 'checked' : '' }}>
                            <label class="col-md-5" for="discount_amount">
                                <i></i>{{ __('Montant Fixe') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Valeur de la réduction -->
            <div class="row mb-2">
                <div class="col-md-6">
                    <div class="row mb-4">
                    <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="start_promo" class="form-label fw-bold">{{ __('Date de Début') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-play"></i></span>
                            <input type="datetime-local" name="start_promo" class="form-control @error('start_promo') is-invalid @enderror" 
                                    value="{{ old('start_promo', $promotion?->start_promo ? date('Y-m-d\TH:i', strtotime($promotion->start_promo)) : '') }}" 
                                    id="start_promo" required>
                        </div>
                        {!! $errors->first('start_promo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="end_promo" class="form-label fw-bold">{{ __('Date de Fin') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-stop"></i></span>
                            <input type="datetime-local" name="end_promo" class="form-control @error('end_promo') is-invalid @enderror" 
                                    value="{{ old('end_promo', $promotion?->end_promo ? date('Y-m-d\TH:i', strtotime($promotion->end_promo)) : '') }}" 
                                    id="end_promo" required>
                        </div>
                        {!! $errors->first('end_promo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3" id="percentage_field">
                        <label for="pourcent" class="form-label fw-bold">{{ __('Pourcentage (%)') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="pourcent" class="form-control @error('pourcent') is-invalid @enderror" 
                                    value="{{ old('pourcent', $promotion?->pourcent) }}" id="pourcent" 
                                    placeholder="0" min="1" max="100" step="0.01">
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="form-text text-muted">Maximum 100%</small>
                        {!! $errors->first('pourcent', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>

                    <div class="form-group mb-3" id="amount_field" style="display: none;">
                        <label for="amount" class="form-label fw-bold">{{ __('Montant') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                                    value="{{ old('amount', $promotion?->amount) }}" id="amount" 
                                    placeholder="0" min="0" step="0.01">
                            <span class="input-group-text">Ar</span>
                        </div>
                        {!! $errors->first('amount', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>


            <!-- Section 4: Application de la promotion -->
            <div class="row mb-4">
                
                <div class="col-12">
                    <div class="form-group mb-3">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="apply_to" value="subcategory" 
                                    id="apply_subcategory" {{ old('apply_to', 'subcategory') == 'subcategory' ? 'checked' : '' }}>
                            <label class="btn btn-outline-grey btn-custom-height" for="apply_subcategory">
                                <i class="fas fa-layer-group me-2"></i>{{ __('Formule') }}
                            </label>

                            <input type="radio" class="btn-check" name="apply_to" value="services" 
                                    id="apply_services" {{ old('apply_to') == 'services' ? 'checked' : '' }}>
                            <label class="btn btn-outline-grey btn-custom-height" for="apply_services">
                                <i class="fas fa-list-check me-2"></i>{{ __('Prestations') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sélection Formule -->
            <div class="row mb-4" id="subcategory_field">
                <div class="col-md-8">
                    <div class="form-group mb-3">
                        <label for="subcategory_id" class="form-label fw-bold">{{ __('Sélectionnez une Formule') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="subcategory_id" class="form-select @error('subcategory_id') is-invalid @enderror" id="subcategory_id">
                                <option value="">{{ __('Choisir une formule...') }}</option>
                                @if(isset($souscategories))
                                    @foreach($souscategories as $souscategory)
                                        <option value="{{ $souscategory->id }}" 
                                                data-services-count="{{ $souscategory->services->count() }}"
                                                {{ old('subcategory_id', $promotion?->subcategory_id) == $souscategory->id ? 'selected' : '' }}>
                                            {{ $souscategory->name }} ({{ $souscategory->services->count() }} prestations)
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div id="subcategory_info" class="form-text"></div>
                        {!! $errors->first('subcategory_id', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <!-- Sélection Prestations Spécifiques -->
            <div class="row mb-4" id="services_field" style="display: none;">
                <div class="col-12">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">{{ __('Sélectionnez les Prestations') }} <span class="text-danger">*</span></label>
                        <div class="card">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-grey" id="select_all_services">
                                            <i class="fas fa-check-double me-1"></i>{{ __('Tout sélectionner') }}
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" id="deselect_all_services">
                                            <i class="fas fa-times me-1"></i>{{ __('Tout désélectionner') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body small" style="max-height: 100%;">
                                @if(isset($souscategories))
                                    @php
                                        $selectedServices = old('services', 
                                            $promotion ? json_decode($promotion->services, true) : []
                                        );
                                    @endphp
                                    @foreach($souscategories as $souscategory)
                                        @if($souscategory->services->count() > 0)
                                            <div class="mb-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-grey bg-opacity-10 rounded">
                                                    <h6 class="mb-0 text-grey">
                                                        <span class="badge bg-primary">{{ $souscategory->name }}</span>
                                                    </h6>
                                                    <button type="button" class="btn btn-grey btn-sm category-toggle" 
                                                            data-category="{{ $souscategory->id }}">
                                                        <i class="fas fa-check me-1"></i>Tout sélectionner
                                                    </button>
                                                </div>
                                                <div class="row">
                                                    @foreach($souscategory->services as $service)
                                                        <div class="col-md-6 col-lg-4 mb-2">
                                                            <div class="form-check p-2 border rounded hover-highlight d-flex align-items-center justify-content-between">
                                                                
                                                                <!-- Checkbox + Label -->
                                                                <div class="d-flex align-items-center">
                                                                    <input class="form-check-input me-2 service-checkbox" type="checkbox" 
                                                                        name="services[]" value="{{ $service->id }}" 
                                                                        id="service_{{ $service->id }}"
                                                                        data-category="{{ $souscategory->id }}"
                                                                        {{ in_array($service->id, (array)$selectedServices) ? 'checked' : '' }}>
                                                                    
                                                                    <label class="form-check-label mb-0" for="service_{{ $service->id }}">
                                                                        <div class="fw-medium">{{ $service->title }}</div>
                                                                        @if(isset($service->price))
                                                                            <small class="text-success fw-bold">{{ number_format($service->price, 0) }} Ar</small>
                                                                        @endif
                                                                    </label>
                                                                </div>

                                                                <!-- Input montant spécifique (caché tant que la case n’est pas cochée) -->
                                                                <input type="number" 
                                                                    name="valeur_specifiques[{{ $service->id }}]" 
                                                                    class="form-control form-control-sm ms-2 text-end service-value-input"
                                                                    id="value_{{ $service->id }}"
                                                                    min="0" 
                                                                    step="0.01"
                                                                    value="{{ isset($serviceValues[$service->id]) ? $serviceValues[$service->id] : '0' }}"
                                                                    placeholder="Montant (Ar)"
                                                                    style="max-width: 100px; display: none;">
                                                            </div>
                                                        </div>

                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Footer avec bouton de soumission -->
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    {{ __('Vérifiez tous les paramètres avant de sauvegarder') }}
                </small>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>{{ __('Enregistrer la Promotion') }}
                </button>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const discountTypeRadios = document.querySelectorAll('input[name="discount_type"]');
    const percentageField = document.getElementById('percentage_field');
    const amountField = document.getElementById('amount_field');
    const percentageInput = document.getElementById('pourcent');
    const amountInput = document.getElementById('amount');
    const discountInfo = document.getElementById('discount_text');
    
    const applyToRadios = document.querySelectorAll('input[name="apply_to"]');
    const subcategoryField = document.getElementById('subcategory_field');
    const servicesField = document.getElementById('services_field');
    
    const selectAllBtn = document.getElementById('select_all_services');
    const deselectAllBtn = document.getElementById('deselect_all_services');
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const categoryToggleBtns = document.querySelectorAll('.category-toggle');
    const subcategorySelect = document.getElementById('subcategory_id');

    // Initialisation des checkboxes au chargement de la page
    serviceCheckboxes.forEach(cb => {
        const input = document.getElementById('value_' + cb.value);
        if (cb.checked) {
            input.style.display = 'block';
        }

        cb.addEventListener('change', function () {
            const inputField = document.getElementById('value_' + this.value);
            if (this.checked) {
                inputField.style.display = 'block';
            } else {
                inputField.style.display = 'none';
                inputField.value = '0';
            }
        });
    });

    // Gestion du type de réduction
    function toggleDiscountFields() {
        const selectedType = document.querySelector('input[name="discount_type"]:checked').value;
        
        if (selectedType === 'percentage') {
            percentageField.style.display = 'block';
            amountField.style.display = 'none';
            percentageInput.required = true;
            amountInput.required = false;
            amountInput.value = '';
            if (discountInfo) {
                discountInfo.innerHTML = '<i class="fas fa-percent me-2"></i>Réduction en pourcentage du prix original';
            }
        } else {
            percentageField.style.display = 'none';
            amountField.style.display = 'block';
            percentageInput.required = false;
            amountInput.required = true;
            percentageInput.value = '';
            if (discountInfo) {
                discountInfo.innerHTML = '<i class="fas fa-coins me-2"></i>Réduction d\'un montant fixe en Ariary';
            }
        }
    }

    // Gestion de l'application (sous-catégorie vs services)
    function toggleApplyFields() {
        const selectedApply = document.querySelector('input[name="apply_to"]:checked').value;
        
        if (selectedApply === 'subcategory') {
            subcategoryField.style.display = 'block';
            servicesField.style.display = 'none';
            document.getElementById('subcategory_id').required = true;
            serviceCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                const input = document.getElementById('value_' + checkbox.value);
                input.style.display = 'none';
                input.value = '0';
            });
        } else {
            subcategoryField.style.display = 'none';
            servicesField.style.display = 'block';
            document.getElementById('subcategory_id').required = false;
            document.getElementById('subcategory_id').value = '';
        }
    }

    // Event listeners
    discountTypeRadios.forEach(radio => {
        radio.addEventListener('change', toggleDiscountFields);
    });

    applyToRadios.forEach(radio => {
        radio.addEventListener('change', toggleApplyFields);
    });

    // Validation du pourcentage avec feedback visuel
    if (percentageInput) {
        percentageInput.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (value > 100) {
                this.value = 100;
                this.classList.add('border-warning');
            } else if (value < 0) {
                this.value = 0;
                this.classList.add('border-warning');
            } else {
                this.classList.remove('border-warning');
            }
        });
    }

    // Sélection/désélection de tous les services
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            serviceCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                const input = document.getElementById('value_' + checkbox.value);
                if (input) {
                    input.style.display = 'block';
                }
            });
            updateCategoryButtons();
        });
    }

    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            serviceCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                const input = document.getElementById('value_' + checkbox.value);
                if (input) {
                    input.style.display = 'none';
                    input.value = '0';
                }
            });
            updateCategoryButtons();
        });
    }

    // Mise à jour des boutons de catégorie
    function updateCategoryButtons() {
        categoryToggleBtns.forEach(btn => {
            const categoryId = btn.dataset.category;
            const categoryCheckboxes = document.querySelectorAll(`input[data-category="${categoryId}"]`);
            const allChecked = Array.from(categoryCheckboxes).every(cb => cb.checked);
            
            btn.innerHTML = allChecked ? 
                '<i class="fas fa-times me-1"></i>Tout désélectionner' : 
                '<i class="fas fa-check me-1"></i>Tout sélectionner';
        });
    }

    // Sélection par catégorie
    categoryToggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const categoryId = this.dataset.category;
            const categoryCheckboxes = document.querySelectorAll(`input[data-category="${categoryId}"]`);
            const allChecked = Array.from(categoryCheckboxes).every(cb => cb.checked);
            
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
                const input = document.getElementById('value_' + checkbox.value);
                if (input) {
                    if (!allChecked) {
                        // Si on sélectionne tout, afficher l'input
                        input.style.display = 'block';
                    } else {
                        // Si on désélectionne tout, masquer l'input
                        input.style.display = 'none';
                        input.value = '0';
                    }
                }
            });
            
            this.innerHTML = allChecked ? 
                '<i class="fas fa-check me-1"></i>Tout sélectionner' : 
                '<i class="fas fa-times me-1"></i>Tout désélectionner';
        });
    });

    // Information sur la sous-catégorie sélectionnée
    if (subcategorySelect) {
        subcategorySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const infoElement = document.getElementById('subcategory_info');
            
            if (selectedOption.value) {
                const servicesCount = selectedOption.dataset.servicesCount;
                infoElement.innerHTML = `
                    <div class="alert alert-success mt-2 mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Cette formule contient <strong>${servicesCount} prestation(s)</strong> qui recevront la promotion.
                    </div>
                `;
            } else {
                infoElement.innerHTML = '';
            }
        });
    }

    // Validation des dates avec feedback amélioré
    const startDateInput = document.getElementById('start_promo');
    const endDateInput = document.getElementById('end_promo');
    
    if (startDateInput) {
        startDateInput.addEventListener('change', function() {
            if (endDateInput) {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value <= this.value) {
                    endDateInput.value = '';
                    showAlert('La date de fin a été réinitialisée car elle était antérieure à la date de début.', 'warning');
                }
            }
        });
    }

    if (endDateInput) {
        endDateInput.addEventListener('change', function() {
            if (this.value && startDateInput && startDateInput.value && this.value <= startDateInput.value) {
                showAlert('La date de fin doit être postérieure à la date de début.', 'danger');
                this.value = '';
            }
        });
    }

    // Fonction pour afficher les alertes
    function showAlert(message, type = 'info') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const alertContainer = document.querySelector('.card-body');
        if (alertContainer) {
            alertContainer.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) alert.remove();
            }, 5000);
        }
    }

    // Mise à jour des placeholders selon le type de réduction
    function updatePlaceholders() {
        const selectedType = document.querySelector('input[name="discount_type"]:checked').value;
        const valueInputs = document.querySelectorAll('.service-value-input');
        
        valueInputs.forEach(input => {
            if (selectedType === 'percentage') {
                input.placeholder = 'Pourcentage (0 par défaut)';
            } else {
                input.placeholder = 'Montant en Ar (0 par défaut)';
            }
        });
    }
    
    // Mise à jour des placeholders lors du changement de type
    discountTypeRadios.forEach(radio => {
        radio.addEventListener('change', updatePlaceholders);
    });
    
    // Validation des inputs de valeur spécifique
    const valueInputs = document.querySelectorAll('.service-value-input');
    valueInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value === '' || this.value < 0) {
                this.value = '0';
            }
        });
    });
    
    // Initialisation
    updatePlaceholders();
    toggleDiscountFields();
    toggleApplyFields();
});
</script>

<style>
/* Variables de couleur grises */
:root {
    --grey-color:rgb(32, 153, 209);
    --grey-light: #f8f9fa;
    --grey-dark: #495057;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

/* Classes grises */
.bg-grey {
    background-color: var(--grey-color) !important;
}

.text-grey {
    color: var(--grey-color) !important;
}

.btn-grey {
    color: #fff;
    background-color: var(--grey-color);
    border-color: var(--grey-color);
}

.btn-grey:hover {
    color: #fff;
    background-color: var(--grey-dark);
    border-color: var(--grey-dark);
}

.btn-outline-grey {
    color: var(--grey-color);
    border-color: var(--grey-color);
}

.btn-outline-grey:hover {
    color: #fff;
    background-color: var(--grey-color);
    border-color: var(--grey-color);
}

/* Hauteur personnalisée pour les boutons */
.btn-custom-height {
    height: 38px; /* Même hauteur que les form-control */
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-label {
    color: #495057;
    margin-bottom: 0.5rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
    color: #6c757d;
}

.btn-group .btn-check:checked + .btn {
    background-color: var(--grey-color);
    border-color: var(--grey-color);
    color: white;
}

.hover-highlight {
    transition: all 0.2s ease;
}

.hover-highlight:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.bg-opacity-10 {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

.border-bottom {
    border-bottom: 2px solid #dee2e6 !important;
}

.fw-bold {
    font-weight: 600 !important;
}

.fw-medium {
    font-weight: 500 !important;
}

.btn-lg {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    height: auto; /* Reset pour le bouton principal */
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
}

.form-control, .form-select {
    padding: 0.25rem 0.5rem;
    font-size: 0.8125rem;
    height: 38px; /* Hauteur standard */
}

.form-group {
    margin-bottom: 0.75rem !important;
}

.row.mb-4 {
    margin-bottom: 1rem !important;
}

@media (max-width: 500px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-bottom: 0;
    }
    
    .col-md-6, .col-md-8, .col-md-4 {
        margin-bottom: 0.5rem;
    }
}
</style>