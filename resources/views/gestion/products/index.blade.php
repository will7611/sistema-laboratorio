@extends('layouts.app')

@section('content')
@include('layouts.alerts.alert')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Sistema de Ventas</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                            <li class="breadcrumb-item active">Productos</li>
                                    </ol>
            </div>
            
        </div>
    </div>
</div>
<div class="row">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Productos</h5>
                </div>
                <div class="card-body">
                    @include('gestion.products.create')
                    <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Imagen</th>
                                <th>Categoria</th>
                                <th>Nombre</th>
                                <th>Description</th>
                                
                                <th>Estado</th>
                                @can('product-edit')
                                    
                                
                                <th><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoProducto">
                                    Nuevo
                                    </button></th>
                                    @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td>{{$product->id}}</td>
                                <td>
                                    @if (file_exists(public_path('storage/' . $product->image)))
                                     <img src="{{ asset('storage/' . $product->image) }}" alt="{{$product->name}}" width="50">  
                                   @else
                                    <img src="{{ asset('assets/images/users/deafult-user.jpg') }}" alt="{{$product->name}}" width="50">
                                   @endif
                                </td>
                                <td>{{$product->categoria->name}}</td>
                                <td>{{$product->name}}</td>
                                <td>{{$product->description}}</td>
                                
                                <td>
                                    @if ($product->status == 1)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                
                                @can('product-edit')
                                
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="#!" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#verProducto-{{$product->id}}"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> Ver</a></li>
                                            <li>
                                                @if ($product->status == 1)
                                                    <button title="Deshabilitar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$product->id}}" class="dropdown-item remove-item-btn">
                                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>  Deshabilitar
                                                    </button>
                                                    @else
                                                    <button title="Habilitar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$product->id}}" class="dropdown-item remove-item-btn">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>Habilitar
                                                    </button>
                                                 @endif
                                            </li>
                                            <li><a href="" class="dropdown-item edit-item-btn" data-bs-toggle="modal" data-bs-target="#editarProducto-{{$product->id}}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Editar</a></li>
                                            <li>
                                                <a class="dropdown-item remove-item-btn" data-bs-toggle="modal" data-bs-target="#eliminarProducto-{{$product->id}}">
                                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Eliminar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                @endcan
                            </tr>
                            @include('gestion.products.show')
                            @include('gestion.products.edit')
                             <!-- Modal -->
                    <div class="modal fade" id="eliminarProducto-{{$product->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{ $product->name }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('products.destroy',['product'=>$product->id]) }}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="confirmModal-{{$product->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{$product->name}}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{ $product->status == 1 ? '¿Seguro que quieres deshabilitar el Producto?' : '¿Seguro que quieres restaurar el Producto?' }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('product/estado',['product'=>$product->id]) }}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                            @empty
                                <span>No hay datos...</span>
                            @endforelse
                            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection