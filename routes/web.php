<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\LiabilityController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
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

Route::get('/', [DashboardController::class, 'show'])->middleware(['auth', 'verified'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'show'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('manage')->middleware('auth')->group(function () {
        Route::resource('assets', AssetController::class)->names([
            'index' => 'manage-assets.index',
            'create' => 'manage-assets.create',
            'store' => 'manage-assets.store',
            'show' => 'manage-assets.show',
            'edit' => 'manage-assets.edit',
            'update' => 'manage-assets.update',
            'destroy' => 'manage-assets.destroy',
        ]);

        Route::get('assets/total-value/{userId}', [AssetController::class, 'getTotalValue'])->name('manage-assets.totalValue');
        Route::post('assets/{asset}/increase', [AssetController::class, 'increaseValue'])->name('manage-assets.increaseValue');
        Route::post('assets/{asset}/decrease', [AssetController::class, 'decreaseValue'])->name('manage-assets.decreaseValue');
    });

    // Liability Routes
    Route::resource('liabilities', LiabilityController::class)->names([
        'index' => 'liabilities.index',
        'create' => 'liabilities.create',
        'store' => 'liabilities.store',
        'show' => 'liabilities.show',
        'edit' => 'liabilities.edit',
        'update' => 'liabilities.update',
        'destroy' => 'liabilities.destroy',
    ]);
    Route::get('liabilities/total-debt/{userId}', [LiabilityController::class, 'getTotalDebt'])->name('liabilities.totalDebt');
    Route::post('liabilities/{liability}/reduce', [LiabilityController::class, 'reduceAmount'])->name('liabilities.reduceAmount');
    Route::post('liabilities/{liability}/increase', [LiabilityController::class, 'increaseAmount'])->name('liabilities.increaseAmount');

    // Transaction Routes
    Route::resource('transactions', TransactionController::class)->names([
        'index' => 'transactions.index',
        'create' => 'transactions.create',
        'store' => 'transactions.store',
        'show' => 'transactions.show',
        'edit' => 'transactions.edit',
        'update' => 'transactions.update',
        'destroy' => 'transactions.destroy',
    ]);
    Route::get('transactions/recent/{userId}', [TransactionController::class, 'recentTransactions'])->name('transactions.recent');

    // Category Routes
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'categories.index',
        'create' => 'categories.create',
        'store' => 'categories.store',
        'show' => 'categories.show',
        'edit' => 'categories.edit',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);
    Route::get('categories/type/{type}', [CategoryController::class, 'getByType'])->name('categories.getByType');
});


require __DIR__ . '/auth.php';
