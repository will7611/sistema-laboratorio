<!-- Grids in modals -->

    <div class="modal fade" id="nuevoCliente" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog modal-md">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="nuevoCliente">Nuevo Paciente</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
    <form action="{{route('pacientes.store')}}" method="POST">
        @csrf
    <div class="row g-3">
       
        <div class="col-xxl-6">
            <div>
                <label for="lastName" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="name"  placeholder="Introducir Nombre Completo">
            </div>
        </div><!--end col-->
        
        <div class="col-xxl-6">
            <div>
                <label for="firstName" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="last_name"  placeholder="Introducir Apellido">
            </div>
        </div><!--end col-->
        <div class="col-lg-4">
            <label for="genderInput" class="form-label">CI</label>
            <input type="text" class="form-control" name="ci"  placeholder="Introducir CI">
        </div><!--end col-->
        <div class="col-xxl-4">
            <div>
                <label for="firstName" class="form-label">Fecha Nacimiento</label>
                <input type="date"  class="form-control" name="birth_date" placeholder="Fecha de Nacimiento">
            </div>
        </div><!--end col-->
        <div class="col-xxl-4">
            <div>
                <label for="firstName" class="form-label">Edad</label>
                <input type="number"  class="form-control" name="age" placeholder="Edad">
            </div>
        </div><!--end col-->
        <div class="col-xxl-4">
            <div>
                <label for="firstName" class="form-label">Telefono</label>
                <input type="text"  class="form-control" name="phone" placeholder="Telefono">
            </div>
        </div><!--end col-->
        <div class="col-lg-8">
            <label for="genderInput" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" placeholder="email">
        </div><!--end col-->
        <div class="col-xxl-12">
            <div>
                <label for="emailInput" class="form-label">Direccion</label>
                <input type="text" class="form-control"  name="address" placeholder="Introducir Direccion">
            </div>
        </div><!--end col-->
    
        <div class="col-lg-12">
            <div class="hstack gap-2 justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
            </div>
        </div><!--end col-->
    </div><!--end row-->
    </form>
    </div>
    </div>
    </div>
    </div>

