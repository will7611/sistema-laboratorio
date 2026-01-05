<div class="modal fade" id="nuevoCliente" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nuevo Paciente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('pacientes.store') }}" method="POST">
          @csrf

          <div class="row g-3">
            <div class="col-xxl-6">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Introducir Nombre">
            </div>

            <div class="col-xxl-6">
              <label class="form-label">Apellido</label>
              <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Introducir Apellido">
            </div>

            <div class="col-lg-4">
              <label class="form-label ">CI</label>
              <input type="text" class="form-control  @error('ci') is-invalid @enderror"  name="ci" value="{{ old('ci') }}" placeholder="Ej: 12345678 o 12345678-1B">
              @error('ci')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-xxl-4">
              <label class="form-label">Fecha Nacimiento</label>
              <input type="date" class="form-control" name="birth_date" value="{{ old('birth_date') }}">
            </div>

            <div class="col-xxl-4">
              <label class="form-label">Teléfono</label>
              <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Teléfono">
            </div>

            <div class="col-lg-8">
              <label class="form-label">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-xxl-12">
              <label class="form-label">Dirección</label>
              <input type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="Introducir Dirección">
            </div>

            <div class="col-lg-12">
              <div class="hstack gap-2 justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
              </div>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
  var el = document.getElementById('nuevoCliente');
  if (el) {
    var modal = new bootstrap.Modal(el);
    modal.show();
  }
});
</script>
@endif