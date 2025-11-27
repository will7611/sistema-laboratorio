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

                    {{-- PACIENTE --}}
                    <div class="row mb-4">
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
                    </div>

                    {{-- BUSCADOR --}}
                    <div class="row mb-3">
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
                                    <th>Seleccionar</th>
                                    <th>Análisis</th>
                                    <th>Área</th>
                                    <th>Precio (Bs)</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal (Bs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($analisis as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input chk-analisis">
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
                                    <td style="width: 120px;">
                                        <input type="number" class="form-control input-cantidad" min="1" value="1" disabled>
                                    </td>
                                    <td>
                                        <span class="subtotal">0.00</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- TOTAL --}}
                    <div class="row mt-4">
                        <div class="col-md-4 offset-md-8">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total:</h5>
                                <h4 class="mb-0">
                                    <span id="total-proforma">0.00</span> Bs
                                </h4>
                            </div>
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <a href="{{ route('proformas.index') }}" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Proforma</button>
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

    // —— Función para quitar acentos y normalizar texto ——
    function normalizar(texto) {
        return texto
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "") 
            .toLowerCase();
    }

    // —— Buscador de análisis ——
    $(document).on('input', '#buscar-analisis', function () {
        const texto = normalizar($(this).val());

        $("#tabla-analisis-proforma tbody tr").each(function () {
            const nombre = normalizar($(this).find("td:nth-child(2)").text());
            const area   = normalizar($(this).find("td:nth-child(3)").text());

            $(this).toggle(
                nombre.includes(texto) || area.includes(texto)
            );
        });
    });


    // —— Helpers de cálculo ——
    function recalcularFila($fila) {
        const checked = $fila.find('.chk-analisis').is(':checked');
        const precio  = parseFloat($fila.find('.precio').data('precio')) || 0;
        const $inputCantidad = $fila.find('.input-cantidad');

        if (!checked) {
            $inputCantidad.prop('disabled', true);
            $fila.find('.subtotal').text('0.00');
            return;
        }

        $inputCantidad.prop('disabled', false);

        let cantidad = parseInt($inputCantidad.val());
        if (isNaN(cantidad) || cantidad < 1) {
            cantidad = 1;
            $inputCantidad.val(1);
        }

        const subtotal = precio * cantidad;
        $fila.find('.subtotal').text(subtotal.toFixed(2));
    }

    function recalcularTotal() {
        let total = 0;
        $('#tabla-analisis-proforma tbody tr').each(function() {
            const sub = parseFloat($(this).find('.subtotal').text());
            if (!isNaN(sub)) {
                total += sub;
            }
        });
        $('#total-proforma').text(total.toFixed(2));
    }

    // —— Eventos de selección/cantidad ——
    $(document).on('change', '.chk-analisis', function() {
        const $fila = $(this).closest('tr');
        recalcularFila($fila);
        recalcularTotal();
    });

    $(document).on('input', '.input-cantidad', function() {
        const $fila = $(this).closest('tr');
        recalcularFila($fila);
        recalcularTotal();
    });

    // —— AJAX setup ——
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // —— Envío del formulario por AJAX ——
    $('#form-proforma').on('submit', function(e) {
        e.preventDefault();

        let $form = $(this);

        const pacienteId = $form.find('select[name="paciente_id"]').val();
        if (!pacienteId) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debes seleccionar un paciente.'
            });
            return;
        }

        let items = [];
        $('#tabla-analisis-proforma tbody tr').each(function() {
            const $fila    = $(this);
            const checked  = $fila.find('.chk-analisis').is(':checked');

            if (checked) {
                items.push({
                    analysis_id: $fila.find('.analisis-id').val(),
                    cantidad:    $fila.find('.input-cantidad').val()
                });
            }
        });

        if (items.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debes seleccionar al menos un análisis.'
            });
            return;
        }

        const data = {
            paciente_id: pacienteId,
            items: items
        };

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Proforma creada',
                        text: response.message || 'La proforma se guardó correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('proformas.index') }}";
                    });

                    $form[0].reset();
                    $('#total-proforma').text('0.00');
                    $('#tabla-analisis-proforma tbody tr').each(function() {
                        $(this).find('.subtotal').text('0.00');
                        $(this).find('.input-cantidad').prop('disabled', true);
                    });
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al guardar la proforma.'
                });
            }
        });
    });

</script>
@endpush
