
<div class="form-group mb-2 mb20">
    <label for="employee_id" class="form-label">{{ __('Employée') }}</label>
    <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
        <option value="">-- Choisir employée--</option>
        @foreach($employees as $serv)
            <option value="{{ $serv->id }}" {{ old('employee_id', $employeesCreneau->employee_id ?? '') == $serv->id ? 'selected' : '' }}>
                {{ $serv->name }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('employee_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
</div>

{{-- CHOIX DU JOUR --}}
<div class="form-group mb-2 mb20">
    <label for="jour" class="form-label">Jour</label>
    <select name="jour" id="jour" class="form-control @error('jour') is-invalid @enderror" required>
        <option value="">-- Choisir un jour --</option>
        @php
            $jours = [
                1 => 'Lundi',
                2 => 'Mardi',
                3 => 'Mercredi',
                4 => 'Jeudi',
                5 => 'Vendredi',
                6 => 'Samedi',
                7 => 'Dimanche'
            ];
        @endphp
        @foreach($jours as $key => $label)
            <option value="{{ $key }}" {{ old('jour', $employeesCreneau->jour ?? '') == $key ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('jour', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
</div>

{{-- CRÉNEAU EXISTANT --}}
<div class="form-group mb-2 mb20" id="select-container">
    <label for="creneau_id" class="form-label">{{ __('Créneau') }}</label>
    <select name="creneau_id" id="creneau_id" class="form-control @error('creneau_id') is-invalid @enderror">
        <option value="">-- Choisir créneau --</option>
        @foreach($creneaux as $serv)
            <option value="{{ $serv->id }}" {{ old('creneau_id', $employeesCreneau->creneau_id ?? '') == $serv->id ? 'selected' : '' }}>
                {{ $serv->creneau }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('creneau_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
</div>

{{-- NOUVEAU CRÉNEAU --}}
<div id="input-container" style="display: none;">
    <div class="mb-3">
        <label for="creneau_input" class="form-label">Nouveau créneau</label>
        <div class="input-group">
            <input type="time" name="creneau_new" id="creneau_input" 
                   class="form-control time-picker @error('creneau_new') is-invalid @enderror" 
                   placeholder="HH:MM" value="{{ old('creneau_new') }}"
                   pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]">
            <span class="input-group-text"><i class="far fa-clock"></i></span>
        </div>
        <small class="text-muted">Format: HH:MM (ex: 09:30)</small>
    </div>
</div>

{{-- BOUTON D'AJOUT --}}
<button type="button" id="toggleNewCreneau" class="btn btn-outline-primary btn-sm w-100">
    + Ajouter un nouveau créneau
</button>

{{-- Champ caché --}}
<input type="hidden" name="is_active" value="{{ old('is_active', $employeesCreneau?->is_active ?? 1) }}">
<div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Valider') }}</button>
        <a href="{{ route('employees-creneaudb') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium">
            <i class="fas fa-arrow-left me-2"></i> {{ __('Retour') }}
        </a>
</div>
<script>
    document.getElementById('toggleNewCreneau').addEventListener('click', function () {
        const selectContainer = document.getElementById('select-container');
        const inputContainer = document.getElementById('input-container');
        const isHidden = inputContainer.style.display === 'none' || inputContainer.style.display === '';

        if (isHidden) {
            selectContainer.style.display = 'none';
            inputContainer.style.display = 'block';
            this.textContent = '← Utiliser un créneau existant';
        } else {
            selectContainer.style.display = 'block';
            inputContainer.style.display = 'none';
            this.textContent = '+ Ajouter un nouveau créneau';
        }
    });
</script>
