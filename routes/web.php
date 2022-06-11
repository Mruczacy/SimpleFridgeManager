<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;

Route::get('/', function () {
    return view('welcome');
})->name("welcome");

Auth::routes();
Route::get('/language-change', LanguageController::class)->name('changeLanguage');
Route::middleware(['auth'])->group(function () {
    Route::prefix('account')->controller(UserController::class)->name('users.')->group(function () {
        Route::put('/{user}', 'updateOwn')->name('updateOwn');
        Route::get('/{user}/edit', 'editOwn')->name('editOwn');
        Route::get('/', 'showMyAccount')->name('showMyAccount');
        Route::delete('/{user}', 'destroyOwn')->name('destroyOwn');
    });
    Route::prefix('myfridges')->controller(FridgeController::class)->name('myfridges.')->group(function () {
        Route::get('/', 'indexOwn')->name('indexOwn');
        Route::get('/{fridge}', 'showOwn')->name('showOwn');
        Route::put('/{fridge}', 'updateOwn')->name('updateOwn');
        Route::get('/{fridge}/edit', 'editOwn')->name('editOwn');
        Route::delete('/{fridge}', 'destroyOwn')->name('destroyOwn');

    });
    Route::prefix('myproducts')->controller(ProductController::class)->name('myproducts.')->group(function () {
        Route::put('/{product}', 'updateOwn')->name('updateOwn');
        Route::get('/{product}/edit', 'editOwn')->name('editOwn');
        Route::delete('/{product}', 'destroyOwn')->name('destroyOwn');
        Route::put('/move/{product}', 'moveProductBetweenFridgesOwn')->name('move');
        Route::get('/moveform/{product}/{fridge}', [ManagementController::class, 'showAMoveFormOwn'])->name('moveform');
    });
    Route::prefix('manage')->controller(ManagementController::class)->name('manage.')->group(function () {
        Route::get('/form/{fridge}', 'showAManageForm')->name('showAManageForm');
        Route::post('/attach/{fridge}', 'attachUserToFridge')->name('attach');
        Route::delete('/detach/{fridge}/{user}', 'detachUserFromFridge')->name('detach');
        Route::post('/resign/{fridge}', 'resignFromFridge')->name('resign');
        Route::put('/transfer/{fridge}', 'transferOwnership')->name('transferOwnership');
        Route::put('/updaterank/{fridge}', 'updateUserRank')->name('updateUserRank');
    });
    Route::resource('fridges', FridgeController::class)->only(['store', 'create']);
    Route::resource('products', ProductController::class)->only(['store']);
    Route::get('products/create/{fridge}', [ProductController::class, 'create'])->name('products.create');
    Route::middleware(['can:isAdmin'])->group(function () {
        Route::put('/products/move/{product}', [ProductController::class, 'moveProductBetweenFridges'])->name('products.move');
        Route::get('/products/moveform/{product}/{fridge}', [ManagementController::class, 'showAMoveForm'])->name('products.moveform');
        Route::resource('users', UserController::class);
        Route::resource('fridges', FridgeController::class)->except(['store', 'create']);
        Route::resource('products', ProductController::class)->except(['store', 'create', 'show']);
        Route::resource('products/categories', ProductCategoryController::class);
    });


});

Route::get('/home', HomeController::class)->name('home');
