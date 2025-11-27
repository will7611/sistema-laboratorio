<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnalysisStoreRequest;
use App\Http\Requests\AnalysisUpdateRequest;
use App\Models\Analysis;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $analisis = Analysis::all();
        return view('gestion.analisis.index', compact('analisis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnalysisStoreRequest $request)
    {
         try {
           
            $data = $request->validated();
            $data['status'] = 1; // si usas columna 'status' en tu tabla
            Analysis::create($data);

            return redirect()->route('analisis.index')->with('success', 'Analisis creado con éxito');

        } catch (\Exception $e) {

            return back()->withErrors([
                'error' => 'Error al guardar Analisis: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Analysis $analysis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Analysis $analysis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnalysisUpdateRequest $request, Analysis $analisi)
    {
         try {
            // dd($request->all());
 
            $data = $request->validated();
            $data['status'] = 1; 
            // $before = $analisi->getOriginal();  
            $updated = $analisi->update($data);
            // $after = $analisi->fresh();  
            // dd(compact('before','data','updated','after'));
            return redirect()->route('analisis.index')->with('success', 'Analisis actualizado con éxito');

        } catch (\Exception $e) {

            return back()->withErrors([
                'error' => 'Error al actualizar Analisis: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Analysis $analysis)
    {
        try {
            if($analysis){

                $analysis->delete();
            }

            return redirect()->route('analisis.index')->with('success', 'Analisis eliminado con éxito');

        } catch (\Exception $e) {

            return back()->withErrors([
                'error' => 'Error al eliminar Analisis: ' . $e->getMessage()
            ])->withInput();
        }
    }
     public function estado($id)
    {
        $analisis = Analysis::find($id);
        if ($analisis->status == 1) {
            Analysis::where('id', $analisis->id)
                ->update([
                    'status' => 0
                ]);
            return redirect()->route('analisis.index')->with('success', 'Analisis Deshabilitado');
        } else {
            Analysis::where('id', $analisis->id)
                ->update([
                    'status' => 1
                ]);
            return redirect()->route('analisis.index')->with('success', 'Analisis Restaurado Correctamente');
        }

    }
}
