@extends('layouts.app')

@section('content')
@include('layouts.alerts.alert')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Sistema - Laboratorio</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                            <li class="breadcrumb-item active">Analisis</li>
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
                    <h5 class="card-title mb-0">Analisis</h5>
                </div>
                <div class="card-body">
                    @include('gestion.analisis.create')
                    
                    <table id="buttons-datatables" class="display table table-bordered dt-responsive" style="width:100%">  
                    
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Area</th>
                                <th>Precio</th>
                                <th>Tiempo en minutos</th>
                                <th>Estado</th>
                               
                                    
                               <th><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaCategoria">
                                    Nuevo
                                </button></th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($analisis as $analisi)
                            <tr >
                                <td>{{$analisi->id}}</td>
                                <td>{{$analisi->name}}</td>
                                <td>{{$analisi->area}}</td>
                                <td>{{$analisi->price}}</td>
                                <td>{{$analisi->duration_minutes}}</td>
                                <td>
                                    @if ($analisi->status == 1)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                {{-- @can('category-edit') --}}
                                    
                                
                                <td>
                                    <div class="dropdown d-inline-block">
    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-more-fill align-middle"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        
        {{-- 1. EDITAR (Solo Admin) --}}
        @role('Admin')
            <li>
                <button type="button" class="dropdown-item edit-item-btn" data-bs-toggle="modal" data-bs-target="#editaranalisis-{{$analisi->id}}">
                    <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Editar
                </button>
            </li>
        @endrole

        {{-- 2. HABILITAR / DESHABILITAR (Admin Y Laboratorista) --}}
        @hasanyrole('Admin|Laboratorista')
            <li>
                @if ($analisi->status == 1)
                    <button title="Deshabilitar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$analisi->id}}" class="dropdown-item remove-item-btn">
                        <i class="ri-stop-circle-fill align-bottom me-2 text-muted"></i> Deshabilitar
                    </button>
                @else
                    <button title="Habilitar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$analisi->id}}" class="dropdown-item remove-item-btn">
                        <i class="ri-play-circle-fill align-bottom me-2 text-muted"></i> Habilitar
                    </button>
                @endif
            </li>
        @endhasanyrole

        {{-- 3. ELIMINAR (Solo Admin) --}}
        @role('Admin')
            <li>
                <button type="button" class="dropdown-item remove-item-btn" data-bs-toggle="modal" data-bs-target="#eliminarcategoria-{{$analisi->id}}">
                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Eliminar
                </button>
            </li>
        @endrole

        {{-- 4. MENSAJE POR DEFECTO (Si no tiene ningún permiso) --}}
        @unlessrole('Admin|Laboratorista')
            <li>
                <span class="dropdown-item disabled text-muted" style="cursor: default;">
                    <i class="ri-lock-fill align-bottom me-2"></i> Solo lectura
                </span>
            </li>
        @endunlessrole

    </ul>
</div>

                                </td>
                                {{-- @endcan --}}
                            </tr>
                            @include('gestion.analisis.edit')

                            <div class="modal fade" id="eliminarcategoria-{{$analisi->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $analisi->name }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <form action="{{ route('analisis.destroy',['analisi'=>$analisi->id]) }}" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Confirmar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="confirmModal-{{$analisi->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">{{$analisi->name}}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $analisi->status == 1 ? '¿Seguro que quieres deshabilitar el proveedor?' : '¿Seguro que quieres restaurar el Proveedor?' }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <form action="{{ route('analisis/estado',['analisi'=>$analisi->id]) }}" method="post">
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