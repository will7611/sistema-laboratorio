@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Nueva Proforma</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('proformas.index') }}">Proformas</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div id="alert-proforma"></div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Datos de Proforma</h5>
            </div>

            <div class="card-body">
                <form id="form-proforma" action="{{ route('proformas.store') }}" method="POST">
                    @csrf
                    <div class="row mb-4">
                        {{-- PACIENTE --}}
                        <div class="col-md-6">
                            <label class="form-label">Paciente</label>
                            <select name="paciente_id" class="form-select" required>
                                <option value="">-- Seleccione un paciente --</option>
                                @foreach ($pacientes as $paciente)
                                    <option value="{{ $paciente->id }}">
                                        {{ $paciente->name }} {{ $paciente->last_name }} ({{ $paciente->ci }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BUSCADOR --}}
                        <div class="col-md-6">
                            <label class="form-label">Buscar análisis</label>
                            <input type="text" id="buscar-analisis" class="form-control" placeholder="Ej: hemograma, glucosa, orina...">
                        </div>
                    </div>
                    
                    {{-- TABLA DE ANÁLISIS --}}
                    <h5 class="mb-3">Análisis clínicos</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tabla-analisis-proforma">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">Sel.</th>
                                    <th>Análisis</th>
                                    <th>Área</th>
                                    <th>Precio (Bs)</th>
                                    <th style="width: 100px;">Cantidad</th>
                                    <th>Subtotal (Bs)</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-analisis">
                                @foreach ($analisis as $item)
                                <tr data-id="{{ $item->id }}" class="fila-analisis">
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input chk-analisis" style="cursor: pointer;">
                                    </td>
                                    <td>
                                        {{ $item->name }}
                                        <input type="hidden" class="analisis-id" value="{{ $item->id }}">
                                    </td>
                                    <td>{{ $item->area }}</td>
                                    <td>
                                        <span class="precio" data-precio="{{ $item->price }}">
                                            {{ number_format($item->price, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control input-cantidad text-center" min="1" value="1" disabled>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="subtotal fw-bold">0.00</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- CONTROLES DE PAGINACIÓN --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 p-2 bg-light rounded">
                        <span id="info-paginacion" class="text-muted fw-bold small">Cargando análisis...</span>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" id="btn-prev" disabled>
                                <i class="bx bx-chevron-left"></i> Anterior
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-next" disabled>
                                Siguiente <i class="bx bx-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    {{-- TOTAL --}}
                    <div class="row mt-4">
                        <div class="col-md-4 offset-md-8">
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded bg-soft-primary">
                                <h5 class="mb-0">Total Estimado:</h5>
                                <h3 class="mb-0 text-primary">
                                    <span id="total-proforma">0.00</span> <small class="fs-6 text-muted">Bs</small>
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <a href="{{ route('proformas.index') }}" class="btn btn-light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bx bx-save"></i> Guardar Proforma
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // --- CONFIGURACIÓN PAGINACIÓN ---
    const FILAS_POR_PAGINA = 10;
    let paginaActual = 1;
    let filasVisibles = []; 

    $(document).ready(function() {
        // Inicializar: guardar todas las filas como visibles al principio
        filasVisibles = $('.fila-analisis').toArray();
        actualizarTabla();
    });

    // —— Función para normalizar texto (quita acentos) ——
    function normalizar(texto) {
        return texto
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase();
    }

    // —— Lógica de Paginación y Renderizado ——
    function actualizarTabla() {
        const totalFilas = filasVisibles.length;
        const totalPaginas = Math.ceil(totalFilas / FILAS_POR_PAGINA);

        if (paginaActual < 1) paginaActual = 1;
        if (paginaActual > totalPaginas && totalPaginas > 0) paginaActual = totalPaginas;

        // Ocultar todas primero
        $('.fila-analisis').hide();

        if (totalFilas > 0) {
            const inicio = (paginaActual - 1) * FILAS_POR_PAGINA;
            const fin = inicio + FILAS_POR_PAGINA;
            
            // Mostrar solo las de esta página
            const filasA_Mostrar = filasVisibles.slice(inicio, fin);
            $(filasA_Mostrar).show();

            const finReal = (fin > totalFilas) ? totalFilas : fin;
            $('#info-paginacion').text(`Mostrando ${inicio + 1}-${finReal} de ${totalFilas} análisis`);
        } else {
            $('#info-paginacion').text('No se encontraron resultados');
        }

        // Estado botones
        $('#btn-prev').prop('disabled', paginaActual === 1 || totalFilas === 0);
        $('#btn-next').prop('disabled', paginaActual >= totalPaginas || totalFilas === 0);
    }

    // —— Eventos Botones Paginación ——
    $('#btn-prev').click(function() {
        if (paginaActual > 1) {
            paginaActual--;
            actualizarTabla();
        }
    });

    $('#btn-next').click(function() {
        const totalPaginas = Math.ceil(filasVisibles.length / FILAS_POR_PAGINA);
        if (paginaActual < totalPaginas) {
            paginaActual++;
            actualizarTabla();
        }
    });

    // —— Buscador ——
    $(document).on('input', '#buscar-analisis', function () {
        const texto = normalizar($(this).val());
        
        filasVisibles = $('.fila-analisis').filter(function() {
            const nombre = normalizar($(this).find("td:nth-child(2)").text());
            const area   = normalizar($(this).find("td:nth-child(3)").text());
            return nombre.includes(texto) || area.includes(texto);
        }).toArray();

        paginaActual = 1;
        actualizarTabla();
    });

    // —— Cálculos de Subtotal y Total ——
    // estricto = true (fuerza a 1 si está vacío), estricto = false (permite vacío mientras escribes)
    function recalcularFila($fila, estricto = false) {
        const checked = $fila.find('.chk-analisis').is(':checked');
        const precio  = parseFloat($fila.find('.precio').data('precio')) || 0;
        const $inputCantidad = $fila.find('.input-cantidad');

        if (!checked) {
            $inputCantidad.prop('disabled', true);
            $fila.find('.subtotal').text('0.00');
            $fila.removeClass('table-active'); 
            return;
        }

        $inputCantidad.prop('disabled', false);
        $fila.addClass('table-active'); 

        let cantidad = parseInt($inputCantidad.val());

        // Lógica corregida para permitir edición fluida
        if (estricto) {
            // Si es estricto (blur o check), forzamos a 1 si es inválido
            if (isNaN(cantidad) || cantidad < 1) {
                cantidad = 1;
                $inputCantidad.val(1);
            }
        } else {
            // Si no es estricto (input), permitimos vacío o 0 visualmente, pero calculamos con 0
            if (isNaN(cantidad) || cantidad < 0) {
                cantidad = 0; 
            }
        }

        const subtotal = precio * cantidad;
        $fila.find('.subtotal').text(subtotal.toFixed(2));
    }

    function recalcularTotal() {
        let total = 0;
        $('.fila-analisis').each(function() {
            const sub = parseFloat($(this).find('.subtotal').text());
            if (!isNaN(sub)) {
                total += sub;
            }
        });
        $('#total-proforma').text(total.toFixed(2));
    }

    // —— Eventos Inputs ——
    
    // 1. Checkbox: Validación estricta
    $(document).on('change', '.chk-analisis', function() {
        const $fila = $(this).closest('tr');
        recalcularFila($fila, true);
        recalcularTotal();
    });

    // 2. Escribir: Validación suave (permite borrar)
    $(document).on('input', '.input-cantidad', function() {
        const $fila = $(this).closest('tr');
        recalcularFila($fila, false);
        recalcularTotal();
    });

    // 3. Salir del input: Validación estricta (rellena con 1 si estaba vacío)
    $(document).on('blur', '.input-cantidad', function() {
        const $fila = $(this).closest('tr');
        recalcularFila($fila, true);
        recalcularTotal();
    });

    // —— AJAX Setup ——
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // —— Envío Formulario ——
    $('#form-proforma').on('submit', function(e) {
        e.preventDefault();
        let $form = $(this);

        // Validar Paciente
        const pacienteId = $form.find('select[name="paciente_id"]').val();
        if (!pacienteId) {
            Swal.fire({ icon: 'warning', title: 'Falta Información', text: 'Por favor seleccione un paciente.' });
            return;
        }

        // Recolectar Items Seleccionados
        let items = [];
        $('.fila-analisis').each(function() {
            const $fila = $(this);
            if ($fila.find('.chk-analisis').is(':checked')) {
                // Asegurar cantidad válida al enviar
                let cant = parseInt($fila.find('.input-cantidad').val()) || 1;
                
                items.push({
                    analysis_id: $fila.find('.analisis-id').val(),
                    cantidad:    cant
                });
            }
        });

        if (items.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Sin Análisis', text: 'Seleccione al menos un análisis para crear la proforma.' });
            return;
        }

        // Enviar
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: { paciente_id: pacienteId, items: items },
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Guardando...');
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Proforma guardada correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('proformas.index') }}";
                    });
                }
            },
            error: function(xhr) {
                $('button[type="submit"]').prop('disabled', false).html('<i class="bx bx-save"></i> Guardar Proforma');
                console.error(xhr.responseText);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Hubo un problema al guardar la proforma.' });
            }
        });
    });
</script>
@endpush
