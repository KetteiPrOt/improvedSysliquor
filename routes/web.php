<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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
Route::middleware(['auth'])->controller(ProductController::class)->group(function () {
    Route::get('/productos', 'index')->name('products.index');
    Route::get('/productos/crear', 'create')->name('products.create');
    Route::post('/productos', 'store')->name('products.store');
    // Route::get('/productos/{product}', 'show')->name('products.show');
    // Route::get('/productos/{product}/editar', 'edit')->name('products.edit');
    // Route::put('/productos/{product}', 'update')->name('products.update');
    // Route::delete('/productos/{product}', 'destroy')->name('products.destroy');
});

require __DIR__.'/auth.php';
