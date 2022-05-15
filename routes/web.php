<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MoneyTransferController;
use App\Http\Controllers\UserSearchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

    Route::get('/money-transfer', [MoneyTransferController::class, 'show'])->name('money-transfer.show');
    Route::post('/money-transfer', [MoneyTransferController::class, 'store'])->name('money-transfer.store');

    Route::get('/search-users', [UserSearchController::class, 'index'])->name('search-users.index');
});
