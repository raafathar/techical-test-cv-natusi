<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BaseDashboardController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Drug\DrugController;
use App\Http\Controllers\Dashboard\Logs\ActivityController;
use App\Http\Controllers\Dashboard\Distributor\DistributorController;
use App\Http\Controllers\Dashboard\Transaction\TransactionController;
use App\Http\Controllers\Dashboard\DetailTransaction\DetailTransactionController;

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/change-layout', [BaseDashboardController::class, 'changeLayout'])->name('change-layout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // add user
    Route::get('/users', [ProfileController::class, 'index'])->name('users.index');
    Route::post('/users/add', [ProfileController::class, 'storeUser'])->name('users.store');

    Route::prefix('logs')->group(function () {
        Route::get('/activity', [ActivityController::class, 'index'])->name('dashboard.logs.activity.index');
    });

    Route::prefix('drug')->group(function () {
        Route::resource('drugs', DrugController::class)
            ->names('dashboard.drug.drugs')->only(['index', 'store', 'update', 'destroy']);
    });

    Route::prefix('distributor')->group(function () {
        Route::resource('distributors', DistributorController::class)
            ->names('dashboard.distributor.distributors')->only(['index', 'store', 'update', 'destroy']);
    });
        
    Route::prefix('detailtransaction')->group(function () {
        Route::resource('detailtransactions', DetailtransactionController::class)
            ->names('dashboard.detailtransaction.detailtransactions')
            ->only(['index']);
    });

    Route::prefix('transaction')->group(function () {
        Route::resource('transactions', TransactionController::class)
            ->names('dashboard.transaction.transactions')
            ->only(['index', 'store', 'show']);

        Route::get('transactions/print/{id}', [TransactionController::class, 'print'])
            ->name('dashboard.transaction.transactions.print');
    });


});
