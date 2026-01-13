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
                    <table id="tabla-ordenes" class="table table-bordered table-striped align-middle ">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Paciente</th>
                                <th>Fecha creación</th>
                                {{-- <th>Estado</th> --}}
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
                                    {{-- <td>{{ ucfirst($orden->status) }}</td> --}}

                                    <td>
                                        @if($orden->resultado && $orden->resultado->pdf_path)
                                            <span class="badge bg-success">PDF cargado</span>
                                        @else
                                            <span class="badge bg-secondary">Sin PDF</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($orden->resultado && $orden->resultado->pdf_path)
        {{-- 1. VER PDF: Visible para TODOS (Pacientes, Recepción, etc.) --}}
        <a href="{{ $orden->resultado->url_pdf }}" target="_blank" class="btn btn-sm btn-info">
            Ver PDF
        </a>

        {{-- 2. ENVIAR: Solo Admin y Laboratorista pueden reenviar correos --}}
        @hasanyrole('Admin|Laboratorista') {{-- [web:1][web:5] --}}
            <button class="btn btn-sm btn-success btn-enviar-correo" 
                    data-resultado-id="{{ $orden->resultado->id }}">
                Enviar WhatsApp/Email
            </button>
        @endhasanyrole

    @else
        {{-- 3. CARGAR: Solo Admin y Laboratorista pueden subir archivos --}}
        @hasanyrole('Admin|Laboratorista')
            <button class="btn btn-sm btn-primary btn-cargar-pdf"
                    data-resultado-id="{{ $orden->resultado->id ?? 0 }}"
                    data-orden-id="{{ $orden->id }}">
                Cargar PDF
            </button>
        @endhasanyrole
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
@role('Admin')
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
@endrole
@push('scripts')
<script>
$(document).ready(function(){

    // 1. Abrir modal de PDF
    $(document).on('click', '.btn-cargar-pdf', function(){
        $('#resultado_id').val($(this).data('resultado-id'));
        $('#orden_id').val($(this).data('orden-id'));
        $('#modalSubirPDF').modal('show');
    });

    // 2. Subir PDF (AJAX)
    $('#formSubirPDF').on('submit', function(e){
        e.preventDefault();
        let resultadoId = $('#resultado_id').val();
        let formData = new FormData(this);

        Swal.fire({
            title: 'Subiendo y enviando a n8n...',
            didOpen: () => Swal.showLoading(),
            allowOutsideClick: false
        });

        $.ajax({
            url: `/resultados/${resultadoId}/upload`, // Ruta web POST
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
                let msg = xhr.responseJSON?.message || 'Error al subir PDF';
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    // 3. Botón "Enviar WhatsApp/Email" (Reenvío manual)
    $(document).on('click', '.btn-enviar-correo', function(){
        const id = $(this).data('resultado-id');

        Swal.fire({
            title: 'Enviando a n8n...',
            text: 'Se enviará correo y WhatsApp al paciente.',
            didOpen: () => Swal.showLoading(),
            allowOutsideClick: false
        });

        $.ajax({
            url: `/resultados/${id}/enviar-n8n`, // Nueva ruta web limpia
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}' // Usamos CSRF token de Blade
            },
            success: function(res){
                Swal.close();
                if(res.success){
                    Swal.fire('Enviado', res.message, 'success');
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function(xhr){
                Swal.close();
                Swal.fire('Error', 'Fallo de conexión con el servidor', 'error');
            }
        });
    });

});
</script>
@endpush
