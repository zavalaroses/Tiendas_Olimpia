<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Log;

class CajaController extends Controller
{
    public function getIndex(){
        return view('caja.index');
    }


}
