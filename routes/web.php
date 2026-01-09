<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ResultController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    
    // Recursos básicos
    Route::resource('roles', RoleController::class);
    Route::resource('permisos', PermisoController::class)->names('permisos');
    Route::resource('users', UserController::class);
    Route::delete('user/estado/{user}', [UserController::class, 'estado'])->name('user/estado');
    
    Route::resource('pacientes', PacienteController::class);
    Route::delete('paciente/estado/{paciente}', [PacienteController::class, 'estado'])->name('paciente/estado');
  
    Route::resource('analisis', AnalysisController::class);
    Route::delete('analisis/estado/{analisi}', [AnalysisController::class, 'estado'])->name('analisis/estado');

    // Proformas
    Route::resource('proformas', ProformaController::class);
    Route::post('proformas/{proforma}/aceptar', [ProformaController::class, 'aceptar'])->name('proformas.aceptar');
   // Route::get('/proformas/{id}/detalles', [ProformaController::class, 'detalles'])->name('detallesAjax');

    Route::post('proformas/{id}/revertir', [ProformaController::class, 'revertir'])->name('proformas.revertir');


        // Órdenes
    Route::resource('ordenes', OrdersController::class)->only(['index', 'show']);

    // --- RESULTADOS (Aquí está lo importante para n8n) ---
    
    // 1. Ver detalles y editar
    Route::resource('resultados', ResultController::class)->only(['index', 'show', 'update']);

    // 2. Formulario para cargar PDF
    Route::get('resultados/{resultado}/upload', [ResultController::class, 'uploadForm'])
        ->name('resultados.uploadForm');

    // 3. Procesar subida de PDF (Sube archivo y avisa a n8n)
    Route::post('resultados/{resultado}/upload', [ResultController::class, 'uploadPdf'])
        ->name('resultados.uploadPdf');
    
    // 4. Reenviar a n8n manualmente (botón de la tabla)
    Route::post('resultados/{id}/enviar-n8n', [ResultController::class, 'sendToN8n'])
        ->name('resultados.enviarN8n');


});
