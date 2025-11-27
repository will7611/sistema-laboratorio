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

class ProformaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proformas = Proforma::all();
        return view('gestion.proformas.index', compact('proformas'));
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
    public function show(Proforma $proforma)
    {
        $proforma->load('paciente', 'detalles.analisis', 'orden');
        return view('gestion.proformas.show', compact('proforma'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proforma $proforma)
    {
        //
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
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proforma $proforma)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proforma $proforma)
    {
        //
    }
}
