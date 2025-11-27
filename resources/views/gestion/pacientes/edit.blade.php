<!-- Grids in modals -->
<div class="modal fade" id="editarclient-{{$paciente->id}}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarclient-{{$paciente->id}}">Editar Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form 
                      action="{{ route('pacientes.update', $paciente->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-xxl-6">
                            <div>
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="name" value="{{ $paciente->name }}" placeholder="Introducir Nombre Completo">
                            </div>
                        </div><!--end col-->
                        
                        <div class="col-xxl-6">
                            <div>
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="last_name" value="{{ $paciente->last_name }}" placeholder="Introducir Apellido">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-4">
                            <label class="form-label">CI</label>
                            <input type="text" class="form-control" name="ci" value="{{ $paciente->ci }}" placeholder="Introducir CI">
                        </div><!--end col-->

                        <div class="col-xxl-4">
                            <div>
                                <label class="form-label">Teléfono</label>
                                <input type="text" value="{{ $paciente->phone }}" class="form-control" name="phone" placeholder="Teléfono">
                            </div>
                        </div><!--end col-->

                        <div class="col-xxl-4">
                            <div>
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" value="{{ $paciente->birth_date }}" class="form-control" name="birth_date">
                            </div>
                        </div><!--end col-->

                        <div class="col-xxl-4">
                            <div>
                                <label class="form-label">Edad</label>
                                <input type="number" value="{{ $paciente->age }}" class="form-control" name="age" placeholder="Edad">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-8">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $paciente->email }}" name="email" placeholder="email">
                        </div><!--end col-->

                        <div class="col-xxl-12">
                            <div>
                                <label class="form-label">Dirección</label>
                                <input type="text" class="form-control" value="{{ $paciente->address }}" name="address" placeholder="Introducir dirección">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </form>
            </div>
        </div>
    </div>
</div>
