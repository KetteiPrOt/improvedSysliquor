<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\SellerController;

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
});

/* --- Kardex --- */
Route::middleware(['auth', 'permission:kardex'])->controller(KardexController::class)->group(function () {
    Route::get('/kardex/consulta', 'setQuery')->name('kardex.setQuery');
    Route::get('/kardex', 'show')->name('kardex.show');
    Route::get('/kardex/movimientos/{movement}', 'showMovement')->name('kardex.showMovement');
});

Route::middleware(['auth', 'permission:sales'])->controller(SalesController::class)->group(function () {
    Route::get('/ventas/crear', 'create')->name('sales.create');
    Route::post('/ventas', 'store')->name('sales.store');
    Route::get('/ventas/{sale}', 'show')->name('sales.show');
    Route::get('/ventas/{movement}/editar', 'edit')->name('sales.edit');
    Route::put('/ventas/{movement}', 'update')->name('sales.update');
    Route::delete('/ventas/{movement}', 'destroy')->name('sales.destroy');
});

Route::middleware(['auth', 'permission:purchases'])->controller(PurchaseController::class)->group(function () {
    Route::get('/compras/crear', 'create')->name('purchases.create');
    Route::post('/compras', 'store')->name('purchases.store');
});

require __DIR__.'/auth.php';
