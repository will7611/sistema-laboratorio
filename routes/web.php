<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('permisos', PermisoController::class)->names('permisos');
    
    Route::resource('users', UserController::class);
    Route::delete('user/estado/{user}', [UserController::class, 'estado'])->name('user/estado');
    
    Route::resource('pacientes', PacienteController::class);
    Route::delete('paciente/estado/{paciente}', [PacienteController::class, 'estado'])->name('paciente/estado');
  
    Route::resource('proformas', ProformaController::class);

    Route::resource('analisis', AnalysisController::class);
    Route::delete('analisis/estado/{analisi}', [AnalysisController::class, 'estado'])->name('analisis/estado');

    // Proformas
    Route::resource('proformas', ProformaController::class);
    // Ruta extra para aceptar proforma y crear orden
    Route::post('proformas/{proforma}/aceptar', [ProformaController::class, 'aceptar'])
    ->name('proformas.aceptar');

    // Órdenes (solo index y show por ahora)
    Route::resource('ordenes', OrdersController::class)->only(['index', 'show']);

    // Resultados: subir PDF (no uso resource completo, sino 2 rutas)
    Route::get('resultados/{resultado}/upload', [ResultController::class, 'uploadForm'])
        ->name('resultados.uploadForm');

    Route::post('resultados/{resultado}/upload', [ResultController::class, 'uploadPdf'])
        ->name('resultados.uploadPdf');
    
    // routes/web.php
    Route::resource('resultados', ResultController::class)->only(['index', 'show', 'update']);
    
    // routes/web.php
    Route::post('resultados/{id}/enviar', [ResultController::class, 'sendToN8n'])
    ->name('resultados.enviar');


    Route::post('/resultados/{id}/send', [ResultController::class, 'sendToN8n'])->name('resultados.sendPdf');

});