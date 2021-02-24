<?php

use App\Http\Controllers\CommissTransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::resource('payments', PaymentsController::class)->except(['edit', 'destroy']);
    Route::resource('users', UserController::class);

    Route::resource('loans', LoanController::class);
    Route::get('loans/customer/{loan}', [LoanController::class, 'getCustomer'])->name('loans.customer');

    Route::resource('commissions', CommissTransactionController::class)->except(['edit', 'destroy']);
    Route::get('commissions/rep/{id}', [CommissTransactionController::class, 'getRep'])->name('commissions.rep');

    Route::get('reports/{type?}', [ReportController::class, 'show'])->name('reports.show');
});

require __DIR__ . '/auth.php';
