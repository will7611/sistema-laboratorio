@extends('layouts.app')

@section('title-head', 'Gestión de Resultados')

@section('content')

<!-- Título y Breadcrumb -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Resultados de Análisis</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Resultados</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <!-- FORMULARIO DE FILTROS -->
                <form action="{{ route('resultados.index') }}" method="GET" class="row g-3 mb-4">
                    
                    <!-- Buscar Paciente -->
                    <div class="col-md-4">
                        <label class="form-label">Buscar Paciente (Nombre/CI)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Ej: Juan Perez..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Rango de Fechas -->
                    <div class="col-md-3">
                        <label class="form-label">Desde</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hasta</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <!-- Filtro Estado -->
                    <div class="col-md-2">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="validado" {{ request('status') == 'validado' ? 'selected' : '' }}>Validado</option>
                            <option value="entregado" {{ request('status') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="col-md-12 text-end">
                        <a href="{{ route('resultados.index') }}" class="btn btn-secondary me-1">
                            <i class="mdi mdi-reload"></i> Limpiar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-filter"></i> Filtrar
                        </button>
                    </div>
                </form>

                <hr>

                <!-- TABLA DE RESULTADOS -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th># Orden</th>
                                <th>Paciente</th>
                                <th>Fecha Resultado</th>
                                <th>Validado Por</th>
                                <th class="text-center">Estado (Cambiar)</th>
                                <th class="text-center">PDF</th>
                                {{-- <th>Acciones</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resultados as $result)
                                <tr>
                                    <!-- ORDEN ID -->
                                    <td class="fw-bold">
                                        <a href="{{ route('ordenes.show', $result->order_id) }}">
                                            #{{ $result->orden->id ?? '---' }}
                                        </a>
                                    </td>

                                    <!-- PACIENTE -->
                                    <td>
                                        <h5 class="font-size-14 mb-1">
                                            {{ $result->orden->paciente->name ?? 'N/A' }} 
                                            {{ $result->orden->paciente->last_name ?? '' }}
                                        </h5>
                                        <small class="text-muted">CI: {{ $result->orden->paciente->ci ?? '-' }}</small>
                                    </td>

                                    <!-- FECHA -->
                                    <td>
                                        <i class="mdi mdi-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($result->result_date)->format('d/m/Y') }}
                                    </td>

                                    <!-- VALIDADO POR (NUEVO) -->
                                    <td>
                                        @if($result->validated_by)
                                            <span class="badge bg-soft-info text-info font-size-12">
                                                <i class="mdi mdi-account-check"></i> 
                                                {{ $result->validator->name ?? 'ID: ' . $result->validated_by }}
                                            </span>
                                            <div class="small text-muted mt-1">
                                                {{ $result->validated_date ? \Carbon\Carbon::parse($result->validated_date)->format('d/m/Y H:i') : '' }}
                                            </div>
                                        @else
                                            <span class="text-muted small fst-italic">- Pendiente -</span>
                                        @endif
                                    </td>

                                    <!-- ESTADO CON BOTÓN DROPDOWN (NUEVO) -->
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" 
                                                    class="btn btn-sm dropdown-toggle w-100 
                                                    {{ $result->status == 'entregado' ? 'btn-success' : ($result->status == 'validado' ? 'btn-info' : 'btn-secondary') }}" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                
                                                @if($result->status == 'entregado')
                                                    <i class="mdi mdi-check-all"></i> Entregado
                                                @elseif($result->status == 'validado')
                                                    <i class="mdi mdi-check-circle"></i> Validado
                                                @else
                                                    <i class="mdi mdi-clock-outline"></i> Pendiente
                                                @endif
                                            </button>
                                            
                                            <ul class="dropdown-menu">
                                                <!-- Opción: Validado -->
                                                <li>
                                                    <form action="{{ route('resultados.updateStatus', $result->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="validado">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="mdi mdi-check-circle text-info"></i> Marcar como Validado
                                                        </button>
                                                    </form>
                                                </li>

                                                <!-- Opción: Entregado -->
                                                <li>
                                                    <form action="{{ route('resultados.updateStatus', $result->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="entregado">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="mdi mdi-check-all text-success"></i> Marcar como Entregado
                                                        </button>
                                                    </form>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                <!-- Opción: Volver a Pendiente -->
                                                <li>
                                                    <form action="{{ route('resultados.updateStatus', $result->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="pendiente">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="mdi mdi-undo text-secondary"></i> Volver a Pendiente
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                    <!-- PDF -->
                                    <td class="text-center">
                                        @if($result->pdf_path)
                                            <a href="{{ route('resultados.download', $result->id) }}" 
                                               class="btn btn-sm btn-danger" 
                                               title="Descargar PDF" target="_blank">
                                                <i class="mdi mdi-file-pdf font-size-16">PDF</i>
                                            </a>
                                        @else
                                            <span class="text-muted small">Sin archivo</span>
                                        @endif
                                    </td>

                                    <!-- ACCIONES EXTRA -->
                                    {{-- <td>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="mdi mdi-eye me-2 text-primary"></i> Ver Detalle
                                                    </a>
                                                </li>
                                                <!-- Botón Manual de Envío a n8n -->
                                                <li>
                                                    <button onclick="enviarAn8n({{ $result->id }})" class="dropdown-item">
                                                        <i class="mdi mdi-whatsapp me-2 text-success"></i> Reenviar WhatsApp
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="avatar-sm mx-auto mb-3">
                                            <span class="avatar-title bg-light rounded-circle text-primary font-size-24">
                                                <i class="mdi mdi-file-find"></i>
                                            </span>
                                        </div>
                                        <h5 class="font-size-15">No se encontraron resultados</h5>
                                        <p class="text-muted mb-0">Intenta ajustar los filtros de búsqueda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINACIÓN -->
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <div class="d-flex justify-content-end">
                            {{ $resultados->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Función simple para reenviar a n8n vía AJAX (si usas el botón extra)
    function enviarAn8n(id) {
        if(!confirm('¿Seguro que deseas reenviar la notificación?')) return;

        fetch(`/resultados/${id}/send-n8n`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Enviado correctamente');
            } else {
                alert('Error al enviar: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush
