<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')->orderBy('created_at', 'desc')->paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }
}