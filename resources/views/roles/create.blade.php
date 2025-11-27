@extends('layouts.app')

@section('content')
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
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif
<div class="row">
       <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Crear Nuevo Rol y Asignar Permisos</h3>
            </div>
            <div class="card-body">
<form method="POST" action="{{ route('roles.store') }}">
    @csrf
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <p>Nombre de Rol:</p>
                <input type="text" name="name" placeholder="Introducir Nombre de Rol" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
            <div class="form-group">
                <p>Permission:</p>
                @foreach($permission as $value)
                    <label><input type="checkbox" name="permission[{{$value->id}}]" value="{{$value->id}}" class="name">
                    {{ $value->name }}</label>
                <br/>
                @endforeach
            </div>
        </div>
         <div class="col-12 d-flex justify-content-center gap-2 mt-3 mb-3">
            <a href="{{route('roles.index')}}" class="btn btn-danger btn-sm">
                Volver
            </a>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-floppy-disk"></i> Crear
            </button>
        </div>
    </div>
</form>
</div>
        </div>
       </div>
</div>

@endsection