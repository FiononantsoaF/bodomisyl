@extends('layouts.app')

@section('template_title')
    Calendrier des Prestataires
@endsection

@section('content')
<div class="container-fluid px-3 py-3">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4">📅 Calendrier des prestataires</h2>

            <!-- Filtrer par prestataire -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <label class="form-label fw-bold">Filtrer par prestataire :</label>
                    <select id="employeeFilter" class="form-select">
                        <option value="">Tous les prestataires</option>
                        @foreach($resources as $resource)
                            <option value="{{ $resource['id'] }}">{{ $resource['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-white p-3 shadow rounded">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">📋 Détails du créneau</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <table class="table">
              <tr>
                  <th>Prestataire</th>
                  <td><strong id="modalEmployee"></strong></td>
              </tr>
              <tr>
                  <th>Créneau</th>
                  <td id="modalTime"></td>
              </tr>
              <tr>
                  <th>Statut</th>
                  <td><span id="modalStatus" class="badge"></span></td>
              </tr>
          </table>
      </div>
    </div>
  </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
    /* Réduire la taille des événements */
    .fc-event {
        cursor: pointer;
        border-radius: 3px;
        font-size: 11px;
        padding: 1px 3px;
    }
    
    /* Réduire la hauteur des créneaux horaires */
    .fc-timegrid-slot {
        height: 25px !important;  /* Réduit de ~40px à 25px */
    }
    
    /* Réduire l'espacement des événements */
    .fc-timegrid-event-harness {
        margin: 0 !important;
    }
    
    .fc-timegrid-event {
        font-size: 10px !important;
        padding: 1px 2px !important;
    }
    
    /* Réduire le texte de l'heure dans l'événement */
    .fc-event-time {
        font-size: 9px !important;
        font-weight: 600;
        padding: 0 2px;
    }
    
    /* Réduire le titre de l'événement */
    .fc-event-title {
        font-size: 10px !important;
        padding: 0 2px;
        line-height: 1.2;
    }
    
    /* Réduire la hauteur de la frame */
    .fc-event-main-frame {
        padding: 1px 2px !important;
    }
    
    /* Réduire les slots de temps sur la gauche */
    .fc-timegrid-axis {
        font-size: 11px !important;
    }
    
    .fc-timegrid-slot-label {
        font-size: 11px !important;
        padding: 2px !important;
    }
    
    @media (max-width: 768px) {
        #calendar { font-size: 10px; }
        .fc-toolbar { flex-wrap: wrap; gap: 10px; }
        .fc-timegrid-slot { height: 20px !important; }
        .fc-event-time { font-size: 8px !important; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const myModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
    const allEvents = @json($events);
    
    // DEBUG: Vérifier les données
    console.log('Events:', allEvents);
    console.log('Number of events:', allEvents.length);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        
        // RÉDUIRE LA TAILLE DES CRÉNEAUX
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        slotDuration: '00:30:00',  // Créneaux de 30 min (au lieu de 1h)
        slotLabelInterval: '01:00', // Afficher les labels toutes les heures
        allDaySlot: false,
        
        // Hauteur compacte
        height: 'auto',
        contentHeight: 400,  // Réduit la hauteur totale
        
        // Espacement réduit
        eventMinHeight: 15,  // Hauteur minimale des événements
        slotEventOverlap: false,  // Pas de chevauchement
        
        events: allEvents,
        
        eventClick: function(info) {
            const event = info.event;
            const props = event.extendedProps;
            
            document.getElementById('modalEmployee').textContent = props.employeeName || 'N/A';
            document.getElementById('modalTime').textContent = 
                new Date(event.start).toLocaleString('fr-FR', {
                    dateStyle: 'full',
                    timeStyle: 'short'
                });
            
            const statusBadge = document.getElementById('modalStatus');
            statusBadge.textContent = props.status;
            statusBadge.className = props.status === 'Actif' 
                ? 'badge bg-success' 
                : 'badge bg-danger';
            
            myModal.show();
        },
        
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour'
        }
    });

    // Filtrage par prestataire
    document.getElementById('employeeFilter').addEventListener('change', function(e) {
        const selectedId = e.target.value;
        
        if (selectedId === '') {
            calendar.getEventSources().forEach(source => source.remove());
            calendar.addEventSource(allEvents);
        } else {
            const filteredEvents = allEvents.filter(event => 
                event.resourceId == selectedId
            );
            calendar.getEventSources().forEach(source => source.remove());
            calendar.addEventSource(filteredEvents);
        }
    });
    calendar.setOption('eventDidMount', function(info) {
        info.el.style.height = '1.5rem';
        info.el.style.width = '3rem';
        info.el.style.borderRadius = '6px';
        info.el.style.display = 'flex';
        info.el.style.justifyContent = 'center';
        info.el.style.alignItems = 'center';
        info.el.style.color = '#d3a321ff';
    });

    calendar.render();
});
</script>

@endsection