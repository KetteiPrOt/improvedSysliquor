<?php

use App\Http\Controllers\CashClosingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\PurchasesReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserPermissionsController;

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

Route::get('/', fn() => redirect()->route('login') );

Route::view('panel', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('perfil', 'profile')
    ->middleware(['auth'])
    ->name('profile');

/* --- CRUD Products --- */
Route::middleware(['auth', 'permission:products'])->controller(ProductController::class)->group(function () {
    Route::get('/productos', 'index')->name('products.index');
    Route::get('/productos/crear', 'create')->name('products.create');
    Route::post('/productos', 'store')->name('products.store');
    Route::get('/productos/{product}', 'show')->name('products.show');
    Route::get('/productos/{product}/editar', 'edit')->name('products.edit');
    Route::put('/productos/{product}', 'update')->name('products.update');
    Route::delete('/productos/{product}', 'destroy')->name('products.destroy');
});

/* --- CRU Types --- */
Route::middleware(['auth', 'permission:products'])->controller(TypeController::class)->group(function(){
    Route::get('/tipos', 'index')->name('types.index');
    Route::get('/tipos/crear', 'create')->name('types.create');
    Route::post('/tipos', 'store')->name('types.store');
    Route::get('/tipos/{type}', 'show')->name('types.show');
    Route::get('/tipos/{type}/editar', 'edit')->name('types.edit');
    Route::put('/tipos/{type}', 'update')->name('types.update');
});

/* -- CRU Presentations -- */
Route::middleware(['auth', 'permission:products'])->controller(PresentationController::class)->group(function(){
    Route::get('/presentationes', 'index')->name('presentations.index');
    Route::get('/presentationes/crear', 'create')->name('presentations.create');
    Route::post('/presentationes', 'store')->name('presentations.store');
    Route::get('/presentationes/{presentation}', 'show')->name('presentations.show');
    Route::get('/presentationes/{presentation}/editar', 'edit')->name('presentations.edit');
    Route::put('/presentationes/{presentation}', 'update')->name('presentations.update');
});

/* --- CRUD Clients --- */
Route::middleware(['auth', 'permission:clients'])->controller(ClientController::class)->group(function () {
    Route::get('/clientes', 'index')->name('clients.index');
    Route::get('/clientes/crear', 'create')->name('clients.create');
    Route::post('/clientes', 'store')->name('clients.store');
    Route::get('/clientes/{client}', 'show')->name('clients.show');
    Route::get('/clientes/{client}/editar', 'edit')->name('clients.edit');
    Route::put('/clientes/{client}', 'update')->name('clients.update');
    Route::delete('/clientes/{client}', 'destroy')->name('clients.destroy');
});

/* --- CRUD Providers --- */
Route::middleware(['auth', 'permission:providers'])->controller(ProviderController::class)->group(function () {
    Route::get('/proveedores', 'index')->name('providers.index');
    Route::get('/proveedores/crear', 'create')->name('providers.create');
    Route::post('/proveedores', 'store')->name('providers.store');
    Route::get('/proveedores/{provider}', 'show')->name('providers.show');
    Route::get('/proveedores/{provider}/editar', 'edit')->name('providers.edit');
    Route::put('/proveedores/{provider}', 'update')->name('providers.update');
    Route::delete('/proveedores/{provider}', 'destroy')->name('providers.destroy');
});

/* --- CRUD Sellers --- */
Route::middleware(['auth', 'permission:sellers'])->controller(SellerController::class)->group(function () {
    Route::get('/vendedores', 'index')->name('sellers.index');
    Route::get('/vendedores/crear', 'create')->name('sellers.create');
});

/* --- Kardex --- */
Route::middleware(['auth', 'permission:kardex'])->controller(KardexController::class)->group(function () {
    Route::get('/kardex/consulta', 'setQuery')->name('kardex.setQuery');
    Route::get('/kardex', 'show')->name('kardex.show');    
    Route::get('/kardex/movimientos/{movement}', 'showMovement')->name('kardex.showMovement');
    Route::delete('/kardex/movimientos/{product}', 'popMovement')->name('kardex.popMovement');
});

/* --- Sales --- */
Route::middleware(['auth', 'permission:sales'])->controller(SaleController::class)->group(function () {
    Route::get('/ventas/seleccionar-bodega', 'selectWarehouse')->name('sales.selectWarehouse');
    Route::post('/ventas/guardar-bodega', 'saveWarehouse')->name('sales.saveWarehouse');
    Route::get('/ventas/crear', 'create')->name('sales.create');
    Route::post('/ventas', 'store')->name('sales.store');
    Route::get('/ventas/{sale}', 'show')->name('sales.show');
    Route::get('/ventas/{movement}/editar', 'edit')->name('sales.edit');
    Route::put('/ventas/{movement}', 'update')->name('sales.update');
    Route::delete('/ventas/{movement}', 'destroy')->name('sales.destroy');
});

/* --- Purchases --- */
Route::middleware(['auth', 'permission:purchases'])->controller(PurchaseController::class)->group(function () {
    Route::get('/compras/seleccionar-bodega', 'selectWarehouse')->name('purchases.selectWarehouse');
    Route::post('/compras/guardar-bodega', 'saveWarehouse')->name('purchases.saveWarehouse');
    Route::get('/compras/crear', 'create')->name('purchases.create');
    Route::post('/compras', 'store')->name('purchases.store');
    Route::get('/compras/{invoice}', 'show')->name('purchases.show');
    Route::put('/compras/{invoice}/confirmar-pago', 'confirmPay')->name('purchases.confirm-pay');
});

/* --- Cash Closing --- */
Route::middleware(['auth', 'permission:cash-closing'])->controller(CashClosingController::class)->group(function () {
    Route::get('/cierre-de-caja/consulta', 'query')->name('cash-closing.query');
    Route::get('/cierre-de-caja/ver', 'show')->name('cash-closing.show');
});

/* -- Inventory -- */
Route::middleware(['auth', 'permission:inventory'])->controller(InventoryController::class)->group(function () {
    Route::get('/inventario/consulta', 'query')->name('inventory.query');
    Route::get('/inventario/ver', 'show')->name('inventory.show');
});

/* -- Permissions -- */
Route::middleware(['auth', 'permission:permissions'])->controller(UserPermissionsController::class)->group(function () {
    Route::get('/permisos-de-usuario', 'users')->name('user-permissions.users');
    Route::get('/permisos-de-usuario/{user}/editar', 'edit')->name('user-permissions.edit');
    Route::put('/permisos-de-usuario/{user}', 'update')->name('user-permissions.update');
    Route::get('/permisos-de-usuario/{user}/roles/editar', 'editRoles')->name('user-permissions.edit-roles');
});

Route::middleware(['auth', 'permission:permissions'])->controller(RoleController::class)->group(function () {
    Route::get('/roles', 'index')->name('roles.index');
    Route::get('/roles/crear', 'create')->name('roles.create');
    Route::post('/roles', 'store')->name('roles.store');
    Route::get('/roles/{role}/usuarios/editar', 'editUsers')->name('roles.edit-users');
    Route::get('/roles/{role}/editar', 'edit')->name('roles.edit');
    Route::put('/roles/{role}', 'update')->name('roles.update');
    Route::delete('/roles/{role}', 'destroy')->name('roles.destroy');
});

/* -- Sales Report -- */
Route::middleware(['auth', 'permission:sales-report'])->controller(SalesReportController::class)->group(function () {
    Route::get('/reporte-de-ventas/consultar', 'query')->name('sales-report.query');
    Route::get('/reporte-de-ventas/ver', 'show')->name('sales-report.show');
    Route::post('/reporte-de-ventas/ver/{movement}/confirmar-venta', 'confirm')->name('sales-report.confirm');
});

/* -- Purchases Report -- */
Route::middleware(['auth', 'permission:purchases-report'])->controller(PurchasesReportController::class)->group(function() {
    Route::get('/reporte-de-compras/consultar', 'query')->name('purchases-report.query');
    Route::get('/reporte-de-compras/ver', 'show')->name('purchases-report.show');
});

require __DIR__.'/auth.php';
