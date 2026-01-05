

<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{asset('assets/images/logo-laboratorio.png')}}" alt="50" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{asset('assets/images/logo-laboratorio.png')}}" alt="150" height="150">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{asset('assets/images/logo-laboratorio.png')}}" alt="50" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{asset('assets/images/logo-laboratorio.png')}}" alt="150" height="150">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('home')}}"  aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                    </a>
                </li> <!-- end Dashboard Menu -->

                <li class="menu-title"><i class="ri-more-fill"></i> <span>Gestion</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarApps" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-apps-2-line"></i> <span>Gestion</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarApps">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#sidebarEmail" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail" data-key="t-email">
                                    Usuarios
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarEmail">
                                    <ul class="nav nav-sm flex-column">
                                        {{-- @can('user-ist') --}}
                                        <li class="nav-item">
                                            <a href="{{route('users.index')}}" class="nav-link">Usuarios</a>
                                        </li>
                                         {{-- @endcan --}}
                                        
                                        <li class="nav-item">
                                            <a href="{{route('roles.index')}}" class="nav-link">Roles</a>
                                        </li>
                                       
                                        <li class="nav-item">
                                            <a href="{{route('permisos.index')}}" class="nav-link">Permisos</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarEcommerce" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEcommerce">Personas                                </a>
                                <div class="collapse menu-dropdown" id="sidebarEcommerce">
                                    <ul class="nav nav-sm flex-column">
                                        
                                        <li class="nav-item">
                                            <a href="{{route('pacientes.index')}}" class="nav-link">Pacientes</a>
                                        </li>
                                        
                                        {{-- <li class="nav-item">
                                            <a href="#" class="nav-link">Proveedores</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">Personal</a>
                                        </li> --}}
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarProjects" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProjects">Inventario                                </a>
                                <div class="collapse menu-dropdown" id="sidebarProjects">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{route('analisis.index')}}" class="nav-link">Analisis</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                </li>
                <li class="menu-title"><i class="ri-more-fill"></i> <span>Proformas</span></li>
                 <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarLanding" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLanding">
                        <i class="ri-rocket-line"></i> <span data-key="t-landing">Proforma</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarLanding">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('proformas.index')}}" class="nav-link" data-key="t-one-page"> Nueva Proforma </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('proformas.index')}}" class="nav-link" data-key="t-one-page"> Ver Proformas </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="menu-title"><i class="ri-more-fill"></i> <span>Orden</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                        <i class="ri-account-circle-line"></i> <span>Orden</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAuth">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link">Nueva Orden</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('ordenes.index')}}" class="nav-link">Lista Ordenes</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="menu-title"><i class="ri-more-fill"></i> <span>Resultados</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarUI">
                        <i class="ri-pencil-ruler-2-line"></i> <span>Resultados</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="sidebarUI">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">Nueva</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">Lista de Resultado</a>
                                    </li>
                                   
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>

