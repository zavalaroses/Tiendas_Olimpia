<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApartadosController extends Controller
{
    public function getApartados(){
        return view('apartados.index');
    }
}
