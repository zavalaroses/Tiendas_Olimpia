<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    //
    public function getUsuarios(){
        return view('usuarios.index');
    }
}
