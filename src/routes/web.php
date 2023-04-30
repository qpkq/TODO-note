<?php

use App\Http\Controllers\Lists\ListController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth', 'prefix' => 'lists'], function () {
    Route::get('/', [ListController::class, 'index'])->name('home');
    Route::get('/{list}', [ListController::class, 'show'])->name('lists.show');
});
