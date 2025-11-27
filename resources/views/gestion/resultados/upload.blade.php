@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Subir resultado (PDF)</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ordenes.index') }}">Órdenes</a></li>
                    <li class="breadcrumb-item active">Subir PDF</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div id="alert-resultado"></div>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Datos del paciente y orden</h5>
            </div>
            <div class="card-body">

                <p><strong>Paciente:</strong> {{ $resultado->orden->paciente->name }} {{ $resultado->orden->paciente->last_name }}</p>
                <p><strong>CI:</strong> {{ $resultado->orden->paciente->ci }}</p>
                <p><strong>Teléfono:</strong> {{ $resultado->orden->paciente->phone }}</p>
                <p><strong>Email:</strong> {{ $resultado->orden->paciente->email }}</p>
                <p><strong>Orden #:</strong> {{ $resultado->orden->id }}</p>
                <p><strong>Estado resultado:</strong> {{ ucfirst($resultado->status) }}</p>

                @if($resultado->pdf_path)
                    <p>
                        <strong>PDF actual:</strong>
                        <a href="{{ $resultado->url_pdf }}" target="_blank">Ver documento</a>
                    </p>
                @endif

                <hr>

                <form id="form-upload-resultado"
                      action="{{ route('resultados.uploadPdf', $resultado->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Archivo PDF</label>
                        <input type="file" name="pdf" class="form-control" accept="application/pdf" required>
                        <div class="form-text">
                            Solo archivos PDF, máximo 5 MB.
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('ordenes.index') }}" class="btn btn-light">Volver</a>
                        <button type="submit" class="btn btn-primary">
                            Subir y enviar a paciente
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $('#form-upload-resultado').on('submit', function(e) {
        e.preventDefault();

        let $form = $(this);
        let formData = new FormData(this);

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Resultado enviado',
                        text: response.message || 'El PDF se subió y se envió al paciente.',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    setTimeout(function() {
                        window.location.href = "{{ route('ordenes.index') }}";
                    }, 2100);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);

                if (xhr.status === 422) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: 'Verifica el archivo seleccionado.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al subir el PDF.'
                    });
                }
            }
        });
    });
</script>
@endpush
