<div class="modal fade" id="editaranalisis-{{$analisi->id}}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editaranalisis-{{$analisi->id}}">Editar Análisis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form  action="{{ route('analisis.update', $analisi->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                              @if ($errors->any())
                                    {{ dd($errors->all()) }}
                                @endif
                    <div class="row g-3">
                        <div class="col-xxl-12">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="name"
                                   value="{{ $analisi->name }}">
                        </div>

                        <div class="col-xxl-12">
                            <label class="form-label">Área</label>
                            <input type="text" class="form-control" name="area"
                                   value="{{ $analisi->area }}">
                        </div>

                        <div class="col-xxl-12">
                            <label class="form-label">Precio</label>
                            <input type="text" class="form-control" name="price"
                                   value="{{ $analisi->price }}">
                        </div>

                        <div class="col-xxl-12">
                            <label class="form-label">Duración en minutos</label>
                            <input type="text" class="form-control" name="duration_minutes"
                                   value="{{ $analisi->duration_minutes }}">
                        </div> 

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
