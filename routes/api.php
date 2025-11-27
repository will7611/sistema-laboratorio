<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Orders;
use App\Models\Result;

Route::middleware('auth:sanctum')->group(function () {

      // Obtener todas las órdenes con resultados y paciente
    Route::get('/ordenes', function(Request $request){
        return Orders::with('paciente', 'resultado')->get();
    });

    // Obtener un resultado específico
    Route::get('/resultados/{resultado}', function(Result $resultado){
        return $resultado->load('orden.paciente');
    });

    // Enviar resultado a n8n (o marcar como enviado)
    Route::post('/resultados/{resultado}/send', function(Request $request, Result $resultado){
        $resultado->status = 'enviado';
        $resultado->save();
        return response()->json([
            'success' => true,
            'message' => 'Resultado enviado a n8n correctamente',
            'resultado' => $resultado
        ]);
    });

    // Subir PDF desde n8n
    Route::post('/resultados/{resultado}/upload', [App\Http\Controllers\ResultController::class, 'uploadPdf']);

    // Ruta pública para n8n (GET)
    Route::get('/ordenes-publicas', function () {
        return Orders::with('paciente', 'resultado')->get();
    });
});
Route::get('/ordenes-publicas', function () {
    return Orders::with('paciente', 'resultado')->get();
});