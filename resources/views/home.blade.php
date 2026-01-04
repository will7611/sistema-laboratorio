@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Sistema - Laboratorio</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                            <li class="breadcrumb-item active">App</li>
                                    </ol>
            </div>
            
        </div>
    </div>
</div>

<div class="row">
    <div class="col">

        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            @php
                                $hour = now()->format('H'); // Hora en formato 24h
                                if ($hour >= 5 && $hour < 12) {
                                    $greeting = 'Buen día';
                                } elseif ($hour >= 12 && $hour < 18) {
                                    $greeting = 'Buenas tardes';
                                } else {
                                    $greeting = 'Buenas noches';
                                }
                            @endphp
                               
                            <h4 class="fs-16 mb-1">{{$greeting}}, {{ auth()->user()->name}}!</h4>
                            {{-- <p class="text-muted mb-0">Here's what's happening with your store
                                today.</p> --}}
                        </div>
                        <div class="mt-3 mt-lg-0">
                            <form action="javascript:void(0);">
                                <div class="row g-3 mb-0 align-items-center">
                                    <div class="col-sm-auto">
                                        <div class="input-group">
                                            <input type="text"
                                                id="fecha"
                                                class="form-control border-0 dash-filter-picker shadow"
                                                data-provider="flatpickr" 
                                                data-range-date="true"
                                                data-date-format="d M, Y"
                                                data-deafult-date="{{ date('d M, Y') }} to {{ date('d M, Y') }}"
                                                data-locale="es"
                                                />
                                            <div
                                                class="input-group-text bg-primary border-primary text-white">
                                                <i class="ri-calendar-2-line"></i></div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-auto">
                                        <a href="{{route('proformas.create')}}" class="btn btn-soft-secondary"><i
                                                class="ri-add-circle-line align-middle me-1"></i>
                                            Nueva Proforma</a>
                                    </div>
                                    <!--end col-->
                                    <div class="col-auto">
                                        <button type="button"
                                            class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn"><i
                                                class="ri-pulse-line"></i></button>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate bg-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p
                                        class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                        Total Usuarios</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-white fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$totalUsers}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4"><span
                                            class="counter-value" data-target="{{$totalUsers}}">0</span>
                                    </h4>
                                    <a href="{{route('users.index')}}" class="text-decoration-underline text-white-50">Ver Usuarios</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                        <i class="bx bx-user-circle text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate bg-secondary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p
                                        class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                        Pacientes</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-white fs-14 mb-0">
                                        <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                       
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4"><span
                                            class="counter-value" data-target="{{$totalPaciente}}">{{$totalPaciente}}</span></h4>
                                    <a href="{{route('pacientes.index')}}" class="text-decoration-underline text-white-50">Ver Pacientes</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                        <i class="bx bx-shopping-bag text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate bg-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p
                                        class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                PRoformas</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-white fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                                                       </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4"><span
                                            class="counter-value" data-target="   ">0  </span>
                                    </h4>
                                    <a href="" class="text-decoration-underline text-white-50">Ver Productos</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                        <i class="bx bx-user-circle text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate bg-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p
                                        class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                        Ordenes</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-white fs-14 mb-0">
                                       {{$totalPaciente}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">$<span
                                            class="counter-value" data-target="{{$totalPaciente}}">0</span>
                                    </h4>
                                    <a href="{{route('pacientes.index')}}"
                                        class="text-decoration-underline text-white-50">Ver pacientes</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                        <i class="bx bx-wallet text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div> <!-- end row-->

            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Estadisticas</h4>
                            <div>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    ALL
                                </button>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    1M
                                </button>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    6M
                                </button>
                                <button type="button" class="btn btn-soft-primary btn-sm">
                                    1Y
                                </button>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-header p-0 border-0 bg-soft-light">
                            <div class="row g-0 text-center">
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1"><span class="counter-value"
                                                data-target="{{$totalUsers}}">0</span></h5>
                                        <p class="text-muted mb-0">Usuarios</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1"><span class="counter-value"
                                                data-target="">0</span></h5>
                                        <p class="text-muted mb-0">Pacientes</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1"><span class="counter-value"
                                                data-target="">0</span></h5>
                                        <p class="text-muted mb-0">Proformas</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div
                                        class="p-3 border border-dashed border-start-0 border-end-0">
                                        <h5 class="mb-1"><span class="counter-value"
                                                data-target="{{$totalPaciente}}">0</span></h5>
                                        <p class="text-muted mb-0">Ordenes</p>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body p-0 pb-2">
                            <div class="w-100">
                                <div id="customer_impression_charts"
                                    data-colors='["--vz-warning", "--vz-primary", "--vz-danger"]'
                                    class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

            <div class="col-xl-4">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Store Visits by Source</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="#"
                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <span class="text-muted">Report<i
                                                class="mdi mdi-chevron-down ms-1"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Download Report</a>
                                        <a class="dropdown-item" href="#">Export</a>
                                        <a class="dropdown-item" href="#">Import</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div id="store-visits-source"
                                data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]'
                                class="apex-charts" dir="ltr"></div>
                        </div>
                    </div> <!-- .card-->
                </div> <!-- .col-->



                <!-- end col -->
            </div>

         
</div>


<script>
    flatpickr("#fecha", {
        mode: "range",
        dateFormat: "d M, Y",
        locale: "es", // idioma español
        defaultDate: [new Date(), new Date()] // hoy hasta hoy
    });


    document.querySelectorAll('.counter-value').forEach(counter => {
    const updateCount = () => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;
        const increment = target / 200;

        if(count < target){
            counter.innerText = Math.ceil(count + increment);
            setTimeout(updateCount, 10);
        } else {
            counter.innerText = target;
        }
    };
    updateCount();
});
</script>

@endsection





{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
