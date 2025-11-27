<!-- Grids in modals -->

<div class="modal fade" id="verUsuario-{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="verUsuario-{{$user->id}}"><b class="fw-bold">Usuario:</b> {{$user->name}}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
  
    <div class="row g-3">
       
        <div class="col-xxl-3 text-center">
            <div>
                <img src="{{asset('storage/'. $user->img)}}" class="img-fluid rounded-2" alt="" width="150">
            </div>
        </div><!--end col-->
        <div class="col-xxl-3">
            <div>
                <label for="roles" class="form-label">Roles</label>
                <div>
                     @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $v)
                            <span class="badge bg-info">{{ $v }}</span>
                        @endforeach
                    @endif
                </div> 
            </div>
        </div><!--end col-->
        <div class="col-xxl-3">
            <div>
                <label for="firstName" class="form-label">Estado</label>
                <br>
                @if ($user->status == 1)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </div>
        </div><!--end col-->
        <div class="col-xxl-3">
            <div>
                <label for="firstName" class="form-label">CI</label>
                <p type="text" class="">{{$user->ci}}</p>
            </div>
        </div><!--end col-->
        <div class="col-xxl-3">
            <div>
                <label for="emailInput" class="form-label">Fecha De Nacimientos</label>
                <input type="date"  class="form-control" value="{{$user->fecha_nacimiento}}" name="fecha_nacimiento" placeholder="Introducir # telefonico">
            </div>
        </div><!--end col-->
        <div class="col-lg-4">
            <label for="genderInput" class="form-label">Email</label>
            <p >{{$user->email}}</p>
        </div><!--end col-->
        
        <div class="col-xxl-4">
            <div>
                <label for="emailInput" class="form-label">Telefono</label>
                <input type="text" class="form-control" value="{{$user->phone}}" name="phone" placeholder="Introducir # telefonico">
            </div>
        </div><!--end col-->
        <div class="col-xxl-4">
            <div>
                <label for="passwordInput" class="form-label">Direccion</label>
                <input type="text" class="form-control" value="{{$user->address}}" name="address" placeholder="Introducir Direccion">
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

{{-- @extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Show User</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $user->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {{ $user->email }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Roles:</strong>
            @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $v)
                    <label class="badge badge-success">{{ $v }}</label>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection --}}