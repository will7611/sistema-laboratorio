@extends('layouts.app')

@section('title-head', 'Dashboard - Laboratorio')

@section('content')

<div class="row mb-3 pb-1">
    <div class="col-12">
        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
            <div class="flex-grow-1">
                @php
                    $hour = now()->format('H');
                    $greeting = ($hour < 12) ? 'Buen día' : (($hour < 18) ? 'Buenas tardes' : 'Buenas noches');
                @endphp
                <h4 class="fs-16 mb-1">{{ $greeting }}, {{ auth()->user()->name }}!</h4>
                <p class="text-muted mb-0">Resumen de actividad del laboratorio al día de hoy.</p>
            </div>
            <div class="mt-3 mt-lg-0">
                <div class="row g-3 mb-0 align-items-center">
                    <div class="col-auto">
                        <a href="{{ route('proformas.create') }}" class="btn btn-primary">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nueva Proforma
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pacientes Totales</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="counter-value" data-target="{{ $totalPaciente ?? 0 }}">0</span>
                        </h4>
                        <a href="{{ route('pacientes.index') }}" class="text-decoration-underline text-muted">Ver listado</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-info text-info rounded fs-3">
                            <i class="mdi mdi-account-group"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Proformas (Mes)</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="counter-value" data-target="{{ $proformasCount ?? 0 }}">0</span>
                        </h4>
                        <a href="{{ route('proformas.index') }}" class="text-decoration-underline text-muted">Ver historial</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-warning text-warning rounded fs-3">
                            <i class="mdi mdi-file-document-outline"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Resultados Pendientes</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="counter-value" data-target="{{ $resultadosPendientes ?? 0 }}">0</span>
                        </h4>
                        <a href="{{ route('resultados.index') }}" class="text-decoration-underline text-muted">Gestionar</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-danger text-danger rounded fs-3">
                            <i class="mdi mdi-test-tube"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Ingresos Hoy</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span>Bs </span><span class="counter-value" data-target="{{ $ingresosHoy ?? 0 }}">0</span>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success text-success rounded fs-3">
                            <i class="mdi mdi-cash-multiple"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Afluencia de Pacientes</h4>
            </div>
            <div class="card-body p-0 pb-2">
                <div class="w-100">
                    <div id="chart-pacientes" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Estado del Laboratorio</h4>
            </div>
            <div class="card-body">
                <div id="chart-estado-ordenes" class="apex-charts" dir="ltr"></div>
                
                <div class="mt-3">
                    <div class="d-flex justify-content-between border-bottom border-bottom-dashed py-2">
                        <p class="fw-medium mb-0"><i class="mdi mdi-circle text-success me-2"></i>Completadas</p>
                        <span class="text-muted pe-5">{{ $ordenesCompletadas ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom border-bottom-dashed py-2">
                        <p class="fw-medium mb-0"><i class="mdi mdi-circle text-warning me-2"></i>En Proceso</p>
                        <span class="text-muted pe-5">{{ $ordenesProceso ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <p class="fw-medium mb-0"><i class="mdi mdi-circle text-danger me-2"></i>Pendientes</p>
                        <span class="text-muted pe-5">{{ $ordenesPendientes ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

{{-- Lógica de WebSockets --}}
<script type="module">
    document.addEventListener("DOMContentLoaded", function() {
        function iniciarWebSockets() {
            if (typeof window.Echo === 'undefined') {
                setTimeout(iniciarWebSockets, 100);
                return;
            }

            console.log("¡Conectando a Reverb!");
            window.Echo.channel('canal-prueba')
                .listen('.App\\Events\\TestReverb', (evento) => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Notificación',
                        text: evento.mensaje,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000
                    });
                });
        }
        iniciarWebSockets();
    });
</script>

{{-- Lógica de Gráficos y Contadores --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. ANIMACIÓN DE CONTADORES
        const counters = document.querySelectorAll('.counter-value');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const duration = 1000; 
            const increment = target / (duration / 10);
            
            let current = 0;
            const updateCount = () => {
                current += increment;
                if (current < target) {
                    counter.innerText = Math.ceil(current);
                    setTimeout(updateCount, 10);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });

        // 2. GRÁFICO DE PACIENTES (BARRAS)
        var optionsPacientes = {
            series: [{
                name: 'Pacientes Atendidos',
                data: [30, 40, 35, 50, 49, 60, 70]
            }],
            chart: { type: 'bar', height: 350, toolbar: { show: false } },
            plotOptions: { bar: { borderRadius: 4, columnWidth: '40%' } },
            colors: ['#405189'],
            xaxis: { categories: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] }
        };
        new ApexCharts(document.querySelector("#chart-pacientes"), optionsPacientes).render();

        // 3. GRÁFICO DE ESTADO (DONUT)
        var optionsDonut = {
            series: [
                {{ (int)($ordenesCompletadas ?? 0) }}, 
                {{ (int)($ordenesProceso ?? 0) }}, 
                {{ (int)($ordenesPendientes ?? 0) }}
            ],
            chart: { height: 280, type: 'donut' },
            labels: ['Completadas', 'En Proceso', 'Pendientes'],
            colors: ['#0ab39c', '#f7b84b', '#f06548'],
            legend: { position: 'bottom' },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#chart-estado-ordenes"), optionsDonut).render();
    });
</script>
@endpush