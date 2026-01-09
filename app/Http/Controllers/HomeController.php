<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Paciente;
use App\Models\Proforma;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
         // Métricas principales
    $totalPaciente = Paciente::count();
    $proformasCount = Proforma::whereMonth('created_at', now()->month)->count();
    $resultadosPendientes = Result::where('status', '!=', 'validado')->count();
    $ingresosHoy = Proforma::whereDate('created_at', now())->sum('total_amount');

    // Datos para gráficos
    $ordenesCompletadas = Orders::where('status', 'completada')->count();
    $ordenesProceso = Orders::where('status', 'en_proceso')->count();
    $ordenesPendientes = Orders::where('status', 'pendiente')->count();

    return view('home', compact(
        'totalPaciente', 
        'proformasCount', 
        'resultadosPendientes', 
        'ingresosHoy',
        'ordenesCompletadas',
        'ordenesProceso',
        'ordenesPendientes'
    ));
    }
}
