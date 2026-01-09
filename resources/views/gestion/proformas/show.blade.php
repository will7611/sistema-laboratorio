@extends('layouts.app')

@section('content')

<!-- Título de la página (Visible solo en pantalla, oculto al imprimir) -->
<div class="row d-print-none">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Detalle de Proforma</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('proformas.index') }}">Proformas</a></li>
                    <li class="breadcrumb-item active">Ver #{{ $proforma->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                
                <!-- CABECERA: Logo y Datos del Laboratorio -->
                <div class="invoice-title">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <!-- LOGO DEL LABORATORIO -->
                            <!-- Ajusta la ruta 'assets/images/logo-dark.png' a donde tengas tu logo -->
                            <div class="mb-4">
                                <img src="{{ asset('assets/images/logo-laboratorio.png') }}" alt="logo" height="60" class="me-3">
                            </div>
                            <div>
                                <h3 class="font-size-20 fw-bold mb-1">LABORATORIO CLÍNICO "SANTIAGO"</h3>
                                {{-- <p class="mb-0 text-muted">Licencia de Funcionamiento: 12345-LP</p> --}}
                            </div>
                        </div>

                        <!-- DATOS DE LA PROFORMA (Lado derecho) -->
                        <div class="text-end">
                            <h4 class="font-size-16 mb-1">Proforma #{{ str_pad($proforma->id, 4, '0', STR_PAD_LEFT) }}</h4>
                            <div class="mb-1">
                                @if($proforma->status == 'aceptada' || $proforma->status == 'Pendiente')
                                    <span class="badge bg-success font-size-12">{{ ucfirst($proforma->status) }}</span>
                                @else
                                    <span class="badge bg-warning font-size-12">{{ ucfirst($proforma->status) }}</span>
                                @endif
                            </div>
                            <small class="text-muted">Fecha de Emisión: {{ \Carbon\Carbon::parse($proforma->issue_date)->format('d/m/Y') }}</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- INFORMACIÓN DE CONTACTO (Laboratorio vs Paciente) -->
                <div class="row">
                    <!-- Columna Izquierda: Datos del Laboratorio -->
                    <div class="col-sm-6">
                        <h5 class="font-size-14 fw-bold mb-2">De:</h5>
                        <address class="text-muted">
                            <strong>Laboratorio Clínico "SANTIAGO"</strong><br>
                            Av. Fernando MErcy #15, Zona Bancaria<br>
                            Sucre, Bolivia<br>
                            Teléfono: (591) 76119524<br>
                            {{-- Email: contacto@labvida.com --}}
                        </address>
                    </div>

                    <!-- Columna Derecha: Datos del Paciente -->
                    <div class="col-sm-6 text-sm-end">
                        <h5 class="font-size-14 fw-bold mb-2">Para (Paciente):</h5>
                        <address class="text-muted">
                            <strong>{{ $proforma->paciente->name ?? 'N/A' }} {{ $proforma->paciente->last_name ?? '' }}</strong><br>
                            CI: {{ $proforma->paciente->ci ?? 'No registrado' }}<br>
                            Edad: {{ $proforma->paciente->age ?? '-' }} años / Sexo: {{ $proforma->paciente->gender ?? '-' }}<br>
                            Celular: {{ $proforma->paciente->phone ?? 'Sin teléfono' }}
                        </address>
                    </div>
                </div>

                <!-- TABLA DE DETALLES -->
                <div class="py-2 mt-3">
                    <h5 class="font-size-15 fw-bold">Detalle de Análisis Solicitados</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-nowrap table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;">#</th>
                                <th>Descripción / Análisis</th>
                                <th class="text-end">Precio Unit.</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($proforma->detalles->count() > 0)
                                @foreach($proforma->detalles as $index => $detalle)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <h5 class="font-size-14 text-truncate mb-1">
                                            {{ optional($detalle->analisis)->name ?? 'Análisis (ID: '.$detalle->analysis_id.')' }}
                                        </h5>
                                        <p class="text-muted mb-0 font-size-12">
                                            Código: {{ optional($detalle->analisis)->code ?? 'S/C' }} | 
                                            Área: {{ optional($detalle->analisis)->area ?? 'General' }}
                                        </p>
                                    </td>
                                    <td class="text-end">{{ number_format($detalle->unit_price, 2) }} Bs</td>
                                    <td class="text-center">{{ $detalle->amount }}</td>
                                    <td class="text-end fw-bold">{{ number_format($detalle->subtotal, 2) }} Bs</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="mdi mdi-alert-circle-outline me-1"></i> No hay detalles registrados.
                                    </td>
                                </tr>
                            @endif
                            
                            <!-- SECCIÓN DE TOTALES -->
                            <tr class="border-top border-top-dashed">
                                <td colspan="3" class="border-0"></td>
                                <td colspan="1" class="border-0 text-end"><strong>Sub Total</strong></td>
                                <td class="border-0 text-end">{{ number_format($proforma->total_amount, 2) }} Bs</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="border-0"></td>
                                <td colspan="1" class="border-0 text-end"><strong>Descuento</strong></td>
                                <td class="border-0 text-end text-danger">- 0.00 Bs</td> <!-- Lógica de descuento si aplica -->
                            </tr>
                            <tr>
                                <td colspan="3" class="border-0"></td>
                                <td colspan="1" class="border-0 text-end"><strong>TOTAL A PAGAR</strong></td>
                                <td class="border-0 text-end">
                                    <h4 class="m-0 fw-bold text-primary">{{ number_format($proforma->total_amount, 2) }} Bs</h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- NOTAS Y FIRMA -->
                <div class="row mt-5">
                    <div class="col-sm-6">
                        <div class="text-muted">
                            <h5 class="font-size-14 mb-2">Notas / Condiciones:</h5>
                            <p class="mb-1 font-size-13"><i class="mdi mdi-circle-medium align-middle text-primary me-1"></i> Esta proforma tiene una validez de 7 días.</p>
                            <p class="mb-1 font-size-13"><i class="mdi mdi-circle-medium align-middle text-primary me-1"></i> Los precios están sujetos a cambios sin previo aviso.</p>
                            <p class="mb-1 font-size-13"><i class="mdi mdi-circle-medium align-middle text-primary me-1"></i> Horario de atención: Lun-Vie 08:00 - 18:00.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 text-center mt-4 mt-sm-0">
                        <br><br><br>
                        <p class="text-muted mb-0">___________________________</p>
                        <p class="fw-bold">Firma / Sello Responsable</p>
                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN (Ocultos al imprimir) -->
                <div class="d-print-none mt-4">
                    <div class="float-end">
                        <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1">
                            <i class="fa fa-print"></i> Imprimir
                        </a>
                        @if($proforma->status != 'aceptada')
                            <a href="#" class="btn btn-primary w-md waves-effect waves-light">Confirmar Proforma</a>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('proformas.index') }}" class="btn btn-secondary waves-effect">
                            <i class="mdi mdi-arrow-left me-1"></i> Volver al listado
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
