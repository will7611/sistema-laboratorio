<?php

namespace App\Http\Controllers;


use App\Models\Paciente;
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
        $data = [
            'totalUsers' => User::count(),
            'totalPaciente' => Paciente::count(),
        ];
    

        return view('home', $data);
    }
}
