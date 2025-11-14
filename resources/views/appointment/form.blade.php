
        <div class="row p-3">
            <div class="col-md-12">
                <!-- Client -->
                <div class="col-md-12 col-sm-6 mb-3">
                    <label for="client_id" class="form-label fw-bold">{{ __('Client') }}</label>
                    <input type="hidden" name="client_id" value="{{ old('client_id', $client->id) }}">
                    <input type="text" class="form-control bg-light" value="{{ $client->name }}" readonly>
                    @error('client_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row align-items-end mb-3 g-3">
                    <div class="col-md-4 col-sm-6">
                        <label for="category_id" class="form-label fw-bold">{{ __('Formule') }}</label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- Sélectionner une formule --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">Formule obligatoire</div>
                        @enderror
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <label for="service_id" class="form-label fw-bold">{{ __('Prestation') }}</label>
                        <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" {{ old('category_id') ? '' : 'disabled' }} require>
                            <option value="">-- Sélectionner une prestation --</option>
                            @if(old('service_id'))
                            @endif
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">Prestation obligatoire</div>
                        @enderror
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label for="employee_id" class="form-label fw-bold">{{ __('Prestataire') }}</label>
                        <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" {{ old('service_id') ? '' : 'disabled' }} require>
                            <option value="">-- Sélectionner un prestataire --</option>
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">Prestataire obligatoire</div>
                        @enderror
                    </div>
                </div>

                <div class="row align-items-end mb-3 g-3">
                    <div class="col-md-4 col-sm-6">
                        <label for="appointment_date" class="form-label fw-bold">{{ __('Date') }}</label>
                        <input type="date" name="appointment_date" id="appointment_date" 
                            class="form-control @error('appointment_date') is-invalid @enderror" 
                            value="{{ old('appointment_date') }}"
                            min="{{ date('Y-m-d') }}">
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Créneau - Version boutons -->
                    <div class="col-md-8 col-sm-6">
                        <label class="form-label fw-bold">{{ __('Créneaux disponibles') }}</label>
                        <input type="hidden" name="creneau_id" id="creneau_id" value="{{ old('creneau_id') }}" require>
                        <div id="creneaux-container" class="d-flex flex-wrap gap-1">
                            <div class="text-muted">Sélectionnez d'abord un prestataire et une date</div>
                        </div>
                        @error('creneau_id')
                            <div class="invalid-feedback d-block"></div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-12 col-sm-6">
                        <label for="comment" class="form-label fw-bold">{{ __('Commentaire') }}</label>
                        <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror" rows="3" placeholder="Commentaire optionnel...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="col-md-12 mt-4">
                <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                    <i class="fas fa-check me-2"></i>{{ __('Valider') }}
                </button>
            </div>
    </div>    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const serviceSelect = document.getElementById('service_id');
            const employeeSelect = document.getElementById('employee_id');
            const dateInput = document.getElementById('appointment_date');
            const creneauInput = document.getElementById('creneau_id');
            const creneauxContainer = document.getElementById('creneaux-container');

            let selectedCreneauBtn = null;

            // Gestion des erreurs de validation côté client
            function clearErrors(select) {
                select.classList.remove('is-invalid');
            }

            // Fonction pour sélectionner un créneau
            function selectCreneau(creneauId, button) {
                // Désélectionner le bouton précédent
                if (selectedCreneauBtn) {
                    selectedCreneauBtn.classList.remove('btn-primary');
                    selectedCreneauBtn.classList.add('btn-outline-primary');
                }
                
                // Sélectionner le nouveau bouton
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-primary');
                selectedCreneauBtn = button;
                
                // Mettre à jour la valeur cachée
                creneauInput.value = creneauId;
                
                // Effacer les erreurs
                creneauInput.classList.remove('is-invalid');
            }

            // Chargement des services par catégorie
            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;
                clearErrors(categorySelect);
                
                if (!categoryId) {
                    serviceSelect.innerHTML = '<option value="">-- Sélectionner un service --</option>';
                    serviceSelect.disabled = true;
                    employeeSelect.innerHTML = '<option value="">-- Sélectionner un prestataire --</option>';
                    employeeSelect.disabled = true;
                    resetCreneaux();
                    return;
                }

                serviceSelect.innerHTML = '<option value="">Chargement...</option>';
                serviceSelect.disabled = false;

                fetch(`/services-by-category/${categoryId}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Erreur réseau');
                        return res.json();
                    })
                    .then(data => {
                        serviceSelect.innerHTML = '<option value="">-- Sélectionner un service --</option>';
                        data.forEach(s => {
                            serviceSelect.innerHTML += `<option value="${s.id}" ${s.id == '{{ old('service_id') }}' ? 'selected' : ''}>${s.title}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        serviceSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            });

            // Chargement des employés par service
            serviceSelect.addEventListener('change', function() {
                const serviceId = this.value;
                clearErrors(serviceSelect);
                
                if (!serviceId) {
                    employeeSelect.innerHTML = '<option value="">-- Sélectionner un prestataire --</option>';
                    employeeSelect.disabled = true;
                    resetCreneaux();
                    return;
                }

                employeeSelect.innerHTML = '<option value="">Chargement...</option>';
                employeeSelect.disabled = false;

                fetch(`/employees-by-service/${serviceId}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Erreur réseau');
                        return res.json();
                    })
                    .then(data => {
                        employeeSelect.innerHTML = '<option value="">-- Sélectionner un prestataire --</option>';
                        data.forEach(e => {
                            employeeSelect.innerHTML += `<option value="${e.id}">${e.name}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        employeeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            });

            // Réinitialiser l'affichage des créneaux
            function resetCreneaux() {
                creneauxContainer.innerHTML = '<div class="text-muted">Sélectionnez d\'abord un prestataire et une date</div>';
                creneauInput.value = '';
                selectedCreneauBtn = null;
            }

            // Chargement des créneaux en boutons
            function loadCreneaux() {
                const employeeId = employeeSelect.value;
                const date = dateInput.value;
                
                clearErrors(employeeSelect);
                clearErrors(dateInput);
                
                if(!employeeId || !date) {
                    resetCreneaux();
                    return;
                }

                creneauxContainer.innerHTML = '<div class="text-muted">Chargement des créneaux...</div>';

                fetch(`/creneaux-by-employee/${employeeId}?date=${date}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Erreur réseau');
                        return res.json();
                    })
                    .then(data => {
                        creneauxContainer.innerHTML = '';
                        
                        if (data.length === 0) {
                            creneauxContainer.innerHTML = '<div class="text-warning">Aucun créneau disponible pour cette date</div>';
                            creneauInput.value = '';
                            return;
                        }

                        data.forEach(c => {
                            const isSelected = c.id == creneauInput.value;
                            const button = document.createElement('button');
                            button.type = 'button';
                            button.className = `btn ${isSelected ? 'btn-primary' : 'btn-outline-primary'} creneau-btn`;
                            button.innerHTML = `
                                <i class="fas fa-clock me-1"></i>
                                ${c.creneau}
                            `;
                            button.dataset.creneauId = c.id;
                            
                            button.addEventListener('click', function() {
                                selectCreneau(c.id, this);
                            });
                            
                            creneauxContainer.appendChild(button);
                            
                            if (isSelected) {
                                selectedCreneauBtn = button;
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        creneauxContainer.innerHTML = '<div class="text-danger">Erreur lors du chargement des créneaux</div>';
                    });
            }

            employeeSelect.addEventListener('change', loadCreneaux);
            dateInput.addEventListener('change', loadCreneaux);

            @if(old('category_id'))
                categorySelect.dispatchEvent(new Event('change'));
            @endif
            
            @if(old('creneau_id'))
                setTimeout(loadCreneaux, 100);
            @endif
        });
    </script>

    <style>
        .creneau-btn {
            min-width: 8rem; /* 128px */
            transition: all 0.2s ease;
        }
        .creneau-btn:hover {
            transform: translateY(-0.1rem); /* ≈ -1.6px */
        }
        .creneaux-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 colonnes fixes */
            gap: 0.5rem;
            max-height: 8rem;
            overflow-y: auto;
            padding: 0.5rem;
            border: 1px solid #e9ecef;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
        }

        .creneau-btn {
            min-width: auto; /* S'adapte à la colonne */
            padding: 0.4rem 0.25rem;
            font-size: 0.75rem;
            transition: all 0.2s ease;
            white-space: nowrap;
            text-align: center;
        }
    </style>