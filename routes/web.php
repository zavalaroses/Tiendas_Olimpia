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
    Route::get('/get-catalogo-tiendas','getCatalgoTiendas')->name('getCatalgoTiendas');
    Route::post('/post-add-chofer','postAddChofer')->name('postAddChofer');
    Route::get('/get-data-choferes','getDataChoferes')->name('getDataChoferes');
    Route::get('/get-chofer-to-edit','getChoferById')->name('getChoferById');
    Route::post('/post-update-chofer','postAddChofer')->name('postAddChofer');
    Route::post('/delete-cat-chofer','postDeleteCatChofer')->name('postDeleteCatChofer');
    Route::get('/get-muebles','getMuebles')->name('getMuebles');
    Route::post('/post-add-mueble','postAddMuble')->name('postAddMuble');
    Route::get('/get-data-muebles','getDataMuebles')->name('getDataMuebles');
    Route::get('/get-mueble-by-id/{id?}','getMuebleByid')->name('getMuebleByid');
    Route::post('/post-update-mueble','postUpdateMueble')->name('postUpdateMueble');
    Route::post('/delete-cat-mueble','postDeleteMueble')->name('postDeleteMueble');
    Route::get('/get-index-proveedores','getProveedores')->name('getProveedores');
    Route::post('/post-add-cat-proveedores','postAddProveedor')->name('postAddProveedor');
    Route::get('/get-data-cat-proveedores','getDataProveedores')->name('getDataProveedores');
    Route::get('/get-proveedor-to-edit','getProveedorById')->name('getProveedorById');
    Route::post('/post-edit-proveedor','postUpdateProveedor')->name('postUpdateProveedor');
    Route::post('/delete-cat-proveedor','postDeleteProveedor')->name('postDeleteProveedor');
});

require __DIR__.'/auth.php';
