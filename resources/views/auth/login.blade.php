<!doctype html>
<html lang="en" data-layout="twocolumn" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none">
<head>
    <meta charset="utf-8" />
    <title>@yield('title-head', 'Sistema - Laboratorio')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <script src="{{ asset('assets/js/layout.js') }}"></script>

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>

        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="index" class="d-block">
                                                    <img src="{{ asset('assets/images/logo-laboratorio.png') }}" alt="" height="150">
                                                </a>
                                            </div>

                                            <div class="mt-auto">
                                                <div class="mb-3">
                                                    <i class="ri-double-quotes-l display-4 text-success"></i>
                                                </div>

                                                <div id="qoutescarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                    </div>

                                                    <div class="carousel-inner text-center text-white-50 pb-5">
                                                        <div class="carousel-item active">
                                                            <p class="fs-15 fst-italic">" Resultados precisos, confianza garantizada. "</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">" Tecnología avanzada para diagnósticos confiables. "</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">" Profesionales comprometidos con tu salud. "</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">" Cada muestra cuenta, cada resultado importa. "</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">" Calidad diagnóstica que marca la diferencia. "</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end carousel -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-primary">Bienvenido - Sistema de Laboratorio!</h5>
                                            <p class="text-muted">Iniciar Sesion.</p>
                                        </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                        <div class="mt-4">
                                            {{-- Mostrar errores normales en Bootstrap, pero NO mostrar el bloque si el error es "inactive" --}}
                                           @if ($errors->has('inactive'))
<script>
  document.addEventListener('DOMContentLoaded', function () {
    console.log('Swal type:', typeof Swal);
    Swal.fire({
      icon: 'error',
      title: 'Acceso denegado',
      text: @json($errors->first('inactive')),
      confirmButtonText: 'Entendido'
    });
  });
</script>
@endif
    @if ($errors->has('email'))
<script>
document.addEventListener('DOMContentLoaded', function () {
  Swal.fire({
    icon: 'error',
    title: 'Datos incorrectos',
    text: @json($errors->first('email')), // normalmente: "These credentials do not match our records."
    confirmButtonText: 'Entendido'
  });
});
</script>
@endif                                    </div>
{{-- DEBUG temporal --}}
{{-- <pre style="background:#111;color:#0f0;padding:10px;">
{{ print_r($errors->toArray(), true) }}
</pre> --}}
                                        <div class="mt-4">
                                            <form action="{{ route('login') }}" method="post">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Email</label>
                                                    <input
                                                        type="email"
                                                        class="form-control"
                                                        id="username"
                                                        name="email"
                                                        value="{{ old('email') }}"
                                                        placeholder="Introducir Correo Electronico"
                                                    >
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="password-input">Contraseña</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input
                                                            type="password"
                                                            class="form-control pe-5"
                                                            placeholder="Introducir Contraseña"
                                                            id="password-input"
                                                            name="password"
                                                        >
                                                        <button
                                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                            type="button"
                                                            id="password-addon"
                                                        >
                                                            <i class="ri-eye-fill align-middle"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <button class="btn btn-success w-100" type="submit">Ingresar</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/node-waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather-icons.min.js') }}"></script>
    <script src="{{asset('assets/js/pages/plugins/lord-icon-2.1.0.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins.min.js')}}"></script>
    <script src="{{asset('assets/libs/particles.js/particles.js.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/particles.app.js')}}"></script>
    <script src="{{asset('assets/js/pages/password-addon.init.')}}"></script>
<!-- Script para mostrar/ocultar contraseña -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleButton = document.getElementById("password-addon");
        const passwordInput = document.getElementById("password-input");
        
        if (toggleButton && passwordInput) {
            toggleButton.addEventListener("click", function() {
                // Alternar tipo de input
                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);
                
                // Alternar icono (Ojo abierto / Ojo tachado)
                const icon = toggleButton.querySelector("i");
                if (icon) {
                    icon.classList.toggle("ri-eye-fill");
                    icon.classList.toggle("ri-eye-off-fill");
                }
            });
        }
    });
</script>
</body>

    </body>
</html>
