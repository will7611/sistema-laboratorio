@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Proformas</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Proformas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@include('layouts.alerts.alert')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Listado de proformas</h5>
                <a href="{{ route('proformas.create') }}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-plus me-1"></i> Nueva Proforma
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla-proformas" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Paciente</th>
                                <th>Fecha emisión</th>
                                <th>Total (Bs)</th>
                                <th>Estado</th>
                                <th>Orden</th>
                                <th style="width: 200px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proformas as $proforma)
                            <tr data-id="{{ $proforma->id }}">
                                <td>{{ $proforma->id }}</td>
                                <td>{{ $proforma->paciente->name }} {{ $proforma->paciente->last_name }}</td>
                                <td>{{ $proforma->issue_date }}</td>
                                <td>{{ number_format($proforma->total_amount, 2) }}</td>
                                
                                <td class="estado-proforma">
                                    @if($proforma->status == 'aceptada' || $proforma->orden)
                                        <span class="badge bg-success">Aceptada</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente</span>
                                    @endif
                                </td>
                                
                                <td class="orden-proforma">
                                    @if($proforma->orden)
                                        <a href="{{ route('ordenes.show', $proforma->orden->id) }}" class="fw-bold">
                                            #{{ $proforma->orden->id }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                
                                <!-- Columna de Acciones Unificada -->
                                <td class="celda-acciones">
                                    <div class="d-flex gap-2">
                                        
                                        <!-- Botón Ver Detalles (Siempre visible) -->
                                        <a href="{{ route('proformas.show', $proforma->id) }}" 
                                           class="btn btn-sm btn-info" title="Ver Detalles">
                                            <i class="mdi mdi-eye"></i>
                                        </a>

                                        @if(!$proforma->orden)
                                            <!-- ESTADO PENDIENTE: Mostrar Editar y Aceptar -->
                                            
                                            <a href="{{ route('proformas.edit', $proforma->id) }}" 
                                               class="btn btn-sm btn-primary" title="Editar Proforma">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>

                                            <button type="button"
                                                class="btn btn-sm btn-success btn-aceptar-proforma"
                                                data-url="{{ route('proformas.aceptar', $proforma->id) }}"
                                                title="Aceptar y Crear Orden">
                                                <i class="mdi mdi-check-circle"></i>
                                            </button>
                                        @else
                                            <!-- ESTADO ACEPTADO: Mostrar Deshacer -->
                                            
                                            <button type="button"
                                                class="btn btn-sm btn-danger btn-revertir-proforma"
                                                data-url="{{ route('proformas.revertir', $proforma->id) }}"
                                                title="Deshacer / Cancelar Aceptación">
                                                <i class="mdi mdi-undo-variant"></i>
                                            </button>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(function () {
        if ($.fn.DataTable) {
            $('#tabla-proformas').DataTable({
                "language": { "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json" },
                "order": [[ 0, "desc" ]] // Ordenar por ID descendente
            });
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        // ==========================================
        // 1. ACEPTAR PROFORMA
        // ==========================================
        $(document).on('click', '.btn-aceptar-proforma', function () {
            let $btn  = $(this);
            let url   = $btn.data('url');
            let $fila = $btn.closest('tr');

            Swal.fire({
                title: '¿Aceptar proforma?',
                text: 'Se creará una orden de análisis automáticamente.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#34c38f',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Sí, aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $btn.prop('disabled', true);

                $.post(url, {}, function (response) {
                    if (response.success) {
                        // 1. Actualizar estado visual
                        $fila.find('.estado-proforma').html('<span class="badge bg-success">Aceptada</span>');

                        // 2. Actualizar columna Orden
                        if (response.orden) {
                            $fila.find('.orden-proforma').html(`<a href="/ordenes/${response.orden.id}" class="fw-bold">#${response.orden.id}</a>`);
                        }

                        // 3. Cambiar botones (Quitar Editar/Aceptar -> Poner Deshacer)
                        let newButtons = `
                            <div class="d-flex gap-2">
                                <a href="/proformas/${response.proforma_id}" class="btn btn-sm btn-info" title="Ver Detalles"><i class="mdi mdi-eye"></i></a>
                                <button type="button" class="btn btn-sm btn-danger btn-revertir-proforma" 
                                    data-url="/proformas/${response.proforma_id}/revertir" title="Deshacer Aceptación">
                                    <i class="mdi mdi-undo-variant"></i>
                                </button>
                            </div>
                        `;
                        $fila.find('.celda-acciones').html(newButtons);

                        Swal.fire('¡Éxito!', 'Orden creada correctamente.', 'success');
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo aceptar.', 'error');
                        $btn.prop('disabled', false);
                    }
                }).fail(function () {
                    Swal.fire('Error', 'Ocurrió un error en el servidor.', 'error');
                    $btn.prop('disabled', false);
                });
            });
        });

        // ==========================================
        // 2. REVERTIR (DESHACER) ACEPTACIÓN
        // ==========================================
        $(document).on('click', '.btn-revertir-proforma', function () {
            let $btn  = $(this);
            let url   = $btn.data('url'); // Ruta nueva para revertir
            let $fila = $btn.closest('tr');

            Swal.fire({
                title: '¿Deshacer aceptación?',
                text: 'La proforma volverá a estado Pendiente y se desvinculará/eliminará la orden asociada.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f46a6a', // Rojo advertencia
                cancelButtonColor: '#74788d',
                confirmButtonText: 'Sí, revertir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $btn.prop('disabled', true);

                $.post(url, {}, function (response) {
                    if (response.success) {
                        // 1. Actualizar estado visual
                        $fila.find('.estado-proforma').html('<span class="badge bg-warning">Pendiente</span>');

                        // 2. Limpiar columna Orden
                        $fila.find('.orden-proforma').text('-');

                        // 3. Restaurar botones (Quitar Deshacer -> Poner Editar/Aceptar)
                        // NOTA: Asegúrate de construir bien las rutas JS si usas nombres de ruta.
                        let id = $fila.data('id'); 
                        let newButtons = `
                            <div class="d-flex gap-2">
                                <a href="/proformas/${id}" class="btn btn-sm btn-info" title="Ver Detalles"><i class="mdi mdi-eye"></i></a>
                                <a href="/proformas/${id}/edit" class="btn btn-sm btn-primary" title="Editar"><i class="mdi mdi-pencil"></i></a>
                                <button type="button" class="btn btn-sm btn-success btn-aceptar-proforma" 
                                    data-url="/proformas/${id}/aceptar" title="Aceptar">
                                    <i class="mdi mdi-check-circle"></i>
                                </button>
                            </div>
                        `;
                        $fila.find('.celda-acciones').html(newButtons);

                        Swal.fire('Revertido', 'La proforma ahora está pendiente.', 'success');
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo revertir.', 'error');
                        $btn.prop('disabled', false);
                    }
                }).fail(function () {
                    Swal.fire('Error', 'Ocurrió un error en el servidor.', 'error');
                    $btn.prop('disabled', false);
                });
            });
        });

    });
</script>
@endpush
