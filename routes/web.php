<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\GarantiasController;
use App\Http\Controllers\ApartadosController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\Auth\RegisteredUserController;

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
Route::middleware(['auth','role'])->controller(UsuariosController::class)->group(function () {
    Route::get('/get-users', 'getUsuarios')->name('getUsuarios');
    Route::get('/get-data-usuarios', 'getDataUsuarios')->name('getDataUsuarios');
    Route::get('/get-catalogo-roles','getCatalogoRoles')->name('getCatalogoRoles');
});
Route::middleware(['auth','role'])->group(function(){
    Route::post('/register-user', [RegisteredUserController::class, 'store']);
});
// rutas de inventarios
Route::middleware('auth')->controller(InventarioController::class)->group(function(){
    Route::get('/get-inventario','getInventario')->name('getInventario');
    Route::get('/get-catalogo-muebles','getCatmuebles')->name('getCatmuebles');
    Route::post('/post-add-entrada','postAddEntrada')->name('postAddEntrada');
    Route::get('/get-data-inventario/{tiendaId?}','getData')->name('getData');
    
});
// rutas de garantias
Route::middleware('auth')->controller(GarantiasController::class)->group(function(){
    Route::get('/get-garantias','getGarantias')->name('getGarantias');
    Route::get('/get-data-muebles-by-tienda/{tienda?}','getMueblesByTienda')->name('getMueblesByTienda');
    Route::post('/post-add-garantia','postAddGarantia')->name('postAddGarantia');
    Route::get('/get-data-garantias/{tienda?}','getDataGarantias')->name('getDataGarantias');
    Route::post('/terminar-garantia','postTerminarGarantia')->name('postTerminarGarantia');

});
// rutas de apartados
Route::middleware('auth')->controller(ApartadosController::class)->group(function(){
    Route::get('/get-apartados','getApartados')->name('getApartados');
    Route::get('/get-precio-by-idMueble/{id?}','getPreciosById')->name('getPreciosById');
    Route::post('/post-add-apartado','postAddPartido')->name('postAddPartido');
    Route::get('/get-data-apartados/{tienda?}','getDataApartados')->name('getDataApartados');
    Route::get('/get-cantidad-restante/{id}','getMontoRestante')->name('getMontoRestante');
    Route::post('/post-pagar-adelanto','postAddAdelanto')->name('postAddAdelanto');
});
// rutas de ventas
Route::middleware('auth')->controller(VentasController::class)->group(function(){
    Route::get('/get-ventas','getVentas')->name('getVentas');
    Route::get('/get-data-salidas-all/{tienda?}','getDataSalidas')->name('getDataSalidas');
    Route::get('/get-choferes-catalogo/{tienda?}','getChoferesEnvio')->name('getChoferesEnvio');
    Route::get('/get-chofer-info-salida/{id?}','getDataToSalida')->name('getDataToSalida');
    Route::post('/post-agendar-salida','postAgendarSalida')->name('postAgendarSalida');
    Route::post('/post-agregar-venta','postAddVenta')->name('postAddVenta');
    Route::post('/finalizar-venta','postFinalizarVenta')->name('postFinalizarVenta');
    
});
// rutas de catalogos
Route::middleware(['auth','role'])->controller(CatalogoController::class)->group(function(){
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
    Route::get('/get-mueble-by-id/{id?}','getMuebleByid')->name('getMuebleByid');
    Route::post('/post-update-mueble','postUpdateMueble')->name('postUpdateMueble');
    Route::post('/delete-cat-mueble','postDeleteMueble')->name('postDeleteMueble');
    Route::get('/get-index-proveedores','getProveedores')->name('getProveedores');
    Route::post('/post-add-cat-proveedores','postAddProveedor')->name('postAddProveedor');
    
    Route::get('/get-proveedor-to-edit','getProveedorById')->name('getProveedorById');
    Route::post('/post-edit-proveedor','postUpdateProveedor')->name('postUpdateProveedor');
    Route::post('/delete-cat-proveedor','postDeleteProveedor')->name('postDeleteProveedor');
});
Route::middleware(['auth'])->controller(CatalogoController::class)->group(function(){
    Route::get('/get-data-cat-proveedores','getDataProveedores')->name('getDataProveedores');
    Route::get('/get-data-muebles','getDataMuebles')->name('getDataMuebles');
});
Route::middleware('auth')->controller(CajaController::class)->group(function(){
    Route::get('/get-index-cajas','getIndex')->name('getCajas');
    Route::get('/get-data-transacciones/{tienda?}','getData')->name('getData');
    Route::get('/get-resumen-corte/{tienda?}','getResumenCorte')->name('getResumenCorte');
    Route::post('/cerrar-corte','cerrarCorte')->name('cerrarCorte');
    Route::post('/post-add-egresos','postAddEgreso')->name('postAddEgreso');
    Route::get('/get-index-historial-cajas','getHistorialCajas')->name('getHistorialCajas');
});
Route::middleware(['auth','role'])->controller(CajaController::class)->group(function(){
    Route::get('/get-data-historial-cajas','getDataHistorialCortes')->name('getDataHistorialCortes');
    Route::get('/get-detalles-corte/{id?}','getDetalleCorte')->name('getDetalleCorte');
});

require __DIR__.'/auth.php';
