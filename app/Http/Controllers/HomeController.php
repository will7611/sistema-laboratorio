<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Paciente;
use App\Models\Proforma;
use App\Models\Result;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 1. KPIs de las tarjetas superiores
        $totalPaciente = Paciente::count();
        
        // Proformas creadas en el mes actual
        $proformasCount = Proforma::whereMonth('created_at', now()->month)
                                   ->whereYear('created_at', now()->year)
                                   ->count();

        // Resultados que aún están marcados como "pendiente"
        $resultadosPendientes = Result::where('status', 'pendiente')->count();

        // Suma de ingresos de hoy (total_amount debe existir en proformas)
        $ingresosHoy = Proforma::whereDate('created_at', today())->sum('total_amount') ?? 0;

        // 2. LÓGICA PARA EL GRÁFICO DONUT (Basado en la tabla Orders)
        // 'pendiente' -> Órdenes creadas pero sin proceso iniciado
        $ordenesPendientes  = Orders::where('status', 'pendiente')->count();
        
        // 'en_proceso' -> Órdenes que ya se están trabajando (Aceptadas)
        $ordenesProceso     = Orders::where('status', 'en_proceso')->count(); 
        
        // 'validado' o 'entregado' -> Se consideran tareas finalizadas en el gráfico
        $ordenesCompletadas = Orders::whereIn('status', ['validado', 'entregado'])->count();

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