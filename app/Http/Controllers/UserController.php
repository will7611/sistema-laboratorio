<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
     function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : View
    {
        $data = User::latest()->paginate(5);
        
        $roles = Role::all();
        
        $role = Role::pluck('name','name')->all();

        return view('users.index',compact('data',  'roles', 'role'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = Role::pluck('name','name')->all();

        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
           //dd($request->all());
            $data = $request->validated();

            //manejo de imagen
            if($request->hasFile('img')){
                $data['img'] = $request->file('img')->store('users', 'public');
            }

            //encriptar contrase;a
            $data['password'] = Hash::make($data['password']);

            //crear usuario y asignar rol
            $user = User::create($data);
            $user->assignRole($request->input('role'));

            return redirect()->route('users.index')->with('success', 'Usuario Creado Exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Error al crear usaurio: ', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::find($id);
        
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        //$roles = Role::all();
        return view('users.edit',compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(UpdateUserRequest $request, $id)
    {
       try {
            $user = User::findOrFail($id);
            
            // request->validated() ya filtra los datos según las reglas del Request
            $data = $request->validated(); 

            // Lógica de imagen (Correcta)
            if($request->hasFile('img')){
                if($user->img && Storage::disk('public')->exists($user->img)){
                    Storage::disk('public')->delete($user->img);
                }
                $data['img'] = $request->file('img')->store('users', 'public');
            }

            // Lógica de contraseña (Correcta)
            if(!empty($data['password'])){
                $data['password'] = Hash::make($data['password']);
            } else {
                // Importante: Eliminar password del array si está vacío para no sobreescribir con null/vacío
                unset($data['password']); 
            }

            $user->update($data);

            // Actualizar Roles
            // Asegúrate que en el Request la regla sea 'role' y aquí recibas 'role'
            if ($request->has('role')) {
                $user->syncRoles($request->input('role'));
            }

            return redirect()->route('users.index')->with('success', 'Usuario Actualizado correctamente');

        } catch (\Exception $e) {
            // Loguear el error es buena práctica para ti como desarrollador
            \Log::error($e->getMessage());
            return redirect()->route('users.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Eliminar imagen si existe
        if ($user->img && Storage::disk('public')->exists($user->img)) {
            Storage::disk('public')->delete($user->img);
        }
        $user->delete();
    
        return redirect()->route('users.index')
                        ->with('success','Usuario deleted successfully');
    }
    public function estado($id)
    {
        $user = User::find($id);
        if ($user->status == 1) {
            User::where('id', $user->id)
                ->update([
                    'status' => 0
                ]);
            return redirect()->route('users.index')->with('success', 'Usuario Deshabilitado');
        } else {
            User::where('id', $user->id)
                ->update([
                    'status' => 1
                ]);
            return redirect()->route('users.index')->with('success', 'Usuario Restaurado Correctamente');
        }

    }
}
