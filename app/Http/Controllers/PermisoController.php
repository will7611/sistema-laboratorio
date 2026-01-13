<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    function __construct()
    {
        // Ajusté los permisos para que coincidan con la lógica estándar
         $this->middleware('permission:permission-list', ['only' => ['index']]);
         $this->middleware('permission:permission-create', ['only' => ['create','store']]);
         $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
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
    public function store(Request $request)
    {
        // Validación para CREAR (sin ignorar ID porque es nuevo)
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name'
        ]);

        try {
            // Forma correcta de crear con Spatie
            Permission::create(['name' => $request->name]);

            return redirect()->route('permisos.index')
                ->with('success', 'Permiso creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('permisos.index')
                ->with('error', 'Error al crear: ' . $e->getMessage());
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
        $permiso = Permission::findById($id); // Buscamos por ID

        // Validación para ACTUALIZAR (ignoramos el ID actual para que no de error de unique)
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$permiso->id
        ]);

        try {
            $permiso->update(['name' => $request->name]);

            return redirect()->route('permisos.index')
                ->with('success', 'Permiso actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('permisos.index')
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $permiso = Permission::findById($id);
            $permiso->delete();

            return redirect()->route('permisos.index')
                ->with('success', 'Permiso eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('permisos.index')
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}
