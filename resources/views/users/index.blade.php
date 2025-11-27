{{-- @extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Users Management</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success mb-2" href="{{ route('users.create') }}"><i class="fa fa-plus"></i> Create New User</a>
        </div>
    </div>
</div>
@session('success')
    <div class="alert alert-success" role="alert"> 
        {{ $value }}
    </div>
@endsession
<table class="table table-bordered">
   <tr>
       <th>No</th>
       <th>Name</th>
       <th>Email</th>
       <th>Roles</th>
       <th width="280px">Action</th>
   </tr>
   @foreach ($data as $key => $user)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
          @if(!empty($user->getRoleNames()))
            @foreach($user->getRoleNames() as $v)
               <label class="badge bg-success">{{ $v }}</label>
            @endforeach
          @endif
        </td>
        <td>
             <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}"><i class="fa-solid fa-list"></i> Show</a>
             <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
              <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline">
                  @csrf
                  @method('DELETE')

                  <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
              </form>
        </td>
    </tr>
 @endforeach
</table>

{!! $data->links('pagination::bootstrap-5') !!} --}}
@extends('layouts.app')
@section('content')
@include('layouts.alerts.alert')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Sistema de Prestamos</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                            <li class="breadcrumb-item active">Usuarios</li>
                                    </ol>
            </div>
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Lista Usuarios</h5>
            </div>
            <div class="card-body">
                @include('users.create')
                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>CI</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Telefono</th>
                            <th>Direccion</th>
                            <th>Estado</th>
                            <th>Roles</th>
                            @can('user-edit')
                            <th class="text-center">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoUsuario">Nuevo Registro</button>
                            </th>    
                            @endcan
                            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $key => $user)
                        <tr>
                            <td>
                                 @if (file_exists(public_path('storage/' . $user->img)))
                                     <img src="{{ asset('storage/' . $user->img) }}" alt="{{$user->name}}" width="50">  
                                   @else
                                    <img src="{{ asset('assets/images/users/deafult-user.jpg') }}" alt="{{$user->name}}" width="50">
                                   @endif
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->ci }}</td>
                            <td>{{ $user->fecha_nacimiento }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->address }}</td>
                             <td>
                                    @if ($user->status == 1)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                            <td>
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $v)
                                    <label class="badge bg-info">{{ $v }}</label>
                                    @endforeach
                                @endif
                            </td>
                            @can('user-edit')  
                                <td class="text-center">
                            {{-- <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                 --}}
                                {{-- <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                                </form> --}}

                                <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="#!" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#verUsuario-{{$user->id}}"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> Ver</a></li>
                                            <li><a href="" class="dropdown-item edit-item-btn" data-bs-toggle="modal" data-bs-target="#editarUsuario-{{$user->id}}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Editar</a></li>
                                            {{-- <li>
                                                <a class="dropdown-item remove-item-btn" data-bs-toggle="modal" data-bs-target="#eliminarUsuario-{{$user->id}}">
                                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Eliminar
                                                </a>
                                            </li> --}}

                                            <li>
                                                @if ($user->status == 1)
                                                    <button title="Deshabilitar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$user->id}}" class="dropdown-item remove-item-btn">
                                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>  Deshabilitar
                                                    </button>
                                                    @else
                                                    <button title="Habilitar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$user->id}}" class="dropdown-item remove-item-btn">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>Habilitar
                                                    </button>
                                                 @endif
                                            </li>
                                            {{-- <li><a href="" class="dropdown-item edit-item-btn" data-bs-toggle="modal" data-bs-target="#editarProducto-{{$user->id}}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Editar</a></li> --}}
                                            <li>
                                                <button type="button" class="dropdown-item remove-item-btn" data-bs-toggle="modal" data-bs-target="#eliminaruser-{{$user->id}}">
                                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Eliminar
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                            </td>
                          @endcan

                             <div class="modal fade" id="eliminaruser-{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $user->name }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <form action="{{ route('users.destroy',['user'=>$user->id]) }}" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Confirmar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    <div class="modal fade" id="confirmModal-{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{$user->name}}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{ $user->status == 1 ? '¿Seguro que quieres deshabilitar el Usuario?' : '¿Seguro que quieres restaurar el Usuario?' }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('user/estado',['user'=>$user->id]) }}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                        </tr>
                        @include('users.show')
                        @include('users.edit') 
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
