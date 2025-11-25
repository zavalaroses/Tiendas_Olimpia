<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GarantiasController extends Controller
{
    public function getGarantias  (){
        return view('garantias.index');
    }
}
