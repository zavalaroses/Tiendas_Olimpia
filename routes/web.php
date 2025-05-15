<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\GarantiasController;
use App\Http\Controllers\ApartadosController;
use App\Http\Controllers\VentasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
    
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function(){
    Route::get('/get-users',[UsuariosController::class,'getUsuarios'])->name('getUsuarios');
});
Route::middleware('auth')->group(function(){
    Route::get('/get-inventario',[InventarioController::class,'getInventario'])->name('getInventario');
});
Route::middleware('auth')->group(function(){
    Route::get('/get-garantias',[InventarioController::class,'getGarantias'])->name('getGarantias');
});
Route::middleware('auth')->group(function(){
    Route::get('/get-apartados',[InventarioController::class,'getApartados'])->name('getApartados');
});
Route::middleware('auth')->group(function(){
    Route::get('/get-ventas',[InventarioController::class,'getVentas'])->name('getVentas');
});

require __DIR__.'/auth.php';
