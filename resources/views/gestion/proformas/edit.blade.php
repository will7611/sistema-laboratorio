@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Editar Proforma #{{ $proforma->id }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('proformas.index') }}">Proformas</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- PUENTE DE DATOS PHP -> JS (Solución Segura) -->
<!-- ============================================================== -->
<div id="data-bridge" 
     data-detalles="{{ json_encode($proforma->detalles) }}" 
     style="display:none;">
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                
                <form action="{{ route('proformas.update', $proforma->id) }}" method="POST" id="form-proforma">
                    @csrf
                    @method('PUT')

                    <!-- SECCIÓN 1: Datos del Paciente -->
                    <h5 class="font-size-14 mb-4"><i class="mdi mdi-account-edit me-1"></i> Datos del Paciente</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="patient_id" class="form-label">Seleccionar Paciente</label>
                            <select name="patient_id" id="patient_id" class="form-control select2" required>
                                <option value="">Buscar paciente...</option>
                                @foreach($pacientes as $paciente)
                                    <option value="{{ $paciente->id }}" 
                                        {{ $proforma->paciente_id == $paciente->id ? 'selected' : '' }}>
                                        {{ $paciente->name }} {{ $paciente->last_name }} - CI: {{ $paciente->ci }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha de Emisión</label>
                            <input type="date" class="form-control" name="issue_date" 
                                value="{{ \Carbon\Carbon::parse($proforma->issue_date)->format('Y-m-d') }}" readonly>
                        </div>
                        <div class="col-md-3">
                             <label class="form-label">Total Actual</label>
                             <input type="text" class="form-control fw-bold" id="display-total-header" 
                                value="{{ number_format($proforma->total_amount, 2) }} Bs" readonly>
                        </div>
                    </div>

                    <hr>

                    <!-- SECCIÓN 2: Selección de Análisis -->
                    <h5 class="font-size-14 mb-3 mt-4"><i class="mdi mdi-flask-outline me-1"></i> Agregar Análisis</h5>
                    
                    <div class="row align-items-end bg-light p-3 rounded mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Buscar Análisis</label>
                            <select id="select-analisis" class="form-control select2">
                                <option value="">Seleccione un análisis...</option>
                                @foreach($analisis as $item)
                                    <option value="{{ $item->id }}" 
                                            data-price="{{ $item->price }}" 
                                            data-code="{{ $item->code ?? 'S/C' }}"
                                            data-name="{{ $item->name }}">
                                        {{ $item->code ?? 'S/C' }} - {{ $item->name }} ({{ number_format($item->price, 2) }} Bs)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary w-100" id="btn-agregar">
                                <i class="mdi mdi-plus-circle"></i> Agregar a la lista
                            </button>
                        </div>
                    </div>

                    <!-- TABLA DE DETALLES -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 100px;">Código</th>
                                    <th>Análisis</th>
                                    <th style="width: 120px;">Precio Unit.</th>
                                    <th style="width: 100px;">Cantidad</th> <!-- COLUMNA CANTIDAD -->
                                    <th style="width: 120px;">Subtotal</th>
                                    <th style="width: 60px;" class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-detalles">
                                <!-- Aquí se cargarán los items vía JS -->
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <td colspan="4" class="text-end fw-bold">TOTAL GENERAL:</td>
                                    <td colspan="2">
                                        <!-- Inputs ocultos para enviar el total -->
                                        <input type="hidden" name="total_amount" id="input-total" value="{{ $proforma->total_amount }}">
                                        <h4 class="m-0 text-primary fw-bold" id="lbl-total">{{ number_format($proforma->total_amount, 2) }} Bs</h4>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <a href="{{ route('proformas.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">
                                <i class="mdi mdi-content-save"></i> Guardar Cambios
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
    // Variable global para evitar redeclaraciones
    var proformaConfig = {
        detalles: $('#data-bridge').data('detalles')
    };

    $(document).ready(function() {
        
        // =========================================================
        // 1. CARGA DE DATOS
        // =========================================================
        console.log("Cargando datos...", proformaConfig.detalles);

        if (proformaConfig.detalles && proformaConfig.detalles.length > 0) {
            proformaConfig.detalles.forEach(function(detalle) {
                let dataAnalisis = detalle.analisis || detalle.analysis;
                if(dataAnalisis) {
                    agregarFila(
                        dataAnalisis.id, 
                        dataAnalisis.code || 'S/C', 
                        dataAnalisis.name, 
                        parseFloat(detalle.unit_price),
                        parseInt(detalle.amount) || 1
                    );
                }
            });
        }

        // =========================================================
        // 2. INICIALIZAR PLUGINS
        // =========================================================
        try {
            if ($.fn.select2) $('.select2').select2();
        } catch (e) { console.error(e); }

        // =========================================================
        // 3. BOTÓN AGREGAR
        // =========================================================
        $('#btn-agregar').click(function() {
            let selected = $('#select-analisis').find(':selected');
            let id = selected.val();

            if (!id) {
                safeSwal('warning', 'Atención', 'Seleccione un análisis primero');
                return;
            }

            if ($(`#fila-${id}`).length > 0) {
                let inputCant = $(`#fila-${id} .input-cantidad`);
                let nuevaCant = parseInt(inputCant.val()) + 1;
                inputCant.val(nuevaCant).trigger('change');
                
                safeToast('success', 'Cantidad actualizada (+1)');
                return;
            }

            let name  = selected.data('name');
            let code  = selected.data('code');
            let price = parseFloat(selected.data('price'));

            agregarFila(id, code, name, price, 1);
            $('#select-analisis').val('').trigger('change');
        });

        // =========================================================
        // 4. FUNCIONES AUXILIARES
        // =========================================================
        function agregarFila(id, code, name, price, amount) {
            let subtotal = price * amount;
            let fila = `
                <tr id="fila-${id}">
                    <td>${code}</td>
                    <td>
                        <input type="hidden" name="analisis_id[]" value="${id}">
                        ${name}
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control input-price" 
                            name="precios[]" value="${price.toFixed(2)}" readonly> 
                    </td>
                    <td>
                        <input type="number" min="1" class="form-control text-center input-cantidad" 
                            name="cantidades[]" value="${amount}">
                    </td>
                    <td>
                        <input type="text" class="form-control text-end input-subtotal" 
                            value="${subtotal.toFixed(2)}" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btn-eliminar">
                            <i class="mdi mdi-trash-can"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#tbody-detalles').append(fila);
            calcularTotal();
        }

        $(document).on('change keyup', '.input-cantidad', function() {
            let fila = $(this).closest('tr');
            let cantidad = parseInt($(this).val());
            let precio = parseFloat(fila.find('.input-price').val());

            if(isNaN(cantidad) || cantidad < 1) {
                cantidad = 1;
                // $(this).val(1); // Descomentar si quieres forzar visualmente
            }
            
            let subtotal = cantidad * precio;
            fila.find('.input-subtotal').val(subtotal.toFixed(2));
            calcularTotal();
        });

        $(document).on('click', '.btn-eliminar', function() {
            $(this).closest('tr').remove();
            calcularTotal();
        });

        function calcularTotal() {
            let total = 0;
            $('.input-subtotal').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#lbl-total').text(total.toFixed(2) + ' Bs');
            $('#input-total').val(total.toFixed(2));
            $('#display-total-header').val(total.toFixed(2) + ' Bs');
        }

        // =========================================================
        // 5. WRAPPERS SEGUROS PARA SWEETALERT
        // =========================================================
        function safeSwal(icon, title, text) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: icon, title: title, text: text, confirmButtonColor: '#556ee6' });
            } else {
                alert(title + ": " + text);
            }
        }

        function safeToast(icon, title) {
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 1500,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
                Toast.fire({ icon: icon, title: title });
            }
        }
    });
</script>
@endpush
