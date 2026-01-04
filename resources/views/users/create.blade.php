<!-- Grids in modals -->

<div class="modal fade" id="nuevoUsuario" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="nuevoUsuario">Nuevo Usuario</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
    <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
        @csrf
    <div class="row g-3">
       
        <div class="col-xxl-12">
            <div>
                <label for="lastName" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="name" placeholder="Introducir Nombre Completo">
            </div>
        </div><!--end col-->
        <div class="col-xxl-6">
            <div>
                <label for="firstName" class="form-label">CI</label>
                <input type="text" class="form-control  @error('ci') is-invalid @enderror" name="ci" value="{{ old('ci') }}" placeholder="Ej: 12345678 o 12345678-1B">
                 @error('ci')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div><!--end col-->
        <div class="col-lg-6">
            <label for="genderInput" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Introducir Email">
        </div><!--end col-->
        <div class="col-xxl-6">
            <div>
                <label for="firstName" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" placeholder="***********">
            </div>
        </div><!--end col-->
        <div class="col-lg-6">
            <label for="genderInput" class="form-label">Repetir Contraseña</label>
            <input type="password" class="form-control" name="password_confirmation" placeholder="***********">
        </div><!--end col-->
        <div class="col-xxl-6">
            <div>
                <label for="emailInput" class="form-label">Telefono</label>
                <input type="text" class="form-control" name="phone" placeholder="Introducir # telefonico">
            </div>
        </div><!--end col-->
        <div class="col-xxl-6">
            <div>
                <label for="passwordInput" class="form-label">Direccion</label>
                <input type="text" class="form-control" name="address" placeholder="Introducir Direccion">
            </div>
        </div><!--end col-->
        <div class="col-xxl-4">
            <div>
                <label for="emailInput" class="form-label">Fecha De Nacimientos</label>
                <input type="date" class="form-control" name="fecha_nacimiento" placeholder="Introducir # telefonico">
            </div>
        </div><!--end col-->
        <div class="col-xxl-8">
            <div>
                <label for="passwordInput" class="form-label">Imagen</label>
                <input type="file" class="form-control" name="img" placeholder="Introducir Direccion">
            </div>
        </div><!--end col-->
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Role:</strong>
                <select name="role[]" class="form-control" multiple="multiple">
                    @foreach ($role as $value => $label)
                        <option value="{{ $value }}">
                            {{ $label }}
                        </option>
                     @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="hstack gap-2 justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </div><!--end col-->
    </div><!--end row-->
    </form>
    </div>
    </div>
    </div>
    </div>

@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
  var el = document.getElementById('nuevoUsuario');
  if (el) {
    var modal = new bootstrap.Modal(el);
    modal.show();
  }
});
</script>
@endif
{{-- @extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Create New User</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm mb-2" href="{{ route('users.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>

@if (count($errors) > 0)
    <div class="alert alert-danger">
      <strong>Whoops!</strong> There were some problems with your input.<br><br>
      <ul>
         @foreach ($errors->all() as $error)
           <li>{{ $error }}</li>
         @endforeach
      </ul>
    </div>
@endif

<form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" placeholder="Name" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                <input type="email" name="email" placeholder="Email" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Password:</strong>
                <input type="password" name="password" placeholder="Password" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Confirm Password:</strong>
                <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Role:</strong>
                <select name="roles[]" class="form-control" multiple="multiple">
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}">
                            {{ $label }}
                        </option>
                     @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
        </div>
    </div>
</form>

<p class="text-center text-primary"><small>Tutorial by ItSolutionStuff.com</small></p>
@endsection --}}