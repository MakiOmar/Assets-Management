<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\LiabilityController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Asset routes
    Route::resource('assets', AssetController::class);
    Route::get('assets/total-value/{userId}', [AssetController::class, 'getTotalValue']);
    Route::post('assets/{asset}/increase', [AssetController::class, 'increaseValue']);
    Route::post('assets/{asset}/decrease', [AssetController::class, 'decreaseValue']);

    // Liability routes
    Route::resource('liabilities', LiabilityController::class);
    Route::get('liabilities/total-debt/{userId}', [LiabilityController::class, 'getTotalDebt']);
    Route::post('liabilities/{liability}/reduce', [LiabilityController::class, 'reduceAmount']);
    Route::post('liabilities/{liability}/increase', [LiabilityController::class, 'increaseAmount']);

    // Transaction routes
    Route::resource('transactions', TransactionController::class);
    Route::get('transactions/recent/{userId}', [TransactionController::class, 'recentTransactions']);

    // Category routes
    Route::resource('categories', CategoryController::class);
    Route::get('categories/type/{type}', [CategoryController::class, 'getByType']);
});

require __DIR__.'/auth.php';
