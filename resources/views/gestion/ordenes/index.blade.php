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
                                <th>Estado</th>
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
                                                Enviar WhatsApp/Email
                                            </button>

                                        @else
                                            <button class="btn btn-sm btn-primary btn-cargar-pdf"
                                                    data-resultado-id="{{ $orden->resultado->id ?? 0 }}"
                                                    data-orden-id="{{ $orden->id }}">
                                                Cargar PDF
                                            </button>
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

{{-- Modal para subir PDF --}}
<div class="modal fade" id="modalSubirPDF" tabindex="-1">
    <div class="modal-dialog">
        <form id="formSubirPDF" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subir PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="resultado_id" id="resultado_id">
                    <input type="hidden" name="orden_id" id="orden_id">

                    <label>Seleccionar PDF</label>
                    <input type="file" name="pdf" class="form-control" accept="application/pdf" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Subir PDF</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function(){

    // Abrir modal de PDF
    $(document).on('click', '.btn-cargar-pdf', function(){
        $('#resultado_id').val($(this).data('resultado-id'));
        $('#orden_id').val($(this).data('orden-id'));
        $('#modalSubirPDF').modal('show');
    });

    // Subir PDF
    $('#formSubirPDF').on('submit', function(e){
        e.preventDefault();

        let resultadoId = $('#resultado_id').val();
        let formData = new FormData(this);

        Swal.fire({
            title: 'Subiendo PDF...',
            didOpen: () => Swal.showLoading(),
            allowOutsideClick: false
        });

        $.ajax({
            url: `/resultados/${resultadoId}/upload`,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res){
                Swal.close();
                Swal.fire('Éxito', res.message, 'success')
                    .then(() => location.reload());
            },
            error: function(xhr){
                Swal.close();
                Swal.fire('Error', xhr.responseJSON?.message || 'Error al subir PDF', 'error');
            }
        });
    });

    // Obtener datos y enviar correo & WhatsApp
    $(document).on('click', '.btn-enviar-correo', function(){
        const id = $(this).data('resultado-id');

        Swal.fire({
            title: 'Obteniendo datos...',
            didOpen: () => Swal.showLoading(),
            allowOutsideClick: false
        });

        $.ajax({
            url: `/api/resultados/${id}`,
            type: 'GET',
            headers: {
                'Authorization': 'Bearer {{ $n8nToken }}'
            },
            success: function(res){
                Swal.close();
                console.log('Datos obtenidos:', res);

                Swal.fire({
                    title: 'Enviar a n8n?',
                    html: `<pre style="text-align:left;">${JSON.stringify(res, null, 2)}</pre>`,
                    showCancelButton: true,
                    confirmButtonText: 'Enviar',
                }).then((result) => {
                    if(result.isConfirmed){
                        enviarAN8n(id);
                    }
                });
            },
            error: function(xhr){
                Swal.close();
                Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo obtener los datos', 'error');
            }
        });
    });

    function enviarAN8n(resultadoId){
        Swal.fire({
            title: 'Enviando a n8n...',
            didOpen: () => Swal.showLoading(),
            allowOutsideClick: false
        });

        $.ajax({
            url: `/api/resultados/${resultadoId}/send`,
            type: 'POST',
            headers: {
                'Authorization': 'Bearer {{ $n8nToken }}'
            },
            success: function(res){
                Swal.close();
                Swal.fire('Éxito', res.message, 'success').then(()=> location.reload());
            },
            error: function(xhr){
                Swal.close();
                Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo enviar', 'error');
            }
        });
    }

});
</script>
@endpush
