@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Detalle de resultado</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('resultados.index') }}">Resultados</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Datos básicos --}}
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Paciente / Orden</h5></div>
            <div class="card-body">
                <p><strong>Paciente:</strong>
                    {{ $resultado->orden->paciente->name }}
                    {{ $resultado->orden->paciente->last_name }}
                </p>
                <p><strong>CI:</strong> {{ $resultado->orden->paciente->ci }}</p>
                <p><strong>Orden ID:</strong> {{ $resultado->orden->id }}</p>
                <p><strong>Estado resultado:</strong> {{ $resultado->status }}</p>
                @if($resultado->url_pdf)
                    <p><strong>PDF:</strong>
                        <a href="{{ $resultado->url_pdf }}" target="_blank">Ver informe</a>
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Notificaciones --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Notificaciones enviadas</h5></div>
            <div class="card-body">
                @if($resultado->notificaciones->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha envío</th>
                                    <th>Canal</th>
                                    <th>Estado</th>
                                    <th>Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resultado->notificaciones as $n)
                                    <tr>
                                        <td>{{ $n->send_date }}</td>
                                        <td>{{ $n->channel }}</td>
                                        <td>{{ $n->status }}</td>
                                        <td>{{ $n->message_error }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No hay notificaciones registradas.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
