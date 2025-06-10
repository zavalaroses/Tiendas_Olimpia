<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function getChoferes(){
        return view('catalogos.choferes.index');
    }
    public function getTiendas(){
        return view('catalogos.tiendas.index');
    }
}
