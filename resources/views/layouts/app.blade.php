
<!doctype html >
<html lang="en" data-layout="twocolumn" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none">

<head>
    <base href="{{ url('/') }}/">
    <meta charset="utf-8" />
    <title>@yield('title-head', 'Sistema - Laboratorio')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />



    <link href="{{asset('assets/libs/jsvectormap/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/swiper/swiper.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
       <!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- Layout config Js -->
<script src="{{asset('assets/js/layout.js')}}"></script>
<!-- Bootstrap Css -->
<link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{asset('assets/css/custom.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('css/styles.css')}}">
@vite(['resources/js/app.js'])
</head>

    <body>

<!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
<x-header-component/>
        <!-- ========== App Menu ========== -->
<x-menu-component/>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->

@yield('content')
<!-- end page title -->
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
<x-footer-component/>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

<x-button-component/>


{{-- <div id="global-loader" style="display: none;">
    <div class="loader-content">
        <!-- Reemplaza con la ruta de tu logo -->
        <img src="{{ asset('assets/images/logo-laboratorio.png') }}" alt="Logo" class="loader-logo">
    </div>
</div> --}}




    <!-- JAVASCRIPT -->

{{-- <script src="{{asset('assets/js/app.min.js')}}"></script> --}}
    <script src="{{asset('assets/libs/bootstrap/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/libs/node-waves/node-waves.min.js')}}"></script>
<script src="{{asset('assets/libs/feather-icons/feather-icons.min.js')}}"></script>
<script src="{{asset('assets/js/pages/plugins/lord-icon-2.1.0.min.js')}}"></script>
<script src="{{asset('assets/js/plugins.min.js')}}"></script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="http://localhost/creative2/public/assets/libs/simplebar/simplebar.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{asset('assets/js/pages/datatables.init.js')}}"></script>




<!-- apexcharts -->
<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/libs/jsvectormap/jsvectormap.min.js')}}"></script>
<script src="{{asset('assets/libs/swiper/swiper.min.js')}}"></script>

<!-- dashboard init -->
<script src="{{asset('assets/js/pages/dashboard-ecommerce.init.js')}}"></script>
<script src="{{asset('assets/js/app.min.js')}}"></script>

 <script>
    document.addEventListener("DOMContentLoaded", function() {
        const loader = document.getElementById('global-loader');

        // 1. Interceptar todos los formularios al enviarse
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Si el formulario es válido (checkValidity es nativo de HTML5)
                if (this.checkValidity()) {
                    loader.style.display = 'flex';

                    // Deshabilitar el botón submit para evitar dobles clics
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if(submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerText = 'Procesando...';
                    }
                }
            });
        });

        // 2. (Opcional) Mostrar loader en enlaces de navegación del menú
        // Evita ponerlo en enlaces que solo abren modales (#) o javascript:void(0)
        const links = document.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const target = this.getAttribute('target');

                // Solo activar si es un enlace real interno y no abre en nueva pestaña
                if (href &&
                    !href.startsWith('#') &&
                    !href.startsWith('javascript') &&
                    target !== '_blank') {
                    loader.style.display = 'flex';
                }
            });
        });

        // 3. Ocultar loader si la página se cargó desde el cache (botón atrás del navegador)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                loader.style.display = 'none';
            }
        });
    });

</script>


<!-- ============================================================== -->
<!-- SCRIPT MAESTRO DE NOTIFICACIONES (TOAST + CAMPANA + PERSISTENCIA) -->
<!-- ============================================================== -->
<script type="module">
    document.addEventListener("DOMContentLoaded", function() {
        let contador = {{ \Illuminate\Support\Facades\DB::table('sys_notifications')->where('notifiable_id', auth()->id())->whereNull('read_at')->count() }};
        
        function updateUI() {
            const badge = document.getElementById('notif-badge');
            const textCount = document.getElementById('notif-text-count');
            if(badge) {
                badge.innerText = contador;
                badge.style.display = contador > 0 ? 'block' : 'none';
            }
            if(textCount) textCount.innerText = contador + ' Nuevas';
        }

        function inicializarEcho() {
            if (typeof window.Echo === 'undefined') {
                setTimeout(inicializarEcho, 200);
                return;
            }

            window.Echo.channel('laboratorio-notificaciones')
                .listen('.App\\Events\\ProformaAceptada', (evento) => {
                    
                    // --- INSTANCIA CON PRIORIDAD MÁXIMA ---
                    const NotificacionFlotante = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 8000,
                        timerProgressBar: true,
                        // ESTO ES CLAVE: Ponemos el Toast por encima de todo (incluso del modal de confirmación)
                        customClass: {
                            container: 'my-swal-container'
                        },
                        didOpen: (toast) => {
                            toast.style.cursor = 'pointer';
                            // Aseguramos que el clic funcione ignorando capas superiores
                            toast.onclick = (e) => {
                                e.stopPropagation();
                                window.location.href = `/notificaciones/orden/${evento.proforma_id}`;
                            };
                        }
                    });

                    NotificacionFlotante.fire({
                        icon: 'success',
                        title: '¡Nueva Orden!',
                        html: `<b>#${evento.proforma_id}</b><br>Paciente: ${evento.paciente_nombre}<br><small>(Clic aquí para ver)</small>`
                    });

                    // --- ACTUALIZAR CAMPANA ---
                    contador++;
                    updateUI();

                    const listAll = document.getElementById('notif-list-all');
                    if (listAll) {
                        const container = listAll.querySelector('.simplebar-content') || listAll;
                        let newItem = `
                            <div class="text-reset notification-item d-block dropdown-item border-bottom bg-light animate__animated animate__fadeInDown">
                                <div class="d-flex">
                                    <div class="avatar-xs me-3 flex-shrink-0">
                                        <span class="avatar-title bg-soft-success text-success rounded-circle fs-16"><i class="bx bx-check-double"></i></span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <a href="/notificaciones/orden/${evento.proforma_id}" class="stretched-link">
                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">Orden #${evento.proforma_id}</h6>
                                        </a>
                                        <div class="fs-12 text-muted"><p class="mb-1">Paciente: ${evento.paciente_nombre}</p></div>
                                    </div>
                                </div>
                            </div>`;
                        container.insertAdjacentHTML('afterbegin', newItem);
                        const empty = document.getElementById('notif-empty-state');
                        if(empty) empty.style.display = 'none';
                    }
                });
        }
        inicializarEcho();
    });
</script>

<style>
/* Forzamos que el contenedor de toasts esté por encima de los modales de confirmación */
.my-swal-container {
    z-index: 999999 !important;
}
</style>
 @stack('scripts')

</body>

</html>