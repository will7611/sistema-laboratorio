<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:permission-list|permission-create', ['only' => ['index','store']]);
         $this->middleware('permission:permission-create', ['only' => ['create','store']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permisos = Permission::all();

        return view('gestion.permisos.index', compact('permisos'));
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
    public function store(Request $request, Permission $permiso)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:permissions,name,'.$permiso->id
            ]);

            $permiso->name = $validatedData['name'];
            $permiso->save();

            return redirect()->route('permisos.index')->with('success', 'Permiso creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('permisos.index')->with('error', 'No se pudo crear el permiso', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
