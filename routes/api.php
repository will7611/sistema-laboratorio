<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Orders;
use App\Models\Result;

// Dejamos esto abierto para pruebas internas o protegido si configuras Sanctum en n8n
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/ordenes', function() {
        return Orders::with('paciente', 'resultado')->get();
    });
});

// Ruta pública simple por si n8n necesita verificar algo
Route::get('/public/ordenes', function () {
    return Orders::with('paciente', 'resultado')->get();
});
