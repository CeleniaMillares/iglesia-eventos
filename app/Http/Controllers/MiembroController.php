<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Miembro;
use Illuminate\Http\Request;

class MiembroController extends Controller
{
    public function index()
    {
        // Traer los usuarios que tienen el rol "Miembro" y su perfil
        $miembros = User::whereHas('roles', function($q){
            $q->where('nombre', 'Miembro');
        })->with('miembro')->paginate(10);

        return view('miembros.index', compact('miembros'));
    }

    public function verificar(User $user)
    {
        // Activar al usuario y su perfil de miembro
        $user->update(['estado' => 'activo']);
        if($user->miembro) {
            $user->miembro->update(['estado_miembro' => 'activo']);
        }
        return back()->with('success', 'Miembro verificado y activado exitosamente.');
    }
}