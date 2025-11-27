<!-- Grids in modals -->

<div class="modal fade" id="verProducto-{{$product->id}}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="verProducto-{{$product->id}}"><b class="fw-bold">Producto:</b> {{$product->name}}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
  
    <div class="row g-3">
       
        <div class="col-xxl-12 text-center">
            <div>
                <img src="{{asset('storage/'. $product->image)}}" class="img-fluid rounded-2" alt="" width="150">
            </div>
        </div><!--end col-->
        <div class="col-xxl-2">
        </div>
        <div class="col-xxl-3">
            <div>
                <label for="firstName" class="form-label">Estado</label>
                @if ($product->status == 1)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </div>
        </div><!--end col-->
        {{-- <div class="col-xxl-3">
            <div>
                <label for="firstName" class="form-label">Stock</label>
                <p type="text" class="">{{$product->stock}}</p>
            </div>
        </div><!--end col-->
        <div class="col-lg-4">
            <label for="genderInput" class="form-label">Precio</label>
            <input type="email" class="form-control" name="stock" value="{{$product->price}}" placeholder="Introducir Email">
        </div><!--end col--> --}}
        <div class="col-xxl-12">
            <div>
                <label for="firstName" class="form-label">Description: 
                {{$product->description}}</label>
            </div>
        </div><!--end col-->
        <div class="modal-footer">
            
            
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <!-- Lista de iconos centrada -->
            <ul class="list-inline mb-0 d-flex justify-content-center flex-grow-1">
                <li class="list-inline-item">
                    <a href="javascript:void(0);" class="lh-1 align-middle link-secondary"><i class="ri-facebook-fill"></i></a>
                </li>
                <li class="list-inline-item">
                    <a href="javascript:void(0);" class="lh-1 align-middle link-success"><i class="ri-whatsapp-line"></i></a>
                </li>
                <li class="list-inline-item">
                    <a href="javascript:void(0);" class="lh-1 align-middle link-primary"><i class="ri-linkedin-fill"></i></a>
                </li>
                <li class="list-inline-item">
                    <a href="javascript:void(0);" class="lh-1 align-middle link-danger"><i class="ri-slack-fill"></i></a>
                </li>
            </ul>
            
            <!-- Botón a la derecha -->
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
        
        
    </div><!--end row-->
  
    </div>
    </div>
    </div>
    </div>