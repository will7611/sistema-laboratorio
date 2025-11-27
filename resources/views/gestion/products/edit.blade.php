<!-- Grids in modals -->

<div class="modal fade" id="editarProducto-{{$product->id}}" tabindex="-1" aria-labelledby="editarProductoLabel-{{$product->id}}" aria-modal="true">
    <div class="modal-dialog ">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="editarProductoLAbel-{{$product->id}}">Editar Producto</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
    <form action="{{route('products.update', $product->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    <div class="row g-3">
        <div class="col-xxl-6">
            <div>
                <label for="lastName" class="form-label">Categoria</label>
               <select name="category_id" id="" class="form-select">
                    <option value="">{{$product->categoria->name}}</option>
                    @foreach ($categorias as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
               </select>
            </div>
        </div><!--end col-->
        <div class="col-xxl-6">
            <div>
                <label for="lastName" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="name" value="{{$product->name}}" placeholder="Introducir Nombre Completo">
            </div>
        </div><!--end col-->
       
        <div class="col-xxl-2">
            <div>
                <label for="firstName" class="form-label">Descripcion</label>
                <textarea type="text" class="form-control" name="description" >{{$product->description}}</textarea>
            </div>
        </div><!--end col-->
        {{-- <div class="col-lg-4">
            <label for="genderInput" class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" value="{{$product->stock}}" placeholder="Introducir Email">
        </div><!--end col-->
      
        <div class="col-xxl-6">
            <div>
                <label for="emailInput" class="form-label">Precio</label>
                <input type="number" class="form-control" value="{{$product->price}}" name="price" placeholder="Introducir # telefonico">
            </div>
        </div><!--end col--> --}}
        <div class="col-xxl-4">
            <div>
                <label for="passwordInput" class="form-label">Imagen Actual</label>
                @if (file_exists(public_path('storage/' . $product->image)))
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{$product->name}}" width="150">  
                @else
                    <img src="{{ asset('assets/images/users/deafult-user.jpg') }}" alt="{{$product->name}}" width="150">
                @endif
            </div>
        </div><!--end col-->
        <div class="col-xxl-4">
            <div>
                <label for="image-{{$product->id}}" class="form-label">Imagen</label>
                <input type="file" id="image-{{$product->id}}" class="form-control" name="image" >
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