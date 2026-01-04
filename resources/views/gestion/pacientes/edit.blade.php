<div class="modal fade" id="editarclient-{{ $paciente->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Paciente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('pacientes.update', $paciente->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row g-3">
            <div class="col-xxl-6">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" name="name" value="{{ old('name', $paciente->name) }}" placeholder="Introducir Nombre">
            </div>

            <div class="col-xxl-6">
              <label class="form-label">Apellido</label>
              <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $paciente->last_name) }}" placeholder="Introducir Apellido">
            </div>

            <div class="col-lg-4">
              <label class="form-label">CI</label>
              <input type="text" class="form-control" name="ci" value="{{ old('ci', $paciente->ci) }}" placeholder="Ej: 12345678 o 12345678-1B">
            </div>

            <div class="col-xxl-4">
              <label class="form-label">Teléfono</label>
              <input type="text" class="form-control" name="phone" value="{{ old('phone', $paciente->phone) }}" placeholder="Teléfono">
            </div>

            <div class="col-xxl-4">
              <label class="form-label">Fecha de Nacimiento</label>
              <input type="date" class="form-control" name="birth_date" value="{{ old('birth_date', optional($paciente->birth_date)->format('Y-m-d')) }}">
            </div>

            {{-- Edad calculada (solo lectura) --}}
            <div class="col-xxl-4">
              <label class="form-label">Edad (calculada)</label>
              <input type="text" class="form-control" value="{{ $paciente->age }}" disabled>
            </div>

            <div class="col-lg-8">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" value="{{ old('email', $paciente->email) }}" placeholder="correo@ejemplo.com">
            </div>

            <div class="col-xxl-12">
              <label class="form-label">Dirección</label>
              <input type="text" class="form-control" name="address" value="{{ old('address', $paciente->address) }}" placeholder="Introducir Dirección">
            </div>

            <div class="col-lg-12">
              <div class="hstack gap-2 justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
              </div>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
