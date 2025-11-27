@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Proformas</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Proformas</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="card">
        <div class="card-header">
            <h2>Detalles proforma #{{$proforma->id}}</h2>
        </div>
        <div class="card-body">
            <h5>Proforma #{{ $proforma->id }}</h5>
            <p>Paciente: {{ $proforma->paciente->name }} {{ $proforma->paciente->last_name }}</p>
            <p>Edad: {{ $proforma->paciente->age }} </p>
            <p>CI: {{ $proforma->paciente->ci }} </p>
            <p>Fecha: {{ $proforma->issue_date }}</p>
            <p>Total: {{ number_format($proforma->total_amount, 2) }} Bs</p>
            <p>Estado: {{$proforma->status}}</p>
          
            <h6>Detalles:</h6>
           
            <ul>
                @foreach($proforma->detalles as $detalle)
                     <li>
                        Análisis: {{ optional($detalle->analisis)->name ?? 'Eliminado' }} <br>
                        ID Análisis: {{ optional($detalle->analisis)->id ?? '-' }} <br>
                        Cantidad: {{ $detalle->amount }} <br>
                        Precio Unitario: {{ number_format(optional($detalle->analisis)->price ?? 0, 2) }} Bs <br>
                        Subtotal: {{ number_format($detalle->subtotal, 2) }} Bs
                     </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>


@endsection