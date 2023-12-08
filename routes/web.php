<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FixedExpenseController;
use App\Http\Controllers\ItemListController;
use App\Http\Controllers\PurchaseItemController;
use App\Http\Controllers\PurchaseListController;
use App\Http\Controllers\PurchaseMonthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VariableExpenseController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return [
        'version' => app()->version()
    ];
});

Route::get('/me', [LoginController::class, 'me'])->name('me');
Route::post('/login', [LoginController::class, 'store'])->name('login');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');


Route::apiResource('users', UserController::class);
Route::apiResource('fixed-expenses', FixedExpenseController::class);
Route::apiResource('variable-expenses', VariableExpenseController::class);
Route::apiResource('purchase-lists', PurchaseListController::class);
Route::apiResource('purchase-lists.item-lists', ItemListController::class);
Route::apiResource('purchase-months', PurchaseMonthController::class);
Route::post('purchase-months/{purchaseMonth}/status/shipping', [PurchaseMonthController::class, 'changeStatusShipping']);
Route::post('purchase-months/{purchaseMonth}/status/finished', [PurchaseMonthController::class, 'changeStatusFinished']);
Route::apiResource('purchase-months.purchase-items', PurchaseItemController::class);
