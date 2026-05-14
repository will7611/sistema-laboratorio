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
                        {{-- PACIENTE (BÚSQUEDA Y RECIENTES) --}}
                        <div class="col-md-6">
                            <label class="form-label">Paciente <small class="text-muted">(buscar por nombre o CI)</small></label>
                            <div class="input-group">
                                <input type="text" 
                                       id="buscar-paciente" 
                                       class="form-control" 
                                       placeholder="Escriba nombre o CI del paciente..."
                                       autocomplete="off">
                                <div class="input-group-text">
                                    <i class="bx bx-search"></i>
                                </div>
                            </div>
                            <div id="resultados-pacientes" class="position-relative mt-1">
                                <div class="dropdown-menu w-100 shadow border-1 border-primary" id="lista-pacientes" style="display: none; max-height: 350px; overflow-y: auto; position: absolute; z-index: 1000;">
                                    <!-- Se llenará dinámicamente con AJAX -->
                                </div>
                            </div>
                            <input type="hidden" name="paciente_id" id="paciente_id" required>
                            <div id="info-paciente-seleccionado" class="mt-1 small text-success fw-bold"></div>
                        </div>

                        {{-- BUSCADOR ANÁLISIS --}}
                        <div class="col-md-6">
                            <label class="form-label">Buscar análisis</label>
                            <input type="text" id="buscar-analisis" class="form-control" placeholder="Ej: hemograma, glucosa, orina...">
                        </div>
                    </div>
                    
                    {{-- TABLA DE ANÁLISIS --}}
                    <h5 class="mb-3">Análisis clínicos</h5>

                    <div class="table-responsive" style="min-height: 300px;">
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
    // --- VARIABLES GLOBALES ---
    const FILAS_POR_PAGINA = 10;
    let paginaActual = 1;
    let filasVisibles = []; 
    
    $(document).ready(function() {
        // Inicializar Análisis
        filasVisibles = $('.fila-analisis').toArray();
        actualizarTabla();
    });

    // —— Funciones Utilitarias ——
    function normalizar(texto) {
        if (!texto) return "";
        return String(texto).normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    }

    // ==========================================
    // LÓGICA DE PACIENTES (BÚSQUEDA AJAX)
    // ==========================================
    
    function buscarPacientesAjax(termino) {
        const urlBusqueda = '{{ route("pacientes.search.ajax") }}';
        console.log("1. Intentando buscar en la URL:", urlBusqueda);
        console.log("2. Término a buscar:", termino);

        const $lista = $('#lista-pacientes');
        $lista.html('<div class="dropdown-item text-center text-muted p-2"><i class="bx bx-loader-alt bx-spin"></i> Buscando...</div>').show();

        $.ajax({
            url: urlBusqueda,
            method: 'GET',
            data: { search: termino }, 
            success: function(data) {
                console.log("3. ¡Éxito! Datos recibidos del servidor:", data);
                
                let res = Array.isArray(data.resultados) ? data.resultados : [];
                let rec = Array.isArray(data.recientes) ? data.recientes : [];
                
                renderizarLista(res, rec, termino);
            },
            error: function(xhr, status, error) {
                console.error("3. ERROR en AJAX");
                console.error("Status:", status);
                console.error("Error string:", error);
                console.error("Response:", xhr.responseText);
                
                $lista.html('<div class="dropdown-item text-center text-danger p-2 text-wrap" style="white-space: normal;">Error de conexión. Revisa la consola (F12)</div>').show();
            }
        });
    }

    function renderizarLista(resultados, recientes, termino) {
        const $lista = $('#lista-pacientes');
        let html = '';

        // ESCENARIO 1: El usuario escribió algo (Mostrar resultados de TODA la tabla)
        if (termino.length > 0) {
            html += '<div class="dropdown-header bg-primary text-white p-2 fw-bold">🔍 RESULTADOS DE BÚSQUEDA GENERAL</div>';
            
            if (resultados.length > 0) {
                resultados.forEach(p => html += crearItemHTML(p, false));
            } else {
                html += '<div class="dropdown-item text-center text-muted p-2">No hay coincidencias para "'+termino+'"</div>';
            }
        } 
        // ESCENARIO 2: El input está vacío (Mostrar SOLO los recientes)
        else {
            html += '<div class="dropdown-header bg-success text-white p-2 fw-bold">👥 PACIENTES CREADOS RECIENTEMENTE</div>';
            
            if (recientes.length > 0) {
                recientes.forEach(p => html += crearItemHTML(p, true));
            } else {
                html += '<div class="dropdown-item text-center text-muted p-2">No hay pacientes registrados recientemente</div>';
            }
        }

        $lista.html(html).show();
    }

    function crearItemHTML(paciente, esReciente) {
        let apellido = paciente.last_name ? paciente.last_name : '';
        let ci = paciente.ci ? paciente.ci : 'S/N';
        let badge = esReciente ? '<span class="badge bg-success-subtle text-success">Nuevo</span>' : '';

        return `
            <a class="dropdown-item paciente-resultado p-2 border-bottom" 
               href="#" data-id="${paciente.id}" data-name="${paciente.name} ${apellido}" data-ci="${ci}">
                <div class="d-flex justify-content-between align-items-center">
                    <strong class="text-dark">${paciente.name} ${apellido}</strong>
                    ${badge}
                </div>
                <small class="text-muted"><i class="bx bx-id-card"></i> CI: ${ci}</small>
            </a>
        `;
    }

   // ==========================================
    // EVENTOS DEL INPUT PACIENTE
    // ==========================================

    // Al hacer click, si está vacío, busca los recientes de hoy
    $('#buscar-paciente').on('click focus', function(e) {
        e.stopPropagation();
        const termino = $(this).val() || '';
        
        if(termino.trim() === '') {
            buscarPacientesAjax(''); 
        } else {
            $('#lista-pacientes').show(); 
        }
    });

    // Al teclear, busca en toda la base de datos
    // NOTA: Eliminamos $(this) dentro del debounce y pasamos el valor directamente
    $('#buscar-paciente').on('input', function(e) {
        const termino = $(this).val() || '';
        ejecutarBusquedaDebounced(termino.trim());
    });

    // Definimos el debounce fuera del evento para que no pierda el contexto
    const ejecutarBusquedaDebounced = debounce(function(termino) {
        buscarPacientesAjax(termino);
    }, 400);

    // SELECCIONAR PACIENTE DE LA LISTA
    $(document).on('click', '.paciente-resultado', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const id = $(this).data('id');
        const nombre = $(this).data('name');
        const ci = $(this).data('ci');
        
        $('#paciente_id').val(id); 
        $('#buscar-paciente').val(nombre); 
        $('#info-paciente-seleccionado').html(`✅ Seleccionado: <strong>${nombre}</strong> (CI: ${ci})`);
        $('#lista-pacientes').hide();
    });

    // OCULTAR LISTA AL HACER CLICK FUERA
    $(document).click(function(e) {
        if (!$(e.target).closest('#buscar-paciente').length && !$(e.target).closest('#lista-pacientes').length) {
            $('#lista-pacientes').hide();
        }
    });

    // ==========================================
    // LÓGICA DE ANÁLISIS (PAGINACIÓN, BÚSQUEDA Y TOTALES)
    // ==========================================
    function actualizarTabla() {
        const totalFilas = filasVisibles.length;
        const totalPaginas = Math.ceil(totalFilas / FILAS_POR_PAGINA);

        if (paginaActual < 1) paginaActual = 1;
        if (paginaActual > totalPaginas && totalPaginas > 0) paginaActual = totalPaginas;

        $('.fila-analisis').hide();

        if (totalFilas > 0) {
            const inicio = (paginaActual - 1) * FILAS_POR_PAGINA;
            const fin = inicio + FILAS_POR_PAGINA;
            
            const filasA_Mostrar = filasVisibles.slice(inicio, fin);
            $(filasA_Mostrar).show();

            const finReal = (fin > totalFilas) ? totalFilas : fin;
            $('#info-paginacion').text(`Mostrando ${inicio + 1}-${finReal} de ${totalFilas} análisis`);
        } else {
            $('#info-paginacion').text('No se encontraron resultados');
        }

        $('#btn-prev').prop('disabled', paginaActual === 1 || totalFilas === 0);
        $('#btn-next').prop('disabled', paginaActual >= totalPaginas || totalFilas === 0);
    }

    $('#btn-prev').click(function() {
        if (paginaActual > 1) { paginaActual--; actualizarTabla(); }
    });

    $('#btn-next').click(function() {
        const totalPaginas = Math.ceil(filasVisibles.length / FILAS_POR_PAGINA);
        if (paginaActual < totalPaginas) { paginaActual++; actualizarTabla(); }
    });

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

        if (estricto) {
            if (isNaN(cantidad) || cantidad < 1) {
                cantidad = 1;
                $inputCantidad.val(1);
            }
        } else {
            if (isNaN(cantidad) || cantidad < 0) { cantidad = 0; }
        }

        const subtotal = precio * cantidad;
        $fila.find('.subtotal').text(subtotal.toFixed(2));
    }

    function recalcularTotal() {
        let total = 0;
        $('.fila-analisis').each(function() {
            const sub = parseFloat($(this).find('.subtotal').text());
            if (!isNaN(sub)) total += sub;
        });
        $('#total-proforma').text(total.toFixed(2));
    }

    $(document).on('change', '.chk-analisis', function() {
        recalcularFila($(this).closest('tr'), true);
        recalcularTotal();
    });

    $(document).on('input', '.input-cantidad', function() {
        recalcularFila($(this).closest('tr'), false);
        recalcularTotal();
    });

    $(document).on('blur', '.input-cantidad', function() {
        recalcularFila($(this).closest('tr'), true);
        recalcularTotal();
    });

    // ==========================================
    // ENVÍO DE FORMULARIO
    // ==========================================
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    $('#form-proforma').on('submit', function(e) {
        e.preventDefault();
        let $form = $(this);

        const pacienteId = $('#paciente_id').val();
        if (!pacienteId) {
            Swal.fire({ icon: 'warning', title: 'Falta Información', text: 'Por favor seleccione un paciente de la lista.' });
            return;
        }

        let items = [];
        $('.fila-analisis').each(function() {
            const $fila = $(this);
            if ($fila.find('.chk-analisis').is(':checked')) {
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
