<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function getVentas(){
        return view('ventas.index');
    }
}
