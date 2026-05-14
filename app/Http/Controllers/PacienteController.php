<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pacientes = Paciente::all();

        return view('gestion.pacientes.index', compact('pacientes'));
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
    public function store(StorePacienteRequest $request)
    {
         try {
            $data = $request->validated();
            $data['status'] = 1;
            Paciente::create($data);

            return redirect()->route('pacientes.index')->with('success', 'Paciente creada con Exito');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error Al guardar Paciente: '. $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paciente $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePacienteRequest $request, Paciente $paciente)
    {
        try {
            $data = $request->validated();
            $paciente->update($data);

            return redirect()->route('pacientes.index')->with('success', 'Paciente actualizada con exito');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error al actualizar el paciente: ', $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        try {
            if($paciente){

                $paciente->delete();
            }
            return redirect()->route('pacientes.index')->with('success', 'Pacientes Eliminado Exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('pacientes.index')->with('error', 'Error al eliminar Paciente', $e->getMessage());
        }
    }
    public function estado($id)
    {
        $paciente = Paciente::find($id);
        if ($paciente->status == 1) {
            Paciente::where('id', $paciente->id)
                ->update([
                    'status' => 0
                ]);
            return redirect()->route('users.index')->with('success', 'Usuario Deshabilitado');
        } else {
            Paciente::where('id', $paciente->id)
                ->update([
                    'status' => 1
                ]);
            return redirect()->route('users.index')->with('success', 'Usuario Restaurado Correctamente');
        }

    }
public function search(Request $request)
{
    try {
        $query = trim($request->get('search', ''));

        // Si no hay búsqueda, devolver los 10 más recientes
        if ($query === '') {
            $recientes = \App\Models\Paciente::select('id', 'name', 'last_name', 'ci', 'created_at')
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'recientes'  => $recientes,
                'resultados' => []
            ]);
        }

        $terminos = preg_split('/\s+/', $query);
        $queryBuilder = \App\Models\Paciente::select('id', 'name', 'last_name', 'ci');

        foreach ($terminos as $termino) {
            if ($termino !== '') {
                $queryBuilder->where(function ($q) use ($termino) {
                    $q->where('name', 'ILIKE', '%' . $termino . '%')
                      ->orWhere('last_name', 'ILIKE', '%' . $termino . '%')
                      ->orWhere('ci', 'ILIKE', '%' . $termino . '%');
                });
            }
        }

        $resultados = $queryBuilder
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json([
            'recientes'  => [],
            'resultados' => $resultados
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 500);
    }
}


}
