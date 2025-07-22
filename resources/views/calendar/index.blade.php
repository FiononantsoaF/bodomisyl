@extends('layouts.app')

@section('template_title')
    Calendrier des Rendez-vous
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="container px-4 py-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <h2 class="mb-4 fw-bold">📅 Calendrier des Rendez-vous</h2>

            <div class="bg-white p-3 shadow rounded">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white" style="background-color:rgb(252, 171, 31)">
        <h5 class="modal-title" id="appointmentModalLabel">🗓️ Détails du Rendez-vous</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row" class="col-md-3">Client</th>
                            <td class="col-md-9"><span id="modalClient"></span></td>
                        </tr>
                        <tr>
                            <th scope="row">Prestataire</th>
                            <td><span id="modalEmployee"></span></td>
                        </tr>
                        <tr>
                            <th scope="row">Service</th>
                            <td><span id="modalService"></span></td>
                        </tr>
                        <tr>
                            <th scope="row">Statut</th>
                            <td><span id="modalStatus"></span></td>
                        </tr>
                        <tr>
                            <th scope="row">Date</th>
                            <td><span id="modalDate"></span></td>
                        </tr>
                        <tr>
                            <th scope="row">Heure</th>
                            <td><span id="modalTime"></span></td>
                        </tr>
                        <tr>
                            <th scope="row">Notes</th>
                            <td><span id="modalNotes"></span></td>
                        </tr>
                </tbody>
                </table>
            </div>
      </div>
      <!-- <div class="modal-body">
        <p><strong>Client:</strong> <span id="modalClient"></span></p>
        <p><strong>Prestataire:</strong> <span id="modalEmployee"></span></p>
        <p><strong>Service:</strong> <span id="modalService"></span></p>
        <p><strong>Statut:</strong> <span id="modalStatus"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Heure:</strong> <span id="modalTime"></span></p>
        <p><strong>Notes:</strong> <span id="modalNotes"></span></p>
      </div> -->
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- FullCalendar & Bootstrap JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        firstDay: 1,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: "Aujourd'hui",
            day: 'Jour',
            week:'Semaine',
            month:'Mois',
            list:'Liste'
        },
        events: @json($appointments),
        eventClick: function (info) {
            const event = info.event;
            const props = event.extendedProps;

            document.getElementById('modalClient').textContent = props.client || 'N/A';
            document.getElementById('modalEmployee').textContent = props.employee || 'N/A';
            document.getElementById('modalService').textContent = props.service || 'N/A';
            document.getElementById('modalStatus').textContent = 
                    props.status === 'pending' ? 'En attente':
                    status === 'confirmed' ? 'Confirmé' : 
                    (props.status || 'N/A');

            const start = new Date(event.start);
            const end = new Date(event.end);

            console.log(end);

            document.getElementById('modalDate').textContent = start.toLocaleDateString('fr-FR');
            document.getElementById('modalTime').textContent =
                start.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) + ' - ' +
                end.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

            document.getElementById('modalNotes').textContent = props.notes || 'Aucune note';

            const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
            modal.show();
        },
        eventDidMount: function (info) {
            info.el.style.backgroundColor = '#facc15';
            info.el.style.color = '#000'; 
            info.el.style.fontWeight = 'bold';
            info.el.style.borderRadius = '4px';
            info.el.style.padding = '2px 4px';
            info.el.title = info.event.title;
        }
    });

    calendar.render();
});
</script>
@endsection
