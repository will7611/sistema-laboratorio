<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\Orders;
use App\Models\Paciente;
use App\Models\Proforma;
use App\Models\ProformaDetail;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProformaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proformas = Proforma::all();
        $detalles = Proforma::all();
        return view('gestion.proformas.index', compact('proformas', 'detalles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pacientes = Paciente::orderBy('name')->get();
        $analisis  = Analysis::where('status', true)->orderBy('name')->get();
        return view('gestion.proformas.create', compact('pacientes', 'analisis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'paciente_id'   => 'required|exists:pacientes,id',
            'items'         => 'required|array|min:1',
            'items.*.analysis_id' => 'required|exists:analyses,id',
            'items.*.cantidad'    => 'required|integer|min:1',
        ]);
       // dd($request->all());
        try {
            DB::beginTransaction();

            $pacienteId = $request->paciente_id;
            $items      = $request->items;

            $total = 0;
            $detalles = [];

            foreach ($items as $item) {
                $analisis = Analysis::findOrFail($item['analysis_id']);
                $cantidad = (int) $item['cantidad'];
                $subtotal = $analisis->price * $cantidad;

                $total += $subtotal;

                $detalles[] = [
                    'analysis_id' => $analisis->id,
                    'amount'      => $cantidad,
                    'unit_price'  => $analisis->price,
                    'subtotal'    => $subtotal,
                ];
            }

            $proforma = Proforma::create([
                'paciente_id'  => $pacienteId,
                'issue_date'   => Carbon::now(),
                'total_amount' => $total,
                'status'       => 'pendiente',
            ]);

            foreach ($detalles as $detalle) {
                $detalle['proforma_id'] = $proforma->id;
                ProformaDetail::create($detalle);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Proforma creada con éxito',
                    'proforma' => $proforma->load('detalles'),
                ]);
            }

            return redirect()->route('proformas.index')
                ->with('success', 'Proforma creada con éxitosssss');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear proforma',
                    'error'   => $e->getMessage(),
                ], 500);
            }

            return back()->withErrors('Error al crear proforma: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
     public function show($id)
    {
        
      $proforma = Proforma::with(['paciente', 'detalles.analisis'])->find($id);

    if (!$proforma) {
        return redirect()->route('proformas.index')->with('error', 'Proforma no encontrada');
    }

    return view('gestion.proformas.show', compact('proforma'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // 1. Cargar la proforma JUNTO con paciente y los análisis de cada detalle.
        // 'detalles.analisis' es VITAL para que la tabla de JS se llene.
        $proforma = Proforma::with(['paciente', 'detalles.analisis'])->findOrFail($id);

        // 2. Validación: No dejar editar si ya tiene orden o está aceptada
        if ($proforma->status == 'aceptada' || $proforma->orden) {
            return redirect()->route('proformas.index')
                ->with('error', 'No se puede editar una proforma que ya ha sido aceptada.');
        }

        // 3. Cargar listas para los desplegables
        $pacientes = Paciente::all();
        $analisis  = Analysis::all();

        // 4. Retornar vista
        return view('gestion.proformas.edit', compact('proforma', 'pacientes', 'analisis'));
    }
    // Aceptar proforma y crear orden+resultado
    public function aceptar(Request $request, Proforma $proforma)
    {
        try {
            DB::beginTransaction();

            if ($proforma->orden) {
                if ($proforma->status !== 'aceptada') {
                    $proforma->update(['status' => 'aceptada']);
                }

                DB::commit();

                if ($request->ajax()) {
                    return response()->json([
                        'success'  => true,
                        'message'  => 'La proforma ya tenía orden creada.',
                        'proforma' => $proforma->load('orden'),
                    ]);
                }

                return back()->with('info', 'La proforma ya había sido aceptada.');
            }

            $proforma->update(['status' => 'aceptada']);

            $orden = Orders::create([
                'paciente_id'   => $proforma->paciente_id,
                'proforma_id'   => $proforma->id,
                'creation_date' => Carbon::now(),
                'status'        => 'pendiente',
            ]);

            $resultado = Result::create([
                'order_id' => $orden->id,
                'status'   => 'pendiente',
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success'   => true,
                    'message'   => 'Proforma aceptada y orden creada correctamente.',
                    'proforma'  => $proforma->load('orden'),
                    'orden'     => $orden,
                    'resultado' => $resultado,
                ]);
            }

            return back()->with('success', 'Proforma aceptada y orden creada.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al aceptar la proforma.',
                    'error'   => $e->getMessage(),
                ], 500);
            }

            return back()->withErrors('Error al aceptar la proforma: ' . $e->getMessage());
        }
    }
   public function detalles($id)
    {
        $proforma = Proforma::with(['paciente', 'detalles.analisis', 'orden'])->find($id);

        if (!$proforma) {
            return response()->json(['error' => 'Proforma no encontrada'], 404);
        }

        return view('gestion.proformas.detalles-ajax', compact('proforma'));
    }  
    
    
    public function detallesAjax($id)
    {
        // Cargar la proforma con paciente y detalles + análisis
        $proforma = Proforma::with(['paciente', 'detalles.analisis'])->find($id);

        if (!$proforma) {
            // Retornar error si no se encuentra la proforma
            return response()->json([
                'status' => 'error',
                'message' => 'Proforma no encontrada.'
            ], 404);
        }

        // Asegurarnos de que 'detalles' sea un array aunque esté vacío
        $detalles = $proforma->detalles ?? collect();

        // Retornar vista parcial con los datos
        return view('gestion.proformas.detalles-ajax', compact('proforma', 'detalles'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function revertir($id)
    {
        try {
            $proforma = Proforma::findOrFail($id);
            
            // Verificamos si tiene orden
            if ($proforma->orden) {
                // OPCIÓN A: Eliminar la orden (si la lógica de negocio lo permite)
                $proforma->orden->delete(); 
                
                // O OPCIÓN B: Solo desvincular (si quieres mantener registro de la orden pero cancelada)
                // $proforma->orden->status = 'anulada';
                // $proforma->orden->save();
            }

            $proforma->status = 'pendiente';
            // Desvincular la relación si es necesario o al borrar la orden ya se soluciona
            $proforma->save();

            return response()->json([
                'success' => true, 
                'message' => 'Proforma revertida a pendiente',
                'proforma_id' => $proforma->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
       // 1. Validación
        $request->validate([
            'patient_id'   => 'required|exists:pacientes,id',
            'analisis_id'  => 'required|array|min:1',
            'cantidades'   => 'required|array|min:1', // Validamos array de cantidades
            'total_amount' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $proforma = Proforma::findOrFail($id);

            // 2. Actualizar Cabecera
            $proforma->update([
                'paciente_id'  => $request->patient_id,
                'total_amount' => $request->total_amount,
                // 'issue_date' usualmente no se cambia, pero si quieres: 'issue_date' => $request->issue_date
            ]);

            // 3. Sincronizar Detalles (Borrar y Crear de nuevo)
            $proforma->detalles()->delete();

            $analisisIds = $request->analisis_id;
            $precios     = $request->precios;
            $cantidades  = $request->cantidades; // Recibimos las cantidades

            for ($i = 0; $i < count($analisisIds); $i++) {
                
                $cantidad = $cantidades[$i];
                $precio   = $precios[$i];
                $subtotal = $cantidad * $precio;

                $proforma->detalles()->create([
                    'analysis_id' => $analisisIds[$i],
                    'unit_price'  => $precio,
                    'amount'      => $cantidad,  // GUARDAMOS LA CANTIDAD
                    'subtotal'    => $subtotal   // GUARDAMOS EL SUBTOTAL REAL
                ]);
            }

            DB::commit();

            return redirect()->route('proformas.index')
                ->with('success', 'Proforma actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proforma $proforma)
    {
        //
    }
}
