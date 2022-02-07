<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
    return view('welcome');
})->name("welcome");

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::prefix('account')->name('users.')->group(function () {
        Route::put('/{user}', [UserController::class, 'updateOwn'])->name('updateOwn');
        Route::get('/{user}/edit', [UserController::class, 'editOwn'])->name('editOwn');
        Route::get('/{user}', [UserController::class, 'showMyAccount'])->name('showMyAccount');
        Route::delete('/{user}', [UserController::class, 'destroyOwn'])->name('destroyOwn');
    });
    Route::resource('users', UserController::class)->middleware('can:isAdmin');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
