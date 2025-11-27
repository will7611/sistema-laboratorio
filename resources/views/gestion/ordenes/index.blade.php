@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Órdenes de análisis</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Órdenes</li>
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
                <h5 class="card-title mb-0">Listado de órdenes</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla-ordenes" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Paciente</th>
                                <th>Fecha creación</th>
                                <th>Estado orden</th>
                                <th>Resultado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ordenes as $orden)
                                <tr>
                                    <td>{{ $orden->id }}</td>
                                    <td>{{ $orden->paciente->name }} {{ $orden->paciente->last_name }}</td>
                                    <td>{{ $orden->creation_date }}</td>
                                    <td>{{ ucfirst($orden->status) }}</td>
                                    <td>
                                        @if($orden->resultado && $orden->resultado->pdf_path)
                                            <span class="badge bg-success">PDF cargado</span>
                                        @else
                                            <span class="badge bg-secondary">Sin PDF</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($orden->resultado && $orden->resultado->pdf_path)
                                            <a href="{{ $orden->resultado->url_pdf }}" target="_blank" class="btn btn-sm btn-info">
                                                Ver PDF
                                            </a>
                                            <button class="btn btn-sm btn-success btn-enviar-correo" 
                                                    data-resultado-id="{{ $orden->resultado->id }}">
                                                Enviar a correo & WhatsApp
                                            </button>
                                        @else
                                            <span class="text-muted">Sin PDF</span>
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
    $(document).ready(function() {

        // Inicializar DataTables
        if ($.fn.DataTable) {
            $('#tabla-ordenes').DataTable({
                "columnDefs": [
                    { "orderable": false, "targets": [4, 5] } // columnas Resultado y Acciones
                ]
            });
        }

        // Configurar AJAX con CSRF
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        // Evento click en botón enviar correo & WhatsApp
        $(document).on('click', '.btn-enviar-correo', function() {
            const resultadoId = $(this).data('resultado-id');
            const $btn = $(this);

            Swal.fire({
                title: 'Enviando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '/resultados/' + resultadoId + '/send', // asegúrate que la ruta exista
                type: 'POST',
                success: function(response) {
                    Swal.close();

                    if(response.success){
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atención',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    let msg = xhr.responseJSON?.message || 'Error al enviar correo y WhatsApp.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg
                    });
                }
            });
        });

    });
</script>
@endpush
