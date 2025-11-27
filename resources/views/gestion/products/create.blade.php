<!-- Grids in modals -->

<div class="modal fade" id="nuevoProducto" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="nuevoProducto">Nuevo Producto</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
    <form action="{{route('products.store')}}" method="post" enctype="multipart/form-data">
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
                <label for="firstName" class="form-label">Descripcion</label>
                <textarea type="text" class="form-control" name="description" ></textarea>
            </div>
        </div><!--end col-->
       
     
        <div class="col-xxl-6">
            <div>
                <label for="passwordInput" class="form-label">Categoria</label>
                <select name="category_id" id="" class="form-select">
                    <option value="" >Elige Una Categoria</option>
                    @foreach ($categorias as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>
        </div><!--end col-->
        
        <div class="col-xxl-8">
            <div>
                <label for="passwordInput" class="form-label">Imagen</label>
                <input type="file" class="form-control" name="image" placeholder="Introducir Direccion">
            </div>
        </div><!--end col-->
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