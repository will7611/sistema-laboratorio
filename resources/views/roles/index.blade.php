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
                                            <li class="breadcrumb-item active">Roles</li>
                                    </ol>
            </div>
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Lista de Roles</h5>
            </div>
            <div class="card-body">
                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            @can('role-edit')
                                <th class="text-center">
                                <a href="{{route('roles.create')}}" class="btn btn-sm btn-success">Nuevo Registro</a>
                            </th>
                            @endcan
                            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($roles as $key => $rol)
                        <tr>
                            <td>{{ $rol->id }}</td>
                            <td>{{ $rol->name }}</td>
                            @can('role-edit')
                               <td class="text-center">
                                <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            {{-- <li><a href="{{ route('roles.edit',$rol->id) }}" class="dropdown-item" ><i class="ri-eye-fill align-bottom me-2 text-muted"></i> Ver</a></li> --}}
                                            <li><a href="{{ route('roles.edit',$rol->id) }}" class="dropdown-item edit-item-btn" ><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Editar</a></li>
                                            <li>
                                                 <form method="POST" action="{{ route('users.destroy', $rol->id) }}" style="display:inline">
                                                   @csrf
                                                    @method('DELETE')
                                                <button class="dropdown-item remove-item-btn" type="submit">
                                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Eliminar
                                                </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                            </td> 
                            @endcan
                            
                        </tr>
                        
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection



