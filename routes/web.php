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
    Route::put('account/{user}', [UserController::class, 'updateOwn'])->name('users.updateOwn');
    Route::get('account/{user}/edit', [UserController::class, 'editOwn'])->name('users.editOwn');
    Route::get('account/{user}', [UserController::class, 'showMyAccount'])->name('users.showMyAccount');
    Route::delete('account/{user}', [UserController::class, 'destroyOwn'])->name('users.destroyOwn');
    Route::resource('users', UserController::class)->middleware('can:isAdmin');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
