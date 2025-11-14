@extends('layouts.app')

@section('title', 'Statistiques des rendez-vous')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Statistiques et graphiques</h3>

    <form method="GET" action="{{ route('stat') }}" class="mb-4 d-flex align-items-center gap-3">
        <label for="year" class="fw-bold">Année :</label>
        <select name="year" id="year" class="form-select w-auto" onchange="this.form.submit()">
            @foreach ($years as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>

    <div class="row">
        <!-- Abonnements -->
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow-sm">
                <h5 class="text-center mb-3">Évolution des abonnements ({{ $year }})</h5>
                <canvas id="subChart" height="200"></canvas>
            </div>
        </div>

        <!-- Rendez-vous -->
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow-sm">
                <h5 class="text-center mb-3">Évolution des rendez-vous ({{ $year }})</h5>
                <canvas id="rdvChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthLabels = ['Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];

    // Données PHP -> JS
    const subsData = @json($subsByMonth);
    const rdvData = @json($rdvByMonth);

    // Initialiser les 12 mois à 0
    const subsArray = Array(12).fill(0);
    const rdvArray = Array(12).fill(0);

    // Remplir les valeurs disponibles
    for (const [month, count] of Object.entries(subsData)) {
        subsArray[month - 1] = count;
    }
    for (const [month, count] of Object.entries(rdvData)) {
        rdvArray[month - 1] = count;
    }

    // === Graph abonnements ===
    new Chart(document.getElementById('subChart'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Abonnements',
                data: subsArray,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderWidth: 1
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    // === Graph rendez-vous ===
    new Chart(document.getElementById('rdvChart'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Rendez-vous',
                data: rdvArray,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.3)',
                tension: 0.3,
                fill: true
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });
</script>
@endsection
