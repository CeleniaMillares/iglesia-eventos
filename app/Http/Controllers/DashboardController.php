<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Evento;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirigir según el rol
        if ($user->tieneAlgunRol(['Administrador'])) {
            return $this->admin();
        } elseif ($user->tieneAlgunRol(['Pastor Principal'])) {
            return $this->pastor();
        } elseif ($user->tieneAlgunRol(['Secretaría'])) {
            return $this->secretaria();
        } elseif ($user->tieneAlgunRol(['Comité de Eventos'])) {
            return $this->comite();
        }

        // Si es un miembro normal
        return view('dashboard'); 
    }

    private function admin()
    {
        $totalUsuarios = User::count();
        $totalEventos = Evento::count();
        $eventosPendientes = Evento::where('estado', 'pendiente')->count();
        
        return view('dashboards.admin', compact('totalUsuarios', 'totalEventos', 'eventosPendientes'));
    }

    private function pastor()
    {
        $eventosPendientes = Evento::where('estado', 'pendiente')->get();
        $totalAprobados = Evento::where('estado', 'aprobado')->count();
        
        return view('dashboards.pastor', compact('eventosPendientes', 'totalAprobados'));
    }

    private function secretaria()
    {
        $totalMiembros = User::has('roles')->whereHas('roles', function($q){
            $q->where('nombre', 'Miembro');
        })->count();
        
        return view('dashboards.secretaria', compact('totalMiembros'));
    }

    private function comite()
    {
        $misEventos = Evento::where('user_creador_id', Auth::id())->orderBy('fecha_inicio', 'desc')->take(5)->get();
        return view('dashboards.comite', compact('misEventos'));
    }
}