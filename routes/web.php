<?php

use App\Http\Livewire\OrderCrud;
use App\Http\Livewire\Pos;
use App\Http\Livewire\PosMobile;
use App\Http\Livewire\ProductCrud;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    Route::get('dashboard', Pos::class)->name('dashboard');
    Route::get('products', ProductCrud::class)->name('product');
    Route::get('orders', OrderCrud::class)->name('order');
});
