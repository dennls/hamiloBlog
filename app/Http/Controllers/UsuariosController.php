<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index()
    {
        $usuario = User::orderBy('id', 'DESC')->paginate(10);
        return view('usuarios.index', compact('usuario'));
    }
}
