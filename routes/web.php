<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;

Route::get('/catalog', [GroupController::class, 'index'])->name('groups.index');
Route::get('/product/{id}', [GroupController::class, 'showProduct'])->name('product.show');
// Route::get('/', [GroupController::class, 'index'])->name('groups.index');

Route::get('/', function () {
    return view('welcome');
});
