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
                    Nueva Proforma
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla-proformas" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Paciente</th>
                                <th>Fecha emisión</th>
                                <th>Total (Bs)</th>
                                <th>Estado</th>
                                <th>Orden</th>
                                <th>Acciones</th>
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
                                    {{ ucfirst($proforma->status) }}
                                </td>
                                <td class="orden-proforma">
                                    @if($proforma->orden)
                                        #{{ $proforma->orden->id }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if(!$proforma->orden)
                                        <button type="button"
                                                class="btn btn-sm btn-success btn-aceptar-proforma"
                                                data-url="{{ route('proformas.aceptar', $proforma->id) }}">
                                            Aceptar y crear orden
                                        </button>
                                    @else
                                        <a href="{{ route('ordenes.index') }}" class="btn btn-sm btn-info">
                                            Ver órdenes
                                        </a>
                                    @endif
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
            $('#tabla-proformas').DataTable();
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        // Click "Aceptar y crear orden"
        $(document).on('click', '.btn-aceptar-proforma', function () {
            let $btn  = $(this);
            let url   = $btn.data('url');
            let $fila = $btn.closest('tr');

            Swal.fire({
                title: '¿Aceptar proforma?',
                text: 'Se creará una orden de análisis para esta proforma.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $btn.prop('disabled', true).text('Procesando...');

                $.post(url, {}, function (response) {
                    if (response.success) {
                        // actualizar estado
                        $fila.find('.estado-proforma').text('Aceptada');

                        if (response.orden) {
                            $fila.find('.orden-proforma').text('#' + response.orden.id);
                        }

                        // cambiar acciones
                        $btn.closest('td').html(`
                            <a href="{{ route('ordenes.index') }}" class="btn btn-sm btn-info">
                                Ver órdenes
                            </a>
                        `);

                        Swal.fire({
                            icon: 'success',
                            title: 'Listo',
                            text: response.message || 'Proforma aceptada y orden creada.',
                            timer: 1800,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'No se pudo aceptar la proforma.'
                        });
                        $btn.prop('disabled', false).text('Aceptar y crear orden');
                    }
                }).fail(function (xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al aceptar la proforma.'
                    });
                    $btn.prop('disabled', false).text('Aceptar y crear orden');
                });
            });
        });
    });
</script>
@endpush
