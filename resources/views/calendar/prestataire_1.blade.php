@extends('layouts.app')

@section('template_title')
    Calendrier des Prestataires - Toast UI
@endsection

@section('content')
<div class="container-fluid px-3 py-3">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4">📅 Calendrier des prestataires</h2>

            <!-- Filtres et actions -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Filtre prestataire -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Filtrer par prestataire :</label>
                            <select id="employeeFilter" class="form-select">
                                <option value="">Tous les prestataires</option>
                                @foreach($resources as $resource)
                                    <option value="{{ $resource['id'] }}">{{ $resource['title'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtre par statut -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Filtrer par statut :</label>
                            <select id="statusFilter" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="Actif">Actif</option>
                                <option value="Inactif">Inactif</option>
                                <option value="Réservé">Réservé</option>
                            </select>
                        </div>

                        <!-- Vue -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Vue :</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-primary" id="btnMonth">Mois</button>
                                <button type="button" class="btn btn-outline-primary active" id="btnWeek">Semaine</button>
                                <button type="button" class="btn btn-outline-primary" id="btnDay">Jour</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation calendrier -->
            <div class="card shadow mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <button class="btn btn-primary" id="btnPrev">
                        <i class="bi bi-chevron-left"></i> Précédent
                    </button>
                    <h4 class="mb-0" id="calendarTitle">Chargement...</h4>
                    <div>
                        <button class="btn btn-outline-primary me-2" id="btnToday">Aujourd'hui</button>
                        <button class="btn btn-primary" id="btnNext">
                            Suivant <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendrier -->
            <div class="bg-white p-3 shadow rounded">
                <div id="calendar" style="height: 800px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal détails -->
<div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">📋 Détails du créneau</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Prestataire :</th>
                        <td><strong id="modalEmployee"></strong></td>
                    </tr>
                    <tr>
                        <th>Date :</th>
                        <td id="modalDate"></td>
                    </tr>
                    <tr>
                        <th>Horaire :</th>
                        <td id="modalTime"></td>
                    </tr>
                    <tr>
                        <th>Durée :</th>
                        <td id="modalDuration"></td>
                    </tr>
                    <tr>
                        <th>Statut :</th>
                        <td><span id="modalStatus" class="badge"></span></td>
                    </tr>
                </table>
                <div id="modalActions" class="mt-3 d-flex gap-2">
                    <button class="btn btn-success btn-sm">✓ Confirmer</button>
                    <button class="btn btn-warning btn-sm">✎ Modifier</button>
                    <button class="btn btn-danger btn-sm">✕ Annuler</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast UI Calendar CSS -->
<link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    /* Personnalisation Toast UI */
    .toastui-calendar-week-view-day-names {
        background-color: #f8f9fa;
        border-bottom: 2px solid #0d6efd;
    }

    /* Couleurs par statut */
    .status-actif {
        background-color: #198754 !important;
        border-color: #146c43 !important;
        color: white !important;
    }

    .status-inactif {
        background-color: #6c757d !important;
        border-color: #5c636a !important;
        color: white !important;
    }

    .status-reserve {
        background-color: #ffc107 !important;
        border-color: #ffca2c !important;
        color: #000 !important;
    }

    /* Style des événements */
    .toastui-calendar-template-time {
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #calendar {
            height: 600px !important;
        }
        .toastui-calendar-template-time {
            font-size: 11px;
        }
    }

    /* Amélioration visuelle */
    .toastui-calendar-event {
        border-radius: 4px;
        padding: 2px 4px;
    }

    .toastui-calendar-weekday-grid-line {
        border-right: 1px solid #e5e5e5;
    }
</style>

<!-- Toast UI Calendar JS -->
<script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>

<script>
// Variables globales
let calendar;
let allEvents = @json($events);
let myModal;

// Préparer les calendriers (catégories)
const calendars = [
    {
        id: 'actif',
        name: 'Créneaux Actifs',
        backgroundColor: '#198754',
        borderColor: '#146c43',
        dragBackgroundColor: '#198754'
    },
    {
        id: 'inactif',
        name: 'Créneaux Inactifs',
        backgroundColor: '#6c757d',
        borderColor: '#5c636a',
        dragBackgroundColor: '#6c757d'
    },
    {
        id: 'reserve',
        name: 'Créneaux Réservés',
        backgroundColor: '#ffc107',
        borderColor: '#ffca2c',
        dragBackgroundColor: '#ffc107'
    }
];

// Convertir les événements au format Toast UI
function convertEvents(events) {
    return events.map(event => {
        const statusMap = {
            'Actif': 'actif',
            'Inactif': 'inactif',
            'Réservé': 'reserve'
        };
        
        return {
            id: event.id || Math.random().toString(36).substr(2, 9),
            calendarId: statusMap[event.status] || 'actif',
            title: event.title,
            start: event.start,
            end: event.end,
            backgroundColor: event.backgroundColor,
            borderColor: event.borderColor,
            color: event.color || '#fff',
            raw: {
                employeeName: event.employeeName,
                resourceId: event.resourceId,
                status: event.status
            }
        };
    });
}

// Changer la vue
function changeView(viewName) {
    if (calendar) {
        calendar.changeView(viewName);
        updateCalendarTitle();
        
        // Mettre à jour les boutons actifs
        document.querySelectorAll('.btn-group button').forEach(btn => {
            btn.classList.remove('active');
        });
        
        if (viewName === 'month') {
            document.getElementById('btnMonth').classList.add('active');
        } else if (viewName === 'week') {
            document.getElementById('btnWeek').classList.add('active');
        } else if (viewName === 'day') {
            document.getElementById('btnDay').classList.add('active');
        }
    }
}

// Mettre à jour le titre
function updateCalendarTitle() {
    if (!calendar) return;
    
    const title = document.getElementById('calendarTitle');
    const date = calendar.getDate();
    const view = calendar.getViewName();
    
    const options = { year: 'numeric', month: 'long' };
    if (view === 'day') {
        options.day = 'numeric';
    }
    
    title.textContent = date.toLocaleDateString('fr-FR', options);
}

// Afficher les détails d'un événement
function showEventDetails(event) {
    document.getElementById('modalEmployee').textContent = event.raw?.employeeName || 'N/A';
    
    const start = new Date(event.start);
    const end = new Date(event.end);
    
    document.getElementById('modalDate').textContent = start.toLocaleDateString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    document.getElementById('modalTime').textContent = 
        `${start.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })} - ${end.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}`;
    
    const duration = Math.round((end - start) / (1000 * 60));
    document.getElementById('modalDuration').textContent = `${duration} minutes`;
    
    const statusBadge = document.getElementById('modalStatus');
    const status = event.raw?.status || 'Actif';
    statusBadge.textContent = status;
    
    const badgeClass = {
        'Actif': 'bg-success',
        'Inactif': 'bg-secondary',
        'Réservé': 'bg-warning text-dark'
    };
    statusBadge.className = `badge ${badgeClass[status] || 'bg-primary'}`;
    
    if (myModal) {
        myModal.show();
    }
}

// Filtrer les événements
function filterEvents(employeeId, status) {
    let filtered = allEvents;
    
    if (employeeId) {
        filtered = filtered.filter(event => event.resourceId == employeeId);
    }
    
    if (status) {
        filtered = filtered.filter(event => event.status === status);
    }
    
    if (calendar) {
        calendar.clear();
        calendar.createEvents(convertEvents(filtered));
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function () {
    console.log('Initialisation du calendrier...');
    
    // Vérifier si Bootstrap est disponible
    if (typeof bootstrap !== 'undefined') {
        myModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
    } else {
        console.warn('Bootstrap non disponible, le modal ne fonctionnera pas');
    }
    
    const calendarEl = document.getElementById('calendar');
    
    if (!calendarEl) {
        console.error('Élément calendar non trouvé');
        return;
    }

    try {
        // Initialiser le calendrier
        calendar = new toastui.Calendar(calendarEl, {
            defaultView: 'week',
            isReadOnly: false,
            useCreationPopup: false,
            useDetailPopup: false,
            calendars: calendars,
            
            week: {
                startDayOfWeek: 1,
                dayNames: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                narrowWeekend: true,
                workweek: false,
                hourStart: 8,
                hourEnd: 20,
                eventView: ['time'],
                taskView: false
            },
            
            month: {
                dayNames: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                startDayOfWeek: 1,
                narrowWeekend: true
            },
            
            timezone: {
                zones: [
                    {
                        timezoneName: 'Europe/Paris',
                        displayLabel: 'Paris'
                    }
                ]
            },
            
            template: {
                time(event) {
                    return `<div style="padding: 2px 4px;">
                        <strong>${event.title}</strong><br>
                        <small>${event.raw?.employeeName || ''}</small>
                    </div>`;
                },
                
                allday(event) {
                    return `<span style="color: #fff;">${event.title}</span>`;
                }
            },
            
            theme: {
                common: {
                    border: '1px solid #e5e5e5',
                    backgroundColor: 'white',
                    holiday: {
                        color: '#ff4040'
                    },
                    saturday: {
                        color: '#333'
                    },
                    dayName: {
                        color: '#333'
                    },
                    today: {
                        color: '#fff'
                    }
                },
                week: {
                    today: {
                        color: '#fff',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)'
                    },
                    pastTime: {
                        color: '#999'
                    },
                    futureTime: {
                        color: '#333'
                    },
                    nowIndicatorLabel: {
                        color: '#0d6efd'
                    },
                    nowIndicatorPast: {
                        border: '1px dashed #0d6efd'
                    },
                    nowIndicatorBullet: {
                        backgroundColor: '#0d6efd'
                    },
                    nowIndicatorToday: {
                        border: '1px solid #0d6efd'
                    },
                    nowIndicatorFuture: {
                        border: 'none'
                    }
                }
            }
        });

        console.log('Calendrier initialisé avec succès');

        // Charger les événements
        calendar.createEvents(convertEvents(allEvents));
        updateCalendarTitle();

        // Événement : clic sur un créneau
        calendar.on('clickEvent', ({ event }) => {
            showEventDetails(event);
        });

        // Événement : glisser-déposer
        calendar.on('beforeUpdateEvent', ({ event, changes }) => {
            calendar.updateEvent(event.id, event.calendarId, changes);
            
            console.log('Événement modifié:', {
                id: event.id,
                newStart: changes.start,
                newEnd: changes.end
            });
        });

        // Boutons de navigation
        document.getElementById('btnPrev').addEventListener('click', () => {
            calendar.prev();
            updateCalendarTitle();
        });

        document.getElementById('btnNext').addEventListener('click', () => {
            calendar.next();
            updateCalendarTitle();
        });

        document.getElementById('btnToday').addEventListener('click', () => {
            calendar.today();
            updateCalendarTitle();
        });

        // Boutons de vue
        document.getElementById('btnMonth').addEventListener('click', () => changeView('month'));
        document.getElementById('btnWeek').addEventListener('click', () => changeView('week'));
        document.getElementById('btnDay').addEventListener('click', () => changeView('day'));

        // Filtres
        document.getElementById('employeeFilter').addEventListener('change', function(e) {
            const selectedId = e.target.value;
            const statusFilter = document.getElementById('statusFilter').value;
            filterEvents(selectedId, statusFilter);
        });

        document.getElementById('statusFilter').addEventListener('change', function(e) {
            const employeeId = document.getElementById('employeeFilter').value;
            const selectedStatus = e.target.value;
            filterEvents(employeeId, selectedStatus);
        });

    } catch (error) {
        console.error('Erreur lors de l\'initialisation du calendrier:', error);
        document.getElementById('calendar').innerHTML = '<div class="alert alert-danger">Erreur lors du chargement du calendrier. Vérifiez la console pour plus de détails.</div>';
    }
});
</script>

@endsection