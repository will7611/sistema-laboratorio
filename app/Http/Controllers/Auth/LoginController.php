<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
     /**
     * Agrega condición para que solo logueen usuarios activos.
     * Auth::attempt() soporta credenciales adicionales además de email/password. [web:170]
     */
    protected function credentials(Request $request)
    {
        return [
            $this->username() => $request->get($this->username()),
            'password' => $request->password,
            'status' => 1, // 1=activo, 0=inactivo
        ];
    }
     /**
     * Mensaje personalizado cuando el usuario existe pero está inactivo.
     * Este método se puede sobrescribir del trait AuthenticatesUsers. [web:200]
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where($this->username(), $request->get($this->username()))->first();

        if ($user && (int) $user->status !== 1) {
            throw ValidationException::withMessages([
                'inactive' => 'No puedes acceder al sistema porque tu usuario está inactivo. Contacta al administrador.',
            ]);
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
