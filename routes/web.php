<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\HomeController;
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
    Route::prefix('myfridges')->name('myfridges.')->group(function () {
        Route::get('/', [FridgeController::class, 'indexOwn'])->name('indexOwn');
        Route::put('/{fridge}', [FridgeController::class, 'updateOwn'])->name('updateOwn');
        Route::get('/{fridge}/edit', [FridgeController::class, 'editOwn'])->name('editOwn');
        Route::delete('/{fridge}', [FridgeController::class, 'destroyOwn'])->name('destroyOwn');
    });
    Route::prefix('myproducts')->name('myproducts.')->group(function () {
        Route::get('/', [ProductController::class, 'indexOwn'])->name('indexOwn');
        Route::put('/{product}', [ProductController::class, 'updateOwn'])->name('updateOwn');
        Route::get('/{product}/edit', [ProductController::class, 'editOwn'])->name('editOwn');
        Route::delete('/{product}', [ProductController::class, 'destroyOwn'])->name('destroyOwn');
    });
    Route::prefix('manage')->name('manage.')->group(function () {
        Route::post('/attach/{fridge}/{user}', [ManagementController::class, 'attachUserToFridge'])->name('attach');
        Route::delete('/detach/{fridge}/{user}', [ManagementController::class, 'detachUserFromFridge'])->name('detach');
    });
    Route::resource('fridges', FridgeController::class)->only(['store', 'create']);
    Route::resource('products', ProductController::class)->only(['store', 'create']);
    Route::middleware(['can:isAdmin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('fridges', FridgeController::class)->except(['store', 'create']);
        Route::resource('products', ProductController::class)->except(['store', 'create']);
    });


});

Route::get('/home', [HomeController::class, 'index'])->name('home');
