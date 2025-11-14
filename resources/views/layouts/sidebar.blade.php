    <body class="sb-nav-fixed">
        @php
                $user = auth()->user();
        @endphp
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            @if($user->role === 'prestataire')
               <a class="navbar-brand ps-3" href="{{ route('dashboard.prestataire') }}">Domisyl</a>
            @else
               <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">Domisyl</a>
            @endif
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <!--input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button-->
                </div>
            </form>
            <!-- Navbar-->

            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Déconnexion
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading"></div>

                            @if($user->role === 'null' || $user->role === 'admin')
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Tableau de bord
                                </a>
                                <a class="nav-link" href="{{ route('calendar.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                                    Calendrier rendez-vous
                                </a>
                                <!--a class="nav-link" href="{{ route('calendar.prestataire') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                                    Calendrier prestataires
                                </a-->
                                <a class="nav-link" href="{{ route('paymentdb') }}">
                                    <div class="sb-nav-link-icon"><i class="bi bi-credit-card"></i></div>
                                    Suivi des  paiements
                                </a>
                                <!--div class="sb-sidenav-menu-heading">Interface</div-->
                                
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseServices" aria-expanded="false" aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                    Formules et Prestations
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                
                                <div class="collapse @if(isset($activemenuservices)) show @else @endif" id="collapseServices" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="{{ route('service-categorydb') }}">Liste Formules</a>
                                        <a class="nav-link" href="{{ route('servicedb') }}">Liste Prestations</a>
                
                                    </nav>
                                </div>

                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                    Abonnements et Rendez-vous
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse @if(isset($activemenuappoint)) show @else @endif" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="{{ route('subscriptiondb') }}">Liste abonnements</a>
                                        <a class="nav-link" href="{{ route('appointmentsdb') }}">Liste rendez-vous</a>
                                    </nav>
                                </div>
                           
                            <!--a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePrestataire" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Prestataire
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePrestataire" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Liste Prestataire</a>
                                </nav>
                            </div-->
                            <!--a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePrestatairesession" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Session / Préstation 
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            
                            <div class="collapse @if(isset($activemenusession)) show @else @endif" id="collapsePrestatairesession" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('categorydb') }}">Liste categories prestations</a>
                                    <a class="nav-link" href="{{ route('sessiondb') }}">Liste session</a>
                                    <a class="nav-link" href="{{ route('service-session') }}">Liste service prestation</a>
                                    <a class="nav-link" href="{{ route('service-session.create') }}">Associer session au prestation</a>
                                </nav>
                            </div-->

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseclients" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Gestion Clients 
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse @if(isset($menuclient)) show @else @endif" id="collapseclients" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('clientdb') }}">Liste Clients</a>  
                                    <!--a class="nav-link" href="{{ route('currencydb') }}">Devise</a--> 
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsemployee" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Gestion  Employés & Créneaux & Emplois
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse @if(isset($menuemployee)) show @else @endif" id="collapsemployee" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">  
                                    <a class="nav-link" href="{{ route('employeedb') }}">Liste employés</a> 
                                    <a class="nav-link" href="{{ route('jobdb') }}">Liste emplois</a>    

                                    <!--a class="nav-link" href="{{ route('creneaudb') }}">Liste créneaux</a-->
                                    <a class="nav-link" href="{{ route('employees-creneaudb') }}">Employés & Créneaux</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapspromo" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Gestion  des promotions
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse @if(isset($promo)) show @else @endif" id="collapspromo" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">  
                                    <a class="nav-link" href="{{ route('promotiondb') }}">Promotions</a>    
                                </nav>
                            </div>

                            <!--a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapscarte" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Carte cadeau
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse @if(isset($activeccs)) show @else @endif" id="collapscarte" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('cartecadeauclientdb') }}">Liste des cartes cadeaux</a>
                                    <a class="nav-link" href="{{ route('cartecadeauservicedb') }}">Liste prestations en carte cadeau</a>       
                                </nav>
                            </div-->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseutilisateur" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Utilisateur Backoffice
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse @if(isset($menuuser)) show @else @endif" id="collapseutilisateur" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('userdb') }}">Liste utilisateurs</a>       
                                </nav>
                            </div>

                            <!--a class="nav-link" href="{{ route('stat') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                                 Statistiques des rendez-vous
                            </a>

                            <a class="nav-link" href="{{ route('testimonialdb') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                                 Temoignage client
                            </a -->
                             @elseif($user->role === 'prestataire')
                                <a class="nav-link" href="{{ route('dashboard.prestataire') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Mes rendez-vous
                                </a>
                                <a class="nav-link" href="{{ route('calendar.prestataire') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                                    Calendrier rendez-vous
                                </a>
                             @endif
                        </div>
                    </div>
                </nav>
            </div>