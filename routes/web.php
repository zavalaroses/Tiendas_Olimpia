<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\GarantiasController;
use App\Http\Controllers\ApartadosController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\CatalogoController;

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
// rutas de usuarios
Route::middleware('auth')->controller(UsuariosController::class)->group(function () {
    Route::get('/get-users', 'getUsuarios')->name('getUsuarios');
    Route::get('/get-data-usuarios', 'getDataUsuarios')->name('getDataUsuarios');
});
// rutas de inventarios
Route::middleware('auth')->group(function(){
    Route::get('/get-inventario',[InventarioController::class,'getInventario'])->name('getInventario');
});
// rutas de garantias
Route::middleware('auth')->group(function(){
    Route::get('/get-garantias',[GarantiasController::class,'getGarantias'])->name('getGarantias');
});
// rutas de apartados
Route::middleware('auth')->group(function(){
    Route::get('/get-apartados',[ApartadosController::class,'getApartados'])->name('getApartados');
});
// rutas de ventas
Route::middleware('auth')->group(function(){
    Route::get('/get-ventas',[VentasController::class,'getVentas'])->name('getVentas');
});
// rutas de catalogos
Route::middleware('auth')->controller(CatalogoController::class)->group(function(){
    Route::get('/get-cat-choferes','getChoferes')->name('getChoferes');
    Route::get('/get-cat-tiendas','getTiendas')->name('getTiendas');
    Route::post('/post-add-cat-tienda','postAddTienda')->name('postAddTienda');
    Route::get('/get-data-cat-tiendas','getDataTiendas')->name('getDataTiendas');

    
});

require __DIR__.'/auth.php';
