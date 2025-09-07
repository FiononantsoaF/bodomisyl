@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Category
@endsection

@section('content')
    <section class="content container-fluid small">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Création ') }} catégories sessions </span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('categorydb.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            <div class="row padding-1 p-1">
                                <div class="col-md-12">
                                    
                                    <div class="form-group mb-2 mb20">
                                        <label for="employee_id" class="form-label">{{ __('Employée') }}</label>
                                        <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror">
                                            <option>-- Choisir employée--</option>
                                            @foreach($employees as $serv)
                                                <option value="{{ $serv->id }}" {{ old('employee_id', $employeesCreneau->employee_id ?? '') == $serv->id ? 'selected' : '' }}>
                                                    {{ $serv->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                        {!! $errors->first('employee_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                    
                                    <div class="form-group mb-2 mb20" id="select-container">
                                        <label for="creneau_id" class="form-label">{{ __('Créneau') }}</label>
                                        
                                        <select name="creneau_id" id="creneau_id" class="form-control @error('creneau_id') is-invalid @enderror">
                                            <option>-- Choisir  créneau--</option>
                                            @foreach($creneaux as $serv)
                                                <option value="{{ $serv->id }}" {{ old('creneau_id', $employeesCreneau->creneau_id ?? '') == $serv->id ? 'selected' : '' }}>
                                                    {{ $serv->creneau }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                        {!! $errors->first('creneau_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                    
                                    <div id="input-container" style="display: none;">
                                        <div class="mb-3">
                                            <label for="creneau_input" class="form-label">Nouveau créneau</label>
                                            <div class="input-group">
                                                <input type="text" name="creneau_new" id="creneau_input" 
                                                        class="form-control time-picker @error('creneau_new') is-invalid @enderror" 
                                                        placeholder="HH:MM" value="{{ old('creneau_new') }}"
                                                        pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]">
                                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                            </div>
                                            <small class="text-muted">Format: HH:MM (ex: 09:30)</small>
                                        </div>
                                    </div>
                                    
                                    <button type="button" id="toggleNewCreneau" class="btn btn-outline-primary btn-sm w-100" onclick="toggleCreneau()">
                                        + Ajouter un nouveau créneau
                                    </button>
                                    
                                    <input type="hidden" name="is_active" class="form-control @error('is_active') is-invalid @enderror" value="{{ old('is_active', $employeesCreneau?->is_active) }}" id="is_active" placeholder="Is Active">

                                </div>
                                <div class="col-md-12 mt20 mt-2">
                                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script>
    function toggleCreneau() {
        console.log("Fonction toggleCreneau appelée");
        
        const selectContainer = document.getElementById("select-container");
        const inputContainer = document.getElementById("input-container");
        const toggleBtn = document.getElementById("toggleNewCreneau");
        
        console.log("selectContainer:", selectContainer);
        console.log("inputContainer:", inputContainer);
        console.log("toggleBtn:", toggleBtn);
        
        if (!selectContainer || !inputContainer || !toggleBtn) {
            console.error("Éléments manquants!");
            return;
        }
        
        const isInputHidden = inputContainer.style.display === "none" || inputContainer.style.display === "";
        if (isInputHidden) {
            selectContainer.style.display = "none";
            inputContainer.style.display = "block";
            toggleBtn.textContent = "← Utiliser un créneau existant";
        } else {
            selectContainer.style.display = "block";
            inputContainer.style.display = "none";
            toggleBtn.textContent = "+ Ajouter un nouveau créneau";
        }
        const creneauSelect = document.getElementById("creneau_id");
        const creneauInput = document.getElementById("creneau_input");
        
        if (creneauSelect && creneauInput) {
            if (isInputHidden) {
                creneauSelect.required = false;
                creneauInput.required = true;
                creneauSelect.name = "";
                creneauInput.name = "creneau_new";
            } else {
                creneauSelect.required = true;
                creneauInput.required = false;
                creneauSelect.name = "creneau_id";
                creneauInput.name = "";
            }
        }
    }
    
    // Attendre que le DOM soit chargé
    document.addEventListener("DOMContentLoaded", function() {
        console.log("DOM chargé - début initialisation");
        
        const toggleBtn = document.getElementById("toggleNewCreneau");
        if (toggleBtn) {
            console.log("Bouton trouvé, ajout du listener");
            toggleBtn.addEventListener("click", toggleCreneau);
        } else {
            console.error("Bouton toggleNewCreneau non trouvé!");
        }
    });
    
    // Fallback avec window.onload
    window.onload = function() {
        console.log("Window.onload - fallback");
        const toggleBtn = document.getElementById("toggleNewCreneau");
        if (toggleBtn && !toggleBtn.onclick) {
            console.log("Ajout du listener via window.onload");
            toggleBtn.addEventListener("click", toggleCreneau);
        }
    };
    </script>
@endsection