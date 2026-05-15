@extends('layouts.app')

@section('content')
@include('layouts.alerts.alert')
<style>
    /* ESTILOS PERSONALIZADOS PARA EL SWITCH DE GÉNERO */
    .gender-switch-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .gender-switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 26px;
        margin-bottom: 0;
    }
    .gender-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .gender-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #4ba3e3; /* Azul - Masculino */
        transition: .3s;
        border-radius: 34px;
    }
    .gender-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }
    .gender-switch input:checked + .gender-slider {
        background-color: #f37cb4; /* Rosado - Femenino */
    }
    .gender-switch input:checked + .gender-slider:before {
        transform: translateX(29px);
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Sistema - Laboratorio</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                            <li class="breadcrumb-item active">Pacientes</li>
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
                    <h5 class="card-title mb-0">Pacientes</h5>
                </div>
                <div class="card-body">
                    @include('gestion.pacientes.create')
                    
                    <table id="buttons-datatables" class="display table table-bordered tabla-pacientes dt-responsive" style="width:100%">  
                    
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>CI</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Sexo</th>
                                <th>Edad</th>
                                <th>Telefono</th>
                                
                                <th>Email</th>
                                
                                <th>Direccion</th>
                                
                                {{-- @can('client-edit') --}}
                                 <th>   
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoCliente">
                                    Nuevo
                                    </button></th>
                                    {{-- @endcan --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pacientes as $paciente)
                            <tr >
                                <td>{{$paciente->id}}</td>
                                <td>{{$paciente->name}} {{ $paciente->last_name }}</td>
                                
                                <td>
                                    {{$paciente->ci}}
                                </td>
                                  <td>
                                    {{$paciente->birth_date}}
                                </td>
                                <td>{{$paciente->gender}}</td>
                                  <td>
                                    {{$paciente->age}}
                                </td>
                                <td>{{ $paciente->phone}}</td>
                                <td>{{ $paciente->email}}</td>
                                <td>{{ $paciente->address}}</td>
                                {{-- @can('client-edit') --}}
                                    
                                
                                <td>
                                   <div class="dropdown d-inline-block">
    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-more-fill align-middle"></i>
    </button>
    
    <ul class="dropdown-menu dropdown-menu-end">
        
        {{-- OPCIONES SOLO PARA ADMIN --}}
        @role('Admin') 
            <li>
                <button type="button" class="dropdown-item edit-item-btn" data-bs-toggle="modal" data-bs-target="#editarclient-{{$paciente->id}}">
                    <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Editar
                </button>
            </li>
            
            <li>
                <button type="button" class="dropdown-item remove-item-btn" data-bs-toggle="modal" data-bs-target="#eliminarclient-{{$paciente->id}}">
                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Eliminar
                </button>
            </li>
        @else
            {{-- OPCIÓN PARA NO ADMINS (Recepcionista, Bioquímico, etc) --}}
            <li>
    <span class="dropdown-item disabled text-muted" style="cursor: default;">
        <i class="ri-lock-fill align-bottom me-2"></i> Solo lectura
    </span>
</li>
            {{-- O simplemente un texto deshabilitado --}}
            {{-- <li><span class="dropdown-item text-muted disabled">Sin acciones permitidas</span></li> --}}
        @endrole

    </ul>
</div>

                                </td>
                                {{-- @endcan --}}
                            </tr>
                            @include('gestion.pacientes.edit')

                            <div class="modal fade" id="eliminarclient-{{$paciente->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $paciente->name }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <form action="{{ route('pacientes.destroy',['paciente'=>$paciente->id]) }}" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Confirmar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @empty
                                <span></span>
                            @endforelse
                            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
